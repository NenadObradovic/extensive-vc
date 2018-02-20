<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-process-2-item <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-p2i-image">
		<?php if ( ! empty( $link_attributes ) ) { ?>
			<a <?php echo implode( ' ', $link_attributes ); ?>>
		<?php } ?>
		
		<?php echo wp_get_attachment_image( $image, 'full' ); ?>
		
		<?php if ( ! empty( $link_attributes ) ) { ?>
			</a>
		<?php } ?>
	</div>
	<div class="evc-p2i-content">
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( $title_tag ); ?> class="evc-p2i-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-p2i-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
</div>
