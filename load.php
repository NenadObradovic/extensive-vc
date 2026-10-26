<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( extensive_vc_is_wpbakery_page_builder_installed() ) {
	require_once EXTENSIVE_VC_ABS_PATH . '/lib/framework.php';
	require_once EXTENSIVE_VC_ABS_PATH . '/lib/helpers-functions.php';
	require_once EXTENSIVE_VC_ABS_PATH . '/lib/social-icons.php';
	require_once EXTENSIVE_VC_ABS_PATH . '/lib/assets-functions.php';
	require_once EXTENSIVE_VC_ABS_PATH . '/lib/responsive-functions.php';
	require_once EXTENSIVE_VC_ABS_PATH . '/lib/predefined-style-class.php';
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/post-types' ) ) {
		require_once EXTENSIVE_VC_ABS_PATH . '/post-types/post-types-interface.php';
		require_once EXTENSIVE_VC_ABS_PATH . '/post-types/post-types-functions.php';
	}
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/shortcodes' ) ) {
		require_once EXTENSIVE_VC_ABS_PATH . '/shortcodes/shortcodes-extends-class.php';
		require_once EXTENSIVE_VC_ABS_PATH . '/shortcodes/shortcodes-functions.php';
	}
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/widgets' ) ) {
		require_once EXTENSIVE_VC_ABS_PATH . '/widgets/widgets-class.php';
		require_once EXTENSIVE_VC_ABS_PATH . '/widgets/widgets-functions.php';
	}
	
	if ( file_exists( EXTENSIVE_VC_ABS_PATH . '/plugins' ) ) {
		require_once EXTENSIVE_VC_ABS_PATH . '/plugins/plugins-functions.php';
	}
}
