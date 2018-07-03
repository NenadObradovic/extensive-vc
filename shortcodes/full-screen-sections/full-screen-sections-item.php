<?php

namespace ExtensiveVC\Shortcodes\EVCFullScreenSections;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCFullScreenSectionsItem' ) ) {
	class EVCFullScreenSectionsItem extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_full_screen_sections_item' );
			$this->setParentBase( 'evc_full_screen_sections' );
			$this->setShortcodeName( esc_html__( 'Full Screen Sections Item', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasParent' => true, 'isNested' => true ) );
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
					'type'       => 'colorpicker',
					'param_name' => 'background_color',
					'heading'    => esc_html__( 'Background Color', 'extensive-vc' )
				),
				array(
					'type'       => 'attach_image',
					'param_name' => 'background_image',
					'heading'    => esc_html__( 'Background Image', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'background_position',
					'heading'     => esc_html__( 'Background Image Position', 'extensive-vc' ),
					'description' => esc_html__( 'Please insert position in format horizontal vertical position, example - center center', 'extensive-vc' ),
					'dependency'  => array( 'item' => 'background_image', 'not_empty' => true )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'background_size',
					'heading'    => esc_html__( 'Background Image Size', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Cover', 'extensive-vc' )   => 'cover',
						esc_html__( 'Contain', 'extensive-vc' ) => 'contain',
						esc_html__( 'Inherit', 'extensive-vc' ) => 'inherit'
					),
					'dependency' => array( 'item' => 'background_image', 'not_empty' => true )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'vertical_alignment',
					'heading'    => esc_html__( 'Content Vertical Alignment', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Top', 'extensive-vc' )     => 'top',
						esc_html__( 'Middle', 'extensive-vc' )  => 'middle',
						esc_html__( 'Bottom', 'extensive-vc' )  => 'bottom'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'horizontal_alignment',
					'heading'    => esc_html__( 'Content Horizontal Alignment', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Left', 'extensive-vc' )    => 'left',
						esc_html__( 'Center', 'extensive-vc' )  => 'center',
						esc_html__( 'Right', 'extensive-vc' )   => 'right'
					)
				),
				array(
					'type'       => 'vc_link',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
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
				'custom_class'         => '',
				'background_color'     => '',
				'background_image'     => '',
				'background_position'  => '',
				'background_size'      => '',
				'vertical_alignment'   => '',
				'horizontal_alignment' => '',
				'custom_link'          => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes']    = $this->getHolderClasses( $params );
			
			$params['background_styles'] = $this->getBackgroundStyles( $params );
			$params['link_attributes']   = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-fssi-link' );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'full-screen-sections', 'templates/full-screen-sections-item', '', $params );
			
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
			$holderClasses[] = ! empty( $params['vertical_alignment'] ) ? 'evc-fssi-va-' . $params['vertical_alignment'] : '';
			$holderClasses[] = ! empty( $params['horizontal_alignment'] ) ? 'evc-fssi-ha-' . $params['horizontal_alignment'] : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode background holder styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getBackgroundStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['background_color'] ) ) {
				$styles[] = 'background-color: ' . $params['background_color'];
			}
			
			if ( ! empty( $params['background_image'] ) ) {
				$styles[] = 'background-image: url(' . wp_get_attachment_url( $params['background_image'] ) . ')';
				
				if ( ! empty( $params['background_position'] ) ) {
					$styles[] = 'background-position:' . $params['background_position'];
				}
				
				if ( ! empty( $params['background_size'] ) ) {
					$styles[] = 'background-size:' . $params['background_size'];
				}
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCFullScreenSectionsItem::getInstance();