<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'owl-carousel' );
wp_enqueue_script( 'owl-carousel' );
?>
<?php do_action( 'extensive_vc_action_before_post_carousel', $params ); ?>
<div class="evc-post-carousel evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_post_carousel_before_content', $params ); ?>
	<div class="evc-pc-slider evc-owl-carousel" <?php extensive_vc_print_inline_attrs( $slider_data, true ); ?>>
		<?php
		if ( $query_results->have_posts() ):
			while ( $query_results->have_posts() ) : $query_results->the_post();
				extensive_vc_template_part( 'shortcodes', 'post-carousel', 'templates/layouts/post', $type, $params );
			endwhile;
		else:
			extensive_vc_template_part( 'shortcodes', 'post-carousel', 'templates/parts/not-found' );
		endif;
		
		wp_reset_postdata();
		?>
	</div>
	<?php do_action( 'extensive_vc_action_post_carousel_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_post_carousel', $params ); ?>