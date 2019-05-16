<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$id = rand( 1000, 9999 );

wp_enqueue_style( 'lightbox' );
wp_enqueue_script( 'lightbox' );
?>
<div class="evc-single-image evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-si-inner <?php echo esc_attr( $image_classes ); ?>">
		<?php if ( ! empty( $link_attributes ) && $image_behavior !== 'lightbox' ) { ?>
			<a <?php echo implode( ' ', $link_attributes ); ?>>
		<?php } ?>
		
		<?php if ( $image_behavior === 'lightbox' ) { ?>
			<a class="evc-si-lightbox" href="<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>" data-lightbox="evc-si-lb-<?php echo esc_attr( $id ); ?>" data-title="<?php echo get_the_title( $image ); ?>">
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
</div>
