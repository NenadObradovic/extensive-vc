<?php

namespace ExtensiveVC\Shortcodes\EVCBlockquote;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCBlockquote' ) ) {
	class EVCBlockquote extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
	
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_blockquote' );
			$this->setShortcodeName( esc_html__( 'Blockquote', 'extensive-vc' ) );
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
					'type'        => 'dropdown',
					'param_name'  => 'type',
					'heading'     => esc_html__( 'Type', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Simple', 'extensive-vc' )    => 'simple',
						esc_html__( 'Left Line', 'extensive-vc' ) => 'left-line'
					),
					'admin_label' => true
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc' )
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
				'custom_class' => '',
				'type'         => 'simple',
				'text'         => ''
			);
			$params = shortcode_atts( $args, $atts );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'blockquote', 'templates/blockquote', '', $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-b-' . esc_attr( $params['type'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
	}
}

EVCBlockquote::getInstance();