<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_add_testimonials_cpt' ) ) {
	/**
	 * Add testimonials custom post type
	 */
	function extensive_vc_add_testimonials_cpt( $cpt_class_name ) {
		$cpt_class_name[] = 'ExtensiveVC\CPT\Testimonials\TestimonialsClass';
		
		return $cpt_class_name;
	}
	
	add_filter( 'extensive_vc_filter_add_custom_post_type', 'extensive_vc_add_testimonials_cpt' );
}