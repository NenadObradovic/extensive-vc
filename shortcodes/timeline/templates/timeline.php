<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_timeline', $params ); ?>
<div class="evc-timeline evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_timeline_before_content', $params ); ?>
	<ol class="evc-tl-list">
		<?php echo $items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Items output is escaped in timeline item template. ?>
	</ol>
	<?php do_action( 'extensive_vc_action_timeline_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_timeline', $params ); ?>
