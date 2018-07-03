<?php

namespace ExtensiveVC\Shortcodes\EVCIconList;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCIconListItem' ) ) {
	class EVCIconListItem extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_icon_list_item' );
			$this->setParentBase( 'evc_icon_list' );
			$this->setShortcodeName( esc_html__( 'Icon List Item', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasParent' => true ) );
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
					)
				),
				extensive_vc_get_shortcode_icon_options_array(),
				array(
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
						'type'       => 'textfield',
						'param_name' => 'icon_right_padding',
						'heading'    => esc_html__( 'Icon Right Padding (px)', 'extensive-vc' ),
						'dependency' => array( 'element' => 'icon_library', 'not_empty' => true ),
						'group'      => esc_html__( 'Icon Options', 'extensive-vc' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'text',
						'heading'     => esc_html__( 'Text', 'extensive-vc' ),
						'admin_label' => true
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'text_color',
						'heading'    => esc_html__( 'Text Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'text', 'not_empty' => true ),
						'group'      => esc_html__( 'Text Options', 'extensive-vc' )
					),
					array(
						'type'       => 'vc_link',
						'param_name' => 'custom_link',
						'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'space_between_items',
						'heading'     => esc_html__( 'Space Between Items (px)', 'extensive-vc' ),
						'description' => esc_html__( 'Fill space between items in your list. Default value is 8', 'extensive-vc' )
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
			$args = array(
				'custom_class'        => '',
				'icon_library'        => '',
				'icon_fontawesome'    => '',
				'icon_openiconic'     => '',
				'icon_typicons'       => '',
				'icon_entypo'         => '',
				'icon_linecons'       => '',
				'icon_monosocial'     => '',
				'icon_material'       => '',
				'icon_size'           => '',
				'icon_right_padding'  => '',
				'icon_color'          => '',
				'text'                => '',
				'text_color'          => '',
				'custom_link'         => '',
				'space_between_items' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			
			$params['icon_styles'] = $this->getIconStyles( $params );
			$params['text_styles'] = $this->getTextStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'icon-list', 'templates/icon-list-item', '', $params );
			
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
			$holderClasses[] = ! empty( $params['custom_link'] ) ? 'evc-ili-has-link' : '';
			
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
			
			if ( $params['space_between_items'] !== '' ) {
				$styles[] = 'margin-bottom: ' . extensive_vc_filter_px( $params['space_between_items'] ) . 'px';
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
			
			if ( $params['icon_right_padding'] !== '' ) {
				$styles[] = 'padding-right: ' . extensive_vc_filter_px( $params['icon_right_padding'] ) . 'px';
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
			
			return implode( ';', $styles );
		}
	}
}

EVCIconListItem::getInstance();