<?php

namespace ExtensiveVC\Shortcodes\EVCImageHotspot;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageHotspot' ) ) {
	class EVCImageHotspot extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Stack of image hotspot elements which are currently rendered, items are registered into the last one
		 */
		private static $renderStack = array();

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_hotspot' );
			$this->setChildBase( 'evc_image_hotspot_item' );
			$this->setShortcodeName( esc_html__( 'Image Hotspot', 'extensive-vc-addon' ) );
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
		 * Register item into currently rendered image hotspot element
		 *
		 * @return array - item settings (item number and default tooltip position)
		 */
		public static function registerItem() {
			$last = count( self::$renderStack ) - 1;

			// Item is rendered outside of image hotspot element
			if ( $last < 0 ) {
				return array(
					'index'            => 1,
					'tooltip_position' => 'top',
				);
			}

			$hotspot = &self::$renderStack[ $last ];

			$hotspot['index']++;

			return array(
				'index'            => $hotspot['index'],
				'tooltip_position' => $hotspot['tooltip_position'],
			);
		}

		/**
		 * Get tooltip positions
		 *
		 * @return array
		 */
		public static function getTooltipPositions() {
			return array(
				'top'    => esc_html__( 'Top', 'extensive-vc-addon' ),
				'bottom' => esc_html__( 'Bottom', 'extensive-vc-addon' ),
				'left'   => esc_html__( 'Left', 'extensive-vc-addon' ),
				'right'  => esc_html__( 'Right', 'extensive-vc-addon' ),
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
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'heading'     => esc_html__( 'Image', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Select image from media library. Set position of each hotspot in percentages in hotspot item options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large, full or any registered image size) or enter image size in pixels: 200x100 (width x height). Leave empty to use original image size', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'image', 'not_empty' => true )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'trigger',
					'heading'    => esc_html__( 'Show Tooltip On', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Hover', 'extensive-vc-addon' ) => 'hover',
						esc_html__( 'Click', 'extensive-vc-addon' ) => 'click'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'tooltip_position',
					'heading'    => esc_html__( 'Tooltip Position', 'extensive-vc-addon' ),
					'value'      => array_flip( self::getTooltipPositions() )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_pulse',
					'heading'    => esc_html__( 'Enable Pulse Animation', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'marker_size',
					'heading'     => esc_html__( 'Marker Size (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 32', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'marker_color',
					'heading'    => esc_html__( 'Marker Icon Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'marker_bg_color',
					'heading'    => esc_html__( 'Marker Background Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'tooltip_width',
					'heading'     => esc_html__( 'Tooltip Width (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 240', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'tooltip_bg_color',
					'heading'    => esc_html__( 'Tooltip Background Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'tooltip_title_color',
					'heading'    => esc_html__( 'Tooltip Title Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'tooltip_text_color',
					'heading'    => esc_html__( 'Tooltip Text Color', 'extensive-vc-addon' ),
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
				'custom_class'        => '',
				'image'               => '',
				'image_size'          => 'full',
				'trigger'             => 'hover',
				'tooltip_position'    => 'top',
				'enable_pulse'        => 'yes',
				'marker_size'         => '',
				'marker_color'        => '',
				'marker_bg_color'     => '',
				'tooltip_width'       => '',
				'tooltip_bg_color'    => '',
				'tooltip_title_color' => '',
				'tooltip_text_color'  => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['trigger']          = 'click' === $params['trigger'] ? 'click' : 'hover';
			$params['tooltip_position'] = array_key_exists( $params['tooltip_position'], self::getTooltipPositions() ) ? $params['tooltip_position'] : $args['tooltip_position'];
			$params['image_size']       = extensive_vc_get_image_size_value( $params['image_size'] );

			self::$renderStack[] = array(
				'index'            => 0,
				'tooltip_position' => $params['tooltip_position'],
			);

			$params['items_html'] = do_shortcode( (string) $content );

			array_pop( self::$renderStack );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-hotspot', 'templates/image-hotspot', '', $params );

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
			$holderClasses[] = 'evc-ih-trigger-' . esc_attr( $params['trigger'] );
			$holderClasses[] = 'yes' === $params['enable_pulse'] ? 'evc-ih-pulse' : '';

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
				'marker_color'        => '--evc-ih-marker-color',
				'marker_bg_color'     => '--evc-ih-marker-bg-color',
				'tooltip_bg_color'    => '--evc-ih-tooltip-bg-color',
				'tooltip_title_color' => '--evc-ih-tooltip-title-color',
				'tooltip_text_color'  => '--evc-ih-tooltip-text-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['marker_size'] ) {
				$styles[] = '--evc-ih-marker-size: ' . absint( $params['marker_size'] ) . 'px';
			}

			if ( '' !== $params['tooltip_width'] ) {
				$styles[] = '--evc-ih-tooltip-width: ' . absint( $params['tooltip_width'] ) . 'px';
			}

			return $styles;
		}
	}
}

EVCImageHotspot::getInstance();
