<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'woocommerce_get_product_thumbnail' ) && has_post_thumbnail() ) {
	if ( ! isset( $image_proportions ) ) {
		$image_proportions = '';
	}
	?>
	<div class="evc-pli-image">
		<?php if ( function_exists( 'woocommerce_show_product_loop_sale_flash' ) && extensive_vc_check_product_mark_visibility() ) { ?>
			<div class="evc-pli-mark">
				<?php woocommerce_show_product_loop_sale_flash(); ?>
			</div>
		<?php } ?>
		<?php echo woocommerce_get_product_thumbnail( $image_proportions ); ?>
	</div>
<?php }
