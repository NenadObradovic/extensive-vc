(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitAccordion();
	});

	/**
	 * Init accordion shortcode functionality, markup follows WAI-ARIA accordion pattern
	 */
	function evcInitAccordion() {
		var holder = $('.evc-accordion');

		if (holder.length) {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches,
				duration = reduceMotion ? 0 : 250;

			holder.each(function () {
				var thisHolder = $(this),
					isAccordion = thisHolder.data('behavior') === 'accordion',
					items = thisHolder.children('.evc-accordion-item'),
					buttons = items.find('> .evc-acc-title-holder > .evc-acc-title');

				var toggleItem = function (item, open) {
					var button = item.find('> .evc-acc-title-holder > .evc-acc-title'),
						content = item.children('.evc-acc-content');

					button.attr('aria-expanded', open ? 'true' : 'false');
					item.toggleClass('evc-acc-item-open', open);

					if (open) {
						content.stop(true, true).removeAttr('hidden').hide().slideDown(duration);
					} else {
						content.stop(true, true).slideUp(duration, function () {
							content.attr('hidden', '').css('display', '');
						});
					}
				};

				buttons.on('click', function () {
					var item = $(this).closest('.evc-accordion-item'),
						open = $(this).attr('aria-expanded') !== 'true';

					if (open && isAccordion) {
						items.not(item).filter('.evc-acc-item-open').each(function () {
							toggleItem($(this), false);
						});
					}

					toggleItem(item, open);
				});

				// Keyboard navigation between accordion headers
				buttons.on('keydown', function (event) {
					var index = buttons.index(this),
						target = null;

					switch (event.key) {
						case 'ArrowDown':
							target = buttons.eq((index + 1) % buttons.length);
							break;
						case 'ArrowUp':
							target = buttons.eq((index - 1 + buttons.length) % buttons.length);
							break;
						case 'Home':
							target = buttons.first();
							break;
						case 'End':
							target = buttons.last();
							break;
					}

					if (target) {
						event.preventDefault();
						target.trigger('focus');
					}
				});
			});
		}
	}

})(jQuery);
