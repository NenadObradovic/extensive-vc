<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $query_results->have_posts() ):
	while ( $query_results->have_posts() ) : $query_results->the_post();
		extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/blog-list-item', '', $params );
	endwhile;
else:
	extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/parts/not-found' );
endif;

wp_reset_postdata();
