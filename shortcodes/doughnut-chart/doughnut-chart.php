<?php

namespace ExtensiveVC\Shortcodes\EVCDoughnutChart;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCDoughnutChart' ) ) {
	class EVCDoughnutChart extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_doughnut_chart' );
			$this->setChildBase( 'evc_doughnut_chart_item' );
			$this->setShortcodeName( esc_html__( 'Doughnut Chart', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( true );
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				add_filter( 'extensive_vc_filter_add_vc_shortcodes_custom_style', array( $this, 'addShortcodeIconCustomStyle' ) );
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
		 * Add shortcode custom css style for Visual Composer shortcodes panel
		 */
		function addShortcodeIconCustomStyle( $style ) {
			$current_style = '.wpb_content_element.wpb_evc_doughnut_chart_item > .wpb_element_wrapper {
				background-color: #f5f5f5;
			}';
			
			$style .= $current_style;
			
			return $style;
		}
		
		/**
		 * Include necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'Chart', EXTENSIVE_VC_SHORTCODES_URL_PATH . '/doughnut-chart/assets/js/plugins/Chart.min.js', array( 'jquery' ), false, true );
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
					'param_name'  => 'canvas_width',
					'heading'     => esc_html__( 'Canvas Width (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Fill canvas width size, default value is 260', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'canvas_height',
					'heading'     => esc_html__( 'Canvas Height (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Fill canvas height size, default value is 260', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'canvas_space',
					'heading'     => esc_html__( 'Canvas Space', 'extensive-vc' ),
					'description' => esc_html__( 'Fill space between items, default value is 2', 'extensive-vc' )
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
				'custom_class'  => '',
				'canvas_width'  => '',
				'canvas_height' => '',
				'canvas_space'  => ''
			);
			$params = shortcode_atts( $args, $atts );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_data']    = $this->getHolderData( $params );
			
			$params['canvas_styles']  = $this->getCanvasStyles( $params );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'doughnut-chart', 'templates/doughnut-chart', '', $params );
			
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
		private function getHolderClasses( $params ) {
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
			
			if ( $params['canvas_space'] !== '' ) {
				$data['data-border-width'] = esc_attr( $params['canvas_space'] );
			}
			
			return $data;
		}
		
		/**
		 * Get canvas styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getCanvasStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['canvas_width'] ) ) {
				$styles[] = 'width: ' . extensive_vc_filter_px( $params['canvas_width'] ) . 'px';
			}
			
			if ( ! empty( $params['canvas_height'] ) ) {
				$styles[] = 'height: ' . extensive_vc_filter_px( $params['canvas_height'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCDoughnutChart::getInstance();
