<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! defined( 'EXTENSIVE_VC_WOOCOMMERCE_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_WOOCOMMERCE_ABS_PATH', EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce' );
}

if ( ! defined( 'EXTENSIVE_VC_WOOCOMMERCE_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_WOOCOMMERCE_URL_PATH', EXTENSIVE_VC_PLUGINS_URL_PATH . 'woocommerce' );
}

if ( ! defined( 'EXTENSIVE_VC_WOO_SHORTCODES_ABS_PATH' ) ) {
	define( 'EXTENSIVE_VC_WOO_SHORTCODES_ABS_PATH', EXTENSIVE_VC_WOOCOMMERCE_ABS_PATH . '/shortcodes' );
}

if ( ! defined( 'EXTENSIVE_VC_WOO_SHORTCODES_URL_PATH' ) ) {
	define( 'EXTENSIVE_VC_WOO_SHORTCODES_URL_PATH', EXTENSIVE_VC_WOOCOMMERCE_URL_PATH . 'shortcodes' );
}
