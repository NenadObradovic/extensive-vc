<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$current_post_id = get_the_ID();
$title           = get_the_title( $current_post_id );
$image           = get_post_meta( $current_post_id, 'evc_client_image', true );
$hover_image     = get_post_meta( $current_post_id, 'evc_client_hover_image', true );
$custom_link     = get_post_meta( $current_post_id, 'evc_client_link', true );

$item_classes   = array();
$item_classes[] = $type === 'gallery' ? 'evc-element-item' : '';
$item_classes[] = ! empty( $hover_image ) ? 'evc-c-has-hover' : '';

$item_classes = implode( ' ', $item_classes );

?>
<div class="evc-c-item <?php echo esc_attr( $item_classes ); ?>">
	<div class="evc-c-item-inner">
		<div class="evc-c-images">
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
		<?php if ( isset( $enable_title ) && $enable_title === 'yes' && ! empty( $title ) ) { ?>
			<<?php echo esc_attr( $title_tag ); ?> class="evc-c-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php if ( ! empty( $custom_link ) ) { ?>
					<a href="<?php echo esc_url( $custom_link ); ?>" target="<?php echo esc_attr( $custom_link_target ); ?>">
				<?php } ?>
					<?php echo esc_html( $title ); ?>
				<?php if ( ! empty( $custom_link ) ) { ?>
					</a>
				<?php } ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php } ?>
	</div>
</div>
