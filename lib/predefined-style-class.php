<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ExtensiveVCPredefinedStyle' ) ) {
	class ExtensiveVCPredefinedStyle {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		private $predefinedStyle;
		
		/**
		 * Constructor
		 */
		private function __construct() {
			$evc_options = extensive_vc_get_global_options();
			
			$this->setPredefinedStyle( $evc_options->options->getOptionValueById( 'evc_predefined_style' ) );
			
			if ( $this->isPredefinedStyleEnabled() ) {
				add_filter( 'body_class', array( $this, 'addBodyClasses' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'enqueueGoogleFont' ) );
			}
		}
		
		/**
		 * Get the instance of ExtensiveVCFramework
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
		 * Getter for private param
		 *
		 * @return string
		 */
		private function getPredefinedStyle() {
			return $this->predefinedStyle;
		}
		
		/**
		 * Setter for private param
		 *
		 * @return string
		 */
		private function setPredefinedStyle( $predefinedStyle ) {
			$this->predefinedStyle = $predefinedStyle;
		}
		
		/**
		 * Check is predefined style enabled
		 *
		 * @return boolean
		 */
		function isPredefinedStyleEnabled() {
			$returnValue = $this->getPredefinedStyle() === 'no' ? false : true;
			
			return $returnValue;
		}
		
		/**
		 * Add predefined style class to body
		 *
		 * @param $classes array
		 *
		 * @return array
		 */
		function addBodyClasses( $classes ) {
			$classes[] = 'evc-predefined-style';
			
			return $classes;
		}
		
		/**
		 * Enqueue google font
		 */
		function enqueueGoogleFont() {
			
			$defaultFontArgs = array(
				'family'  => urlencode( 'Raleway:400,500,600,700,800,900|Poppins:400,700' ),
				'subset'  => urlencode( 'latin-ext' ),
				'display' => 'swap',
			);

			$googleFont = add_query_arg( apply_filters( 'extensive_vc_filter_google_fonts_args', $defaultFontArgs ), 'https://fonts.googleapis.com/css' );

			wp_enqueue_style( 'extensive-vc-google-fonts', esc_url_raw( $googleFont ), array(), EXTENSIVE_VC_VERSION );
		}
	}
}

if ( ! function_exists( 'extensive_vc_init_predefined_style' ) ) {
	/**
	 * Init instance of ExtensiveVCPredefinedStyle class
	 *
	 * @inheritdoc this calling instance is set on init action hook because main framework class is init on same hook
	 */
	function extensive_vc_init_predefined_style() {
		ExtensiveVCPredefinedStyle::getInstance();
	}
	
	add_action( 'init', 'extensive_vc_init_predefined_style' );
}
