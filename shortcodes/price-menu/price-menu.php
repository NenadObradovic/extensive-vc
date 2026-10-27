<?php

namespace ExtensiveVC\Shortcodes\EVCPriceMenu;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPriceMenu' ) ) {
	class EVCPriceMenu extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Stack of price menus which are currently rendered, items read settings from the last one
		 */
		private static $renderStack = array();

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_price_menu' );
			$this->setChildBase( 'evc_price_menu_item' );
			$this->setShortcodeName( esc_html__( 'Price Menu', 'extensive-vc-addon' ) );
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
		 * Get settings of currently rendered price menu
		 *
		 * @return array
		 */
		public static function getCurrentSettings() {
			$last = count( self::$renderStack ) - 1;

			return $last < 0 ? array(
				'title_tag'  => 'h5',
				'image_size' => 'thumbnail',
			) : self::$renderStack[ $last ];
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
					'param_name'  => 'style',
					'heading'     => esc_html__( 'Style', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Dotted Line Between Title And Price', 'extensive-vc-addon' ) => 'dotted',
						esc_html__( 'Line Between Items', 'extensive-vc-addon' )                => 'line',
						esc_html__( 'Simple', 'extensive-vc-addon' )                            => 'simple'
					),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large, full or any registered image size) or enter image size in pixels: 200x100 (width x height). Default value is thumbnail', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'image_shape',
					'heading'    => esc_html__( 'Image Shape', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Square', 'extensive-vc-addon' )  => 'square',
						esc_html__( 'Rounded', 'extensive-vc-addon' ) => 'rounded',
						esc_html__( 'Circle', 'extensive-vc-addon' )  => 'circle'
					)
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_width',
					'heading'     => esc_html__( 'Image Width (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 80', 'extensive-vc-addon' )
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
					'param_name' => 'price_color',
					'heading'    => esc_html__( 'Price Color', 'extensive-vc-addon' ),
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
					'param_name' => 'line_color',
					'heading'    => esc_html__( 'Line Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'dotted', 'line' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'badge_color',
					'heading'    => esc_html__( 'Badge Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'badge_bg_color',
					'heading'    => esc_html__( 'Badge Background Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'item_spacing',
					'heading'     => esc_html__( 'Space Between Items (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 25', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
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
				'custom_class'   => '',
				'style'          => 'dotted',
				'image_size'     => 'thumbnail',
				'image_shape'    => 'square',
				'image_width'    => '',
				'title_tag'      => 'h5',
				'title_color'    => '',
				'price_color'    => '',
				'text_color'     => '',
				'line_color'     => '',
				'badge_color'    => '',
				'badge_bg_color' => '',
				'item_spacing'   => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['style']       = in_array( $params['style'], array( 'dotted', 'line', 'simple' ), true ) ? $params['style'] : $args['style'];
			$params['image_shape'] = in_array( $params['image_shape'], array( 'square', 'rounded', 'circle' ), true ) ? $params['image_shape'] : $args['image_shape'];

			self::$renderStack[] = array(
				'title_tag'  => extensive_vc_escape_title_tag( ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'], 'h5' ),
				'image_size' => extensive_vc_get_image_size_value( '' !== trim( $params['image_size'] ) ? $params['image_size'] : $args['image_size'] ),
			);

			$params['items_html'] = do_shortcode( (string) $content );

			array_pop( self::$renderStack );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'price-menu', 'templates/price-menu', '', $params );

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
			$holderClasses[] = 'evc-pm-style-' . esc_attr( $params['style'] );
			$holderClasses[] = 'evc-pm-image-' . esc_attr( $params['image_shape'] );

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
				'title_color'    => '--evc-pm-title-color',
				'price_color'    => '--evc-pm-price-color',
				'text_color'     => '--evc-pm-text-color',
				'line_color'     => '--evc-pm-line-color',
				'badge_color'    => '--evc-pm-badge-color',
				'badge_bg_color' => '--evc-pm-badge-bg-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['image_width'] ) {
				$styles[] = '--evc-pm-image-width: ' . absint( $params['image_width'] ) . 'px';
			}

			if ( '' !== $params['item_spacing'] ) {
				$styles[] = '--evc-pm-item-spacing: ' . absint( $params['item_spacing'] ) . 'px';
			}

			return $styles;
		}
	}
}

EVCPriceMenu::getInstance();
