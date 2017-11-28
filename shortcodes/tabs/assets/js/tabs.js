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
				var thisTabs = $(this);
				
				thisTabs.children('.evc-tabs-item').each(function (index) {
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
				
				thisTabs.css({'visibility': 'visible'});
			});
		}
	}
	
})(jQuery);