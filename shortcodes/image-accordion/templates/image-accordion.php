<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_image_accordion', $params ); ?>
<div class="evc-image-accordion evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" data-behavior="<?php echo esc_attr( $behavior ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_image_accordion_before_content', $params ); ?>
	<div class="evc-ia-items">
		<?php echo $items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Items output is escaped in image accordion item template. ?>
	</div>
	<?php do_action( 'extensive_vc_action_image_accordion_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_image_accordion', $params ); ?>
