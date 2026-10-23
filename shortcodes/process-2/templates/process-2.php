<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_process_2', $params ); ?>
<div class="evc-process-2 evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_process_2_before_content', $params ); ?>
	<div class="evc-p2-cover-bg" <?php extensive_vc_print_inline_style( $bg_cover_styles ); ?>></div>
	<div class="evc-p2-inner">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php do_action( 'extensive_vc_action_process_2_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_process_2', $params ); ?>
