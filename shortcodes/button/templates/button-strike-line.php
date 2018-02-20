<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<a class="evc-button evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php echo implode( ' ', $link_attributes ); ?> <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data ); ?>>
	<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/icon', '', $params ); ?>
	<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/text', '', $params ); ?>
	<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/parts/strike-line', '', $params ); ?>
</a>
