(function ($) {
	"use strict";
	
	$(document).ready(function () {
		evcInitOWLCarousel();
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
					dots: pagination,
					nav: navigation,
					navText: [
						'<span class="evc-prev-arrow ion-ios-arrow-left"></span>',
						'<span class="evc-next-arrow ion-ios-arrow-right"></span>'
					],
					responsive: {
						0: {
							items: responsiveNumberOfItems1
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
	
})(jQuery);