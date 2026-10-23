<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'owl-carousel' );
wp_enqueue_script( 'owl-carousel' );
?>
<?php do_action( 'extensive_vc_action_before_testimonials', $params ); ?>
<div class="evc-testimonials evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_testimonials_before_content', $params ); ?>
	<div class="evc-t-inner evc-owl-carousel clearfix" <?php extensive_vc_print_inline_attrs( $slider_data, true ); ?>>
		<?php
			if ( $query_results->have_posts() ):
				while ( $query_results->have_posts() ) : $query_results->the_post();
					$current_post_id = get_the_ID();
					
					$params['current_id'] = $current_post_id;
					$params['title']      = get_the_title( $current_post_id );
					$params['text']       = get_post_meta( $current_post_id, 'evc_testimonial_text', true );
					$params['author']     = get_post_meta( $current_post_id, 'evc_testimonial_author', true );
					$params['position']   = get_post_meta( $current_post_id, 'evc_testimonial_position', true );
					
					extensive_vc_template_part( 'cpt', 'testimonials', 'templates/testimonials-item', '', $params );
				endwhile;
			else:
				extensive_vc_template_part( 'cpt', 'testimonials', 'templates/parts/not-found' );
			endif;
			
			wp_reset_postdata();
		?>
	</div>
	<?php do_action( 'extensive_vc_action_testimonials_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_testimonials', $params ); ?>
