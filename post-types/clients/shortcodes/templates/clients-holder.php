<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'owl-carousel' );
wp_enqueue_script( 'owl-carousel' );
?>
<?php do_action( 'extensive_vc_action_before_clients', $params ); ?>
<div class="evc-clients evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_clients_before_content', $params ); ?>
	<div class="evc-c-inner evc-owl-carousel clearfix" <?php extensive_vc_print_inline_attrs( $slider_data, true ); ?>>
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
