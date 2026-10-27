(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitImageAccordion();
	});

	/**
	 * Init image accordion shortcode functionality, item is opened on hover or click and on keyboard focus
	 */
	function evcInitImageAccordion() {
		var holder = $('.evc-image-accordion');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					openOnHover = thisHolder.data('behavior') !== 'click',
					items = thisHolder.find('> .evc-ia-items > .evc-ia-item'),
					defaultItem = items.filter('.evc-ia-active');

				var setActive = function (item) {
					items.each(function () {
						var isActive = item && this === item[0];

						$(this).toggleClass('evc-ia-active', isActive).children('.evc-ia-trigger').attr('aria-expanded', isActive ? 'true' : 'false');
					});
				};

				items.children('.evc-ia-trigger').on('click', function () {
					var item = $(this).parent();

					// Click on opened item closes it when accordion is opened on click
					setActive(!openOnHover && item.hasClass('evc-ia-active') ? null : item);
				});

				// Item with focused link or trigger is opened, so its content is visible
				items.on('focusin', function () {
					setActive($(this));
				});

				if (openOnHover) {
					items.on('mouseenter', function () {
						setActive($(this));
					});

					thisHolder.on('mouseleave', function () {
						setActive(defaultItem.length ? defaultItem : null);
					});
				}
			});
		}
	}

})(jQuery);
