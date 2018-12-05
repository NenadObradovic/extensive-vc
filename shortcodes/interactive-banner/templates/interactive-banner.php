<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-interactive-banner evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-ib-image">
		<?php if ( is_array( $image_size ) ) {
			echo extensive_vc_generate_thumbnail( $image, $image_size[0], $image_size[1] );
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
					echo extensive_vc_get_module_template_part( 'shortcodes', 'interactive-banner', 'templates/parts/icon', '', $params );
				}
				
				echo extensive_vc_get_module_template_part( 'shortcodes', 'interactive-banner', 'templates/parts/title', '', $params );
				
				echo extensive_vc_get_module_template_part( 'shortcodes', 'interactive-banner', 'templates/parts/text', '', $params );
				?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php echo implode( ' ', $link_attributes ); ?>></a>
	<?php } ?>
</div>
