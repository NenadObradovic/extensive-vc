<?php

namespace ExtensiveVC\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Interface PostTypesInterface
 * @package ExtensiveVC\CPT
 */
interface PostTypesInterface {
	
	/**
	 * Returns base for shortcode
	 *
	 * @return string
	 */
	public function getBase();
	
	/**
	 * Returns base taxonomy for shortcode
	 *
	 * @return string
	 */
	public function getBaseTaxonomy();
	
	/**
	 * Register custom post types
	 */
	public function register();
}