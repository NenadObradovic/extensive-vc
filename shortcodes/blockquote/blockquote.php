<?php

namespace ExtensiveVC\Shortcodes\EVCBlockquote;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCBlockquote' ) ) {
	class EVCBlockquote extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
	
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_blockquote' );
			$this->setShortcodeName( esc_html__( 'Blockquote', 'extensive-vc' ) );
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
						esc_html__( 'Simple', 'extensive-vc' )    => 'simple',
						esc_html__( 'Left Line', 'extensive-vc' ) => 'left-line',
						esc_html__( 'With Icon', 'extensive-vc' ) => 'with-icon'
					),
					'admin_label' => true
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
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'font_size',
					'heading'    => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'line_height',
					'heading'    => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
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
				'custom_class' => '',
				'type'         => 'simple',
				'text'         => '',
				'text_color'   => '',
				'font_size'    => '',
				'line_height'  => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'blockquote', 'templates/blockquote', '', $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-b-' . esc_attr( $params['type'] ) : '';
			
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
			
			if ( ! empty( $params['text_color'] ) ) {
				$styles[] = 'color: ' . $params['text_color'];
			}
			
			if ( ! empty( $params['font_size'] ) ) {
				if ( extensive_vc_string_ends_with( $params['font_size'], 'px' ) || extensive_vc_string_ends_with( $params['font_size'], 'em' ) ) {
					$styles[] = 'font-size: ' . $params['font_size'];
				} else {
					$styles[] = 'font-size: ' . $params['font_size'] . 'px';
				}
			}
			
			if ( ! empty( $params['line_height'] ) ) {
				if ( extensive_vc_string_ends_with( $params['line_height'], 'px' ) || extensive_vc_string_ends_with( $params['line_height'], 'em' ) ) {
					$styles[] = 'line-height: ' . $params['line_height'];
				} else {
					$styles[] = 'line-height: ' . $params['line_height'] . 'px';
				}
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCBlockquote::getInstance();