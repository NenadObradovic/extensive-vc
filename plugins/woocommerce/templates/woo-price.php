<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $enable_price ) ) {
	$enable_price = 'yes';
}

if ( function_exists( 'woocommerce_template_loop_price' ) && extensive_vc_check_product_price_visibility( $enable_price === 'yes' ) ) { ?>
	<div class="evc-pli-price">
		<?php woocommerce_template_loop_price(); ?>
	</div>
<?php }
