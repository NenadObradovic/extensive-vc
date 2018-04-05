<?php

namespace ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ShortcodesClass' ) ) {
	class ShortcodesClass {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		private $loadedShortcodes = array();
		
		/**
		 * Constructor
		 */
		private function __construct() {
		}
		
		/**
		 * Get the instance of ShortcodesClass
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		/**
		 * Cloning disabled
		 */
		private function __clone() {
		}
		
		/**
		 * Serialization disabled
		 */
		private function __sleep() {
		}
		
		/**
		 * De-serialization disabled
		 */
		private function __wakeup() {
		}
		
		/**
		 * Getter for private param
		 *
		 * @return array
		 */
		public function getLoadedShortcodes() {
			return $this->loadedShortcodes;
		}
		
		/**
		 * Setter for private param
		 */
		public function setLoadedShortcodes( $key, $value ) {
			$this->loadedShortcodes[ $key ] = $value;
		}
		
		/**
		 * Adds all shortcodes
		 */
		private function addShortcodes() {
			$shortcodes = apply_filters( 'extensive_vc_filter_add_vc_shortcode', $shortcodes = array() );
			
			foreach ( $shortcodes as $key => $value ) {
				if ( ! array_key_exists( $key, $this->getLoadedShortcodes() ) ) {
					$this->setLoadedShortcodes( $key, $value );
				}
			}
		}
		
		/**
		 * Loops through added shortcodes and calls render method of each shortcode object
		 */
		function load() {
			$this->addShortcodes();
			
			$shortcodes = $this->getLoadedShortcodes();
			
			ksort( $shortcodes );
			
			foreach ( $shortcodes as $key => $value ) {
				add_shortcode( $key, $value );
			}
		}
	}
}