<?php

namespace ExtensiveVC\Shortcodes\EVCPieChart;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPieChartItem' ) ) {
	class EVCPieChartItem extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_pie_chart_item' );
			$this->setParentBase( 'evc_pie_chart' );
			$this->setShortcodeName( esc_html__( 'Pie Chart Item', 'extensive-vc' ) );
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
			$params = array(
				array(
					'type'       => 'textfield',
					'param_name' => 'label',
					'heading'    => esc_html__( 'Label', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'value',
					'heading'    => esc_html__( 'Value', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' )
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
				'label' => '',
				'value' => '',
				'color' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_data']    = $this->getHolderData( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'pie-chart', 'templates/pie-chart-item', '', $params );
			
			return $html;
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
			
			if ( ! empty( $params['label'] ) ) {
				$data['data-label'] = esc_attr( $params['label'] );
			}
			
			if ( ! empty( $params['value'] ) ) {
				$data['data-value'] = esc_attr( $params['value'] );
			}
			
			if ( ! empty( $params['color'] ) ) {
				$data['data-color'] = esc_attr( $params['color'] );
			}
			
			return $data;
		}
	}
}

EVCPieChartItem::getInstance();