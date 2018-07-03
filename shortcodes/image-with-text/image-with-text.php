<?php

namespace ExtensiveVC\Shortcodes\EVCImageWithText;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageWithText' ) ) {
	class EVCImageWithText extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_with_text' );
			$this->setShortcodeName( esc_html__( 'Image With Text', 'extensive-vc' ) );
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
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'heading'     => esc_html__( 'Image', 'extensive-vc' ),
					'description' => esc_html__( 'Select image from media library', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large or full) or enter image size in pixels: 200x100 (width x height). Leave empty to use original image size', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'image_behavior',
					'heading'    => esc_html__( 'Image Behavior', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_image_behavior_array() )
				),
				array(
					'type'       => 'vc_link',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title_top_margin',
					'heading'    => esc_html__( 'Title Top Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'text_color',
					'heading'    => esc_html__( 'Text Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'text_top_margin',
					'heading'    => esc_html__( 'Text Top Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
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
				'custom_class'     => '',
				'image'            => '',
				'image_size'       => 'full',
				'image_behavior'   => '',
				'custom_link'      => '',
				'title'            => '',
				'title_tag'        => 'h4',
				'title_color'      => '',
				'title_top_margin' => '',
				'text'             => '',
				'text_color'       => '',
				'text_top_margin'  => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			
			$params['image_classes']   = $this->getImageClasses( $params );
			$params['image_size']      = $this->getImageSize( $params['image_size'] );
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'] );
			
			$params['title_tag']    = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles'] = $this->getTitleStyles( $params );
			$params['text_styles']  = $this->getTextStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-with-text', 'templates/image-with-text', '', $params );
			
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
			$holderClasses[] = ! empty( $params['custom_link'] ) && $params['image_behavior'] !== 'lightbox' ? 'evc-shortcode-has-link' : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get image classes
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getImageClasses( $params ) {
			$itemClasses = array();
			
			$itemClasses[] = ! empty( $params['image_behavior'] ) ? 'evc-ib-' . $params['image_behavior'] : '';
			
			return implode( ' ', $itemClasses );
		}
		
		/**
		 * Get image size
		 *
		 * @param $imageSize string/array - image size value
		 *
		 * @return string/array
		 */
		private function getImageSize( $imageSize ) {
			$imageSize = trim( $imageSize );
			//Find digits
			preg_match_all( '/\d+/', $imageSize, $matches );
			
			if ( in_array( $imageSize, array( 'thumbnail', 'medium', 'large', 'full' ) ) ) {
				return $imageSize;
			} elseif ( ! empty( $matches[0] ) ) {
				return array(
					$matches[0][0],
					$matches[0][1]
				);
			} else {
				return 'full';
			}
		}
		
		/**
		 * Get title styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getTitleStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['title_color'] ) ) {
				$styles[] = 'color: ' . $params['title_color'];
			}
			
			if ( $params['title_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['title_top_margin'] ) . 'px';
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
			
			if ( $params['text_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['text_top_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCImageWithText::getInstance();