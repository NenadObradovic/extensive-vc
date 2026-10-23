<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_icon_list', $params ); ?>
<div class="evc-icon-list evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_icon_list_before_content', $params ); ?>
	<?php echo do_shortcode( $content ); ?>
	<?php do_action( 'extensive_vc_action_icon_list_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_icon_list', $params ); ?>
