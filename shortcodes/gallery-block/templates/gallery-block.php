<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$i  = 0;
$id = rand( 1000, 9999 );

wp_enqueue_style( 'lightbox' );
wp_enqueue_script( 'lightbox' );
?>
<div class="evc-gallery-block evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-gb-wrapper evc-element-wrapper">
		<?php foreach ( $images as $image ) {
			$i++;
			
			$image_wrapper_classes = '';
			$image_size            = $default_image_size;
			if ( $i === 1 && $featured_image_size !== 'no-featured-image' ) {
				$image_wrapper_classes = 'evc-gb-featured-image';
				$image_size            = $featured_image_size;
			}
			?>
			<div class="evc-gb-image-wrapper evc-element-item <?php echo esc_attr( $image_wrapper_classes ); ?>">
				<div class="evc-gb-image <?php echo esc_attr( $image_classes ); ?>">
					<?php if ( ! empty( $custom_links ) && $image_behavior !== 'lightbox' ) {
						$custom_link_value = isset( $custom_links[ $i ] ) ? $custom_links[ $i ] : '#';
						?>
						<a class="evc-gb-custom-link" href="<?php echo esc_url( $custom_link_value ); ?>" target="<?php echo esc_attr( $custom_link_target ); ?>" title="<?php echo esc_attr( $image['title'] ); ?>">
					<?php } ?>
					
					<?php if ( $image_behavior === 'lightbox' ) { ?>
						<a class="evc-gb-lightbox" href="<?php echo esc_url( wp_get_attachment_url( $image['image_id'] ) ); ?>" data-lightbox="evc-gb-lb-<?php echo esc_attr( $id ); ?>" data-title="<?php echo get_the_title( $image['image_id'] ); ?>">
					<?php } ?>
					
					<?php
						if ( is_array( $image_size ) && count( $image_size ) ) {
							echo extensive_vc_generate_thumbnail( $image['image_id'], $image_size[0], $image_size[1] );
						} else {
							echo wp_get_attachment_image( $image['image_id'], $image_size );
						}
					?>
					
					<?php if ( ! empty( $custom_links ) || $image_behavior === 'lightbox' ) { ?>
						</a>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>
