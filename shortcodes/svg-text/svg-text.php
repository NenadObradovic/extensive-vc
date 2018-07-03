<?php

namespace ExtensiveVC\Shortcodes\EVCSVGText;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCSVGText' ) ) {
	class EVCSVGText extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_svg_text' );
			$this->setShortcodeName( esc_html__( 'SVG Text', 'extensive-vc' ) );
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
					'type'       => 'textfield',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc' )
				),
				array(
					'type'       => 'vc_link',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'font_size',
					'heading'    => esc_html__( 'Font Size (px)', 'extensive-vc' ),
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
					'param_name' => 'text_transform',
					'heading'    => esc_html__( 'Text Transform', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_text_transform_array( true ) ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1440',
					'heading'     => esc_html__( 'Font Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1440px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1366',
					'heading'     => esc_html__( 'Font Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1366px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1280',
					'heading'     => esc_html__( 'Font Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1280px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1024',
					'heading'     => esc_html__( 'Font Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for tablet landscape screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_768',
					'heading'     => esc_html__( 'Font Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for tablet portrait screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_680',
					'heading'     => esc_html__( 'Font Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for mobiles screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
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
				'custom_class'     => '',
				'text'             => '',
				'custom_link'      => '',
				'color'            => '',
				'font_size'        => '',
				'font_weight'      => '',
				'text_transform'   => '',
				'font_size_1440'   => '',
				'font_size_1366'   => '',
				'font_size_1280'   => '',
				'font_size_1024'   => '',
				'font_size_768'    => '',
				'font_size_680'    => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_rand_class'] = 'evc-st-' . mt_rand( 500, 10000 );
			$params['holder_classes']    = $this->getHolderClasses( $params );
			$params['holder_styles']     = $this->getHolderStyles( $params );
			$params['holder_data']       = $this->getHolderData( $params );
			
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-st-link' );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'svg-text', 'templates/svg-text', '', $params );
			
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
			$holderClasses[] = ! empty( $params['holder_rand_class'] ) ? esc_attr( $params['holder_rand_class'] ) : '';
			
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
			
			if ( ! empty( $params['color'] ) ) {
				$styles[] = 'color: ' . $params['color'];
			}
			
			if ( ! empty( $params['font_size'] ) ) {
				$styles[] = 'font-size: ' . extensive_vc_filter_px( $params['font_size'] ) . 'px';
				$styles[] = 'height: ' . extensive_vc_filter_px( $params['font_size'] ) . 'px';
			}
			
			if ( ! empty( $params['font_weight'] ) ) {
				$styles[] = 'font-weight: ' . $params['font_weight'];
			}
			
			if ( ! empty( $params['text_transform'] ) ) {
				$styles[] = 'text-transform: ' . $params['text_transform'];
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
			$data                    = array();
			$data['data-item-class'] = $params['holder_rand_class'];
			
			$laptopLargeFS = $params['font_size_1440'];
			if ( $laptopLargeFS !== '' ) {
				$data['data-font-size-1440'] = extensive_vc_filter_px( $laptopLargeFS ) . 'px';
			}
			
			$laptopSmallFS = $params['font_size_1366'];
			if ( $laptopSmallFS !== '' ) {
				$data['data-font-size-1366'] = extensive_vc_filter_px( $laptopSmallFS ) . 'px';
			}
			
			$laptopMacFS = $params['font_size_1280'];
			if ( $laptopMacFS !== '' ) {
				$data['data-font-size-1280'] = extensive_vc_filter_px( $laptopMacFS ) . 'px';
			}
			
			$tabletLandscapeFS = $params['font_size_1024'];
			if ( $tabletLandscapeFS !== '' ) {
				$data['data-font-size-1024'] = extensive_vc_filter_px( $tabletLandscapeFS ) . 'px';
			}
			
			$tabletPortraitFS = $params['font_size_768'];
			if ( $tabletPortraitFS !== '' ) {
				$data['data-font-size-768'] = extensive_vc_filter_px( $tabletPortraitFS ) . 'px';
			}
			
			$mobilesFS = $params['font_size_680'];
			if ( $mobilesFS !== '' ) {
				$data['data-font-size-680'] = extensive_vc_filter_px( $mobilesFS ) . 'px';
			}
			
			return $data;
		}
	}
}

EVCSVGText::getInstance();