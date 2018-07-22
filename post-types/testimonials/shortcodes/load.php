<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_testimonials_shortcode' ) ) {
	/**
	 * Include shortcode main file
	 */
	function extensive_vc_include_testimonials_shortcode() {
		include_once 'testimonials.php';
	}
	
	add_action( 'extensive_vc_action_include_shortcodes_file', 'extensive_vc_include_testimonials_shortcode' );
}