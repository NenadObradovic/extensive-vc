<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'chart-js' );
?>
<?php do_action( 'extensive_vc_action_before_pie_chart', $params ); ?>
<div class="evc-pie-chart evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_pie_chart_before_content', $params ); ?>
	<canvas class="evc-pci-canvas" width="260" height="260" <?php extensive_vc_print_inline_style( $canvas_styles ); ?>></canvas>
	<?php echo do_shortcode( $content ); ?>
	<?php do_action( 'extensive_vc_action_pie_chart_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_pie_chart', $params ); ?>
