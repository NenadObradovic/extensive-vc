<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-process-2-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_process_2_item_before_content', $params ); ?>
	<div class="evc-p2i-image">
		<?php if ( ! empty( $link_attributes ) ) { ?>
			<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>>
		<?php } ?>
		
		<?php echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) ); ?>
		
		<?php if ( ! empty( $link_attributes ) ) { ?>
			</a>
		<?php } ?>
	</div>
	<div class="evc-p2i-content">
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-p2i-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-p2i-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
	<?php do_action( 'extensive_vc_action_process_2_item_after_content', $params ); ?>
</div>
