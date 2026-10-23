<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_clients', $params ); ?>
<div class="evc-clients evc-shortcode evc-element-has-columns evc-disable-bottom-space <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_clients_before_content', $params ); ?>
	<div class="evc-c-inner evc-element-wrapper">
		<?php
			if ( $query_results->have_posts() ):
				while ( $query_results->have_posts() ) : $query_results->the_post();
					extensive_vc_template_part( 'cpt', 'clients', 'templates/clients-item', '', $params );
				endwhile;
			else:
				extensive_vc_template_part( 'cpt', 'clients', 'templates/parts/not-found' );
			endif;
			
			wp_reset_postdata();
		?>
	</div>
	<?php do_action( 'extensive_vc_action_clients_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_clients', $params ); ?>
