<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_add_clients_cpt' ) ) {
	/**
	 * Add clients custom post type
	 */
	function extensive_vc_add_clients_cpt( $cpt_class_name ) {
		$cpt_class_name[] = 'ExtensiveVC\CPT\Clients\ClientsClass';
		
		return $cpt_class_name;
	}
	
	add_filter( 'extensive_vc_filter_add_custom_post_type', 'extensive_vc_add_clients_cpt' );
}