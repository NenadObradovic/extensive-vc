<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_separator', $params ); ?>
<div class="evc-separator evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_separator_before_content', $params ); ?>
	<div class="evc-s-inner" <?php extensive_vc_print_inline_style( $holder_styles ); ?>></div>
	<?php do_action( 'extensive_vc_action_separator_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_separator', $params ); ?>
