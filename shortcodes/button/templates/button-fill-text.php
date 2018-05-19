<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php if ( $button_has_alignment ) { ?>
	<div class="evc-button-wrapper evc-btn-<?php echo esc_attr( $button_alignment ); ?>">
<?php } ?>
<a class="evc-button evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php echo implode( ' ', $link_attributes ); ?> <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data ); ?>>
	<span class="evc-btn-original-text" <?php extensive_vc_print_inline_style( $fill_text_original_styles ); ?>>
	    <?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/icon', '', $params ); ?>
	    <?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/text', '', $params ); ?>
	</span>
	<span class="evc-btn-hover-text" <?php extensive_vc_print_inline_style( $fill_text_hover_styles ); ?>>
	    <?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/icon', '', $params ); ?>
	    <?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/text', '', $params ); ?>
	</span>
</a>
<?php if ( $button_has_alignment ) { ?>
	</div>
<?php } ?>
