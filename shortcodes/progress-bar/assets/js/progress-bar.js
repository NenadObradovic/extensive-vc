(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitProgressBar();
	});
	
	/*
	 **	Init progress bar shortcode functionality
	 */
	function evcInitProgressBar() {
		var progressBar = $('.evc-progress-bar');
		
		if (progressBar.length) {
			progressBar.each(function () {
				var thisBar = $(this),
					isVerticalType = thisBar.hasClass('evc-pb-vertical'),
					percent = thisBar.find('.evc-pb-percent'),
					barContent = thisBar.find('.evc-pb-active-bar'),
					percentValue = parseFloat(barContent.data('percentage'));
				
				if (typeof percentValue !== 'undefined' && percentValue !== false) {
					thisBar.appear(function () {
						evcInitProgressBarCounter(percent, percentValue);
						
						if(isVerticalType) {
							barContent.stop().animate({'height': percentValue + '%'}, 1500);
						} else {
							barContent.stop().animate({'width': percentValue + '%'}, 1500);
						}
					}, {accX: 0, accY: -80});
				}
			});
		}
	}
	
	/*
	 **	Init progress bar shortcode counter to count percent from zero to defined percent value
	 */
	function evcInitProgressBarCounter(percent, percentValue) {
		
		if (percent.length) {
			percent.each(function () {
				var thisPercent = $(this);
				
				thisPercent.css({'opacity': '1'}).countTo({
					from: 0,
					to: percentValue,
					speed: 1500,
					refreshInterval: 50
				});
			});
		}
	}
	
})(jQuery);