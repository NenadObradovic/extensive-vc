<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-process evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-p-mark-horizontal">
		<?php for ( $i = 1; $i <= $number_of_items; $i ++ ) { ?>
			<div class="evc-p-mark">
				<div class="evc-p-line" <?php extensive_vc_print_inline_style( $line_styles ); ?>></div>
				<div class="evc-p-circle" <?php extensive_vc_print_inline_style( $circle_styles ); ?>><?php echo esc_attr( $i ); ?></div>
			</div>
		<?php } ?>
	</div>
	<div class="evc-p-mark-vertical">
		<?php for ( $i = 1; $i <= $number_of_items; $i ++ ) { ?>
			<div class="evc-p-mark">
				<div class="evc-p-line" <?php extensive_vc_print_inline_style( $line_styles ); ?>></div>
				<div class="evc-p-circle" <?php extensive_vc_print_inline_style( $circle_styles ); ?>><?php echo esc_attr( $i ); ?></div>
			</div>
		<?php } ?>
	</div>
	<div class="evc-p-inner">
		<?php echo do_shortcode( $content ); ?>
	</div>
</div>
