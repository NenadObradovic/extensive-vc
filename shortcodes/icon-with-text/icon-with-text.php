<?php

namespace ExtensiveVC\Shortcodes\EVCIconWithText;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCIconWithText' ) ) {
	class EVCIconWithText extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_icon_with_text' );
			$this->setShortcodeName( esc_html__( 'Icon With Text', 'extensive-vc' ) );
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
							esc_html__( 'Icon Top', 'extensive-vc' )  => 'icon-top',
							esc_html__( 'Icon Left', 'extensive-vc' ) => 'icon-left'
						),
						'save_always' => true
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
						'type'        => 'dropdown',
						'param_name'  => 'title_tag',
						'heading'     => esc_html__( 'Title Tag', 'extensive-vc' ),
						'value'       => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
						'save_always' => true,
						'dependency'  => array( 'element' => 'title', 'not_empty' => true ),
						'group'       => esc_html__( 'Title Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'title_color',
						'heading'    => esc_html__( 'Title Color', 'extensive-vc' ),
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
				'type'             => 'icon-top',
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
				'text_padding'     => '',
				'custom_link'      => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			
			$params['icon_styles'] = $this->getIconStyles( $params );
			
			$params['title_tag']    = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles'] = $this->getTitleStyles( $params );
			$params['text_styles']  = $this->getTextStyles( $params );
			
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-iwt-link' );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'icon-with-text', 'templates/icon-with-text', '', $params );
			
			return $html;
		}
		
		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters value
		 * @param $args array - default shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderClasses( $params, $args ) {
			$holderClasses = array();
			
			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-iwt-' . $params['type'] : 'evc-iwt-' . $args['type'];
			
			return implode( ' ', $holderClasses );
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

EVCIconWithText::getInstance();