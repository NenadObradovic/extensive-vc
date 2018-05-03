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
				
				thisHolder.appear(function () {
					thisHolder.addClass('evc-lg-appeared');
					
					new Chart(canvas, {
						type: 'line',
						data: {
							labels: labels,
							datasets: [{
								label: legendText,
								data: values
							}]
						}
					});
				}, {accX: 0, accY: -80});
			});
		}
	}
	
})(jQuery);