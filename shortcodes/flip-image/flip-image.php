<?php

namespace ExtensiveVC\Shortcodes\EVCFlipImage;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCFlipImage' ) ) {
	class EVCFlipImage extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_flip_image' );
			$this->setShortcodeName( esc_html__( 'Flip Image', 'extensive-vc' ) );
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
						esc_html__( 'Horizontal', 'extensive-vc' ) => 'horizontal',
						esc_html__( 'Vertical', 'extensive-vc' )   => 'vertical'
					),
					'admin_label' => true
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
					'type'       => 'vc_link',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'content_bg_color',
					'heading'    => esc_html__( 'Content Background Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title',
					'heading'    => esc_html__( 'Title', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true ) ),
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
				'type'             => 'horizontal',
				'image'            => '',
				'image_size'       => 'full',
				'custom_link'      => '',
				'content_bg_color' => '',
				'title'            => '',
				'title_tag'        => 'h4',
				'title_color'      => '',
				'text'             => '',
				'text_color'       => '',
				'text_top_margin'  => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			
			$params['image_size']      = $this->getImageSize( $params['image_size'] );
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-fi-link' );
			
			$params['content_styles'] = $this->getContentStyles( $params );
			$params['title_tag']      = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles']   = $this->getTitleStyles( $params );
			$params['text_styles']    = $this->getTextStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'flip-image', 'templates/flip-image', '', $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-fi-' . esc_attr( $params['type'] ) : 'evc-fi-' . esc_attr( $args['type'] );
			
			return implode( ' ', $holderClasses );
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
		 * Get content styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getContentStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['content_bg_color'] ) ) {
				$styles[] = 'background-color: ' . $params['content_bg_color'];
			}
			
			return implode( ';', $styles );
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

EVCFlipImage::getInstance();