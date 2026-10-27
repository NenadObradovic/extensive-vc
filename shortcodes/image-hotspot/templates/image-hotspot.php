<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_image_hotspot', $params ); ?>
<div class="evc-image-hotspot evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" data-trigger="<?php echo esc_attr( $trigger ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_image_hotspot_before_content', $params ); ?>
	<div class="evc-ih-inner">
		<?php echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) ); ?>
		<?php echo $items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Items output is escaped in image hotspot item template. ?>
	</div>
	<?php do_action( 'extensive_vc_action_image_hotspot_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_image_hotspot', $params ); ?>
