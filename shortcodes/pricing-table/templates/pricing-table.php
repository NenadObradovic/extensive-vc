<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pricing-table evc-shortcode evc-element-has-columns <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-pt-wrapper evc-element-wrapper">
		<?php echo do_shortcode( $content ); ?>
	</div>
</div>
