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
		 * Adds shortcode
		 *
		 * @param $shortcode EVCShortcode
		 */
		private function addShortcode( EVCShortcode $shortcode ) {
			$shortcodeBase = $shortcode->getBase();
			
			if ( ! array_key_exists( $shortcodeBase, $this->loadedShortcodes ) ) {
				$this->loadedShortcodes[ $shortcodeBase ] = array(
					'base'   => $shortcodeBase,
					'render' => array( $shortcode, 'render' )
				);
			}
		}
		
		/**
		 * Adds all shortcodes
		 *
		 * @see ShortcodesClass::addShortcode()
		 */
		private function addShortcodes() {
			$shortcodesInstance = apply_filters( 'extensive_vc_filter_add_vc_shortcode', $shortcodesInstance = array() );
			
			if ( ! empty( $shortcodesInstance ) ) {
				foreach ( $shortcodesInstance as $shortcodeInstance ) {
					$this->addShortcode( $shortcodeInstance );
				}
			}
		}
		
		/**
		 * Loops through added shortcodes and calls render method of each shortcode object
		 */
		function load() {
			$this->addShortcodes();
			
			foreach ( $this->loadedShortcodes as $shortcode ) {
				add_shortcode( $shortcode['base'], $shortcode['render'] );
			}
		}
	}
}