<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'woocommerce_template_loop_add_to_cart' ) ) { ?>
	<div class="evc-pli-add-to-cart">
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</div>
<?php }
