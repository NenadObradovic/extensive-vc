(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitImageComparison();
	});

	/**
	 * Init image comparison shortcode functionality
	 * Range input is used for keyboard and screen readers, pointer events are used for dragging
	 */
	function evcInitImageComparison() {
		var holder = $('.evc-image-comparison');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					element = thisHolder[0],
					inner = thisHolder.find('.evc-ic-inner')[0],
					range = thisHolder.find('.evc-ic-range')[0],
					isVertical = thisHolder.hasClass('evc-ic-vertical'),
					moveOnHover = thisHolder.hasClass('evc-ic-move-on-hover'),
					isDragging = false;

				if (!inner || !range) {
					return;
				}

				var setPosition = function (position) {
					position = Math.min(100, Math.max(0, position));

					element.style.setProperty('--evc-ic-position', position + '%');
					range.value = position;
				};

				var getPointerPosition = function (event) {
					var rect = inner.getBoundingClientRect();

					return isVertical ? (event.clientY - rect.top) / rect.height * 100 : (event.clientX - rect.left) / rect.width * 100;
				};

				range.addEventListener('input', function () {
					setPosition(parseFloat(range.value));
				});

				inner.addEventListener('pointerdown', function (event) {
					isDragging = true;
					inner.setPointerCapture(event.pointerId);
					setPosition(getPointerPosition(event));
					thisHolder.addClass('evc-ic-dragging');
				});

				inner.addEventListener('pointermove', function (event) {
					if (isDragging || (moveOnHover && event.pointerType === 'mouse')) {
						setPosition(getPointerPosition(event));
					}
				});

				var stopDragging = function () {
					isDragging = false;
					thisHolder.removeClass('evc-ic-dragging');
				};

				inner.addEventListener('pointerup', stopDragging);
				inner.addEventListener('pointercancel', stopDragging);
			});
		}
	}

})(jQuery);
