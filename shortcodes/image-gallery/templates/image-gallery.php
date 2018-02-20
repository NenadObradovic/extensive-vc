<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$i  = 0;
$id = rand( 1000, 9999 );
?>
<div class="evc-image-gallery evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-ig-wrapper evc-element-has-columns evc-element-wrapper <?php echo esc_attr( $inner_classes ); ?>">
		<?php foreach ( $images as $image ) { ?>
			<div class="evc-ig-image-item evc-element-item">
				<div class="evc-ig-image <?php echo esc_attr( $image_classes ); ?>">
					<?php if ( ! empty( $custom_links ) && $image_behavior !== 'lightbox' ) { ?>
						<a class="evc-ig-custom-link" href="<?php echo esc_url( $custom_links[ $i ++ ] ); ?>" target="<?php echo esc_attr( $custom_link_target ); ?>" title="<?php echo esc_attr( $image['title'] ); ?>">
					<?php } ?>
					
					<?php if ( $image_behavior === 'lightbox' ) { ?>
						<a class="evc-ig-lightbox" href="<?php echo esc_url( wp_get_attachment_url( $image['image_id'] ) ); ?>" data-lightbox="evc-ig-lb-<?php echo esc_attr( $id ); ?>" data-title="<?php echo get_the_title( $image['image_id'] ); ?>">
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
