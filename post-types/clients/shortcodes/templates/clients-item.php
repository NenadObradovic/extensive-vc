<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$item_classes = ! empty( $hover_image ) ? 'evc-c-has-hover' : '';
?>
<div class="evc-c-item <?php echo esc_attr( $item_classes ); ?>">
	<?php if ( ! empty( $custom_link ) ) { ?>
		<a class="evc-c-link" href="<?php echo esc_url( $custom_link ); ?>" target="<?php echo esc_attr( $custom_link_target ); ?>">
	<?php } ?>
		<?php if ( ! empty( $image ) ) {
			echo wp_get_attachment_image( $image, 'full', false, array( 'class' => 'evc-c-image evc-c-original-image' ) );
		} ?>
		<?php if ( ! empty( $hover_image ) ) {
			echo wp_get_attachment_image( $hover_image, 'full', false, array( 'class' => 'evc-c-image evc-c-hover-image' ) );
		} ?>
	<?php if ( ! empty( $custom_link ) ) { ?>
		</a>
	<?php } ?>
</div>
