(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitBusinessHours();
	});

	/**
	 * Init business hours shortcode functionality, item which contains current day in site timezone is highlighted
	 */
	function evcInitBusinessHours() {
		var holder = $('.evc-business-hours[data-timezone-offset]');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					offset = parseInt(thisHolder.data('timezone-offset'), 10) || 0,
					siteTime = new Date(Date.now() + offset * 60000),
					today = siteTime.getUTCDay() || 7; // ISO-8601 day number, Sunday is 7

				thisHolder.find('.evc-bh-item[data-days]').each(function () {
					var days = String($(this).data('days')).split(',');

					if (days.indexOf(String(today)) !== -1) {
						$(this).addClass('evc-bh-today').attr('aria-current', 'date');
					}
				});
			});
		}
	}

})(jQuery);
