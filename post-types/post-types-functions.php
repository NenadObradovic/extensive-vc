<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_custom_post_types' ) ) {
	/**
	 * Include main load file for custom post types
	 */
	function extensive_vc_include_custom_post_types() {
		foreach ( glob( EXTENSIVE_VC_CPT_ABS_PATH . '/*/load.php' ) as $cpt_load ) {
			include_once $cpt_load;
		}
		
		// Hook to include additional custom post types
		do_action( 'extensive_vc_action_include_cpt_file' );
	}
	
	add_action( 'init', 'extensive_vc_include_custom_post_types', 5 );
}

if ( ! function_exists( 'extensive_vc_load_custom_post_types' ) ) {
	/**
	 * Register/load all custom post types
	 */
	function extensive_vc_load_custom_post_types() {
		include_once 'post-types-class.php';
		
		ExtensiveVC\CPT\PostTypesClass::getInstance()->register();
	}
	
	add_action( 'init', 'extensive_vc_load_custom_post_types', 6 ); // permission 6 is set to be after extensive_vc_include_custom_post_types hook
}

if ( ! function_exists( 'extensive_vc_include_custom_post_types_meta_boxes' ) ) {
	/**
	 * Include meta boxes files for custom post types
	 */
	function extensive_vc_include_custom_post_types_meta_boxes() {
		foreach ( glob( EXTENSIVE_VC_CPT_ABS_PATH . '/*/admin/meta-boxes/*.php' ) as $metaBoxesMap ) {
			include_once $metaBoxesMap;
		}
	}
	
	add_action( 'after_setup_theme', 'extensive_vc_include_custom_post_types_meta_boxes' );
}