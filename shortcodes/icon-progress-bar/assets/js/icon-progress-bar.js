(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitIconProgressBar();
	});

	/*
	 **	Init icon progress bar shortcode functionality
	 */
	function evcInitIconProgressBar() {
		var iconProgressBar = $('.evc-icon-progress-bar');

		if (iconProgressBar.length) {
			iconProgressBar.each(function () {
				var thisBar = $(this),
					barIcons = thisBar.find('.evc-ipb-icon'),
					numberOfActiveIcons = thisBar.data('number-of-active-icons'),
					activeItemsColor = thisBar.data('icon-active-color'),
					timeouts = [];

				if (barIcons.length && typeof numberOfActiveIcons !== 'undefined' && numberOfActiveIcons !== false) {
					thisBar.appear(function () {
						barIcons.each(function (i) {
							if (i < numberOfActiveIcons) {
								var time = (i + 1) * 150;

								timeouts[i] = setTimeout(function () {
									$(barIcons[i]).addClass('evc-active');

									if (typeof numberOfActiveIcons !== 'undefined' && numberOfActiveIcons !== false) {
										$(barIcons[i]).css('color', activeItemsColor);
									}
								}, time);
							}
						});
					}, {accX: 0, accY: -80});
				}
			});
		}
	}

})(jQuery);