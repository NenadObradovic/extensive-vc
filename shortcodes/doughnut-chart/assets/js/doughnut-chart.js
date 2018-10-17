(function ($) {
	'use strict';
	
	evc.doughnutChart = [];
	
	$(document).ready(function () {
		evcDoughnutChart.init();
	});
	
	var evcDoughnutChart = {
		charts: [],
		init: function (settings) {
			this.holder = $('.evc-doughnut-chart');
			
			// Allow overriding the default config
			$.extend(this.holder, settings);
			
			if (this.holder.length) {
				this.holder.each(function () {
					evcDoughnutChart.createChart($(this));
				});
				
				evc.doughnutChart.push(this);
			}
		},
		createChart: function (holder) {
			holder.appear(function () {
				holder.addClass('evc-dc-appeared');
				
				var chart = new Chart(holder.children('canvas'), {
					type: 'doughnut',
					data: evcDoughnutChart.getChartData(holder),
					options: evcDoughnutChart.getChartOptions(holder)
				});
				
				evcDoughnutChart.charts.push(chart);
			}, {accX: 0, accY: -80});
		},
		getChartData: function (holder) {
			var chartItem = holder.children('.evc-doughnut-chart-item'),
				borderColorData = holder.data('border-color'),
				borderColor = borderColorData !== undefined && borderColorData !== '' ? borderColorData : '#fff',
				borderHoverColorData = holder.data('border-hover-color'),
				hoverBorderColor = borderHoverColorData !== undefined && borderHoverColorData !== '' ? borderHoverColorData : '#efefef',
				borderWidthData = holder.data('border-width'),
				borderWidth = borderWidthData !== undefined && borderWidthData !== '' ? parseInt(borderWidthData, 10) : 2,
				labels = [],
				values = [],
				colors = [],
				data = {};
			
			chartItem.each(function () {
				var thisItem = $(this),
					label = thisItem.data('label'),
					value = thisItem.data('value'),
					color = thisItem.data('color');
				
				if (label !== undefined && label !== '') {
					labels.push(label);
				}
				
				if (value !== undefined && value !== '' && color !== undefined && color !== '') {
					values.push(value);
					colors.push(color);
				}
			});
			
			data['labels'] = labels;
			data['datasets'] = [{
				data: values,
				backgroundColor: colors,
				borderColor: borderColor,
				hoverBorderColor: hoverBorderColor,
				borderWidth: borderWidth
			}];
			
			return data;
		},
		getChartOptions: function (holder) {
			var enableLegend = holder.data('enable-legend'),
				legendPosition = holder.data('legend-position'),
				legendTextSizeData = holder.data('legend-text-size'),
				legendTextSize = legendTextSizeData !== undefined && legendTextSizeData !== '' ? parseInt(legendTextSizeData, 10) : 12,
				legendColorData = holder.data('legend-color'),
				legendColor = legendColorData !== undefined && legendColorData !== '' ? legendColorData : '#666',
				options = {};
			
			options['responsive'] = true;
			options['legend'] = {
				display: enableLegend,
				position: legendPosition,
				labels: {
					fontSize: legendTextSize,
					fontColor: legendColor
				}
			};
			
			return options;
		}
	};
	
})(jQuery);