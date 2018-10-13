<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'Chart' );
?>
<div class="evc-pie-chart evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<canvas class="evc-pci-canvas" width="260" height="260" <?php extensive_vc_print_inline_style( $canvas_styles ); ?>></canvas>
	<?php echo do_shortcode( $content ); ?>
</div>
