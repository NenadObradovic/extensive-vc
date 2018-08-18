<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! empty( $pagination_type ) ) { ?>
	<div class="evc-bl-pagination evc-pagination-holder">
		<div class="evc-pagination-spinner evc-abs">
			<div class="evc-ps-bounce-1"></div>
			<div class="evc-ps-bounce-2"></div>
			<div class="evc-ps-bounce-3"></div>
		</div>
		<?php echo extensive_vc_render_shortcode( 'evc_button', $button_params ); ?>
	</div>
<?php } ?>