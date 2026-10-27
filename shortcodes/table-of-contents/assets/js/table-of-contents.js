(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitTableOfContents();
	});

	/**
	 * Init table of contents shortcode functionality, list is generated from headings in the page content
	 */
	function evcInitTableOfContents() {
		var holder = $('.evc-table-of-contents');

		if (holder.length) {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			holder.each(function () {
				var thisHolder = $(this),
					list = thisHolder.find('.evc-toc-list'),
					listTag = list.prop('tagName').toLowerCase(),
					levels = String(thisHolder.data('headings') || 'h2,h3').split(','),
					hierarchical = thisHolder.data('hierarchical') !== 'no',
					offset = parseInt(thisHolder.data('scroll-offset'), 10) || 0,
					container = evcGetTableOfContentsContainer(thisHolder),
					links = [];

				var headings = $(container).find(levels.join(',')).filter(function () {
					var heading = $(this);

					return !heading.closest('.evc-table-of-contents, .evc-toc-exclude').length && heading.is(':visible') && heading.text().trim() !== '';
				});

				if (!headings.length) {
					return;
				}

				// Each heading is added under the closest previous heading with lower level
				var stack = [{level: 0, list: list}];

				headings.each(function () {
					var heading = $(this),
						level = parseInt(this.tagName.substring(1), 10);

					if (!this.id) {
						this.id = evcGetUniqueHeadingId(heading.text());
					}

					if (hierarchical) {
						while (stack.length > 1 && stack[stack.length - 1].level >= level) {
							stack.pop();
						}
					}

					var parent = hierarchical ? stack[stack.length - 1] : stack[0],
						parentList = parent.list;

					if (!parentList) {
						parentList = parent.list = $('<' + listTag + ' class="evc-toc-sublist"></' + listTag + '>').appendTo(parent.item);
					}

					var item = $('<li class="evc-toc-item"></li>').appendTo(parentList),
						link = $('<a class="evc-toc-link"></a>').attr('href', '#' + this.id).text(heading.text().trim()).appendTo(item);

					links.push({link: link, heading: this});

					if (hierarchical) {
						stack.push({level: level, item: item, list: null});
					}
				});

				thisHolder.addClass('evc-toc-ready');

				list.on('click', '.evc-toc-link', function (event) {
					var heading = document.getElementById(this.getAttribute('href').substring(1));

					if (!heading) {
						return;
					}

					event.preventDefault();

					window.scrollTo({
						top: heading.getBoundingClientRect().top + window.pageYOffset - offset,
						behavior: reduceMotion ? 'auto' : 'smooth'
					});

					if (window.history && window.history.replaceState) {
						window.history.replaceState(null, '', '#' + heading.id);
					}

					// Focus is moved to the heading, so keyboard users continue from the selected section
					if (!heading.hasAttribute('tabindex')) {
						heading.setAttribute('tabindex', '-1');
					}

					heading.focus({preventScroll: true});
				});

				thisHolder.find('.evc-toc-toggle').on('click', function () {
					var button = $(this),
						body = thisHolder.find('.evc-toc-body'),
						expand = button.attr('aria-expanded') !== 'true';

					button.attr('aria-expanded', expand ? 'true' : 'false');
					body.prop('hidden', !expand);
				});

				if (thisHolder.data('highlight') !== 'no') {
					evcInitTableOfContentsHighlight(links, offset);
				}

				if (thisHolder.hasClass('evc-toc-sticky')) {
					evcInitTableOfContentsStickyColumn(thisHolder);
				}
			});
		}
	}

	/**
	 * Get content which contains headings
	 *
	 * @param holder object - table of contents element
	 *
	 * @return object
	 */
	function evcGetTableOfContentsContainer(holder) {
		var selector = holder.data('container'),
			container = null;

		if (selector) {
			try {
				container = document.querySelector(selector);
			} catch (error) {
				container = null;
			}
		}

		if (!container) {
			container = holder.closest('.wpb-content-wrapper, .entry-content, article, main')[0] || document.body;
		}

		return container;
	}

	/**
	 * Get unique id for heading from its text
	 *
	 * @param text string - heading text
	 *
	 * @return string
	 */
	function evcGetUniqueHeadingId(text) {
		var slug = text.trim().toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9Ѐ-ӿ]+/g, '-').replace(/^-+|-+$/g, ''),
			id = slug || 'section',
			index = 2;

		while (document.getElementById(id)) {
			id = (slug || 'section') + '-' + index++;
		}

		return id;
	}

	/**
	 * Sticky element follows the scroll only inside its parent, so the column and its inner wrappers are stretched to the row height.
	 * Many themes don't support equal height columns, so the height is set with script
	 *
	 * @param holder object - table of contents element
	 */
	function evcInitTableOfContentsStickyColumn(holder) {
		var column = holder.closest('.vc_column_container, .wpb_column');

		if (!column.length || !column.parent().length) {
			return;
		}

		var wrappers = holder.parentsUntil(column.parent());

		var update = function () {
			wrappers.css('min-height', '');

			// Columns are stacked on small screens, so there is no space for sticky behavior
			if (window.innerWidth <= 768) {
				return;
			}

			var rowBottom = column.parent()[0].getBoundingClientRect().bottom;

			wrappers.each(function () {
				var height = rowBottom - this.getBoundingClientRect().top;

				if (height > this.offsetHeight) {
					this.style.minHeight = Math.floor(height) + 'px';
				}
			});
		};

		update();
		$(window).on('load resize', update);

		// Css sticky position doesn't work inside elements with hidden overflow (used by many themes), so element is moved with script
		var hasClippedParent = holder.parentsUntil('html').filter(function () {
			return $(this).css('overflow-x') !== 'visible' || $(this).css('overflow-y') !== 'visible';
		}).length > 0;

		if (hasClippedParent) {
			var offset = parseInt(holder.css('top'), 10) || 0,
				currentShift = 0,
				ticking = false;

			holder.addClass('evc-toc-sticky-js');

			var move = function () {
				var shift = 0;

				if (window.innerWidth > 768) {
					var top = holder[0].getBoundingClientRect().top - currentShift,
						maxShift = holder.parent()[0].getBoundingClientRect().bottom - top - holder.outerHeight();

					shift = Math.max(0, Math.min(offset - top, maxShift));
				}

				currentShift = Math.round(shift);
				holder.css('transform', currentShift ? 'translateY(' + currentShift + 'px)' : '');
				ticking = false;
			};

			$(window).on('scroll resize', function () {
				if (!ticking) {
					ticking = true;
					window.requestAnimationFrame(move);
				}
			});

			move();
		}
	}

	/**
	 * Highlight link of the section which is currently read
	 *
	 * @param links array - list of links and headings
	 * @param offset int - scroll offset
	 */
	function evcInitTableOfContentsHighlight(links, offset) {
		var ticking = false;

		var update = function () {
			var current = null;

			$.each(links, function (index, item) {
				if (item.heading.getBoundingClientRect().top - offset - 10 <= 0) {
					current = item;
				}
			});

			$.each(links, function (index, item) {
				var isCurrent = item === current;

				item.link.toggleClass('evc-toc-active', isCurrent);

				if (isCurrent) {
					item.link.attr('aria-current', 'location');
				} else {
					item.link.removeAttr('aria-current');
				}
			});

			ticking = false;
		};

		$(window).on('scroll', function () {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		});

		update();
	}

})(jQuery);
