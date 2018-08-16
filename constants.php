<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! defined( 'EXTENSIVE_VC_VERSION' ) ) {
	define( 'EXTENSIVE_VC_VERSION', '1.7.2' );
}

if ( ! defined( 'EXTENSIVE_VC_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_ABS_PATH', dirname( __FILE__ ) );
}

if ( ! defined( 'EXTENSIVE_VC_REL_PATH' ) ) {
	define( 'EXTENSIVE_VC_REL_PATH', dirname( plugin_basename( __FILE__ ) ) );
}

if ( ! defined( 'EXTENSIVE_VC_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_URL_PATH', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'EXTENSIVE_VC_ASSETS_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_ASSETS_ABS_PATH', EXTENSIVE_VC_ABS_PATH . '/assets' );
}

if ( ! defined( 'EXTENSIVE_VC_ASSETS_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_ASSETS_URL_PATH', EXTENSIVE_VC_URL_PATH . 'assets' );
}

if ( ! defined( 'EXTENSIVE_VC_SHORTCODES_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_SHORTCODES_ABS_PATH', EXTENSIVE_VC_ABS_PATH . '/shortcodes' );
}

if ( ! defined( 'EXTENSIVE_VC_SHORTCODES_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_SHORTCODES_URL_PATH', EXTENSIVE_VC_URL_PATH . 'shortcodes' );
}

if ( ! defined( 'EXTENSIVE_VC_CPT_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_CPT_ABS_PATH', EXTENSIVE_VC_ABS_PATH . '/post-types' );
}

if ( ! defined( 'EXTENSIVE_VC_CPT_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_CPT_URL_PATH', EXTENSIVE_VC_URL_PATH . 'post-types' );
}

if ( ! defined( 'EXTENSIVE_VC_PLUGINS_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_PLUGINS_ABS_PATH', EXTENSIVE_VC_ABS_PATH . '/plugins' );
}

if ( ! defined( 'EXTENSIVE_VC_PLUGINS_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_PLUGINS_URL_PATH', EXTENSIVE_VC_URL_PATH . 'plugins' );
}

if ( ! defined( 'EXTENSIVE_VC_THEME_ROOT_PATH' ) ) {
	define( 'EXTENSIVE_VC_THEME_ROOT_PATH', get_stylesheet_directory() );
}
