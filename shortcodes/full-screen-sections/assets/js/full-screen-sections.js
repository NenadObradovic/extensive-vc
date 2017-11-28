(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitFullScreenSections();
	});
	
	/*
	 **	Init full screen sections shortcode
	 */
	function evcInitFullScreenSections() {
		var fullScreenSections = $('.evc-full-screen-sections');
		
		if (fullScreenSections.length) {
			fullScreenSections.each(function () {
				var thisFullScreenSections = $(this),
					fullScreenSectionsWrapper = thisFullScreenSections.children('.evc-fss-wrapper'),
					enableNavigationData = thisFullScreenSections.data('enable-navigation'),
					enableNavigation = enableNavigationData === 'yes';
				
				fullScreenSectionsWrapper.fullpage({
					sectionSelector: '.evc-fss-item',
					loopTop: true,
					loopBottom: true,
					verticalCentered: false,
					navigation: false,
					onLeave: function (index, nextIndex, direction) {
						
						if (fullScreenSectionsWrapper.hasClass('evc-fss-first-init')) {
							fullScreenSectionsWrapper.removeClass('evc-fss-first-init');
						}
					},
					afterRender: function () {
						
						if (enableNavigation) {
							thisFullScreenSections.children('.evc-fss-nav-holder').css('visibility', 'visible');
						}
						
						fullScreenSectionsWrapper.addClass('evc-fss-is-loaded evc-fss-first-init');
					}
				});
				
				if (enableNavigation) {
					thisFullScreenSections.find('#evc-fss-nav-up').on('click', function () {
						$.fn.fullpage.moveSectionUp();
						return false;
					});
					
					thisFullScreenSections.find('#evc-fss-nav-down').on('click', function () {
						$.fn.fullpage.moveSectionDown();
						return false;
					});
				}
			});
		}
	}
	
})(jQuery);