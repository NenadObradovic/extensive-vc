<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-product-list evc-shortcode evc-element-has-columns evc-disable-bottom-space <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-pl-wrapper evc-element-wrapper">
		<?php
		if ( $query_results->have_posts() ):
			while ( $query_results->have_posts() ) : $query_results->the_post();
				echo extensive_vc_get_module_template_part( 'woocommerce', 'product-list', 'templates/product-list-item', '', $params );
			endwhile;
		else:
			echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'not-found' );
		endif;
		
		wp_reset_postdata();
		?>
	</div>
</div>
