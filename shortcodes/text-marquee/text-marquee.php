<?php

namespace ExtensiveVC\Shortcodes\EVCTextMarquee;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCTextMarquee' ) ) {
	class EVCTextMarquee extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_text_marquee' );
			$this->setShortcodeName( esc_html__( 'Text Marquee', 'extensive-vc' ) );
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
					'type'        => 'textfield',
					'param_name'  => 'text',
					'heading'     => esc_html__( 'Text', 'extensive-vc' ),
					'admin_label' => true
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'font_size',
					'heading'    => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'line_height',
					'heading'    => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'font_weight',
					'heading'    => esc_html__( 'Font Weight', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_font_weight_array( true ) ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'font_style',
					'heading'    => esc_html__( 'Font Style', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_font_style_array( true ) ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'letter_spacing',
					'heading'    => esc_html__( 'Letter Spacing (px or em)', 'extensive-vc' ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'text_transform',
					'heading'    => esc_html__( 'Text Transform', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_text_transform_array( true ) ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				)
			);
			
			return $params;
		}
		
		/**
		 * Renders shortcodes HTML
		 *
		 * @param $atts array - shortcode params
		 * @param $content string - shortcode content
		 *
		 * @return html
		 */
		function render( $atts, $content = null ) {
			$args   = array(
				'custom_class'   => '',
				'text'           => '',
				'color'          => '',
				'font_size'      => '',
				'line_height'    => '',
				'font_weight'    => '',
				'font_style'     => '',
				'letter_spacing' => '',
				'text_transform' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			
			$params['text_styles'] = $this->getTextStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'text-marquee', 'templates/text-marquee', '', $params );
			
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
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get text styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getTextStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['color'] ) ) {
				$styles[] = 'color: ' . $params['color'];
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
			
			if ( ! empty( $params['font_weight'] ) ) {
				$styles[] = 'font-weight: ' . $params['font_weight'];
			}
			
			if ( ! empty( $params['font_style'] ) ) {
				$styles[] = 'font-style: ' . $params['font_style'];
			}
			
			if ( ! empty( $params['letter_spacing'] ) ) {
				if ( extensive_vc_string_ends_with( $params['letter_spacing'], 'px' ) || extensive_vc_string_ends_with( $params['letter_spacing'], 'em' ) ) {
					$styles[] = 'letter-spacing: ' . $params['letter_spacing'];
				} else {
					$styles[] = 'letter-spacing: ' . $params['letter_spacing'] . 'px';
				}
			}
			
			if ( ! empty( $params['text_transform'] ) ) {
				$styles[] = 'text-transform: ' . $params['text_transform'];
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCTextMarquee::getInstance();