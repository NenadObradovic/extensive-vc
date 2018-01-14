(function ($) {
	"use strict";
	
	$(document).ready(function () {
		evcInitColorPicker();
		evcInitMediaUploader();
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
	
	function evcInitMediaUploader() {
		var holder = $('.evc-meta-box-image-holder');
		
		if (holder.length) {
			holder.each(function () {
				var thisItem = $(this),
					uploadFrame,
					uploadButton = thisItem.find('.evc-meta-box-upload-button'),
					removeUpload = thisItem.find('.evc-meta-box-remove-upload-button'),
					hiddenField = thisItem.find('input[type="hidden"]');
				
				uploadButton.on('click', function (e) {
					e.preventDefault();
					
					//if the upload media frame already exists, reopen it
					if (uploadFrame) {
						uploadFrame.open();
						return;
					}
					
					uploadFrame = wp.media.frames.fileFrame = wp.media({
						title: $(this).data('frame-title'),
						button: {
							text: $(this).data('frame-button-text')
						},
						library: {
							type: 'image'
						},
						multiple: false
					});
					
					uploadFrame.on('select', function () {
						var image = thisItem.find('.evc-meta-box-image'),
							attachment = uploadFrame.state().get('selection').first().toJSON();
						
						if (image.length) {
							image.remove();
						}
						
						if (attachment.hasOwnProperty('url')) {
							thisItem.prepend('<img class="evc-meta-box-image" src="' + attachment.url + '" />');
							hiddenField.val(attachment.id);
							removeUpload.show();
						}
					}).open();
				});
				
				removeUpload.on('click', function () {
					$(this).hide();
					hiddenField.val('');
					thisItem.find('.evc-meta-box-image').remove();
					
					return false;
				});
			});
		}
	}
	
})(jQuery);