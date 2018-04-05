<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $enable_rating ) ) {
	$enable_rating = 'yes';
}

if ( function_exists( 'woocommerce_template_loop_rating' ) && extensive_vc_check_product_rating_visibility( $enable_rating === 'yes' ) ) { ?>
	<div class="evc-pli-ratings">
		<?php woocommerce_template_loop_rating(); ?>
	</div>
<?php }
