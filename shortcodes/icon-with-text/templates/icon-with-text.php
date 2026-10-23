<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_icon_with_text', $params ); ?>
<div class="evc-icon-with-text evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_icon_with_text_before_content', $params ); ?>
	<div class="evc-iwt-icon-holder">
		<?php if ( ! empty( $custom_icon ) ) {
			echo wp_kses_post( extensive_vc_get_attachment_image( $custom_icon, $image_size ) );
		} else {
			extensive_vc_template_part( 'shortcodes', 'icon-with-text', 'templates/parts/icon', '', $params );
		} ?>
	</div>
	<div class="evc-iwt-content">
		<?php extensive_vc_template_part( 'shortcodes', 'icon-with-text', 'templates/parts/text', '', $params ); ?>
	</div>
	<?php do_action( 'extensive_vc_action_icon_with_text_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_icon_with_text', $params ); ?>
