(function ($) {
	"use strict";
	
	window.evc = {};

	/**
	 * Check element to be in the viewport
	 */
	var evcIsInViewport = {
		check: function ($element, callback, onlyOnce, callbackOnExit) {
			if ($element.length) {
				var offset = typeof $element.data('viewport-offset') !== 'undefined' ? $element.data('viewport-offset') : 0.15; // When item is 15% in the viewport

				// Fallback for browsers without IntersectionObserver support
				if (!('IntersectionObserver' in window)) {
					callback.call($element);

					return;
				}

				// Element higher than viewport can't reach the threshold, so trigger it as soon as it enters the viewport
				if ($element.outerHeight() * offset > window.innerHeight) {
					offset = 0;
				}

				var observer = new IntersectionObserver(
					function (entries) {
						// isIntersecting is true when element and viewport are overlapping
						// isIntersecting is false when element and viewport don't overlap
						if (entries[0].isIntersecting === true) {
							callback.call($element);

							// Stop watching the element when it's initialized
							if (onlyOnce !== false) {
								observer.disconnect();
							}
						} else if (callbackOnExit && onlyOnce === false) {
							callbackOnExit.call($element);
						}
					},
					{threshold: [offset]}
				);

				observer.observe($element[0]);
			}
		}
	};

	evc.evcIsInViewport = evcIsInViewport;

	$(document).ready(function () {
		evcInitOWLCarousel();
		evcInitPagination();
	});
	
	/**
	 * Init owl carousel functionality
	 */
	function evcInitOWLCarousel() {
		var carouselHolder = $('.evc-owl-carousel');
		
		if (carouselHolder.length) {
			carouselHolder.each(function () {
				var thisCarousel = $(this),
					carouselItemsNumber = thisCarousel.children().length,
					numberOfItemsData = thisCarousel.data('number-of-items'),
					numberOfItems = typeof numberOfItemsData !== 'undefined' && numberOfItemsData !== false ? parseInt(numberOfItemsData, 10) : 1,
					loop = thisCarousel.data('enable-loop') === 'yes',
					autoplay = thisCarousel.data('enable-autoplay') === 'yes',
					autoplayHoverPause = thisCarousel.data('enable-autoplay-hover-pause') === 'yes',
					speedData = thisCarousel.data('carousel-speed'),
					speed = typeof speedData !== 'undefined' && speedData !== false ? parseInt(speedData, 10) : 5000,
					speedAnimationData = thisCarousel.data('carousel-speed-animation'),
					speedAnimation = typeof speedAnimationData !== 'undefined' && speedAnimationData !== false ? parseInt(speedAnimationData, 10) : 600,
					marginData = thisCarousel.data('carousel-margin'),
					margin = typeof marginData !== 'undefined' && marginData !== false ? parseInt(marginData, 10) : 0,
					centerData = thisCarousel.data('enable-center'),
					center = centerData === 'yes',
					navigation = thisCarousel.data('enable-navigation') === 'yes',
					pagination = thisCarousel.data('enable-pagination') === 'yes';
				
				if (navigation && pagination) {
					thisCarousel.addClass('evc-carousel-has-both-control');
				}
				
				if (carouselItemsNumber <= 1) {
					loop = false;
					autoplay = false;
					navigation = false;
					pagination = false;
				}

				if(center && numberOfItems < 3) {
					numberOfItems = 3;
				}
				
				var responsiveNumberOfItems1 = 1,
					responsiveNumberOfItems2 = numberOfItems < 3 ? numberOfItems : 2,
					responsiveNumberOfItems3 = numberOfItems < 3 ? numberOfItems : 3,
					responsiveNumberOfItems4 = numberOfItems < 5 ? numberOfItems : 4;
				
				thisCarousel.owlCarousel({
					items: numberOfItems,
					loop: loop,
					autoplay: autoplay,
					autoplayHoverPause: autoplayHoverPause,
					autoplayTimeout: speed,
					smartSpeed: speedAnimation,
					margin: margin,
					center: center,
					dots: pagination,
					nav: navigation,
					navText: [
						'<span class="evc-prev-arrow">' + evcVars.global.sliderNavPrevArrow + '</span>',
						'<span class="evc-next-arrow">' + evcVars.global.sliderNavNextArrow + '</span>'
					],
					responsive: {
						0: {
							items: responsiveNumberOfItems1,
							margin: 0
						},
						681: {
							items: responsiveNumberOfItems2
						},
						769: {
							items: responsiveNumberOfItems3
						},
						1025: {
							items: responsiveNumberOfItems4
						},
						1281: {
							items: numberOfItems
						}
					},
					onInitialize: function () {
						thisCarousel.addClass('evc-owl-carousel-init');
					}
				});
			});
		}
	}
	
	/*
	 **	Init shortcodes pagination functionality
	 */
	function evcInitPagination() {
		var holder = $('.evc-has-pagination');
		
		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					itemsHolder = thisHolder.children('.evc-element-wrapper'),
					loadMoreButton = thisHolder.find('.evc-load-more-button'),
					loadingSpinner = thisHolder.find('.evc-pagination-spinner');
				
				loadMoreButton.on('click', function (e) {
					e.preventDefault();
					
					loadMoreButton.addClass('evc-loading');
					loadingSpinner.addClass('evc-loading');
					
					var shortcodeOptions = thisHolder.data('options');
					
					$.ajax({
						type: 'POST',
						data: {
							action: 'extensive_vc_init_shortcode_pagination',
							options: shortcodeOptions
						},
						url: evcVars.global.adminAjaxUrl,
						success: function (data) {
							var response = typeof data === 'string' ? JSON.parse(data) : data,
								responseData = response.data !== null && typeof response.data === 'object' ? response.data : {html: response.data};

							loadMoreButton.removeClass('evc-loading');
							loadingSpinner.removeClass('evc-loading');

							if (response.status !== 'success') {
								return;
							}

							itemsHolder.append(responseData.html);

							shortcodeOptions['next_page'] = typeof responseData.next_page !== 'undefined' ? parseInt(responseData.next_page, 10) : parseInt(shortcodeOptions['next_page'], 10) + 1;

							if (typeof responseData.max_pages_num !== 'undefined') {
								shortcodeOptions['max_pages_num'] = parseInt(responseData.max_pages_num, 10);
							}

							if (shortcodeOptions['next_page'] > shortcodeOptions['max_pages_num']) {
								loadMoreButton.hide();
							}

							// Trigger event so 3rd party scripts can initialize new loaded items
							$(document.body).trigger('extensive_vc_trigger_pagination_loaded', [thisHolder, responseData]);
						},
						error: function () {
							loadMoreButton.removeClass('evc-loading');
							loadingSpinner.removeClass('evc-loading');
						}
					});
				});
			});
		}
	}
	
})(jQuery);
