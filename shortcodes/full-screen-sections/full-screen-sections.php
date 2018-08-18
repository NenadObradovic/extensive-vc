<?php

namespace ExtensiveVC\Shortcodes\EVCFullScreenSections;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCFullScreenSections' ) ) {
	class EVCFullScreenSections extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_full_screen_sections' );
			$this->setChildBase( 'evc_full_screen_sections_item' );
			$this->setShortcodeName( esc_html__( 'Full Screen Sections', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasChild' => true ) );
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				add_filter( 'extensive_vc_filter_add_vc_shortcodes_custom_style', array( $this, 'addShortcodeIconCustomStyle' ) );
				add_action( 'extensive_vc_enqueue_additional_scripts_before_main_js', array( $this, 'enqueueShortcodeAdditionalScripts' ) );
			}
		}
		
		/**
		 * Get the instance of ExtensiveVCFramework
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( self::$instance == null ) {
				return new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Add shortcode custom css style for Visual Composer shortcodes panel
		 */
		function addShortcodeIconCustomStyle( $style ) {
			$current_style = '.vc_shortcodes_container.wpb_evc_full_screen_sections_item { background-color: #f5f5f5; }';
			
			$style .= $current_style;
			
			return $style;
		}
		
		/**
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'fullPage', EXTENSIVE_VC_SHORTCODES_URL_PATH . '/full-screen-sections/assets/js/plugins/jquery.fullPage.min.js', array( 'jquery' ), false, true );
		}
		
		/**
		 * Set shortcode parameters for Visual Composer shortcodes options panel
		 */
		function shortcodeParameters() {
			$params = array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_navigation',
					'heading'    => esc_html__( 'Enable Navigation Arrows', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'top_margin_offset',
					'heading'     => esc_html__( 'Top Margin Offset (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Defines top margin offset to put shortcode behind header if header element is not transparent', 'extensive-vc' )
				)
			);
			
			return $params;
		}
		
		/**
		 * Renders shortcode HTML
		 *
		 * @param $atts array - shortcode params
		 * @param $content string - shortcode content
		 *
		 * @return html
		 */
		function render( $atts, $content = null ) {
			$args   = array(
				'custom_class'      => '',
				'enable_navigation' => 'yes',
				'top_margin_offset' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			$params['holder_data']    = $this->getHolderData( $params );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'full-screen-sections', 'templates/full-screen-sections', '', $params );
			
			return $html;
		}
		
		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters
		 *
		 * @return string
		 */
		private function getHolderClasses( $params ) {
			$holderClasses = array();
			
			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode holder styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderStyles( $params ) {
			$styles = array();
			
			if ( $params['top_margin_offset'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['top_margin_offset'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get shortcode holder data
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderData( $params ) {
			$data = array();
			
			if ( ! empty( $params['enable_navigation'] ) ) {
				$data['data-enable-navigation'] = $params['enable_navigation'];
			}
			
			return $data;
		}
	}
}

EVCFullScreenSections::getInstance();