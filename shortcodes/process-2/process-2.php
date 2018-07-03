<?php

namespace ExtensiveVC\Shortcodes\EVCProcess2;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCProcess2' ) ) {
	class EVCProcess2 extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_process_2' );
			$this->setChildBase( 'evc_process_2_item' );
			$this->setShortcodeName( esc_html__( 'Process 2', 'extensive-vc' ) );
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
			$current_style = '.wpb_content_element.wpb_evc_process_2_item > .wpb_element_wrapper { background-color: #f5f5f5; }';
			
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
					'value'      => array_flip( extensive_vc_get_number_of_columns_array( array( 'one', 'five', 'six' ) ) )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'switch_to_one_column',
					'heading'     => esc_html__( 'Switch to One Column', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default None', 'extensive-vc' ) => '',
						esc_html__( 'Below 1366px', 'extensive-vc' ) => '1366',
						esc_html__( 'Below 1280px', 'extensive-vc' ) => '1280',
						esc_html__( 'Below 1024px', 'extensive-vc' ) => '1024',
						esc_html__( 'Below 768px', 'extensive-vc' )  => '768',
						esc_html__( 'Below 680px', 'extensive-vc' )  => '680',
						esc_html__( 'Below 480px', 'extensive-vc' )  => '480'
					),
					'description' => esc_html__( 'Choose on which stage item will be full width', 'extensive-vc' )
				),
				array(
					'type'        => 'attach_image',
					'param_name'  => 'background_cover_image',
					'heading'     => esc_html__( 'Background Cover Image', 'extensive-vc' ),
					'description' => esc_html__( 'Select image from media library', 'extensive-vc' )
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
				'custom_class'           => '',
				'number_of_columns'      => 'three',
				'switch_to_one_column'   => '',
				'background_cover_image' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes']  = $this->getHolderClasses( $params, $args );
			$params['bg_cover_styles'] = $this->getBgCoverStyles( $params['background_cover_image'] );
			
			$params['number_of_items'] = $this->getNumberOfItems( $params['number_of_columns'] );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'process-2', 'templates/process-2', '', $params );
			
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
			$holderClasses[] = ! empty( $params['switch_to_one_column'] ) ? 'evc-responsive-' . $params['switch_to_one_column'] : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get background cover styles
		 *
		 * @param $params string - image url
		 *
		 * @return string
		 */
		private function getBgCoverStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['background_cover_image'] ) ) {
				$styles[] = 'background-image: url(' . wp_get_attachment_url( $params['background_cover_image'] ) . ')';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get number of items
		 *
		 * @param $number_of_columns string - number of columns parameter value
		 *
		 * @return string
		 */
		private function getNumberOfItems( $number_of_columns ) {
			$number = 3;
			
			switch ( $number_of_columns ) {
				case 'two':
					$number = 2;
					break;
				case 'three':
					$number = 3;
					break;
				case 'four':
					$number = 4;
					break;
			}
			
			return $number;
		}
	}
}

EVCProcess2::getInstance();
