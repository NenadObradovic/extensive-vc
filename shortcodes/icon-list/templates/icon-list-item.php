<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-il-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-list', 'templates/parts/icon', '', $params ); ?>
	<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'icon-list', 'templates/parts/text', '', $params ); ?>
</div>