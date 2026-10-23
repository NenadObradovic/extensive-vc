<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-tabs-item <?php echo esc_attr( $holder_classes ); ?>" id="tab-<?php echo esc_attr( sanitize_title( $tab_title ) ); ?>">
	<?php do_action( 'extensive_vc_action_tabs_item_before_content', $params ); ?>
	<?php echo do_shortcode( $content ); ?>
	<?php do_action( 'extensive_vc_action_tabs_item_after_content', $params ); ?>
</div>
