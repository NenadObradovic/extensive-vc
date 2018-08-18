<?php

namespace ExtensiveVC\Shortcodes\EVCPieChart;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPieChart' ) ) {
	class EVCPieChart extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_pie_chart' );
			$this->setChildBase( 'evc_pie_chart_item' );
			$this->setShortcodeName( esc_html__( 'Pie Chart', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasChild' => true ) );
			
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
			$current_style = '.wpb_content_element.wpb_evc_pie_chart_item > .wpb_element_wrapper { background-color: #f5f5f5; }';
			
			$style .= $current_style;
			
			return $style;
		}
		
		/**
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'Chart', EXTENSIVE_VC_SHORTCODES_URL_PATH . '/pie-chart/assets/js/plugins/Chart.min.js', array( 'jquery' ), false, true );
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
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'space_color',
					'heading'    => esc_html__( 'Canvas Space Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'space_hover_color',
					'heading'    => esc_html__( 'Canvas Space Hover Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_legend',
					'heading'    => esc_html__( 'Enable Legend', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'legend_position',
					'heading'    => esc_html__( 'Legend Position', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Top', 'extensive-vc' )    => 'top',
						esc_html__( 'Right', 'extensive-vc' )  => 'right',
						esc_html__( 'Bottom', 'extensive-vc' ) => 'bottom',
						esc_html__( 'Left', 'extensive-vc' )   => 'left'
					),
					'dependency' => array( 'element' => 'enable_legend', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'legend_text_size',
					'heading'     => esc_html__( 'Legend Text Size (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Fill legend text font size, default value is 12', 'extensive-vc' ),
					'dependency'  => array( 'element' => 'enable_legend', 'value' => array( 'yes' ) ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'legend_color',
					'heading'    => esc_html__( 'Legend Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'enable_legend', 'value' => array( 'yes' ) ),
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
			$args   = array(
				'custom_class'      => '',
				'canvas_width'      => '',
				'canvas_height'     => '',
				'canvas_space'      => '',
				'space_color'       => '',
				'space_hover_color' => '',
				'enable_legend'     => 'yes',
				'legend_position'   => 'top',
				'legend_text_size'  => '',
				'legend_color'      => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_data']    = $this->getHolderData( $params, $args );
			
			$params['canvas_styles'] = $this->getCanvasStyles( $params );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'pie-chart', 'templates/pie-chart', '', $params );
			
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
		 * Get shortcode holder data
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderData( $params, $args ) {
			$data = array();
			
			if ( $params['canvas_space'] !== '' ) {
				$data['data-border-width'] = esc_attr( $params['canvas_space'] );
			}
			
			if ( ! empty( $params['space_color'] ) ) {
				$data['data-border-color'] = esc_attr( $params['space_color'] );
			}
			
			if ( ! empty( $params['space_hover_color'] ) ) {
				$data['data-border-hover-color'] = esc_attr( $params['space_hover_color'] );
			}
			
			$data['data-enable-legend']   = $params['enable_legend'] !== 'no';
			$data['data-legend-position'] = ! empty( $params['legend_position'] ) ? esc_attr( $params['legend_position'] ) : $args['legend_position'];
			
			if ( $params['legend_text_size'] !== '' ) {
				$data['data-legend-text-size'] = esc_attr( $params['legend_text_size'] );
			}
			
			if ( ! empty( $params['legend_color'] ) ) {
				$data['data-legend-color'] = esc_attr( $params['legend_color'] );
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

EVCPieChart::getInstance();
