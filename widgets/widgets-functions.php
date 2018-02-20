<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_widgets' ) ) {
	/**
	 * Include main widgets load file
	 */
	function extensive_vc_include_widgets() {
		
		foreach ( glob( EXTENSIVE_VC_SHORTCODES_ABS_PATH . '/*/widgets/load.php' ) as $widget_load ) {
			include_once $widget_load;
		}
		
		// Hook to include additional widgets
		do_action( 'extensive_vc_action_include_widgets_file' );
	}
	
	add_action( 'init', 'extensive_vc_include_widgets', 0 ); // permission 0 is set to be before widgets_init hook
}

if ( ! function_exists( 'extensive_vc_register_widgets' ) ) {
	/**
	 * Register all widgets
	 */
	function extensive_vc_register_widgets() {
		$widgets = apply_filters( 'extensive_vc_filter_register_widgets', $widgets = array() );
		
		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				register_widget( $widget );
			}
		}
	}
	
	add_action( 'widgets_init', 'extensive_vc_register_widgets' );
}