<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once 'woocommerce-functions.php';

if ( extensive_vc_woocommerce_installed() ) {
	include_once 'woocommerce-constants.php';
	include_once 'shortcodes/woo-shortcodes-functions.php';
}
