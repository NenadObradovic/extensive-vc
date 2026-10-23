<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_clients_shortcode' ) ) {
	/**
	 * Include shortcode main file
	 */
	function extensive_vc_include_clients_shortcode() {
		require_once __DIR__ . '/clients.php';
	}
	
	add_action( 'extensive_vc_action_include_shortcodes_file', 'extensive_vc_include_clients_shortcode' );
}