<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $enable_ratings ) ) {
	$enable_ratings = 'yes';
}

if ( function_exists( 'woocommerce_template_loop_rating' ) && extensive_vc_check_product_rating_visibility( $enable_ratings === 'yes' ) ) { ?>
	<div class="evc-pli-ratings">
		<?php woocommerce_template_loop_rating(); ?>
	</div>
<?php }
