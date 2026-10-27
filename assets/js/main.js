/******/ (() => { // webpackBootstrap
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

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitAccordion();
	});

	/**
	 * Init accordion shortcode functionality, markup follows WAI-ARIA accordion pattern
	 */
	function evcInitAccordion() {
		var holder = $('.evc-accordion');

		if (holder.length) {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches,
				duration = reduceMotion ? 0 : 250;

			holder.each(function () {
				var thisHolder = $(this),
					isAccordion = thisHolder.data('behavior') === 'accordion',
					items = thisHolder.children('.evc-accordion-item'),
					buttons = items.find('> .evc-acc-title-holder > .evc-acc-title');

				var toggleItem = function (item, open) {
					var button = item.find('> .evc-acc-title-holder > .evc-acc-title'),
						content = item.children('.evc-acc-content');

					button.attr('aria-expanded', open ? 'true' : 'false');
					item.toggleClass('evc-acc-item-open', open);

					if (open) {
						content.stop(true, true).removeAttr('hidden').hide().slideDown(duration);
					} else {
						content.stop(true, true).slideUp(duration, function () {
							content.attr('hidden', '').css('display', '');
						});
					}
				};

				buttons.on('click', function () {
					var item = $(this).closest('.evc-accordion-item'),
						open = $(this).attr('aria-expanded') !== 'true';

					if (open && isAccordion) {
						items.not(item).filter('.evc-acc-item-open').each(function () {
							toggleItem($(this), false);
						});
					}

					toggleItem(item, open);
				});

				// Keyboard navigation between accordion headers
				buttons.on('keydown', function (event) {
					var index = buttons.index(this),
						target = null;

					switch (event.key) {
						case 'ArrowDown':
							target = buttons.eq((index + 1) % buttons.length);
							break;
						case 'ArrowUp':
							target = buttons.eq((index - 1 + buttons.length) % buttons.length);
							break;
						case 'Home':
							target = buttons.first();
							break;
						case 'End':
							target = buttons.last();
							break;
					}

					if (target) {
						event.preventDefault();
						target.trigger('focus');
					}
				});
			});
		}
	}

})(jQuery);

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitBusinessHours();
	});

	/**
	 * Init business hours shortcode functionality, item which contains current day in site timezone is highlighted
	 */
	function evcInitBusinessHours() {
		var holder = $('.evc-business-hours[data-timezone-offset]');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					offset = parseInt(thisHolder.data('timezone-offset'), 10) || 0,
					siteTime = new Date(Date.now() + offset * 60000),
					today = siteTime.getUTCDay() || 7; // ISO-8601 day number, Sunday is 7

				thisHolder.find('.evc-bh-item[data-days]').each(function () {
					var days = String($(this).data('days')).split(',');

					if (days.indexOf(String(today)) !== -1) {
						$(this).addClass('evc-bh-today').attr('aria-current', 'date');
					}
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
		evcInitCountdown();
	});

	/**
	 * Init countdown shortcode functionality
	 */
	function evcInitCountdown() {
		var holder = $('.evc-countdown');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					type = thisHolder.data('type'),
					expireAction = thisHolder.data('expire-action'),
					endTime = parseInt(thisHolder.data('end'), 10) || 0,
					digits = thisHolder.find('.evc-cd-digit'),
					unitSeconds = {days: 86400, hours: 3600, minutes: 60, seconds: 1},
					interval;

				// Evergreen countdown end time is saved per visitor
				if (type === 'evergreen') {
					endTime = evcGetEvergreenEndTime(thisHolder.data('id'), parseInt(thisHolder.data('duration'), 10) || 0);
				}

				var expire = function () {
					clearInterval(interval);

					if (expireAction === 'hide') {
						thisHolder.hide();
					} else if (expireAction === 'message' && thisHolder.find('.evc-cd-expired-message').length) {
						thisHolder.addClass('evc-cd-expired');
						thisHolder.find('.evc-cd-timer').attr('hidden', '');
						thisHolder.find('.evc-cd-expired-message').removeAttr('hidden');
					}
				};

				var update = function () {
					var remaining = Math.max(0, Math.floor((endTime - Date.now()) / 1000));

					// Values of hidden units are added to the next visible unit
					digits.each(function () {
						var digit = $(this),
							seconds = unitSeconds[digit.data('unit')] || 1,
							value = Math.floor(remaining / seconds);

						remaining -= value * seconds;
						digit.text(value < 10 ? '0' + value : value);
					});

					if (endTime <= Date.now()) {
						expire();
					}
				};

				update();
				interval = setInterval(update, 1000);
			});
		}
	}

	/**
	 * Get evergreen countdown end time, end time is saved into local storage when visitor opens the page for the first time
	 */
	function evcGetEvergreenEndTime(id, duration) {
		var key = 'evcCountdown' + id,
			endTime = 0;

		try {
			endTime = parseInt(window.localStorage.getItem(key), 10) || 0;
		} catch (e) {
			endTime = 0;
		}

		if (!endTime) {
			endTime = Date.now() + duration * 1000;

			try {
				window.localStorage.setItem(key, endTime);
			} catch (e) {
				// Local storage is not available, countdown starts again on every page load
			}
		}

		return endTime;
	}

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

				evc.evcIsInViewport.check(thisCounter, function () {
					thisCounter.css('opacity', '1');

					digit.countTo({
						from: 0,
						to: parseFloat(digit.text()),
						speed: 1500,
						refreshInterval: 100
					});
				});
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
			evc.evcIsInViewport.check(holder, function () {
				holder.addClass('evc-dc-appeared');
				
				var chart = new Chart(holder.children('canvas')[0], {
					type: 'doughnut',
					data: evcDoughnutChart.getChartData(holder),
					options: evcDoughnutChart.getChartOptions(holder)
				});
				
				evcDoughnutChart.charts.push(chart);
			});
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
			options['plugins'] = {
				legend: {
					display: !!enableLegend,
					position: legendPosition,
					labels: {
						font: {
							size: legendTextSize
						},
						color: legendColor
					}
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
					evc.evcIsInViewport.check(thisBar, function () {
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
					});
				}
			});
		}
	}

})(jQuery);

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitImageAccordion();
	});

	/**
	 * Init image accordion shortcode functionality, item is opened on hover or click and on keyboard focus
	 */
	function evcInitImageAccordion() {
		var holder = $('.evc-image-accordion');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					openOnHover = thisHolder.data('behavior') !== 'click',
					items = thisHolder.find('> .evc-ia-items > .evc-ia-item'),
					defaultItem = items.filter('.evc-ia-active');

				var setActive = function (item) {
					items.each(function () {
						var isActive = item && this === item[0];

						$(this).toggleClass('evc-ia-active', isActive).children('.evc-ia-trigger').attr('aria-expanded', isActive ? 'true' : 'false');
					});
				};

				items.children('.evc-ia-trigger').on('click', function () {
					var item = $(this).parent();

					// Click on opened item closes it when accordion is opened on click
					setActive(!openOnHover && item.hasClass('evc-ia-active') ? null : item);
				});

				// Item with focused link or trigger is opened, so its content is visible
				items.on('focusin', function () {
					setActive($(this));
				});

				if (openOnHover) {
					items.on('mouseenter', function () {
						setActive($(this));
					});

					thisHolder.on('mouseleave', function () {
						setActive(defaultItem.length ? defaultItem : null);
					});
				}
			});
		}
	}

})(jQuery);

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitImageComparison();
	});

	/**
	 * Init image comparison shortcode functionality
	 * Range input is used for keyboard and screen readers, pointer events are used for dragging
	 */
	function evcInitImageComparison() {
		var holder = $('.evc-image-comparison');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					element = thisHolder[0],
					inner = thisHolder.find('.evc-ic-inner')[0],
					range = thisHolder.find('.evc-ic-range')[0],
					isVertical = thisHolder.hasClass('evc-ic-vertical'),
					moveOnHover = thisHolder.hasClass('evc-ic-move-on-hover'),
					isDragging = false;

				if (!inner || !range) {
					return;
				}

				var setPosition = function (position) {
					position = Math.min(100, Math.max(0, position));

					element.style.setProperty('--evc-ic-position', position + '%');
					range.value = position;
				};

				var getPointerPosition = function (event) {
					var rect = inner.getBoundingClientRect();

					return isVertical ? (event.clientY - rect.top) / rect.height * 100 : (event.clientX - rect.left) / rect.width * 100;
				};

				range.addEventListener('input', function () {
					setPosition(parseFloat(range.value));
				});

				inner.addEventListener('pointerdown', function (event) {
					isDragging = true;
					inner.setPointerCapture(event.pointerId);
					setPosition(getPointerPosition(event));
					thisHolder.addClass('evc-ic-dragging');
				});

				inner.addEventListener('pointermove', function (event) {
					if (isDragging || (moveOnHover && event.pointerType === 'mouse')) {
						setPosition(getPointerPosition(event));
					}
				});

				var stopDragging = function () {
					isDragging = false;
					thisHolder.removeClass('evc-ic-dragging');
				};

				inner.addEventListener('pointerup', stopDragging);
				inner.addEventListener('pointercancel', stopDragging);
			});
		}
	}

})(jQuery);

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitImageHotspot();
	});

	/**
	 * Init image hotspot shortcode functionality, tooltip is opened on hover/focus or click
	 */
	function evcInitImageHotspot() {
		var holder = $('.evc-image-hotspot');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					openOnHover = thisHolder.data('trigger') !== 'click',
					items = thisHolder.find('.evc-ih-item').has('.evc-ih-tooltip');

				var toggleItem = function (item, open) {
					item.toggleClass('evc-ih-open', open).children('.evc-ih-marker').attr('aria-expanded', open ? 'true' : 'false');

					if (open) {
						evcFitTooltip(item);
					}
				};

				var closeOthers = function (item) {
					items.not(item).filter('.evc-ih-open').each(function () {
						toggleItem($(this), false);
					});
				};

				items.children('.evc-ih-marker').on('click', function () {
					// Tooltip which is opened on hover stays opened on click, so it works the same on touch devices
					var item = $(this).parent(),
						open = openOnHover || !item.hasClass('evc-ih-open');

					closeOthers(item);
					toggleItem(item, open);
				});

				if (openOnHover) {
					items.on('mouseenter focusin', function () {
						closeOthers($(this));
						toggleItem($(this), true);
					}).on('mouseleave', function () {
						if (!$(this).find(':focus').length) {
							toggleItem($(this), false);
						}
					}).on('focusout', function (event) {
						if (!$.contains(this, event.relatedTarget)) {
							toggleItem($(this), false);
						}
					});
				}

				// Opened tooltip is closed with Escape key or click outside of hotspot
				$(document).on('keydown', function (event) {
					if (event.key === 'Escape') {
						items.filter('.evc-ih-open').each(function () {
							toggleItem($(this), false);

							if ($(this).find(':focus').length) {
								$(this).children('.evc-ih-marker').trigger('focus');
							}
						});
					}
				}).on('click', function (event) {
					if (!$(event.target).closest('.evc-ih-item').length) {
						items.filter('.evc-ih-open').each(function () {
							toggleItem($(this), false);
						});
					}
				});
			});
		}
	}

	/**
	 * Move tooltip horizontally, so it doesn't go outside of the screen
	 *
	 * @param item object - hotspot item
	 */
	function evcFitTooltip(item) {
		var tooltip = item.children('.evc-ih-tooltip')[0],
			space = 10,
			shift = 0;

		if (!tooltip) {
			return;
		}

		tooltip.style.setProperty('--evc-ih-shift', '0px');

		var rect = tooltip.getBoundingClientRect(),
			viewportWidth = document.documentElement.clientWidth;

		if (rect.left < space) {
			shift = space - rect.left;
		} else if (rect.right > viewportWidth - space) {
			shift = viewportWidth - space - rect.right;
		}

		tooltip.style.setProperty('--evc-ih-shift', Math.round(shift) + 'px');
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
			evc.evcIsInViewport.check(holder, function () {
				holder.addClass('evc-pc-appeared');
				
				var chart = new Chart(holder.children('canvas')[0], {
					type: 'pie',
					data: evcPieChart.getChartData(holder),
					options: evcPieChart.getChartOptions(holder)
				});
				
				evcPieChart.charts.push(chart);
			});
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
			options['plugins'] = {
				legend: {
					display: !!enableLegend,
					position: legendPosition,
					labels: {
						font: {
							size: legendTextSize
						},
						color: legendColor
					}
				}
			};
			
			return options;
		}
	};
	
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
				
				evc.evcIsInViewport.check(thisHolder, function () {
					thisHolder.addClass('evc-process-appeared');
				});
			});
		}
	}
	
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
				
				evc.evcIsInViewport.check(thisHolder, function () {
					thisHolder.addClass('evc-process-appeared');
				});
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
					evc.evcIsInViewport.check(thisBar, function () {
						evcInitProgressBarCounter(percent, percentValue);
						
						if(isVerticalType) {
							barContent.stop().animate({'height': percentValue + '%'}, 1500);
						} else {
							barContent.stop().animate({'width': percentValue + '%'}, 1500);
						}
					});
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
		evcInitSectionTitleAnimatedWords();
	});

	/**
	 * Init section title animated words (typing and rotating effects)
	 */
	function evcInitSectionTitleAnimatedWords() {
		var holder = $('.evc-section-title .evc-st-animated');

		if (holder.length) {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			holder.each(function () {
				var thisHolder = $(this),
					words = thisHolder.data('words'),
					type = thisHolder.data('type'),
					delay = parseInt(thisHolder.data('delay'), 10) || 2000;

				// Animation is not used when user prefers reduced motion, first word is displayed
				if (reduceMotion || !Array.isArray(words) || words.length < 2) {
					return;
				}

				evc.evcIsInViewport.check(thisHolder, function () {
					if (type === 'rotating') {
						evcRotateWords(thisHolder, words, delay);
					} else {
						evcTypeWords(thisHolder, words, delay);
					}
				});
			});
		}
	}

	/**
	 * Typing effect, word is typed letter by letter and then deleted
	 */
	function evcTypeWords(holder, words, delay) {
		var element = holder[0],
			wordIndex = 0,
			charIndex = words[0].length,
			isDeleting = true;

		holder.addClass('evc-st-animated-active');

		var tick = function () {
			var word = words[wordIndex],
				timeout = isDeleting ? 40 : 90;

			charIndex += isDeleting ? -1 : 1;
			element.textContent = word.substring(0, charIndex);

			if (!isDeleting && charIndex === word.length) {
				isDeleting = true;
				timeout = delay;
			} else if (isDeleting && charIndex === 0) {
				isDeleting = false;
				wordIndex = (wordIndex + 1) % words.length;
				timeout = 300;
			}

			setTimeout(tick, timeout);
		};

		setTimeout(tick, delay);
	}

	/**
	 * Rotating effect, words are changed with fade animation
	 */
	function evcRotateWords(holder, words, delay) {
		var wordIndex = 0;

		setInterval(function () {
			holder.addClass('evc-st-animated-hide');

			setTimeout(function () {
				wordIndex = (wordIndex + 1) % words.length;
				holder[0].textContent = words[wordIndex];
				holder.removeClass('evc-st-animated-hide');
			}, 300);
		}, delay);
	}

})(jQuery);

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitSocialShare();
	});

	/**
	 * Init social share shortcode functionality, copy link button and device share menu button
	 */
	function evcInitSocialShare() {
		var holder = $('.evc-social-share');

		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this),
					status = thisHolder.find('.evc-ss-status'),
					statusTimeout;

				var showStatus = function (message) {
					clearTimeout(statusTimeout);
					status.text(message).addClass('evc-ss-status-visible');

					statusTimeout = setTimeout(function () {
						status.removeClass('evc-ss-status-visible').text('');
					}, 2500);
				};

				thisHolder.find('.evc-ss-copy').on('click', function () {
					var button = $(this),
						url = button.data('url');

					evcCopyText(url).then(function () {
						showStatus(button.data('copied-label'));
					}, function () {
						// Clipboard is not available (e.g. page is not opened through https), so link is shown for manual copy
						window.prompt('', url);
					});
				});

				// Device share menu is available only in browsers which support Web Share API
				if (typeof navigator.share === 'function') {
					thisHolder.find('.evc-ss-item-native').removeAttr('hidden');

					thisHolder.find('.evc-ss-native').on('click', function () {
						var button = $(this);

						navigator.share({
							title: button.data('title'),
							url: button.data('url')
						}).catch(function () {
							// Share is canceled by the user
						});
					});
				}
			});
		}
	}

	/**
	 * Copy text into clipboard
	 *
	 * @param text string
	 *
	 * @return Promise
	 */
	function evcCopyText(text) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(text);
		}

		return new Promise(function (resolve, reject) {
			var field = $('<textarea readonly></textarea>').val(text).css({position: 'fixed', top: 0, left: 0, opacity: 0}).appendTo('body');

			field[0].select();

			try {
				document.execCommand('copy') ? resolve() : reject();
			} catch (error) {
				reject();
			}

			field.remove();
		});
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
		evcInitTableOfContents();
	});

	/**
	 * Init table of contents shortcode functionality, list is generated from headings in the page content
	 */
	function evcInitTableOfContents() {
		var holder = $('.evc-table-of-contents');

		if (holder.length) {
			var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

			holder.each(function () {
				var thisHolder = $(this),
					list = thisHolder.find('.evc-toc-list'),
					listTag = list.prop('tagName').toLowerCase(),
					levels = String(thisHolder.data('headings') || 'h2,h3').split(','),
					hierarchical = thisHolder.data('hierarchical') !== 'no',
					offset = parseInt(thisHolder.data('scroll-offset'), 10) || 0,
					container = evcGetTableOfContentsContainer(thisHolder),
					links = [];

				var headings = $(container).find(levels.join(',')).filter(function () {
					var heading = $(this);

					return !heading.closest('.evc-table-of-contents, .evc-toc-exclude').length && heading.is(':visible') && heading.text().trim() !== '';
				});

				if (!headings.length) {
					return;
				}

				// Each heading is added under the closest previous heading with lower level
				var stack = [{level: 0, list: list}];

				headings.each(function () {
					var heading = $(this),
						level = parseInt(this.tagName.substring(1), 10);

					if (!this.id) {
						this.id = evcGetUniqueHeadingId(heading.text());
					}

					if (hierarchical) {
						while (stack.length > 1 && stack[stack.length - 1].level >= level) {
							stack.pop();
						}
					}

					var parent = hierarchical ? stack[stack.length - 1] : stack[0],
						parentList = parent.list;

					if (!parentList) {
						parentList = parent.list = $('<' + listTag + ' class="evc-toc-sublist"></' + listTag + '>').appendTo(parent.item);
					}

					var item = $('<li class="evc-toc-item"></li>').appendTo(parentList),
						link = $('<a class="evc-toc-link"></a>').attr('href', '#' + this.id).text(heading.text().trim()).appendTo(item);

					links.push({link: link, heading: this});

					if (hierarchical) {
						stack.push({level: level, item: item, list: null});
					}
				});

				thisHolder.addClass('evc-toc-ready');

				list.on('click', '.evc-toc-link', function (event) {
					var heading = document.getElementById(this.getAttribute('href').substring(1));

					if (!heading) {
						return;
					}

					event.preventDefault();

					window.scrollTo({
						top: heading.getBoundingClientRect().top + window.pageYOffset - offset,
						behavior: reduceMotion ? 'auto' : 'smooth'
					});

					if (window.history && window.history.replaceState) {
						window.history.replaceState(null, '', '#' + heading.id);
					}

					// Focus is moved to the heading, so keyboard users continue from the selected section
					if (!heading.hasAttribute('tabindex')) {
						heading.setAttribute('tabindex', '-1');
					}

					heading.focus({preventScroll: true});
				});

				thisHolder.find('.evc-toc-toggle').on('click', function () {
					var button = $(this),
						body = thisHolder.find('.evc-toc-body'),
						expand = button.attr('aria-expanded') !== 'true';

					button.attr('aria-expanded', expand ? 'true' : 'false');
					body.prop('hidden', !expand);
				});

				if (thisHolder.data('highlight') !== 'no') {
					evcInitTableOfContentsHighlight(links, offset);
				}

				if (thisHolder.hasClass('evc-toc-sticky')) {
					evcInitTableOfContentsStickyColumn(thisHolder);
				}
			});
		}
	}

	/**
	 * Get content which contains headings
	 *
	 * @param holder object - table of contents element
	 *
	 * @return object
	 */
	function evcGetTableOfContentsContainer(holder) {
		var selector = holder.data('container'),
			container = null;

		if (selector) {
			try {
				container = document.querySelector(selector);
			} catch (error) {
				container = null;
			}
		}

		if (!container) {
			container = holder.closest('.wpb-content-wrapper, .entry-content, article, main')[0] || document.body;
		}

		return container;
	}

	/**
	 * Get unique id for heading from its text
	 *
	 * @param text string - heading text
	 *
	 * @return string
	 */
	function evcGetUniqueHeadingId(text) {
		var slug = text.trim().toLowerCase().normalize('NFKD').replace(/[̀-ͯ]/g, '').replace(/[^a-z0-9Ѐ-ӿ]+/g, '-').replace(/^-+|-+$/g, ''),
			id = slug || 'section',
			index = 2;

		while (document.getElementById(id)) {
			id = (slug || 'section') + '-' + index++;
		}

		return id;
	}

	/**
	 * Sticky element follows the scroll only inside its parent, so the column and its inner wrappers are stretched to the row height.
	 * Many themes don't support equal height columns, so the height is set with script
	 *
	 * @param holder object - table of contents element
	 */
	function evcInitTableOfContentsStickyColumn(holder) {
		var column = holder.closest('.vc_column_container, .wpb_column');

		if (!column.length || !column.parent().length) {
			return;
		}

		var wrappers = holder.parentsUntil(column.parent());

		var update = function () {
			wrappers.css('min-height', '');

			// Columns are stacked on small screens, so there is no space for sticky behavior
			if (window.innerWidth <= 768) {
				return;
			}

			var rowBottom = column.parent()[0].getBoundingClientRect().bottom;

			wrappers.each(function () {
				var height = rowBottom - this.getBoundingClientRect().top;

				if (height > this.offsetHeight) {
					this.style.minHeight = Math.floor(height) + 'px';
				}
			});
		};

		update();
		$(window).on('load resize', update);

		// Css sticky position doesn't work inside elements with hidden overflow (used by many themes), so element is moved with script
		var hasClippedParent = holder.parentsUntil('html').filter(function () {
			return $(this).css('overflow-x') !== 'visible' || $(this).css('overflow-y') !== 'visible';
		}).length > 0;

		if (hasClippedParent) {
			var offset = parseInt(holder.css('top'), 10) || 0,
				currentShift = 0,
				ticking = false;

			holder.addClass('evc-toc-sticky-js');

			var move = function () {
				var shift = 0;

				if (window.innerWidth > 768) {
					var top = holder[0].getBoundingClientRect().top - currentShift,
						maxShift = holder.parent()[0].getBoundingClientRect().bottom - top - holder.outerHeight();

					shift = Math.max(0, Math.min(offset - top, maxShift));
				}

				currentShift = Math.round(shift);
				holder.css('transform', currentShift ? 'translateY(' + currentShift + 'px)' : '');
				ticking = false;
			};

			$(window).on('scroll resize', function () {
				if (!ticking) {
					ticking = true;
					window.requestAnimationFrame(move);
				}
			});

			move();
		}
	}

	/**
	 * Highlight link of the section which is currently read
	 *
	 * @param links array - list of links and headings
	 * @param offset int - scroll offset
	 */
	function evcInitTableOfContentsHighlight(links, offset) {
		var ticking = false;

		var update = function () {
			var current = null;

			$.each(links, function (index, item) {
				if (item.heading.getBoundingClientRect().top - offset - 10 <= 0) {
					current = item;
				}
			});

			$.each(links, function (index, item) {
				var isCurrent = item === current;

				item.link.toggleClass('evc-toc-active', isCurrent);

				if (isCurrent) {
					item.link.attr('aria-current', 'location');
				} else {
					item.link.removeAttr('aria-current');
				}
			});

			ticking = false;
		};

		$(window).on('scroll', function () {
			if (!ticking) {
				ticking = true;
				window.requestAnimationFrame(update);
			}
		});

		update();
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
				
				evc.evcIsInViewport.check(thisTabs, function () {
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
					auxText = marqueeElements.filter('.evc-tm-aux'),
					delta = 1, // pixel movement
					speedCoeff = 0.8, // below 1 to slow down, above 1 to speed up
					marqueeWidth = 0,
					positions = [],
					isVisible = false,
					isRunning = false,
					resizeTimeout;

				if (!originalText.length || !auxText.length) {
					return;
				}

				// Calculate element width from its natural size, so previous inline width doesn't affect the result
				var calcWidth = function () {
					marqueeElements.css('width', '');

					return Math.max(thisTextMarquee.outerWidth(), originalText.outerWidth());
				};

				var setPositions = function () {
					marqueeElements.each(function (i) {
						$(this).css('transform', 'translate3d(' + positions[i] + 'px,0,0)');
					});
				};

				// Set the same width to both elements and place aux element right after the original one
				var setup = function () {
					marqueeWidth = calcWidth();
					positions = [0, marqueeWidth];

					marqueeElements.css({'width': marqueeWidth, 'left': 0});
					setPositions();
				};

				// Movement loop
				var marqueeEffect = function () {
					if (!isVisible) {
						isRunning = false;

						return;
					}

					for (var i = 0; i < positions.length; i++) {
						positions[i] -= delta * speedCoeff;

						// Element is moved out of the view, so place it after the other element
						if (positions[i] <= -marqueeWidth) {
							positions[i] += marqueeWidth * positions.length;
						}
					}

					setPositions();
					window.requestAnimationFrame(marqueeEffect);
				};

				var start = function () {
					isVisible = true;

					if (!isRunning) {
						isRunning = true;
						window.requestAnimationFrame(marqueeEffect);
					}
				};

				setup();

				// Recalculate width when web fonts are loaded, text width can be changed after font swap
				if (document.fonts && document.fonts.ready) {
					document.fonts.ready.then(setup);
				}

				$(window).on('load', setup);

				$(window).on('resize', function () {
					clearTimeout(resizeTimeout);
					resizeTimeout = setTimeout(setup, 150);
				});

				// Animate only while element is in the viewport
				thisTextMarquee.data('viewport-offset', 0);
				evc.evcIsInViewport.check(thisTextMarquee, start, false, function () {
					isVisible = false;
				});
			});
		}
	}

})(jQuery);

(function ($) {
	'use strict';

	$(document).ready(function () {
		evcInitTimeline();
	});

	/**
	 * Init timeline shortcode appear animation, items are shown when they are scrolled into the viewport
	 */
	function evcInitTimeline() {
		var holder = $('.evc-timeline.evc-tl-has-animation');

		if (holder.length && !(window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches)) {
			holder.each(function () {
				// Items are hidden only when script is loaded, so content is visible without javascript
				$(this).find('.evc-timeline-item').each(function () {
					var item = $(this);

					item.addClass('evc-tl-hidden');

					evc.evcIsInViewport.check(item, function () {
						item.removeClass('evc-tl-hidden');
					});
				});
			});
		}
	}

})(jQuery);

/******/ })()
;