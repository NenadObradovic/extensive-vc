<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-process-2 evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-p2-cover-bg" <?php extensive_vc_print_inline_style( $bg_cover_styles ); ?>></div>
	<div class="evc-p2-inner">
		<?php echo do_shortcode( $content ); ?>
	</div>
</div>
