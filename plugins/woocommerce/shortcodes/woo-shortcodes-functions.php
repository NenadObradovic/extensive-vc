<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_woocommerce_shortcodes' ) ) {
	function extensive_vc_include_woocommerce_shortcodes() {
		foreach ( glob( EXTENSIVE_VC_WOO_SHORTCODES_ABS_PATH . '/*/load.php' ) as $shortcode_load ) {
			include_once $shortcode_load;
		}
	}
	
	add_action( 'extensive_vc_action_include_shortcodes_file', 'extensive_vc_include_woocommerce_shortcodes' );
}
