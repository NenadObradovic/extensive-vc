<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-blog-list evc-shortcode evc-element-has-columns <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-bl-wrapper evc-element-wrapper">
		<?php
		if ( $query_results->have_posts() ):
			while ( $query_results->have_posts() ) : $query_results->the_post();
				if ( $type === 'standard' ) {
					echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/layouts/post', $layout_collections, $params );
				} else {
					echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/layouts/post', $type, $params );
				}
			endwhile;
		else:
			echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/not-found' );
		endif;
		
		wp_reset_postdata();
		?>
	</div>
</div>