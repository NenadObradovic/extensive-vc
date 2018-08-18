<?php

namespace ExtensiveVC\Shortcodes\EVCLineGraph;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCLineGraph' ) ) {
	class EVCLineGraph extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_line_graph' );
			$this->setChildBase( 'evc_line_graph_item' );
			$this->setShortcodeName( esc_html__( 'Line Graph', 'extensive-vc' ) );
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
			$current_style = '.wpb_content_element.wpb_evc_line_graph_item > .wpb_element_wrapper { background-color: #f5f5f5; }';
			
			$style .= $current_style;
			
			return $style;
		}
		
		/**
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'Chart', EXTENSIVE_VC_SHORTCODES_URL_PATH . '/line-graph/assets/js/plugins/Chart.min.js', array( 'jquery' ), false, true );
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
					'param_name' => 'legend_text',
					'heading'    => esc_html__( 'Legend Text', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'line_color',
					'heading'    => esc_html__( 'Line Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'line_thickness',
					'heading'    => esc_html__( 'Line Thickness (px)', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'disable_line',
					'heading'     => esc_html__( 'Disable Line', 'extensive-vc' ),
					'description' => esc_html__( 'Enabling this option will hide line on graph', 'extensive-vc' ),
					'value'       => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'fill_background_color',
					'heading'     => esc_html__( 'Fill Background Color', 'extensive-vc' ),
					'description' => esc_html__( 'Set background color under the line', 'extensive-vc' ),
					'dependency'  => array( 'element' => 'disable_line', 'value' => array( 'no' ) ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc' )
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
				'custom_class'          => '',
				'legend_text'           => esc_html__( 'Legend', 'extensive-vc' ),
				'line_color'            => '',
				'line_thickness'        => '',
				'disable_line'          => 'no',
				'fill_background_color' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_data']    = $this->getHolderData( $params, $args );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'line-graph', 'templates/line-graph', '', $params );
			
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
			
			$data['data-legend-text'] = ! empty( $params['legend_text'] ) ? esc_attr( $params['legend_text'] ) : $args['legend_text'];
			
			if ( ! empty( $params['line_color'] ) ) {
				$data['data-border-color'] = esc_attr( $params['line_color'] );
			}
			
			if ( $params['line_thickness'] !== '' ) {
				$data['data-border-width'] = esc_attr( extensive_vc_filter_px( $params['line_thickness'] ) );
			}
			
			$data['data-disable-line'] = ! empty( $params['disable_line'] ) ? esc_attr( $params['disable_line'] ) : $args['disable_line'];
			
			if ( ! empty( $params['fill_background_color'] ) ) {
				$data['data-background-color'] = esc_attr( $params['fill_background_color'] );
			}
			
			return $data;
		}
	}
}

EVCLineGraph::getInstance();
