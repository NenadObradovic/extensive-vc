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