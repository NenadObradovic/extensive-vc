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