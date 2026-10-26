(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitCountdown();
	});

	/**
	 * Init countdown shortcode functionality
	 */
	function evcInitCountdown() {
		var holder = $('.evc-countdown');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					type = thisHolder.data('type'),
					expireAction = thisHolder.data('expire-action'),
					endTime = parseInt(thisHolder.data('end'), 10) || 0,
					digits = thisHolder.find('.evc-cd-digit'),
					unitSeconds = {days: 86400, hours: 3600, minutes: 60, seconds: 1},
					interval;

				// Evergreen countdown end time is saved per visitor
				if (type === 'evergreen') {
					endTime = evcGetEvergreenEndTime(thisHolder.data('id'), parseInt(thisHolder.data('duration'), 10) || 0);
				}

				var expire = function () {
					clearInterval(interval);

					if (expireAction === 'hide') {
						thisHolder.hide();
					} else if (expireAction === 'message' && thisHolder.find('.evc-cd-expired-message').length) {
						thisHolder.addClass('evc-cd-expired');
						thisHolder.find('.evc-cd-timer').attr('hidden', '');
						thisHolder.find('.evc-cd-expired-message').removeAttr('hidden');
					}
				};

				var update = function () {
					var remaining = Math.max(0, Math.floor((endTime - Date.now()) / 1000));

					// Values of hidden units are added to the next visible unit
					digits.each(function () {
						var digit = $(this),
							seconds = unitSeconds[digit.data('unit')] || 1,
							value = Math.floor(remaining / seconds);

						remaining -= value * seconds;
						digit.text(value < 10 ? '0' + value : value);
					});

					if (endTime <= Date.now()) {
						expire();
					}
				};

				update();
				interval = setInterval(update, 1000);
			});
		}
	}

	/**
	 * Get evergreen countdown end time, end time is saved into local storage when visitor opens the page for the first time
	 */
	function evcGetEvergreenEndTime(id, duration) {
		var key = 'evcCountdown' + id,
			endTime = 0;

		try {
			endTime = parseInt(window.localStorage.getItem(key), 10) || 0;
		} catch (e) {
			endTime = 0;
		}

		if (!endTime) {
			endTime = Date.now() + duration * 1000;

			try {
				window.localStorage.setItem(key, endTime);
			} catch (e) {
				// Local storage is not available, countdown starts again on every page load
			}
		}

		return endTime;
	}

})(jQuery);
