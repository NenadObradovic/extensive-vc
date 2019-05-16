<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$id = rand( 1000, 9999 );

wp_enqueue_style( 'lightbox' );
wp_enqueue_script( 'lightbox' );
?>
<div class="evc-image-with-text evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-iwt-image <?php echo esc_attr( $image_classes ); ?>">
		<?php if ( ! empty( $link_attributes ) && $image_behavior !== 'lightbox' ) { ?>
			<a <?php echo implode( ' ', $link_attributes ); ?>>
		<?php } ?>
		
		<?php if ( $image_behavior === 'lightbox' ) { ?>
			<a class="evc-iwt-lightbox" href="<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>" data-lightbox="evc-iwt-lb-<?php echo esc_attr( $id ); ?>" data-title="<?php echo get_the_title( $image ); ?>">
		<?php } ?>
		
		<?php
			if ( is_array( $image_size ) && count( $image_size ) ) {
				echo extensive_vc_generate_thumbnail( $image, $image_size[0], $image_size[1] );
			} else {
				echo wp_get_attachment_image( $image, $image_size );
			}
		?>
		
		<?php if ( ! empty( $link_attributes ) || $image_behavior === 'lightbox' ) { ?>
			</a>
		<?php } ?>
	</div>
	<div class="evc-iwt-content">
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( $title_tag ); ?> class="evc-iwt-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-iwt-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
</div>
