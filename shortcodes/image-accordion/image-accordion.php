<?php

namespace ExtensiveVC\Shortcodes\EVCImageAccordion;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageAccordion' ) ) {
	class EVCImageAccordion extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Stack of image accordions which are currently rendered, items are registered into the last one
		 */
		private static $renderStack = array();

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_accordion' );
			$this->setChildBase( 'evc_image_accordion_item' );
			$this->setShortcodeName( esc_html__( 'Image Accordion', 'extensive-vc-addon' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );

			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasChild' => true ) );
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
		 * Register item into currently rendered image accordion
		 *
		 * @return array - item settings (active state, title tag and image size)
		 */
		public static function registerItem() {
			$last = count( self::$renderStack ) - 1;

			// Item is rendered outside of image accordion element
			if ( $last < 0 ) {
				return array(
					'active'     => false,
					'title_tag'  => 'h4',
					'image_size' => 'large',
				);
			}

			$accordion = &self::$renderStack[ $last ];

			$accordion['index']++;

			return array(
				'active'     => $accordion['index'] === $accordion['active_item'],
				'title_tag'  => $accordion['title_tag'],
				'image_size' => $accordion['image_size'],
			);
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
					'param_name'  => 'orientation',
					'heading'     => esc_html__( 'Orientation', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Horizontal', 'extensive-vc-addon' ) => 'horizontal',
						esc_html__( 'Vertical', 'extensive-vc-addon' )   => 'vertical'
					),
					'description' => esc_html__( 'Horizontal accordion is displayed vertically on mobile devices', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'behavior',
					'heading'    => esc_html__( 'Open Item On', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Hover', 'extensive-vc-addon' ) => 'hover',
						esc_html__( 'Click', 'extensive-vc-addon' ) => 'click'
					)
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'active_item',
					'heading'     => esc_html__( 'Opened Item', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Number of item which is opened by default. Default value is 1, set 0 to keep all items closed', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'height',
					'heading'     => esc_html__( 'Height (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Height of the element for horizontal orientation or height of opened item for vertical orientation. Default value is 450', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large, full or any registered image size) or enter image size in pixels: 200x100 (width x height). Default value is large', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'space_between_items',
					'heading'     => esc_html__( 'Space Between Items (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 6', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'content_alignment',
					'heading'    => esc_html__( 'Content Alignment', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Bottom Left', 'extensive-vc-addon' ) => 'bottom-left',
						esc_html__( 'Center', 'extensive-vc-addon' )      => 'center'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'text_color',
					'heading'    => esc_html__( 'Text Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'overlay_color',
					'heading'    => esc_html__( 'Overlay Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'active_overlay_color',
					'heading'    => esc_html__( 'Opened Item Overlay Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
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
				'custom_class'         => '',
				'orientation'          => 'horizontal',
				'behavior'             => 'hover',
				'active_item'          => '1',
				'height'               => '',
				'image_size'           => 'large',
				'space_between_items'  => '',
				'content_alignment'    => 'bottom-left',
				'title_tag'            => 'h4',
				'title_color'          => '',
				'text_color'           => '',
				'overlay_color'        => '',
				'active_overlay_color' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['orientation']       = 'vertical' === $params['orientation'] ? 'vertical' : 'horizontal';
			$params['behavior']          = 'click' === $params['behavior'] ? 'click' : 'hover';
			$params['content_alignment'] = 'center' === $params['content_alignment'] ? 'center' : 'bottom-left';

			self::$renderStack[] = array(
				'index'       => 0,
				'active_item' => '' !== trim( $params['active_item'] ) ? absint( $params['active_item'] ) : 1,
				'title_tag'   => extensive_vc_escape_title_tag( ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'], 'h4' ),
				'image_size'  => extensive_vc_get_image_size_value( '' !== trim( $params['image_size'] ) ? $params['image_size'] : $args['image_size'] ),
			);

			$params['items_html'] = do_shortcode( (string) $content );

			array_pop( self::$renderStack );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-accordion', 'templates/image-accordion', '', $params );

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
			$holderClasses[] = 'evc-ia-' . esc_attr( $params['orientation'] );
			$holderClasses[] = 'evc-ia-open-on-' . esc_attr( $params['behavior'] );
			$holderClasses[] = 'evc-ia-content-' . esc_attr( $params['content_alignment'] );

			return implode( ' ', $holderClasses );
		}

		/**
		 * Get shortcode holder styles, colors are set through css variables
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderStyles( $params ) {
			$styles    = array();
			$variables = array(
				'title_color'          => '--evc-ia-title-color',
				'text_color'           => '--evc-ia-text-color',
				'overlay_color'        => '--evc-ia-overlay-color',
				'active_overlay_color' => '--evc-ia-active-overlay-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['height'] ) {
				$styles[] = '--evc-ia-height: ' . absint( $params['height'] ) . 'px';
			}

			if ( '' !== $params['space_between_items'] ) {
				$styles[] = '--evc-ia-gap: ' . absint( $params['space_between_items'] ) . 'px';
			}

			return $styles;
		}
	}
}

EVCImageAccordion::getInstance();
