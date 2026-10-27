<?php

namespace ExtensiveVC\Shortcodes\EVCTimeline;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCTimeline' ) ) {
	class EVCTimeline extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Stack of timelines which are currently rendered, items read settings from the last one
		 */
		private static $renderStack = array();

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_timeline' );
			$this->setChildBase( 'evc_timeline_item' );
			$this->setShortcodeName( esc_html__( 'Timeline', 'extensive-vc-addon' ) );
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
		 * Get settings of currently rendered timeline
		 *
		 * @return array
		 */
		public static function getCurrentSettings() {
			$last = count( self::$renderStack ) - 1;

			return $last < 0 ? array(
				'title_tag'  => 'h4',
				'image_size' => 'large',
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
					'param_name'  => 'layout',
					'heading'     => esc_html__( 'Layout', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Alternating', 'extensive-vc-addon' )   => 'alternating',
						esc_html__( 'Line On The Left', 'extensive-vc-addon' )  => 'left',
						esc_html__( 'Line On The Right', 'extensive-vc-addon' ) => 'right'
					),
					'description' => esc_html__( 'Alternating layout is displayed with line on the left on mobile devices', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'style',
					'heading'    => esc_html__( 'Style', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Boxed', 'extensive-vc-addon' )  => 'boxed',
						esc_html__( 'Simple', 'extensive-vc-addon' ) => 'simple'
					)
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large, full or any registered image size) or enter image size in pixels: 200x100 (width x height). Default value is large', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_animation',
					'heading'    => esc_html__( 'Enable Appear Animation', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) )
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
					'param_name' => 'line_color',
					'heading'    => esc_html__( 'Line Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'marker_color',
					'heading'    => esc_html__( 'Marker Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'date_color',
					'heading'    => esc_html__( 'Date Color', 'extensive-vc-addon' ),
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
					'param_name' => 'box_bg_color',
					'heading'    => esc_html__( 'Box Background Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'boxed' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'item_spacing',
					'heading'     => esc_html__( 'Space Between Items (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 40', 'extensive-vc-addon' ),
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
				'custom_class'     => '',
				'layout'           => 'alternating',
				'style'            => 'boxed',
				'image_size'       => 'large',
				'enable_animation' => 'yes',
				'title_tag'        => 'h4',
				'line_color'       => '',
				'marker_color'     => '',
				'date_color'       => '',
				'title_color'      => '',
				'text_color'       => '',
				'box_bg_color'     => '',
				'item_spacing'     => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['layout'] = in_array( $params['layout'], array( 'alternating', 'left', 'right' ), true ) ? $params['layout'] : $args['layout'];
			$params['style']  = 'simple' === $params['style'] ? 'simple' : 'boxed';

			self::$renderStack[] = array(
				'title_tag'  => extensive_vc_escape_title_tag( ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'], 'h4' ),
				'image_size' => extensive_vc_get_image_size_value( '' !== trim( $params['image_size'] ) ? $params['image_size'] : $args['image_size'] ),
			);

			$params['items_html'] = do_shortcode( (string) $content );

			array_pop( self::$renderStack );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'timeline', 'templates/timeline', '', $params );

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
			$holderClasses[] = 'evc-tl-layout-' . esc_attr( $params['layout'] );
			$holderClasses[] = 'evc-tl-' . esc_attr( $params['style'] );
			$holderClasses[] = 'yes' === $params['enable_animation'] ? 'evc-tl-has-animation' : '';

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
				'line_color'   => '--evc-tl-line-color',
				'marker_color' => '--evc-tl-marker-color',
				'date_color'   => '--evc-tl-date-color',
				'title_color'  => '--evc-tl-title-color',
				'text_color'   => '--evc-tl-text-color',
				'box_bg_color' => '--evc-tl-box-bg-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['item_spacing'] ) {
				$styles[] = '--evc-tl-item-spacing: ' . absint( $params['item_spacing'] ) . 'px';
			}

			return $styles;
		}
	}
}

EVCTimeline::getInstance();
