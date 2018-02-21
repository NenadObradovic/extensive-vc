<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( extensive_vc_is_visual_composer_installed() ) {
	include_once 'lib/framework.php';
	include_once 'lib/helpers-functions.php';
	include_once 'lib/predefined-style-class.php';
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/post-types' ) ) {
		include_once 'post-types/post-types-interface.php';
		include_once 'post-types/post-types-functions.php';
	}
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/shortcodes' ) ) {
		include_once 'shortcodes/shortcodes-extends-class.php';
		include_once 'shortcodes/shortcodes-functions.php';
	}
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/widgets' ) ) {
		include_once 'widgets/widgets-class.php';
		include_once 'widgets/widgets-functions.php';
	}
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/plugins' ) ) {
		include_once 'plugins/plugins-functions.php';
	}
}
