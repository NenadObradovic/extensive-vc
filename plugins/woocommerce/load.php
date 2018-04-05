<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce/woocommerce-functions.php';

if ( extensive_vc_woocommerce_installed() ) {
	include_once EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce/woocommerce-constants.php';
	include_once EXTENSIVE_VC_WOO_SHORTCODES_ABS_PATH . '/woo-shortcodes-functions.php';
}
