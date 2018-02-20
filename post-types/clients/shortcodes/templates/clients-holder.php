<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-clients evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-c-inner evc-owl-carousel clearfix" <?php extensive_vc_print_inline_attrs( $slider_data, true ); ?>>
		<?php
			if ( $query_results->have_posts() ):
				while ( $query_results->have_posts() ) : $query_results->the_post();
					$current_post_id       = get_the_ID();
					$params['image']       = get_post_meta( $current_post_id, 'evc_client_image', true );
					$params['hover_image'] = get_post_meta( $current_post_id, 'evc_client_hover_image', true );
					$params['custom_link'] = get_post_meta( $current_post_id, 'evc_client_link', true );
					
					echo extensive_vc_get_module_template_part( 'cpt', 'clients', 'templates/clients-item', '', $params );
				endwhile;
			else:
				echo extensive_vc_get_module_template_part( 'cpt', 'clients', 'templates/clients-not-found' );
			endif;
			
			wp_reset_postdata();
		?>
	</div>
</div>
