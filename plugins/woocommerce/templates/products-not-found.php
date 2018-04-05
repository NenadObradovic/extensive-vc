<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'wc_no_products_found' ) ) {
	wc_no_products_found();
}