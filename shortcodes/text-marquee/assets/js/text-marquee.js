(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitTextMarquee();
	});

	/**
	 * Init Text Marquee effect
	 */
	function evcInitTextMarquee() {
		var textMarquee = $('.evc-text-marquee');

		if (textMarquee.length) {
			textMarquee.each(function () {
				var thisTextMarquee = $(this),
					marqueeElements = thisTextMarquee.find('.evc-tm-element'),
					originalText = marqueeElements.filter('.evc-tm-original'),
					auxText = marqueeElements.filter('.evc-tm-aux'),
					delta = 1, // pixel movement
					speedCoeff = 0.8, // below 1 to slow down, above 1 to speed up
					marqueeWidth = 0,
					positions = [],
					isVisible = false,
					isRunning = false,
					resizeTimeout;

				if (!originalText.length || !auxText.length) {
					return;
				}

				// Calculate element width from its natural size, so previous inline width doesn't affect the result
				var calcWidth = function () {
					marqueeElements.css('width', '');

					return Math.max(thisTextMarquee.outerWidth(), originalText.outerWidth());
				};

				var setPositions = function () {
					marqueeElements.each(function (i) {
						$(this).css('transform', 'translate3d(' + positions[i] + 'px,0,0)');
					});
				};

				// Set the same width to both elements and place aux element right after the original one
				var setup = function () {
					marqueeWidth = calcWidth();
					positions = [0, marqueeWidth];

					marqueeElements.css({'width': marqueeWidth, 'left': 0});
					setPositions();
				};

				// Movement loop
				var marqueeEffect = function () {
					if (!isVisible) {
						isRunning = false;

						return;
					}

					for (var i = 0; i < positions.length; i++) {
						positions[i] -= delta * speedCoeff;

						// Element is moved out of the view, so place it after the other element
						if (positions[i] <= -marqueeWidth) {
							positions[i] += marqueeWidth * positions.length;
						}
					}

					setPositions();
					window.requestAnimationFrame(marqueeEffect);
				};

				var start = function () {
					isVisible = true;

					if (!isRunning) {
						isRunning = true;
						window.requestAnimationFrame(marqueeEffect);
					}
				};

				setup();

				// Recalculate width when web fonts are loaded, text width can be changed after font swap
				if (document.fonts && document.fonts.ready) {
					document.fonts.ready.then(setup);
				}

				$(window).on('load', setup);

				$(window).on('resize', function () {
					clearTimeout(resizeTimeout);
					resizeTimeout = setTimeout(setup, 150);
				});

				// Animate only while element is in the viewport
				thisTextMarquee.data('viewport-offset', 0);
				evc.evcIsInViewport.check(thisTextMarquee, start, false, function () {
					isVisible = false;
				});
			});
		}
	}

})(jQuery);
