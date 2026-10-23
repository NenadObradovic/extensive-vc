<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_button', $params ); ?>
<?php if ( $button_has_alignment ) { ?>
	<div class="evc-button-wrapper evc-btn-<?php echo esc_attr( $button_alignment ); ?>">
<?php } ?>
<a class="evc-button evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_link_attributes( $link_attributes ); ?> <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data ); ?>>
	<?php do_action( 'extensive_vc_action_button_before_content', $params ); ?>
	<span class="evc-btn-original-text" <?php extensive_vc_print_inline_style( $fill_text_original_styles ); ?>>
	    <?php extensive_vc_template_part( 'shortcodes', 'button', 'templates/parts/icon', '', $params ); ?>
	    <?php extensive_vc_template_part( 'shortcodes', 'button', 'templates/parts/text', '', $params ); ?>
	</span>
	<span class="evc-btn-hover-text" <?php extensive_vc_print_inline_style( $fill_text_hover_styles ); ?>>
	    <?php extensive_vc_template_part( 'shortcodes', 'button', 'templates/parts/icon', '', $params ); ?>
	    <?php extensive_vc_template_part( 'shortcodes', 'button', 'templates/parts/text', '', $params ); ?>
	</span>
	<?php do_action( 'extensive_vc_action_button_after_content', $params ); ?>
</a>
<?php if ( $button_has_alignment ) { ?>
	</div>
<?php } ?>
<?php do_action( 'extensive_vc_action_after_button', $params ); ?>
