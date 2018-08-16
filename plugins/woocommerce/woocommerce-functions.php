<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_woocommerce_installed' ) ) {
	/**
	 * Checks if WooCommerce plugin installed
	 *
	 * @return boolean
	 */
	function extensive_vc_woocommerce_installed() {
		return class_exists( 'WooCommerce' );
	}
}

if ( ! function_exists( 'extensive_vc_check_product_mark_visibility' ) ) {
	/**
	 * Checks if product mark exist for the current product
	 *
	 * @param $itemEnabled boolean - check item visibility throw shortcode option
	 *
	 * @return boolean
	 */
	function extensive_vc_check_product_mark_visibility( $itemEnabled = true ) {
		global $product;
		
		if ( method_exists( $product, 'is_on_sale' ) && $itemEnabled ) {
			return $product->is_on_sale();
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'extensive_vc_check_product_rating_visibility' ) ) {
	/**
	 * Checks if product rating exist for the current product
	 *
	 * @param $itemEnabled boolean - check item visibility throw shortcode option
	 *
	 * @return boolean
	 */
	function extensive_vc_check_product_rating_visibility( $itemEnabled = true ) {
		global $product;
		
		if ( method_exists( $product, 'get_average_rating' ) && $itemEnabled ) {
			return get_option( 'woocommerce_enable_review_rating' ) !== 'no' && $product->get_average_rating() > 0;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'extensive_vc_check_product_price_visibility' ) ) {
	/**
	 * Checks if product price exist for the current product
	 *
	 * @param $itemEnabled boolean - check item visibility throw shortcode option
	 *
	 * @return boolean
	 */
	function extensive_vc_check_product_price_visibility( $itemEnabled = true ) {
		global $product;
		
		if ( method_exists( $product, 'get_price_html' ) && $itemEnabled ) {
			return $product->get_price_html() !== '';
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'extensive_vc_check_product_category_visibility' ) ) {
	/**
	 * Checks if product category exist for the current product
	 *
	 * @param $itemEnabled boolean - check item visibility throw shortcode option
	 *
	 * @return boolean
	 */
	function extensive_vc_check_product_category_visibility( $itemEnabled = true ) {
		global $product;
		
		return method_exists( $product, 'get_id' ) && $itemEnabled;
	}
}
