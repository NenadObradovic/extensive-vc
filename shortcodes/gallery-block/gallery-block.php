<?php

namespace ExtensiveVC\Shortcodes\EVCGalleryBlock;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCGalleryBlock' ) ) {
	class EVCGalleryBlock extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_gallery_block' );
			$this->setShortcodeName( esc_html__( 'Gallery Block', 'extensive-vc' ) );
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
						esc_html__( 'Featured Image Top', 'extensive-vc' )          => 'featured-top',
						esc_html__( 'Featured Image On Left Side', 'extensive-vc' ) => 'featured-left'
					),
					'save_always' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'space_between_items',
					'heading'    => esc_html__( 'Space Between Items', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_space_between_items_array() )
				),
				array(
					'type'        => 'attach_images',
					'param_name'  => 'images',
					'heading'     => esc_html__( 'Images', 'extensive-vc' ),
					'description' => esc_html__( 'Select images from media library. The first image you upload will be set as the featured image if you set Featured Image Size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'featured_image_size',
					'heading'     => esc_html__( 'Featured Image Size', 'extensive-vc' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large or full) or enter image size in pixels: 200x100 (width x height). Leave empty to use original image size', 'extensive-vc' )
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
					'type'        => 'textarea',
					'param_name'  => 'custom_links',
					'heading'     => esc_html__( 'Custom Links', 'extensive-vc' ),
					'description' => esc_html__( 'Delimit links by comma', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'custom_link_target',
					'heading'    => esc_html__( 'Custom Link Target', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_link_target_array() ),
					'dependency' => array( 'element' => 'custom_links', 'not_empty' => true )
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
				'custom_class'        => '',
				'type'                => 'featured-top',
				'images'              => '',
				'featured_image_size' => '',
				'image_size'          => 'full',
				'image_behavior'      => '',
				'custom_links'        => '',
				'custom_link_target'  => '_self',
				'space_between_items' => 'normal'
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			
			$params['image_classes']       = $this->getImageClasses( $params );
			$params['images']              = $this->getImages( $params );
			$params['featured_image_size'] = $this->getImageSize( false, $params['featured_image_size'] );
			$params['default_image_size']  = $this->getImageSize( $params['image_size'], false );
			
			$params['custom_links']       = $this->getCustomLinks( $params );
			$params['custom_link_target'] = ! empty( $params['custom_link_target'] ) ? $params['custom_link_target'] : $args['custom_link_target'];
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'gallery-block', 'templates/gallery-block', '', $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-gb-' . esc_attr( $params['type'] ) : 'evc-gb-' . esc_attr( $args['type'] );
			$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'evc-' . $params['space_between_items'] . '-space' : 'evc-' . $args['space_between_items'] . '-space';
			$holderClasses[] = ! empty( $params['custom_links'] ) && $params['image_behavior'] !== 'lightbox' ? 'evc-shortcode-has-link' : '';
			
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
		 * Get images attributes
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getImages( $params ) {
			$imageIds = array();
			$images   = array();
			$i        = 0;
			
			if ( $params['images'] !== '' ) {
				$imageIds = explode( ',', $params['images'] );
			}
			
			foreach ( $imageIds as $id ) {
				$image['image_id'] = $id;
				$imageOriginal     = wp_get_attachment_image_src( $id, 'full' );
				$image['url']      = $imageOriginal[0];
				$image['title']    = get_the_title( $id );
				$image['alt']      = get_post_meta( $id, '_wp_attachment_image_alt', true );
				
				$images[ $i ] = $image;
				$i ++;
			}
			
			return $images;
		}
		
		/**
		 * Get image size
		 *
		 * @param $imageSize string/array - image size value
		 * @param $featuredImageSize string/array - featured image size value
		 *
		 * @return string/array
		 */
		private function getImageSize( $imageSize, $featuredImageSize ) {
			$imageSize = ! empty( $featuredImageSize ) ? trim( $featuredImageSize ) : trim( $imageSize );
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
				return ! empty( $featuredImageSize ) ? 'no-featured-image' : 'full';
			}
		}
		
		/**
		 * Get custom links
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getCustomLinks( $params ) {
			$customLinks = array();
			
			if ( ! empty( $params['custom_links'] ) ) {
				$customLinks = array_map( 'trim', explode( ',', str_replace( ' ', '', $params['custom_links'] ) ) );
			}
			
			return $customLinks;
		}
	}
}

EVCGalleryBlock::getInstance();