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