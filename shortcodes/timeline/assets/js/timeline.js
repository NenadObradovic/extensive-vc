(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitTimeline();
	});

	/**
	 * Init timeline shortcode appear animation, items are shown when they are scrolled into the viewport
	 */
	function evcInitTimeline() {
		var holder = $('.evc-timeline.evc-tl-has-animation');

		if (holder.length && !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
			holder.each(function () {
				// Items are hidden only when script is loaded, so content is visible without javascript
				$(this).find('.evc-timeline-item').each(function () {
					var item = $(this);

					item.addClass('evc-tl-hidden');

					evc.evcIsInViewport.check(item, function () {
						item.removeClass('evc-tl-hidden');
					});
				});
			});
		}
	}

})(jQuery);
