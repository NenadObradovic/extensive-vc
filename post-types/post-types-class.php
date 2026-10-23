<?php

namespace ExtensiveVC\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'PostTypesClass' ) ) {
	class PostTypesClass {
		
		/**
		 * Singleton classes
		 */
		private static $instance;
		private $postTypes = array();
		
		/**
		 * Get the instance of PostTypesClass
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		/**
		 * Adds custom post type
		 *
		 * @param $postType PostTypesInterface
		 */
		private function addPostType( PostTypesInterface $postType ) {
			if ( ! array_key_exists( $postType->getBase(), $this->postTypes ) ) {
				$this->postTypes[ $postType->getBase() ] = $postType;
			}
		}
		
		/**
		 * Adds all custom post types
		 *
		 * @see PostTypesClass::addPostType()
		 */
		private function addPostTypes() {
			$cptClassNames = apply_filters( 'extensive_vc_filter_add_custom_post_type', $cptClassNames = array() );
			
			if ( ! empty( $cptClassNames ) ) {
				foreach ( $cptClassNames as $cptClassName ) {
					$this->addPostType( new $cptClassName );
				}
			}
		}
		
		/**
		 * Loops through each post type in array and calls register method
		 */
		public function register() {
			$this->addPostTypes();
			
			foreach ( $this->postTypes as $postType ) {
				$postType->register();
			}
		}
	}
}