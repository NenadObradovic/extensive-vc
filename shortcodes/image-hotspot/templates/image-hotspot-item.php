<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-ih-item <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_image_hotspot_item_before_content', $params ); ?>
	<button type="button" class="evc-ih-marker evc-ih-marker-<?php echo esc_attr( $marker_type ); ?>" <?php echo $has_tooltip ? 'aria-expanded="false" aria-controls="' . esc_attr( $item_id ) . '"' : ''; ?>>
		<span class="evc-ih-marker-inner" aria-hidden="true">
			<?php if ( 'number' === $marker_type ) { ?>
				<?php echo esc_html( $index ); ?>
			<?php } elseif ( 'text' === $marker_type ) { ?>
				<?php echo esc_html( $marker_text ); ?>
			<?php } ?>
		</span>
		<span class="evc-screen-reader-text"><?php echo esc_html( $marker_label ); ?></span>
	</button>
	<?php if ( $has_tooltip ) { ?>
		<div class="evc-ih-tooltip" id="<?php echo esc_attr( $item_id ); ?>">
			<?php if ( ! empty( $title ) ) { ?>
				<p class="evc-ih-title"><?php echo esc_html( $title ); ?></p>
			<?php } ?>
			<?php if ( ! empty( $text ) ) { ?>
				<p class="evc-ih-text"><?php echo esc_html( $text ); ?></p>
			<?php } ?>
			<?php if ( ! empty( $link_attributes ) ) { ?>
				<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>><?php echo esc_html( $link_text ); ?></a>
			<?php } ?>
		</div>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_image_hotspot_item_after_content', $params ); ?>
</div>
