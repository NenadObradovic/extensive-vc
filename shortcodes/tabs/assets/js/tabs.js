(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitTabs();
	});
	
	/*
	 **	Init tabs shortcode
	 */
	function evcInitTabs() {
		var tabs = $('.evc-tabs');
		
		if (tabs.length) {
			tabs.each(function () {
				var thisTabs = $(this),
					tabContent = thisTabs.find('.evc-tabs-item');
				
				tabContent.each(function (index) {
					index = index + 1;
					
					var that = $(this),
						link = that.attr('id'),
						navItem = that.parent().find('.evc-tabs-nav li:nth-child(' + index + ') a'),
						navLink = navItem.attr('href');
					
					link = '#' + link;
					
					if (link.indexOf(navLink) > -1) {
						navItem.attr('href', link);
					}
				});
				
				thisTabs.tabs();
				
				thisTabs.appear(function () {
					thisTabs.css({'visibility': 'visible'});
					showTabContent(tabContent);
				});
				
				thisTabs.find('.evc-tabs-nav li').each(function () {
					$(this).children().on('click', function () {
						setTimeout(function () {
							showTabContent(tabContent);
						}, 50);
					});
				});
			});
		}
		
		function showTabContent(tabContent) {
			tabContent.each(function () {
				var thisTabContent = $(this);
				
				if (thisTabContent.is(':visible')) {
					thisTabContent.addClass('evc-active');
				} else {
					thisTabContent.removeClass('evc-active');
				}
			});
		}
	}
	
})(jQuery);