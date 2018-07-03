<?php

namespace ExtensiveVC\Shortcodes\EVCSeparator;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCSeparator' ) ) {
	class EVCSeparator extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_separator' );
			$this->setShortcodeName( esc_html__( 'Separator', 'extensive-vc' ) );
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
					'type'       => 'dropdown',
					'param_name' => 'position',
					'heading'    => esc_html__( 'Position', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Center', 'extensive-vc' ) => 'center',
						esc_html__( 'Left', 'extensive-vc' )   => 'left',
						esc_html__( 'Right', 'extensive-vc' )  => 'right'
					)
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'width',
					'heading'    => esc_html__( 'Width (px or %)', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'thickness',
					'heading'    => esc_html__( 'Thickness (px)', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'style',
					'heading'    => esc_html__( 'Style', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Dashed', 'extensive-vc' )  => 'dashed',
						esc_html__( 'Solid', 'extensive-vc' )   => 'solid',
						esc_html__( 'Dotted', 'extensive-vc' )  => 'dotted'
					)
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'top_margin',
					'heading'    => esc_html__( 'Top Margin (px or %)', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'bottom_margin',
					'heading'    => esc_html__( 'Bottom Margin (px or %)', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
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
			$args = array(
				'custom_class'  => '',
				'position'      => 'center',
				'width'         => '',
				'thickness'     => '',
				'style'         => '',
				'top_margin'    => '',
				'bottom_margin' => '',
				'color'         => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'separator', 'templates/separator', '', $params );
			
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
			$holderClasses[] = ! empty( $params['position'] ) ? 'evc-separator-' . $params['position'] : '';
			
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
			
			if ( $params['width'] !== '' ) {
				if ( extensive_vc_string_ends_with( $params['width'], '%' ) || extensive_vc_string_ends_with( $params['width'], 'px' ) ) {
					$styles[] = 'width: ' . $params['width'];
				} else {
					$styles[] = 'width: ' . extensive_vc_filter_px( $params['width'] ) . 'px';
				}
			}
			
			if ( $params['thickness'] !== '' ) {
				$styles[] = 'border-bottom-width: ' . extensive_vc_filter_px( $params['thickness'] ) . 'px';
			}
			
			if ( $params['style'] !== '' ) {
				$styles[] = 'border-style: ' . $params['style'];
			}
			
			if ( $params['top_margin'] !== '' ) {
				if ( extensive_vc_string_ends_with( $params['top_margin'], '%' ) || extensive_vc_string_ends_with( $params['top_margin'], 'px' ) ) {
					$styles[] = 'margin-top: ' . $params['top_margin'];
				} else {
					$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['top_margin'] ) . 'px';
				}
			}
			
			if ( $params['bottom_margin'] !== '' ) {
				if ( extensive_vc_string_ends_with( $params['bottom_margin'], '%' ) || extensive_vc_string_ends_with( $params['bottom_margin'], 'px' ) ) {
					$styles[] = 'margin-bottom: ' . $params['bottom_margin'];
				} else {
					$styles[] = 'margin-bottom: ' . extensive_vc_filter_px( $params['bottom_margin'] ) . 'px';
				}
			}
			
			if ( ! empty( $params['color'] ) ) {
				$styles[] = 'border-color: ' . $params['color'];
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCSeparator::getInstance();