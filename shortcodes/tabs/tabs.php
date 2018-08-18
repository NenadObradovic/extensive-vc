<?php

namespace ExtensiveVC\Shortcodes\EVCTabs;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCTabs' ) ) {
	class EVCTabs extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_tabs' );
			$this->setChildBase( 'evc_tabs_item' );
			$this->setShortcodeName( esc_html__( 'Tabs', 'extensive-vc' ) );
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
			$current_style = '.vc_shortcodes_container.wpb_evc_tabs_item { background-color: #f5f5f5; }';
			
			$style .= $current_style;
			
			return $style;
		}
		
		/**
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'jquery-ui-tabs' );
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
					'type'        => 'dropdown',
					'param_name'  => 'type',
					'heading'     => esc_html__( 'Type', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Standard', 'extensive-vc' ) => 'standard',
						esc_html__( 'Simple', 'extensive-vc' )   => 'simple',
						esc_html__( 'Vertical', 'extensive-vc' ) => 'vertical',
						esc_html__( 'Centered', 'extensive-vc' ) => 'centered'
					),
					'save_always' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'animation_type',
					'heading'     => esc_html__( 'Animation Type', 'extensive-vc' ),
					'description' => esc_html__( 'Choose tab content animation on item click', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc' )           => '',
						esc_html__( 'Fade', 'extensive-vc' )              => 'fade',
						esc_html__( 'Slide From Bottom', 'extensive-vc' ) => 'slide-from-bottom',
						esc_html__( 'Slide From Right', 'extensive-vc' )  => 'slide-from-right'
					)
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'skin',
					'heading'     => esc_html__( 'Skin', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Light', 'extensive-vc' )   => 'light'
					)
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
				'custom_class'   => '',
				'type'           => 'standard',
				'animation_type' => '',
				'skin'           => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['tab_titles']     = $this->getTabTitles( $content );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'tabs', 'templates/tabs', '', $params );
			
			return $html;
		}
		
		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters
		 * @param $args array - default shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderClasses( $params, $args ) {
			$holderClasses = array();
			
			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-t-' . esc_attr( $params['type'] ) : 'evc-t-' . esc_attr( $args['type'] );
			$holderClasses[] = ! empty( $params['animation_type'] ) ? 'evc-t-' . esc_attr( $params['animation_type'] ) : '';
			$holderClasses[] = ! empty( $params['skin'] ) ? 'evc-t-skin-' . esc_attr( $params['skin'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode tab titles array
		 *
		 * @param $content string - shortcode content
		 *
		 * @return array
		 */
		private function getTabTitles( $content ) {
			// Extract tab titles
			preg_match_all( '/tab_title="([^\"]+)"/i', $content, $matches, PREG_OFFSET_CAPTURE );
			$tab_titles = array();
			
			/**
			 * get tab titles array
			 */
			if ( isset( $matches[0] ) ) {
				$tab_titles = $matches[0];
			}
			
			$tab_title_array = array();
			
			foreach ( $tab_titles as $tab ) {
				preg_match( '/tab_title="([^\"]+)"/i', $tab[0], $tab_matches, PREG_OFFSET_CAPTURE );
				$tab_title_array[] = $tab_matches[1][0];
			}
			
			return $tab_title_array;
		}
	}
}

EVCTabs::getInstance();