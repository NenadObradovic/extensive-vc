<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-clients evc-shortcode evc-element-has-columns evc-disable-bottom-space <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-c-inner evc-element-wrapper">
		<?php
			if ( $query_results->have_posts() ):
				while ( $query_results->have_posts() ) : $query_results->the_post();
					echo extensive_vc_get_module_template_part( 'cpt', 'clients', 'templates/clients-item', '', $params );
				endwhile;
			else:
				echo extensive_vc_get_module_template_part( 'cpt', 'clients', 'templates/parts/not-found' );
			endif;
			
			wp_reset_postdata();
		?>
	</div>
</div>
