<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-flip-image evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-fi-image">
		<?php
		if ( is_array( $image_size ) && count( $image_size ) ) {
			echo extensive_vc_generate_thumbnail( $image, $image_size[0], $image_size[1] );
		} else {
			echo wp_get_attachment_image( $image, $image_size );
		}
		?>
	</div>
	<div class="evc-fi-content-wrapper" <?php extensive_vc_print_inline_style( $content_styles ); ?>>
		<div class="evc-fi-content-inner">
			<div class="evc-fi-content">
				<?php if ( ! empty( $title ) ) { ?>
					<<?php echo esc_attr( $title_tag ); ?> class="evc-fi-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
					<?php echo esc_html( $title ); ?>
				</<?php echo esc_attr( $title_tag ); ?>>
				<?php } ?>
				<?php if ( ! empty( $text ) ) { ?>
					<p class="evc-fi-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php echo implode( ' ', $link_attributes ); ?>></a>
	<?php } ?>
</div>
