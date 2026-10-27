(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitSocialShare();
	});

	/**
	 * Init social share shortcode functionality, copy link button and device share menu button
	 */
	function evcInitSocialShare() {
		var holder = $('.evc-social-share');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					status = thisHolder.find('.evc-ss-status'),
					statusTimeout;

				var showStatus = function (message) {
					clearTimeout(statusTimeout);
					status.text(message).addClass('evc-ss-status-visible');

					statusTimeout = setTimeout(function () {
						status.removeClass('evc-ss-status-visible').text('');
					}, 2500);
				};

				thisHolder.find('.evc-ss-copy').on('click', function () {
					var button = $(this),
						url = button.data('url');

					evcCopyText(url).then(function () {
						showStatus(button.data('copied-label'));
					}, function () {
						// Clipboard is not available (e.g. page is not opened through https), so link is shown for manual copy
						window.prompt('', url);
					});
				});

				// Device share menu is available only in browsers which support Web Share API
				if (typeof navigator.share === 'function') {
					thisHolder.find('.evc-ss-item-native').removeAttr('hidden');

					thisHolder.find('.evc-ss-native').on('click', function () {
						var button = $(this);

						navigator.share({
							title: button.data('title'),
							url: button.data('url')
						}).catch(function () {
							// Share is canceled by the user
						});
					});
				}
			});
		}
	}

	/**
	 * Copy text into clipboard
	 *
	 * @param text string
	 *
	 * @return Promise
	 */
	function evcCopyText(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		}

		return new Promise(function (resolve, reject) {
			var field = $('<textarea readonly></textarea>').val(text).css({position: 'fixed', top: 0, left: 0, opacity: 0}).appendTo('body');

			field[0].select();

			try {
				document.execCommand('copy') ? resolve() : reject();
			} catch (error) {
				reject();
			}

			field.remove();
		});
	}

})(jQuery);
