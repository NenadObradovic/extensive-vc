<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-il-item <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_icon_list_item_before_content', $params ); ?>
	<div class="evc-ili-inner">
		<?php extensive_vc_template_part( 'shortcodes', 'icon-list', 'templates/parts/icon', '', $params ); ?>
		<?php extensive_vc_template_part( 'shortcodes', 'icon-list', 'templates/parts/text', '', $params ); ?>
		<?php extensive_vc_template_part( 'shortcodes', 'icon-list', 'templates/parts/link', '', $params ); ?>
	</div>
	<?php do_action( 'extensive_vc_action_icon_list_item_after_content', $params ); ?>
</div>
