<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$i  = 0;
$id = wp_rand( 1000, 9999 );

wp_enqueue_style( 'owl-carousel' );
wp_enqueue_script( 'owl-carousel' );

wp_enqueue_style( 'lightbox' );
wp_enqueue_script( 'lightbox' );
?>
<?php do_action( 'extensive_vc_action_before_image_gallery', $params ); ?>
<div class="evc-image-gallery evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_image_gallery_before_content', $params ); ?>
	<div class="evc-ig-slider evc-owl-carousel" <?php extensive_vc_print_inline_attrs( $slider_data, true ); ?>>
		<?php foreach ( $images as $image ) { ?>
			<div class="evc-ig-image-item">
				<div class="evc-ig-image <?php echo esc_attr( $image_classes ); ?>">
					<?php if ( ! empty( $custom_links ) && $image_behavior !== 'lightbox' ) { ?>
						<a class="evc-ig-custom-link" href="<?php echo esc_url( $custom_links[ $i ++ ] ); ?>" target="<?php echo esc_attr( $custom_link_target ); ?>" title="<?php echo esc_attr( $image['title'] ); ?>">
					<?php } ?>
					
					<?php if ( $image_behavior === 'lightbox' ) { ?>
						<a class="evc-ig-lightbox" href="<?php echo esc_url( wp_get_attachment_url( $image['image_id'] ) ); ?>" data-lightbox="evc-ig-lb-<?php echo esc_attr( $id ); ?>" data-title="<?php echo esc_attr( get_the_title( $image['image_id'] ) ); ?>">
					<?php } ?>
						
					<?php
						if ( is_array( $image_size ) && count( $image_size ) ) {
							echo wp_kses_post( extensive_vc_generate_thumbnail( $image['image_id'], $image_size[0], $image_size[1] ) );
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
	<?php do_action( 'extensive_vc_action_image_gallery_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_image_gallery', $params ); ?>
