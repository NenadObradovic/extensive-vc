(function ($) {
	"use strict";
	
	$(document).ready(function () {
		evcInitColorPicker();
	});
	
	/**
	 * Init color picker functionality for admin options
	 */
	function evcInitColorPicker() {
		var holder = $('input.evc-color-picker');
		
		if (holder.length) {
			holder.each(function () {
				var thisHolder = $(this);

				thisHolder.wpColorPicker();
			});
		}
	}
	
})(jQuery);