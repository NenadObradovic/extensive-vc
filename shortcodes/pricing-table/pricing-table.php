<?php

namespace ExtensiveVC\Shortcodes\EVCPricingTable;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPricingTable' ) ) {
	class EVCPricingTable extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_pricing_table' );
			$this->setChildBase( 'evc_pricing_table_item' );
			$this->setShortcodeName( esc_html__( 'Pricing Table', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasChild' => true ) );
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				add_filter( 'extensive_vc_filter_add_vc_shortcodes_custom_style', array( $this, 'addShortcodeIconCustomStyle' ) );
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
			$current_style = '.wpb_content_element.wpb_evc_pricing_table_item > .wpb_element_wrapper { background-color: #f5f5f5; }';
			
			$style .= $current_style;
			
			return $style;
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
					'param_name' => 'number_of_columns',
					'heading'    => esc_html__( 'Number of Columns', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_number_of_columns_array( array( 'six' ) ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'space_between_items',
					'heading'    => esc_html__( 'Space Between Items', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_space_between_items_array() )
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
				'custom_class'        => '',
				'number_of_columns'   => 'three',
				'space_between_items' => 'normal'
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'pricing-table', 'templates/pricing-table', '', $params );
			
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
			$holderClasses[] = ! empty( $params['number_of_columns'] ) ? 'evc-' . $params['number_of_columns'] . '-columns' : 'evc-' . $args['number_of_columns'] . '-columns';
			$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'evc-' . $params['space_between_items'] . '-space' : 'evc-' . $args['space_between_items'] . '-space';
			
			return implode( ' ', $holderClasses );
		}
	}
}

EVCPricingTable::getInstance();