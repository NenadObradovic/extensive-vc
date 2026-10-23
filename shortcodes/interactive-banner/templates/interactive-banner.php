<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_interactive_banner', $params ); ?>
<div class="evc-interactive-banner evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_interactive_banner_before_content', $params ); ?>
	<div class="evc-ib-image">
		<?php if ( is_array( $image_size ) ) {
			echo wp_kses_post( extensive_vc_generate_thumbnail( $image, $image_size[0], $image_size[1] ) );
		} else {
			echo wp_get_attachment_image( $image, $image_size );
		} ?>
	</div>
	<div class="evc-ib-content-wrapper" <?php extensive_vc_print_inline_style( $content_styles ); ?>>
		<div class="evc-ib-content-inner">
			<div class="evc-ib-content">
				<?php
				if ( ! empty( $custom_icon ) ) {
					echo wp_get_attachment_image( $custom_icon, 'full', false, array( 'class' => 'evc-ib-custom-icon' ) );
				} else {
					extensive_vc_template_part( 'shortcodes', 'interactive-banner', 'templates/parts/icon', '', $params );
				}
				
				extensive_vc_template_part( 'shortcodes', 'interactive-banner', 'templates/parts/title', '', $params );
				
				extensive_vc_template_part( 'shortcodes', 'interactive-banner', 'templates/parts/text', '', $params );
				?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>></a>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_interactive_banner_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_interactive_banner', $params ); ?>
