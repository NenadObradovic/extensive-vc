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
			$this->setShortcodeName( esc_html__( 'Flip Image', 'extensive-vc-addon' ) );
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
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		/**
		 * Set shortcode parameters for WPBakery Page Builder shortcodes options panel
		 */
		function shortcodeParameters() {
			$params = array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'type',
					'heading'     => esc_html__( 'Type', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Horizontal', 'extensive-vc-addon' ) => 'horizontal',
						esc_html__( 'Vertical', 'extensive-vc-addon' )   => 'vertical'
					),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'front_type',
					'heading'     => esc_html__( 'Front Side', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Image', 'extensive-vc-addon' )   => 'image',
						esc_html__( 'Content', 'extensive-vc-addon' ) => 'content'
					),
					'description' => esc_html__( 'Choose image or content (title, text and background) for the front side of the element', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'heading'     => esc_html__( 'Image', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Select image from media library. If front side is content, image is used as front side background', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large or full) or enter image size in pixels: 200x100 (width x height). Leave empty to use original image size', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'front_title',
					'heading'    => esc_html__( 'Front Side Title', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'front_type', 'value' => array( 'content' ) )
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'front_text',
					'heading'    => esc_html__( 'Front Side Text', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'front_type', 'value' => array( 'content' ) )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'front_bg_color',
					'heading'    => esc_html__( 'Front Side Background Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'front_type', 'value' => array( 'content' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'front_color',
					'heading'    => esc_html__( 'Front Side Text Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'front_type', 'value' => array( 'content' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'min_height',
					'heading'     => esc_html__( 'Height (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Set element height when front side is content. Default value is 300px', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'front_type', 'value' => array( 'content' ) ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'vc_link',
					'param_name'  => 'custom_link',
					'heading'     => esc_html__( 'Custom Link', 'extensive-vc-addon' ),
					'description' => esc_html__( 'If back side button is set, link is used for the button, otherwise whole element is linked', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'content_bg_color',
					'heading'    => esc_html__( 'Content Background Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title',
					'heading'    => esc_html__( 'Back Side Title', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true ) ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Back Side Text', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'text_color',
					'heading'    => esc_html__( 'Text Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'text_top_margin',
					'heading'    => esc_html__( 'Text Top Margin (px)', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'button_text',
					'heading'     => esc_html__( 'Back Side Button Text', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Button uses custom link value', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Button Options', 'extensive-vc-addon' )
				)
			);

			$params = array_merge( $params, extensive_vc_get_button_shortcode_options_array( true ) );

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
				'front_type'       => 'image',
				'front_title'      => '',
				'front_text'       => '',
				'front_bg_color'   => '',
				'front_color'      => '',
				'min_height'       => '',
				'image'            => '',
				'image_size'       => 'full',
				'custom_link'      => '',
				'content_bg_color' => '',
				'title'            => '',
				'title_tag'        => 'h4',
				'title_color'      => '',
				'text'             => '',
				'text_color'       => '',
				'text_top_margin'  => '',
				'button_text'      => ''
			);
			$args   = array_merge( $args, extensive_vc_get_button_shortcode_default_args() );
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['front_type'] = in_array( $params['front_type'], array( 'image', 'content' ), true ) ? $params['front_type'] : $args['front_type'];
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			
			$params['image_size']      = $this->getImageSize( $params['image_size'] );
			$params['link_attributes'] = empty( $params['button_text'] ) ? extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-fi-link' ) : array();
			$params['button_params']   = extensive_vc_get_button_shortcode_params( array_merge( $params, array( 'button_custom_link' => $params['custom_link'] ) ) );
			$params['front_styles']    = $this->getFrontStyles( $params );
			
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
			$holderClasses[] = 'evc-fi-front-' . esc_attr( $params['front_type'] );
			$holderClasses[] = ! empty( $params['button_text'] ) ? 'evc-fi-has-button' : '';

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
			return extensive_vc_get_image_size_value( $imageSize );
		}
		
		/**
		 * Get front side styles, used when front side is content
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getFrontStyles( $params ) {
			$styles = array();

			if ( 'content' !== $params['front_type'] ) {
				return $styles;
			}

			if ( ! empty( $params['front_bg_color'] ) ) {
				$styles[] = 'background-color: ' . $params['front_bg_color'];
			}

			if ( ! empty( $params['image'] ) ) {
				$image_url = wp_get_attachment_image_url( $params['image'], 'full' );

				if ( ! empty( $image_url ) ) {
					$styles[] = 'background-image: url(' . esc_url( $image_url ) . ')';
				}
			}

			if ( ! empty( $params['front_color'] ) ) {
				$styles[] = 'color: ' . $params['front_color'];
			}

			if ( '' !== $params['min_height'] ) {
				$styles[] = 'min-height: ' . absint( $params['min_height'] ) . 'px';
			}

			return $styles;
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
				$styles[] = 'margin-top: ' . intval( $params['text_top_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCFlipImage::getInstance();