<?php

namespace ExtensiveVC\Shortcodes\EVCImageGallery;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageGallery' ) ) {
	class EVCImageGallery extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_gallery' );
			$this->setShortcodeName( esc_html__( 'Image Gallery', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
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
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_style( 'owl-carousel', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/owl-carousel/owl.carousel.min.css' );
			wp_enqueue_script( 'owl-carousel', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/owl-carousel/owl.carousel.min.js', array( 'jquery' ), false, true );
			
			wp_enqueue_style( 'lightbox', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/lightbox/lightbox.min.css' );
			wp_enqueue_script( 'lightbox', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/lightbox/lightbox.min.js', array( 'jquery' ), false, true );
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
						esc_html__( 'Image Grid', 'extensive-vc' ) => 'grid',
						esc_html__( 'Slider', 'extensive-vc' )     => 'slider',
						esc_html__( 'Carousel', 'extensive-vc' )   => 'carousel'
					),
					'save_always' => true,
					'admin_label' => true
				),
				array(
					'type'        => 'attach_images',
					'param_name'  => 'images',
					'heading'     => esc_html__( 'Images', 'extensive-vc' ),
					'description' => esc_html__( 'Select images from media library', 'extensive-vc' )
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
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'number_of_columns',
					'heading'    => esc_html__( 'Number of Columns', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_number_of_columns_array() ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'grid' ) ),
					'group'      => esc_html__( 'Grid Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'space_between_items',
					'heading'    => esc_html__( 'Space Between Items', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_space_between_items_array() ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'grid' ) ),
					'group'      => esc_html__( 'Grid Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'number_of_visible_items',
					'heading'    => esc_html__( 'Number of Visible Items', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'One', 'extensive-vc' )   => '1',
						esc_html__( 'Two', 'extensive-vc' )   => '2',
						esc_html__( 'Three', 'extensive-vc' ) => '3',
						esc_html__( 'Four', 'extensive-vc' )  => '4',
						esc_html__( 'Five', 'extensive-vc' )  => '5',
						esc_html__( 'Six', 'extensive-vc' )   => '6'
					),
					'dependency' => array( 'element' => 'type', 'value' => array( 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_loop',
					'heading'    => esc_html__( 'Enable Slider Loop', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay',
					'heading'    => esc_html__( 'Enable Slider Autoplay', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay_pause',
					'heading'    => esc_html__( 'Enable Slider Autoplay Hover Pause', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed',
					'heading'     => esc_html__( 'Slide Duration (ms)', 'extensive-vc' ),
					'description' => esc_html__( 'Speed of slide in milliseconds. Default value is 5000', 'extensive-vc' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed_animation',
					'heading'     => esc_html__( 'Slide Animation Duration (ms)', 'extensive-vc' ),
					'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 600', 'extensive-vc' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_margin',
					'heading'     => esc_html__( 'Slide Margin (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Define right margin for slide items. Default value is 0', 'extensive-vc' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_navigation',
					'heading'    => esc_html__( 'Enable Slider Navigation', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_pagination',
					'heading'    => esc_html__( 'Enable Slider Pagination', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'carousel_navigation_skin',
					'heading'     => esc_html__( 'Slider Navigation Skin', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Light', 'extensive-vc' )   => 'light'
					),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider', 'carousel' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
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
				'custom_class'             => '',
				'type'                     => 'grid',
				'images'                   => '',
				'image_size'               => 'full',
				'image_behavior'           => '',
				'custom_links'             => '',
				'custom_link_target'       => '_self',
				'number_of_columns'        => 'three',
				'space_between_items'      => 'normal',
				'number_of_visible_items'  => '1',
				'carousel_loop'            => 'yes',
				'carousel_autoplay'        => 'yes',
				'carousel_autoplay_pause'  => 'no',
				'carousel_speed'           => '5000',
				'carousel_speed_animation' => '600',
				'carousel_margin'          => '',
				'carousel_navigation'      => 'yes',
				'carousel_pagination'      => 'yes',
				'carousel_navigation_skin' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['slider_data']    = $this->getSliderData( $params, $args );
			
			$params['image_classes'] = $this->getImageClasses( $params );
			$params['images']        = $this->getImages( $params );
			$params['image_size']    = $this->getImageSize( $params['image_size'] );
			
			$params['custom_links']       = $this->getCustomLinks( $params );
			$params['custom_link_target'] = ! empty( $params['custom_link_target'] ) ? $params['custom_link_target'] : $args['custom_link_target'];
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-gallery', 'templates/image-gallery', $params['type'], $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-ig-' . $params['type'] . '-type' : 'evc-ig-' . $args['type'] . '-type';
			$holderClasses[] = ! empty( $params['number_of_columns'] ) ? 'evc-' . $params['number_of_columns'] . '-columns' : 'evc-' . $args['number_of_columns'] . '-columns';
			$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'evc-' . $params['space_between_items'] . '-space' : 'evc-' . $args['space_between_items'] . '-space';
			$holderClasses[] = ! empty( $params['custom_links'] ) && $params['image_behavior'] !== 'lightbox' ? 'evc-shortcode-has-link' : '';
			$holderClasses[] = ! empty( $params['carousel_navigation_skin'] ) ? 'evc-carousel-skin-' . esc_attr( $params['carousel_navigation_skin'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get slider data
		 *
		 * @param $params array - shortcode parameters value
		 * @param $args array - default shortcode parameters value
		 *
		 * @return array
		 */
		private function getSliderData( $params, $args ) {
			$data = array();
			
			$data['data-number-of-items']             = $params['number_of_visible_items'] !== '' && $params['type'] === 'carousel' ? $params['number_of_visible_items'] : $args['number_of_visible_items'];
			$data['data-enable-loop']                 = ! empty( $params['carousel_loop'] ) ? $params['carousel_loop'] : $args['carousel_loop'];
			$data['data-enable-autoplay']             = ! empty( $params['carousel_autoplay'] ) ? $params['carousel_autoplay'] : $args['carousel_autoplay'];
			$data['data-enable-autoplay-hover-pause'] = ! empty( $params['carousel_autoplay_pause'] ) ? $params['carousel_autoplay_pause'] : $args['carousel_autoplay_pause'];
			$data['data-carousel-speed']              = ! empty( $params['carousel_speed'] ) ? $params['carousel_speed'] : $args['carousel_speed'];
			$data['data-carousel-speed-animation']    = ! empty( $params['carousel_speed_animation'] ) ? $params['carousel_speed_animation'] : $args['carousel_speed_animation'];
			if ( $params['type'] === 'carousel' && empty( $params['carousel_margin'] ) ) {
				$data['data-carousel-margin'] = '30';
			} else {
				$data['data-carousel-margin'] = ! empty( $params['carousel_margin'] ) ? $params['carousel_margin'] : $args['carousel_margin'];
			}
			$data['data-enable-navigation'] = ! empty( $params['carousel_navigation'] ) ? $params['carousel_navigation'] : $args['carousel_navigation'];
			$data['data-enable-pagination'] = ! empty( $params['carousel_pagination'] ) ? $params['carousel_pagination'] : $args['carousel_pagination'];
			
			return $data;
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

EVCImageGallery::getInstance();