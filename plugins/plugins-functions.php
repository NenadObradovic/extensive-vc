<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_plugins' ) ) {
	/**
	 * Include main plugins load file
	 */
	function extensive_vc_include_plugins() {
		
		foreach ( glob( EXTENSIVE_VC_PLUGINS_ABS_PATH . '/*/load.php' ) as $plugin_load ) {
			include_once $plugin_load;
		}
		
		// Hook to include additional plugins
		do_action( 'extensive_vc_action_include_plugins_file' );
	}
	
	add_action( 'init', 'extensive_vc_include_plugins', 0 ); // permission 0 is set to be before widgets_init hook
}
