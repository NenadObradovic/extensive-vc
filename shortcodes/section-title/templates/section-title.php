<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-section-title evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<div class="evc-st-inner">
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( $title_tag ); ?> class="evc-st-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php } ?>
		<?php if ( $enable_separator === 'yes' ) { ?>
			<div class="evc-st-separator" <?php extensive_vc_print_inline_style( $separator_styles ); ?>></div>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<<?php echo esc_attr( $text_tag ); ?> class="evc-st-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>>
				<?php echo esc_html( $text ); ?>
			</<?php echo esc_attr( $text_tag ); ?>>
		<?php } ?>
		<?php if ( ! empty( $button_text ) && ! empty( $button_custom_link ) ) { ?>
			<div class="evc-st-button">
				<?php echo extensive_vc_render_shortcode( 'evc_button', $button_params ); ?>
			</div>
		<?php } ?>
	</div>
</div>
