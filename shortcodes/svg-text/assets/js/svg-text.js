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