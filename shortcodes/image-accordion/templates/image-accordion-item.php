<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-ia-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_image_accordion_item_before_content', $params ); ?>
	<?php if ( ! empty( $image ) ) { ?>
		<div class="evc-ia-image">
			<?php echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) ); ?>
		</div>
	<?php } ?>
	<button type="button" class="evc-ia-trigger" aria-expanded="<?php echo $is_active ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $item_id ); ?>">
		<span class="evc-screen-reader-text"><?php echo esc_html( '' !== $title ? $title : esc_html__( 'Open item', 'extensive-vc-addon' ) ); ?></span>
	</button>
	<div class="evc-ia-content" id="<?php echo esc_attr( $item_id ); ?>">
		<div class="evc-ia-content-inner">
			<?php if ( ! empty( $title ) ) { ?>
				<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h4' ) ); ?> class="evc-ia-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h4' ) ); ?>>
			<?php } ?>
			<?php if ( ! empty( $text ) ) { ?>
				<p class="evc-ia-text"><?php echo esc_html( $text ); ?></p>
			<?php } ?>
			<?php if ( ! empty( $link_attributes ) ) { ?>
				<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>><?php echo esc_html( $link_text ); ?></a>
			<?php } ?>
		</div>
	</div>
	<?php do_action( 'extensive_vc_action_image_accordion_item_after_content', $params ); ?>
</div>
