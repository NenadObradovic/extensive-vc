(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitSectionTitleAnimatedWords();
	});

	/**
	 * Init section title animated words (typing and rotating effects)
	 */
	function evcInitSectionTitleAnimatedWords() {
		var holder = $('.evc-section-title .evc-st-animated');

		if (holder.length) {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			holder.each(function () {
				var thisHolder = $(this),
					words = thisHolder.data('words'),
					type = thisHolder.data('type'),
					delay = parseInt(thisHolder.data('delay'), 10) || 2000;

				// Animation is not used when user prefers reduced motion, first word is displayed
				if (reduceMotion || !Array.isArray(words) || words.length < 2) {
					return;
				}

				evc.evcIsInViewport.check(thisHolder, function () {
					if (type === 'rotating') {
						evcRotateWords(thisHolder, words, delay);
					} else {
						evcTypeWords(thisHolder, words, delay);
					}
				});
			});
		}
	}

	/**
	 * Typing effect, word is typed letter by letter and then deleted
	 */
	function evcTypeWords(holder, words, delay) {
		var element = holder[0],
			wordIndex = 0,
			charIndex = words[0].length,
			isDeleting = true;

		holder.addClass('evc-st-animated-active');

		var tick = function () {
			var word = words[wordIndex],
				timeout = isDeleting ? 40 : 90;

			charIndex += isDeleting ? -1 : 1;
			element.textContent = word.substring(0, charIndex);

			if (!isDeleting && charIndex === word.length) {
				isDeleting = true;
				timeout = delay;
			} else if (isDeleting && charIndex === 0) {
				isDeleting = false;
				wordIndex = (wordIndex + 1) % words.length;
				timeout = 300;
			}

			setTimeout(tick, timeout);
		};

		setTimeout(tick, delay);
	}

	/**
	 * Rotating effect, words are changed with fade animation
	 */
	function evcRotateWords(holder, words, delay) {
		var wordIndex = 0;

		setInterval(function () {
			holder.addClass('evc-st-animated-hide');

			setTimeout(function () {
				wordIndex = (wordIndex + 1) % words.length;
				holder[0].textContent = words[wordIndex];
				holder.removeClass('evc-st-animated-hide');
			}, 300);
		}, delay);
	}

})(jQuery);
