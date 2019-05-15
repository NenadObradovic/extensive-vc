(function ($) {
	"use strict";
	
	window.evc = {};
	
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
						'<span class="evc-prev-arrow ' + evcVars.global.sliderNavPrevArrow + '"></span>',
						'<span class="evc-next-arrow ' + evcVars.global.sliderNavNextArrow + '"></span>'
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
							var response = JSON.parse(data);
							
							shortcodeOptions['next_page'] = parseInt(shortcodeOptions['next_page'], 10) + 1;
							
							itemsHolder.append(response.data);
							
							loadMoreButton.removeClass('evc-loading');
							loadingSpinner.removeClass('evc-loading');
							
							if (shortcodeOptions['next_page'] > shortcodeOptions['max_pages_num']) {
								loadMoreButton.hide();
							}
						}
					});
				});
			});
		}
	}
	
})(jQuery);
(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcButton().init();
	});
	
	/*
	 **	Init button shortcode functionality for hover colors
	 */
	var evcButton = function () {
		var buttons = $('.evc-button');
		
		var buttonHoverColor = function (button) {
			if (typeof button.data('hover-color') !== 'undefined') {
				var changeButtonColor = function (event) {
					event.data.button.css('color', event.data.color);
				};
				
				var originalColor = button.css('color'),
					hoverColor = button.data('hover-color');
				
				button.on('mouseenter', {button: button, color: hoverColor}, changeButtonColor);
				button.on('mouseleave', {button: button, color: originalColor}, changeButtonColor);
			}
		};
		
		var buttonHoverBackgroundColor = function (button) {
			if (typeof button.data('hover-background-color') !== 'undefined') {
				var changeButtonBg = function (event) {
					event.data.button.css('background-color', event.data.color);
				};
				
				var originalBackgroundColor = button.css('background-color'),
					hoverBackgroundColor = button.data('hover-background-color');
				
				button.on('mouseenter', {button: button, color: hoverBackgroundColor}, changeButtonBg);
				button.on('mouseleave', {button: button, color: originalBackgroundColor}, changeButtonBg);
			}
		};
		
		var buttonHoverBorderColor = function (button) {
			if (typeof button.data('hover-border-color') !== 'undefined') {
				var changeBorderColor = function (event) {
					event.data.button.css('border-color', event.data.color);
				};
				
				// take one color of the four sides because in otherwise script will messed up
				var originalBorderColor = button.css('borderTopColor'),
					hoverBorderColor = button.data('hover-border-color');
				
				button.on('mouseenter', {button: button, color: hoverBorderColor}, changeBorderColor);
				button.on('mouseleave', {button: button, color: originalBorderColor}, changeBorderColor);
			}
		};
		
		return {
			init: function () {
				if (buttons.length) {
					buttons.each(function () {
						var thisButton = $(this);
						
						buttonHoverColor(thisButton);
						buttonHoverBackgroundColor(thisButton);
						buttonHoverBorderColor(thisButton);
					});
				}
			}
		};
	};
	
})(jQuery);
(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitCounter();
	});

	/*
	 **	Init counter shortcode functionality
	 */
	function evcInitCounter() {
		var counter = $('.evc-counter');

		if (counter.length) {
			counter.each(function () {
				var thisCounter = $(this),
					digit = thisCounter.find('.evc-c-digit');

				thisCounter.appear(function () {
					thisCounter.css('opacity', '1');

					digit.countTo({
						from: 0,
						to: parseFloat(digit.text()),
						speed: 1500,
						refreshInterval: 100
					});
				}, {accX: 0, accY: -80});
			});
		}
	}

})(jQuery);
(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcCustomFontResizeStyle();
	});
	
	/*
	 **	Init Custom Font shortcode resizing style for text
	 */
	function evcCustomFontResizeStyle() {
		var holder = $('.evc-custom-font');
		
		if (holder.length) {
			holder.each(function () {
				var thisItem = $(this),
					itemClass = '',
					itemClassData = thisItem.data('item-class'),
					laptopFSData = thisItem.data('font-size-1440'),
					smallLaptopFSData = thisItem.data('font-size-1366'),
					macLaptopFSData = thisItem.data('font-size-1280'),
					ipadLandscapeFSData = thisItem.data('font-size-1024'),
					ipadPortraitFSData = thisItem.data('font-size-768'),
					mobileFSData = thisItem.data('font-size-680'),
					laptopLHData = thisItem.data('line-height-1440'),
					smallLaptopLHData = thisItem.data('line-height-1366'),
					macLaptopLHData = thisItem.data('line-height-1280'),
					ipadLandscapeLHData = thisItem.data('line-height-1024'),
					ipadPortraitLHData = thisItem.data('line-height-768'),
					mobileLHData = thisItem.data('line-height-680'),
					laptopStyle = '',
					smallLaptopStyle = '',
					macLaptopStyle = '',
					ipadLandscapeStyle = '',
					ipadPortraitStyle = '',
					mobileLandscapeStyle = '',
					style = '',
					responsiveStyle = '';
				
				if (typeof itemClassData !== 'undefined' && itemClassData !== false) {
					itemClass = itemClassData;
				}
				
				if (typeof laptopFSData !== 'undefined' && laptopFSData !== false) {
					laptopStyle += 'font-size: ' + laptopFSData + ' !important;';
				}
				if (typeof smallLaptopFSData !== 'undefined' && smallLaptopFSData !== false) {
					smallLaptopStyle += 'font-size: ' + smallLaptopFSData + ' !important;';
				}
				if (typeof macLaptopFSData !== 'undefined' && macLaptopFSData !== false) {
					macLaptopStyle += 'font-size: ' + macLaptopFSData + ' !important;';
				}
				if (typeof ipadLandscapeFSData !== 'undefined' && ipadLandscapeFSData !== false) {
					ipadLandscapeStyle += 'font-size: ' + ipadLandscapeFSData + ' !important;';
				}
				if (typeof ipadPortraitFSData !== 'undefined' && ipadPortraitFSData !== false) {
					ipadPortraitStyle += 'font-size: ' + ipadPortraitFSData + ' !important;';
				}
				if (typeof mobileFSData !== 'undefined' && mobileFSData !== false) {
					mobileLandscapeStyle += 'font-size: ' + mobileFSData + ' !important;';
				}
				
				if (typeof laptopLHData !== 'undefined' && laptopLHData !== false) {
					laptopStyle += 'line-height: ' + laptopLHData + ' !important;';
				}
				if (typeof smallLaptopLHData !== 'undefined' && smallLaptopLHData !== false) {
					smallLaptopStyle += 'line-height: ' + smallLaptopLHData + ' !important;';
				}
				if (typeof macLaptopLHData !== 'undefined' && macLaptopLHData !== false) {
					macLaptopStyle += 'line-height: ' + macLaptopLHData + ' !important;';
				}
				if (typeof ipadLandscapeLHData !== 'undefined' && ipadLandscapeLHData !== false) {
					ipadLandscapeStyle += 'line-height: ' + ipadLandscapeLHData + ' !important;';
				}
				if (typeof ipadPortraitLHData !== 'undefined' && ipadPortraitLHData !== false) {
					ipadPortraitStyle += 'line-height: ' + ipadPortraitLHData + ' !important;';
				}
				if (typeof mobileLHData !== 'undefined' && mobileLHData !== false) {
					mobileLandscapeStyle += 'line-height: ' + mobileLHData + ' !important;';
				}
				
				if (laptopStyle.length || smallLaptopStyle.length || macLaptopStyle.length || ipadLandscapeStyle.length || ipadPortraitStyle.length || mobileLandscapeStyle.length) {
					
					if (laptopStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1440px) {.evc-custom-font." + itemClass + " { " + laptopStyle + " } }";
					}
					if (smallLaptopStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1366px) {.evc-custom-font." + itemClass + " { " + smallLaptopStyle + " } }";
					}
					if (macLaptopStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1280px) {.evc-custom-font." + itemClass + " { " + macLaptopStyle + " } }";
					}
					if (ipadLandscapeStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1024px) {.evc-custom-font." + itemClass + " { " + ipadLandscapeStyle + " } }";
					}
					if (ipadPortraitStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 768px) {.evc-custom-font." + itemClass + " { " + ipadPortraitStyle + " } }";
					}
					if (mobileLandscapeStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 680px) {.evc-custom-font." + itemClass + " { " + mobileLandscapeStyle + " } }";
					}
				}
				
				if (responsiveStyle.length) {
					style = '<style type="text/css">' + responsiveStyle + '</style>';
				}
				
				if (style.length) {
					$('head').append(style);
				}
			});
		}
	}
	
})(jQuery);
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
(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitIconProgressBar();
	});

	/*
	 **	Init icon progress bar shortcode functionality
	 */
	function evcInitIconProgressBar() {
		var iconProgressBar = $('.evc-icon-progress-bar');

		if (iconProgressBar.length) {
			iconProgressBar.each(function () {
				var thisBar = $(this),
					barIcons = thisBar.find('.evc-ipb-icon'),
					numberOfActiveIcons = thisBar.data('number-of-active-icons'),
					activeItemsColor = thisBar.data('icon-active-color'),
					timeouts = [];

				if (barIcons.length && typeof numberOfActiveIcons !== 'undefined' && numberOfActiveIcons !== false) {
					thisBar.appear(function () {
						barIcons.each(function (i) {
							if (i < numberOfActiveIcons) {
								var time = (i + 1) * 150;

								timeouts[i] = setTimeout(function () {
									$(barIcons[i]).addClass('evc-active');

									if (typeof numberOfActiveIcons !== 'undefined' && numberOfActiveIcons !== false) {
										$(barIcons[i]).css('color', activeItemsColor);
									}
								}, time);
							}
						});
					}, {accX: 0, accY: -80});
				}
			});
		}
	}

})(jQuery);
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
					borderColor = holderBorderColor !== undefined && holderBorderColor !== '' ? holderBorderColor : '',
					holderBorderWidth = thisHolder.data('border-width'),
					borderWidth = holderBorderWidth !== undefined && holderBorderWidth !== '' ? holderBorderWidth : '',
					showLine = thisHolder.data('disable-line') !== 'yes',
					holderBackgroundColor = thisHolder.data('background-color'),
					backgroundColor = holderBackgroundColor !== undefined && holderBackgroundColor !== '' ? holderBackgroundColor : '',
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
								data: values,
								backgroundColor: backgroundColor,
								borderColor: borderColor,
								borderWidth: borderWidth,
								showLine: showLine
							}]
						}
					});
				}, {accX: 0, accY: -80});
			});
		}
	}
	
})(jQuery);
(function ($) {
	'use strict';
	
	evc.pieChart = [];
	
	$(document).ready(function () {
		evcPieChart.init();
	});
	
	var evcPieChart = {
		charts: [],
		init: function (settings) {
			this.holder = $('.evc-pie-chart');
			
			// Allow overriding the default config
			$.extend(this.holder, settings);
			
			if (this.holder.length) {
				this.holder.each(function () {
					evcPieChart.createChart($(this));
				});
				
				evc.pieChart.push(this);
			}
		},
		createChart: function (holder) {
			holder.appear(function () {
				holder.addClass('evc-pc-appeared');
				
				var chart = new Chart(holder.children('canvas'), {
					type: 'pie',
					data: evcPieChart.getChartData(holder),
					options: evcPieChart.getChartOptions(holder)
				});
				
				evcPieChart.charts.push(chart);
			}, {accX: 0, accY: -80});
		},
		getChartData: function (holder) {
			var chartItem = holder.children('.evc-pie-chart-item'),
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
(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitProcess();
	});
	
	/**
	 * Inti process shortcode functionality on appear
	 */
	function evcInitProcess() {
		var holder = $('.evc-process');
		
		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this);
				
				thisHolder.appear(function () {
					thisHolder.addClass('evc-process-appeared');
				}, {accX: 0, accY: -80});
			});
		}
	}
	
})(jQuery);
(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitProcess2();
	});
	
	/**
	 * Inti process 2 shortcode functionality on appear
	 */
	function evcInitProcess2() {
		var holder = $('.evc-process-2');
		
		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this);
				
				thisHolder.appear(function () {
					thisHolder.addClass('evc-process-appeared');
				}, {accX: 0, accY: -80});
			});
		}
	}
	
})(jQuery);
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
(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcSVGTextResizeStyle();
	});
	
	/*
	 **	Init SVG Text shortcode resizing style for text
	 */
	function evcSVGTextResizeStyle() {
		var holder = $('.evc-svg-text');
		
		if (holder.length) {
			holder.each(function () {
				var thisItem = $(this),
					itemClass = '',
					itemClassData = thisItem.data('item-class'),
					laptopFSData = thisItem.data('font-size-1440'),
					smallLaptopFSData = thisItem.data('font-size-1366'),
					macLaptopFSData = thisItem.data('font-size-1280'),
					ipadLandscapeFSData = thisItem.data('font-size-1024'),
					ipadPortraitFSData = thisItem.data('font-size-768'),
					mobileFSData = thisItem.data('font-size-680'),
					laptopStyle = '',
					smallLaptopStyle = '',
					macLaptopStyle = '',
					ipadLandscapeStyle = '',
					ipadPortraitStyle = '',
					mobileLandscapeStyle = '',
					style = '',
					responsiveStyle = '';
				
				if (typeof itemClassData !== 'undefined' && itemClassData !== false) {
					itemClass = itemClassData;
				}
				
				if (typeof laptopFSData !== 'undefined' && laptopFSData !== false) {
					laptopStyle += 'font-size: ' + laptopFSData + ' !important;';
					laptopStyle += 'height: ' + laptopFSData + ' !important;';
				}
				if (typeof smallLaptopFSData !== 'undefined' && smallLaptopFSData !== false) {
					smallLaptopStyle += 'font-size: ' + smallLaptopFSData + ' !important;';
					smallLaptopStyle += 'height: ' + smallLaptopFSData + ' !important;';
				}
				if (typeof macLaptopFSData !== 'undefined' && macLaptopFSData !== false) {
					macLaptopStyle += 'font-size: ' + macLaptopFSData + ' !important;';
					macLaptopStyle += 'height: ' + macLaptopFSData + ' !important;';
				}
				if (typeof ipadLandscapeFSData !== 'undefined' && ipadLandscapeFSData !== false) {
					ipadLandscapeStyle += 'font-size: ' + ipadLandscapeFSData + ' !important;';
					ipadLandscapeStyle += 'height: ' + ipadLandscapeFSData + ' !important;';
				}
				if (typeof ipadPortraitFSData !== 'undefined' && ipadPortraitFSData !== false) {
					ipadPortraitStyle += 'font-size: ' + ipadPortraitFSData + ' !important;';
					ipadPortraitStyle += 'height: ' + ipadPortraitFSData + ' !important;';
				}
				if (typeof mobileFSData !== 'undefined' && mobileFSData !== false) {
					mobileLandscapeStyle += 'font-size: ' + mobileFSData + ' !important;';
					mobileLandscapeStyle += 'height: ' + mobileFSData + ' !important;';
				}
				
				if (laptopStyle.length || smallLaptopStyle.length || macLaptopStyle.length || ipadLandscapeStyle.length || ipadPortraitStyle.length || mobileLandscapeStyle.length) {
					
					if (laptopStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1440px) {.evc-svg-text." + itemClass + " { " + laptopStyle + " } }";
					}
					if (smallLaptopStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1366px) {.evc-svg-text." + itemClass + " { " + smallLaptopStyle + " } }";
					}
					if (macLaptopStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1280px) {.evc-svg-text." + itemClass + " { " + macLaptopStyle + " } }";
					}
					if (ipadLandscapeStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 1024px) {.evc-svg-text." + itemClass + " { " + ipadLandscapeStyle + " } }";
					}
					if (ipadPortraitStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 768px) {.evc-svg-text." + itemClass + " { " + ipadPortraitStyle + " } }";
					}
					if (mobileLandscapeStyle.length) {
						responsiveStyle += "@media only screen and (max-width: 680px) {.evc-svg-text." + itemClass + " { " + mobileLandscapeStyle + " } }";
					}
				}
				
				if (responsiveStyle.length) {
					style = '<style type="text/css">' + responsiveStyle + '</style>';
				}
				
				if (style.length) {
					$('head').append(style);
				}
			});
		}
	}
	
})(jQuery);
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
(function ($) {
	'use strict';
	
	$(document).ready(function () {
		evcInitTextMarquee();
	});
    
    /**
     * Init Text Marquee effect
     */
    function evcInitTextMarquee() {
	    var textMarquee = $('.evc-text-marquee');
	
	    if (textMarquee.length) {
		    textMarquee.each(function () {
			    var thisTextMarquee = $(this),
				    marqueeElements = thisTextMarquee.find('.evc-tm-element'),
				    originalText = marqueeElements.filter('.evc-tm-original'),
				    auxText = marqueeElements.filter('.evc-tm-aux');
			
			    var calcWidth = function (element) {
				    var width;
				
				    if (thisTextMarquee.outerWidth() > element.outerWidth()) {
					    width = thisTextMarquee.outerWidth();
				    } else {
					    width = element.outerWidth();
				    }
				
				    return width;
			    };
			
			    var marqueeEffect = function () {
				    evcRequestAnimationFrame();
				
				    var delta = 1, //pixel movement
					    speedCoeff = 0.8, // below 1 to slow down, above 1 to speed up
					    marqueeWidth = calcWidth(originalText);
				
				    marqueeElements.css({'width': marqueeWidth}); // set the same width to both elements
				    auxText.css('left', marqueeWidth); //set to the right of the initial marquee element
				
				    //movement loop
				    marqueeElements.each(function (i) {
					    var marqueeElement = $(this),
						    currentPos = 0;
					
					    var evcInfiniteScrollEffect = function () {
						    currentPos -= delta;
						
						    //move marquee element
						    if (marqueeElement.position().left <= -marqueeWidth) {
							    marqueeElement.css('left', parseInt(marqueeWidth - delta));
							    currentPos = 0;
						    }
						
						    marqueeElement.css('transform', 'translate3d(' + speedCoeff * currentPos + 'px,0,0)');
						
						    requestNextAnimationFrame(evcInfiniteScrollEffect);
						
						    $(window).resize(function () {
							    marqueeWidth = calcWidth(originalText);
							    currentPos = 0;
							    originalText.css('left', 0);
							    auxText.css('left', marqueeWidth); //set to the right of the inital marquee element
						    });
					    };
					
					    evcInfiniteScrollEffect();
				    });
			    };
			
			    marqueeEffect();
		    });
	    }
    }
    
    /*
     * Request Animation Frame shim
     */
	function evcRequestAnimationFrame() {
		window.requestNextAnimationFrame =
			(function () {
				var originalWebkitRequestAnimationFrame,
					wrapper,
					callback,
					geckoVersion = 0,
					userAgent = navigator.userAgent,
					index = 0,
					self = this;
				
				// Workaround for Chrome 10 bug where Chrome
				// does not pass the time to the animation function
				if (window.webkitRequestAnimationFrame) {
					// Define the wrapper
					wrapper = function (time) {
						if (time === undefined) {
							time = +new Date();
						}
						
						self.callback(time);
					};
					
					// Make the switch
					originalWebkitRequestAnimationFrame = window.webkitRequestAnimationFrame;
					
					window.webkitRequestAnimationFrame = function (callback, element) {
						self.callback = callback;
						
						// Browser calls the wrapper and wrapper calls the callback
						originalWebkitRequestAnimationFrame(wrapper, element);
					};
				}
				
				// Workaround for Gecko 2.0, which has a bug in
				// mozRequestAnimationFrame() that restricts animations
				// to 30-40 fps.
				if (window.mozRequestAnimationFrame) {
					// Check the Gecko version. Gecko is used by browsers
					// other than Firefox. Gecko 2.0 corresponds to
					// Firefox 4.0.
					
					index = userAgent.indexOf('rv:');
					
					if (userAgent.indexOf('Gecko') !== -1) {
						geckoVersion = userAgent.substr(index + 3, 3);
						
						if (geckoVersion === '2.0') {
							// Forces the return statement to fall through
							// to the setTimeout() function.
							
							window.mozRequestAnimationFrame = undefined;
						}
					}
				}
				
				return window.requestAnimationFrame   ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame    ||
					window.oRequestAnimationFrame      ||
					window.msRequestAnimationFrame     ||
					
					function (callback, element) {
						var start,
							finish;
						
						window.setTimeout( function () {
							start = +new Date();
							callback(start);
							finish = +new Date();
							
							self.timeout = 1000 / 60 - (finish - start);
							
						}, self.timeout);
					};
				}
			)();
	}

})(jQuery);