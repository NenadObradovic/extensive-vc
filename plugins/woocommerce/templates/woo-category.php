<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $enable_category ) ) {
	$enable_category = 'no';
}

if ( extensive_vc_check_product_category_visibility( $enable_category === 'yes' ) ) {
	global $product;
	
	$product_categories = wc_get_product_category_list( $product->get_id(), ', ' );
	
	if ( ! empty( $product_categories ) ) { ?>
		<p class="evc-pli-category"><?php echo wp_kses_post( $product_categories ); ?></p>
	<?php } ?>
<?php } ?>
