<?php

namespace ExtensiveVC\Shortcodes\EVCDropcaps;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCDropcaps' ) ) {
	class EVCDropcaps extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_dropcaps' );
			$this->setShortcodeName( esc_html__( 'Dropcaps', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
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
						esc_html__( 'Simple', 'extensive-vc' ) => 'simple',
						esc_html__( 'Circle', 'extensive-vc' ) => 'circle',
						esc_html__( 'Square', 'extensive-vc' ) => 'square'
					),
					'admin_label' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'letter',
					'heading'    => esc_html__( 'Letter', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'letter_color',
					'heading'    => esc_html__( 'Letter Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Letter Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'letter_bg_color',
					'heading'    => esc_html__( 'Letter Background Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'circle', 'square' ) ),
					'group'      => esc_html__( 'Letter Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'text_color',
					'heading'    => esc_html__( 'Text Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Text Options', 'extensive-vc' )
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
			$args = array(
				'custom_class'    => '',
				'type'            => 'simple',
				'letter'          => '',
				'letter_color'    => '',
				'letter_bg_color' => '',
				'text'            => '',
				'text_color'      => ''
			);
			$params = shortcode_atts( $args, $atts );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			
			$params['letter_styles'] = $this->getLetterStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'dropcaps', 'templates/dropcaps', '', $params );
			
			return $html;
		}
		
		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderClasses( $params ) {
			$holderClasses = array();
			
			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-d-' . esc_attr( $params['type'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get holder styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['text_color'] ) ) {
				$styles[] = 'color: ' . $params['text_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get letter styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getLetterStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['letter_color'] ) ) {
				$styles[] = 'color: ' . $params['letter_color'];
			}
			
			if ( ! empty( $params['letter_bg_color'] ) ) {
				$styles[] = 'background-color: ' . $params['letter_bg_color'];
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCDropcaps::getInstance();