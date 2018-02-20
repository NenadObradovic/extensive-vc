<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-icon-with-text evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-iwt-icon-holder">
		<?php if ( ! empty( $custom_icon ) ) {
			echo wp_get_attachment_image( $custom_icon, 'full' );
		} else {
			echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-with-text', 'templates/parts/icon', '', $params );
		} ?>
	</div>
	<div class="evc-iwt-content">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-with-text', 'templates/parts/text', '', $params ); ?>
	</div>
</div>
