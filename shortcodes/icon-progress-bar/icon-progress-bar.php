<?php

namespace ExtensiveVC\Shortcodes\EVCIconProgressBar;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCIconProgressBar' ) ) {
	class EVCIconProgressBar extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_icon_progress_bar' );
			$this->setShortcodeName( esc_html__( 'Icon Progress Bar', 'extensive-vc' ) );
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
						'type'       => 'textfield',
						'param_name' => 'number_of_icons',
						'heading'    => esc_html__( 'Number Of Icons', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'number_of_active_icons',
						'heading'    => esc_html__( 'Number Of Active Icons', 'extensive-vc' )
					)
				),
				extensive_vc_get_shortcode_icon_options_array(),
				array(
					array(
						'type'       => 'textfield',
						'param_name' => 'icon_size',
						'heading'    => esc_html__( 'Icon Size (px)', 'extensive-vc' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'icon_color',
						'heading'    => esc_html__( 'Icon Color', 'extensive-vc' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'icon_active_color',
						'heading'    => esc_html__( 'Icon Active Color', 'extensive-vc' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'space_between_icons',
						'heading'    => esc_html__( 'Space Between Icons (px)', 'extensive-vc' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
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
				'custom_class'           => '',
				'number_of_icons'        => '',
				'number_of_active_icons' => '',
				'icon_library'           => '',
				'icon_fontawesome'       => '',
				'icon_openiconic'        => '',
				'icon_typicons'          => '',
				'icon_entypo'            => '',
				'icon_linecons'          => '',
				'icon_monosocial'        => '',
				'icon_material'          => '',
				'icon_size'              => '',
				'icon_color'             => '',
				'icon_active_color'      => '',
				'space_between_icons'    => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['holder_data']    = $this->getHolderData( $params );
			
			$params['icon_styles'] = $this->getIconStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'icon-progress-bar', 'templates/icon-progress-bar', '', $params );
			
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
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode holder data
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderData( $params ) {
			$data = array();
			
			$data['data-number-of-active-icons'] = ! empty( $params['number_of_active_icons'] ) ? intval( $params['number_of_active_icons'] ) : '';
			$data['data-icon-active-color']     = ! empty( $params['icon_active_color'] ) ? $params['icon_active_color'] : '';
			
			return $data;
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
			
			if ( ! empty( $params['space_between_icons'] ) ) {
				$styles[] = 'margin-right: ' . extensive_vc_filter_px( $params['space_between_icons'] ) . 'px';
				$styles[] = 'margin-bottom: ' . extensive_vc_filter_px( $params['space_between_icons'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCIconProgressBar::getInstance();