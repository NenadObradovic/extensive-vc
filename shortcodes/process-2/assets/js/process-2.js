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