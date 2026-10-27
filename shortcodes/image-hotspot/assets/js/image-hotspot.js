(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitImageHotspot();
	});

	/**
	 * Init image hotspot shortcode functionality, tooltip is opened on hover/focus or click
	 */
	function evcInitImageHotspot() {
		var holder = $('.evc-image-hotspot');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					openOnHover = thisHolder.data('trigger') !== 'click',
					items = thisHolder.find('.evc-ih-item').has('.evc-ih-tooltip');

				var toggleItem = function (item, open) {
					item.toggleClass('evc-ih-open', open).children('.evc-ih-marker').attr('aria-expanded', open ? 'true' : 'false');

					if (open) {
						evcFitTooltip(item);
					}
				};

				var closeOthers = function (item) {
					items.not(item).filter('.evc-ih-open').each(function () {
						toggleItem($(this), false);
					});
				};

				items.children('.evc-ih-marker').on('click', function () {
					// Tooltip which is opened on hover stays opened on click, so it works the same on touch devices
					var item = $(this).parent(),
						open = openOnHover || !item.hasClass('evc-ih-open');

					closeOthers(item);
					toggleItem(item, open);
				});

				if (openOnHover) {
					items.on('mouseenter focusin', function () {
						closeOthers($(this));
						toggleItem($(this), true);
					}).on('mouseleave', function () {
						if (!$(this).find(':focus').length) {
							toggleItem($(this), false);
						}
					}).on('focusout', function (event) {
						if (!$.contains(this, event.relatedTarget)) {
							toggleItem($(this), false);
						}
					});
				}

				// Opened tooltip is closed with Escape key or click outside of hotspot
				$(document).on('keydown', function (event) {
					if (event.key === 'Escape') {
						items.filter('.evc-ih-open').each(function () {
							toggleItem($(this), false);

							if ($(this).find(':focus').length) {
								$(this).children('.evc-ih-marker').trigger('focus');
							}
						});
					}
				}).on('click', function (event) {
					if (!$(event.target).closest('.evc-ih-item').length) {
						items.filter('.evc-ih-open').each(function () {
							toggleItem($(this), false);
						});
					}
				});
			});
		}
	}

	/**
	 * Move tooltip horizontally, so it doesn't go outside of the screen
	 *
	 * @param item object - hotspot item
	 */
	function evcFitTooltip(item) {
		var tooltip = item.children('.evc-ih-tooltip')[0],
			space = 10,
			shift = 0;

		if (!tooltip) {
			return;
		}

		tooltip.style.setProperty('--evc-ih-shift', '0px');

		var rect = tooltip.getBoundingClientRect(),
			viewportWidth = document.documentElement.clientWidth;

		if (rect.left < space) {
			shift = space - rect.left;
		} else if (rect.right > viewportWidth - space) {
			shift = viewportWidth - space - rect.right;
		}

		tooltip.style.setProperty('--evc-ih-shift', Math.round(shift) + 'px');
	}

})(jQuery);
