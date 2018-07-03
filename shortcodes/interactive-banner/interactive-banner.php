<?php

namespace ExtensiveVC\Shortcodes\EVCInteractiveBanner;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCInteractiveBanner' ) ) {
	class EVCInteractiveBanner extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_interactive_banner' );
			$this->setShortcodeName( esc_html__( 'Interactive Banner', 'extensive-vc' ) );
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
			$params = array_merge(
				array(
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
							esc_html__( 'Classic', 'extensive-vc' )             => 'classic',
							esc_html__( 'Bottom Animation', 'extensive-vc' )    => 'bottom-animation',
							esc_html__( 'Bordered', 'extensive-vc' )            => 'bordered',
							esc_html__( 'Slide From Bottom', 'extensive-vc' )   => 'slide-from-bottom',
							esc_html__( 'Shutter In Vertical', 'extensive-vc' ) => 'shutter-in-vertical'
						),
						'save_always' => true
					),
					array(
						'type'        => 'attach_image',
						'param_name'  => 'image',
						'heading'     => esc_html__( 'Image', 'extensive-vc' ),
						'description' => esc_html__( 'Select image from media library', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'overlay_color',
						'heading'    => esc_html__( 'Overlay Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'image', 'not_empty' => true )
					)
				),
				extensive_vc_get_shortcode_icon_options_array(),
				array(
					array(
						'type'       => 'attach_image',
						'param_name' => 'custom_icon',
						'heading'    => esc_html__( 'Custom Icon', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'icon_size',
						'heading'    => esc_html__( 'Icon Size (px)', 'extensive-vc' ),
						'dependency' => array( 'element' => 'icon_library', 'not_empty' => true ),
						'group'      => esc_html__( 'Icon Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'icon_color',
						'heading'    => esc_html__( 'Icon Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'icon_library', 'not_empty' => true ),
						'group'      => esc_html__( 'Icon Options', 'extensive-vc' )
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
						'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
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
					),
					array(
						'type'       => 'vc_link',
						'param_name' => 'custom_link',
						'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
					)
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
				'custom_class'     => '',
				'type'             => 'classic',
				'image'            => '',
				'overlay_color'    => '',
				'icon_library'     => '',
				'icon_fontawesome' => '',
				'icon_openiconic'  => '',
				'icon_typicons'    => '',
				'icon_entypo'      => '',
				'icon_linecons'    => '',
				'icon_monosocial'  => '',
				'icon_material'    => '',
				'custom_icon'      => '',
				'icon_size'        => '',
				'icon_color'       => '',
				'title'            => '',
				'title_tag'        => 'h4',
				'title_color'      => '',
				'text'             => '',
				'text_color'       => '',
				'text_top_margin'  => '',
				'custom_link'      => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['content_styles'] = $this->getContentStyles( $params );
			
			$params['icon_styles']  = $this->getIconStyles( $params );
			$params['title_tag']    = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles'] = $this->getTitleStyles( $params );
			$params['text_styles']  = $this->getTextStyles( $params );
			
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-ib-link' );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'interactive-banner', 'templates/interactive-banner', '', $params );
			
			return $html;
		}
		
		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderClasses( $params, $args ) {
			$holderClasses = array();
			
			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-ib-' . esc_attr( $params['type'] ) : 'evc-ib-' . esc_attr( $args['type'] );
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get content styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getContentStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['overlay_color'] ) ) {
				
				if ( $params['type'] === 'shutter-in-vertical' ) {
					$styles[] = 'color: ' . $params['overlay_color'];
				} else {
					$styles[] = 'background-color: ' . $params['overlay_color'];
				}
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get icon styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getIconStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['icon_size'] ) ) {
				$styles[] = 'font-size: ' . extensive_vc_filter_px( $params['icon_size'] ) . 'px';
			}
			
			if ( ! empty( $params['icon_color'] ) ) {
				$styles[] = 'color: ' . $params['icon_color'];
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

EVCInteractiveBanner::getInstance();