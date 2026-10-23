<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$id = wp_rand( 1000, 9999 );

wp_enqueue_style( 'lightbox' );
wp_enqueue_script( 'lightbox' );
?>
<?php do_action( 'extensive_vc_action_before_image_with_text', $params ); ?>
<div class="evc-image-with-text evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_image_with_text_before_content', $params ); ?>
	<div class="evc-iwt-image <?php echo esc_attr( $image_classes ); ?>">
		<?php if ( ! empty( $link_attributes ) && $image_behavior !== 'lightbox' ) { ?>
			<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>>
		<?php } ?>
		
		<?php if ( $image_behavior === 'lightbox' ) { ?>
			<a class="evc-iwt-lightbox" href="<?php echo esc_url( wp_get_attachment_url( $image ) ); ?>" data-lightbox="evc-iwt-lb-<?php echo esc_attr( $id ); ?>" data-title="<?php echo esc_attr( get_the_title( $image ) ); ?>">
		<?php } ?>
		
		<?php
			if ( is_array( $image_size ) && count( $image_size ) ) {
				echo wp_kses_post( extensive_vc_generate_thumbnail( $image, $image_size[0], $image_size[1] ) );
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
			<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-iwt-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-iwt-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
	<?php do_action( 'extensive_vc_action_image_with_text_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_image_with_text', $params ); ?>
