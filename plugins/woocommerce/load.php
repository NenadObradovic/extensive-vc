<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once __DIR__ . '/woocommerce-functions.php';

if ( extensive_vc_woocommerce_installed() ) {
	require_once __DIR__ . '/woocommerce-constants.php';
	require_once __DIR__ . '/shortcodes/woo-shortcodes-functions.php';
}
