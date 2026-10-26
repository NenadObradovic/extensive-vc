<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_image_comparison', $params ); ?>
<div class="evc-image-comparison evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_image_comparison_before_content', $params ); ?>
	<div class="evc-ic-inner">
		<div class="evc-ic-image evc-ic-after">
			<?php echo wp_kses_post( extensive_vc_get_attachment_image( $after_image, $image_size ) ); ?>
			<?php if ( ! empty( $after_label ) ) { ?>
				<span class="evc-ic-label evc-ic-label-after" aria-hidden="true"><?php echo esc_html( $after_label ); ?></span>
			<?php } ?>
		</div>
		<div class="evc-ic-image evc-ic-before">
			<?php echo wp_kses_post( extensive_vc_get_attachment_image( $before_image, $image_size ) ); ?>
			<?php if ( ! empty( $before_label ) ) { ?>
				<span class="evc-ic-label evc-ic-label-before" aria-hidden="true"><?php echo esc_html( $before_label ); ?></span>
			<?php } ?>
		</div>
		<input class="evc-ic-range" type="range" min="0" max="100" step="0.5" value="<?php echo esc_attr( $start_position ); ?>" aria-label="<?php esc_attr_e( 'Before and after images comparison slider', 'extensive-vc-addon' ); ?>" <?php echo 'vertical' === $orientation ? 'aria-orientation="vertical"' : ''; ?>>
		<span class="evc-ic-handle" aria-hidden="true">
			<span class="evc-ic-handle-button">
				<?php extensive_vc_print_svg_icon( 'vertical' === $orientation ? 'chevron-up' : 'arrow-left' ); ?>
				<?php extensive_vc_print_svg_icon( 'vertical' === $orientation ? 'chevron-down' : 'arrow-right' ); ?>
			</span>
		</span>
	</div>
	<?php do_action( 'extensive_vc_action_image_comparison_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_image_comparison', $params ); ?>
