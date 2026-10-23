<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_product_list', $params ); ?>
<div class="evc-product-list evc-shortcode evc-element-has-columns evc-disable-bottom-space <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_product_list_before_content', $params ); ?>
	<div class="evc-pl-wrapper evc-element-wrapper">
		<?php
		if ( $query_results->have_posts() ):
			while ( $query_results->have_posts() ) : $query_results->the_post();
				extensive_vc_template_part( 'woocommerce', 'product-list', 'templates/product-list-item', '', $params );
			endwhile;
		else:
			extensive_vc_template_part( 'woocommerce-part', 'templates', 'not-found' );
		endif;
		
		wp_reset_postdata();
		?>
	</div>
	<?php do_action( 'extensive_vc_action_product_list_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_product_list', $params ); ?>
