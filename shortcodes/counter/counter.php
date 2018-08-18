<?php

namespace ExtensiveVC\Shortcodes\EVCCounter;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCCounter' ) ) {
	class EVCCounter extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_counter' );
			$this->setShortcodeName( esc_html__( 'Counter', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
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
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'counter', EXTENSIVE_VC_SHORTCODES_URL_PATH . '/counter/assets/js/plugins/counter.js', array( 'jquery' ), false, true );
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
					'param_name'  => 'digit',
					'heading'     => esc_html__( 'Digit', 'extensive-vc' ),
					'admin_label' => true
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'digit_color',
					'heading'    => esc_html__( 'Digit Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'digit', 'not_empty' => true ),
					'group'      => esc_html__( 'Digit Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'digit_font_size',
					'heading'    => esc_html__( 'Digit Font Size (px or em)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'digit', 'not_empty' => true ),
					'group'      => esc_html__( 'Digit Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'digit_line_height',
					'heading'    => esc_html__( 'Digit Line Height (px or em)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'digit', 'not_empty' => true ),
					'group'      => esc_html__( 'Digit Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'digit_font_weight',
					'heading'    => esc_html__( 'Digit Font Weight', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_font_weight_array( true ) ),
					'dependency' => array( 'element' => 'digit', 'not_empty' => true ),
					'group'      => esc_html__( 'Digit Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true ) ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title_top_margin',
					'heading'    => esc_html__( 'Title Top Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc' )
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
					'group'      => esc_html__( 'Text Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'text_top_margin',
					'heading'    => esc_html__( 'Text Top Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Text Options', 'extensive-vc' )
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
				'custom_class'      => '',
				'digit'             => '',
				'digit_color'       => '',
				'digit_font_size'   => '',
				'digit_line_height' => '',
				'digit_font_weight' => '',
				'title'             => '',
				'title_tag'         => 'h5',
				'title_color'       => '',
				'title_top_margin'  => '',
				'text'              => '',
				'text_color'        => '',
				'text_top_margin'   => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			
			$params['digit_styles'] = $this->getDigitStyles( $params );
			$params['title_tag']    = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles'] = $this->getTitleStyles( $params );
			$params['text_styles']  = $this->getTextStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'counter', 'templates/counter', '', $params );
			
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
		 * Get digit styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getDigitStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['digit_color'] ) ) {
				$styles[] = 'color: ' . $params['digit_color'];
			}
			
			if ( ! empty( $params['digit_font_size'] ) ) {
				if ( extensive_vc_string_ends_with( $params['digit_font_size'], 'px' ) || extensive_vc_string_ends_with( $params['digit_font_size'], 'em' ) ) {
					$styles[] = 'font-size: ' . $params['digit_font_size'];
				} else {
					$styles[] = 'font-size: ' . $params['digit_font_size'] . 'px';
				}
			}
			
			if ( ! empty( $params['digit_line_height'] ) ) {
				if ( extensive_vc_string_ends_with( $params['digit_line_height'], 'px' ) || extensive_vc_string_ends_with( $params['digit_line_height'], 'em' ) ) {
					$styles[] = 'line-height: ' . $params['digit_line_height'];
				} else {
					$styles[] = 'line-height: ' . $params['digit_line_height'] . 'px';
				}
			}
			
			if ( ! empty( $params['digit_font_weight'] ) ) {
				$styles[] = 'font-weight: ' . $params['digit_font_weight'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get title styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getTitleStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['title_color'] ) ) {
				$styles[] = 'color: ' . $params['title_color'];
			}
			
			if ( $params['title_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['title_top_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
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
			
			if ( ! empty( $params['text_color'] ) ) {
				$styles[] = 'color: ' . $params['text_color'];
			}
			
			if ( $params['text_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['text_top_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCCounter::getInstance();