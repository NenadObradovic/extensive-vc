<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'chart-js' );
?>
<?php do_action( 'extensive_vc_action_before_line_graph', $params ); ?>
<div class="evc-line-graph evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_line_graph_before_content', $params ); ?>
	<canvas class="evc-lg-canvas"></canvas>
	<?php echo do_shortcode( $content ); ?>
	<?php do_action( 'extensive_vc_action_line_graph_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_line_graph', $params ); ?>