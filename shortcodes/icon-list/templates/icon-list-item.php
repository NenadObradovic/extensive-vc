<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-il-item <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<div class="evc-ili-inner">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-list', 'templates/parts/icon', '', $params ); ?>
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-list', 'templates/parts/text', '', $params ); ?>
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-list', 'templates/parts/link', '', $params ); ?>
	</div>
</div>
