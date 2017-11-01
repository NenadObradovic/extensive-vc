(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitCounter();
	});

	/*
	 **	Init counter shortcode functionality
	 */
	function evcInitCounter() {
		var counter = $('.evc-counter');

		if (counter.length) {
			counter.each(function () {
				var thisCounter = $(this),
					digit = thisCounter.find('.evc-c-digit');

				thisCounter.appear(function () {
					thisCounter.css('opacity', '1');

					digit.countTo({
						from: 0,
						to: parseFloat(digit.text()),
						speed: 1500,
						refreshInterval: 100
					});
				}, {accX: 0, accY: -80});
			});
		}
	}

})(jQuery);