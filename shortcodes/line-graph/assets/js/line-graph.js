(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitLineGraph();
	});
	
	/**
	 * Init line graph shortcode
	 */
	function evcInitLineGraph() {
		var holder = $('.evc-line-graph');
		
		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					legendText = thisHolder.data('legend-text'),
					holderBorderColor = thisHolder.data('border-color'),
					borderColor = holderBorderColor !== undefined && holderBorderColor !== '' ? holderBorderColor : 'rgba(0, 0, 0, 0.1)',
					holderBorderWidth = thisHolder.data('border-width'),
					borderWidth = holderBorderWidth !== undefined && holderBorderWidth !== '' ? parseInt(holderBorderWidth, 10) : 3,
					showLine = thisHolder.data('disable-line') !== 'yes',
					holderBackgroundColor = thisHolder.data('background-color'),
					backgroundColor = holderBackgroundColor !== undefined && holderBackgroundColor !== '' ? holderBackgroundColor : 'rgba(0, 0, 0, 0.1)',
					pieChartItem = thisHolder.children('.evc-line-graph-item'),
					canvas = thisHolder.children('canvas'),
					labels = [],
					values = [];
				
				pieChartItem.each(function () {
					var thisItem = $(this),
						label = thisItem.data('label'),
						value = thisItem.data('value');
					
					if (label !== undefined && label !== '') {
						labels.push(label);
					}
					
					if (value !== undefined && value !== '' ) {
						values.push(value);
					}
				});
				
				evc.evcIsInViewport.check(thisHolder, function () {
					thisHolder.addClass('evc-lg-appeared');
					
					new Chart(canvas[0], {
						type: 'line',
						data: {
							labels: labels,
							datasets: [{
								label: legendText,
								data: values,
								backgroundColor: backgroundColor,
								borderColor: borderColor,
								borderWidth: borderWidth,
								showLine: showLine,
								fill: true,
								tension: 0.4
							}]
						}
					});
				});
			});
		}
	}
	
})(jQuery);
