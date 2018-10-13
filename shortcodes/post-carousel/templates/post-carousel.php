<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_style( 'owl-carousel' );
wp_enqueue_script( 'owl-carousel' );
?>
<div class="evc-post-carousel evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-pc-slider evc-owl-carousel" <?php extensive_vc_print_inline_attrs( $slider_data, true ); ?>>
		<?php
		if ( $query_results->have_posts() ):
			while ( $query_results->have_posts() ) : $query_results->the_post();
				echo extensive_vc_get_module_template_part( 'shortcodes', 'post-carousel', 'templates/layouts/post', $type, $params );
			endwhile;
		else:
			echo extensive_vc_get_module_template_part( 'shortcodes', 'post-carousel', 'templates/parts/not-found' );
		endif;
		
		wp_reset_postdata();
		?>
	</div>
</div>