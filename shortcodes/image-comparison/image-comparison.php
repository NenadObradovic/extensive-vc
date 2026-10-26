<?php

namespace ExtensiveVC\Shortcodes\EVCImageComparison;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageComparison' ) ) {
	class EVCImageComparison extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_comparison' );
			$this->setShortcodeName( esc_html__( 'Image Comparison', 'extensive-vc-addon' ) );
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
					'type'        => 'attach_image',
					'param_name'  => 'before_image',
					'heading'     => esc_html__( 'Before Image', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Before and after images should have the same dimensions', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'attach_image',
					'param_name' => 'after_image',
					'heading'    => esc_html__( 'After Image', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large, full or any registered image size) or enter image size in pixels: 200x100 (width x height). Leave empty to use original image size', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'orientation',
					'heading'     => esc_html__( 'Orientation', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Horizontal', 'extensive-vc-addon' ) => 'horizontal',
						esc_html__( 'Vertical', 'extensive-vc-addon' )   => 'vertical'
					),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'start_position',
					'heading'     => esc_html__( 'Start Position (%)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 50', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'move_on_hover',
					'heading'     => esc_html__( 'Move Slider On Mouse Hover', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'description' => esc_html__( 'By default slider is moved by dragging', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'before_label',
					'heading'    => esc_html__( 'Before Label', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Labels Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'after_label',
					'heading'    => esc_html__( 'After Label', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Labels Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'labels_visibility',
					'heading'    => esc_html__( 'Labels Visibility', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Always', 'extensive-vc-addon' )   => 'always',
						esc_html__( 'On Hover', 'extensive-vc-addon' ) => 'hover'
					),
					'group'      => esc_html__( 'Labels Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'handle_color',
					'heading'    => esc_html__( 'Handle Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'labels_bg_color',
					'heading'    => esc_html__( 'Labels Background Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'labels_color',
					'heading'    => esc_html__( 'Labels Color', 'extensive-vc-addon' ),
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
				'custom_class'      => '',
				'before_image'      => '',
				'after_image'       => '',
				'image_size'        => 'full',
				'orientation'       => 'horizontal',
				'start_position'    => '50',
				'move_on_hover'     => 'no',
				'before_label'      => '',
				'after_label'       => '',
				'labels_visibility' => 'always',
				'handle_color'      => '',
				'labels_bg_color'   => '',
				'labels_color'      => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			if ( empty( $params['before_image'] ) || empty( $params['after_image'] ) ) {
				return '';
			}

			$params['orientation']    = 'vertical' === $params['orientation'] ? 'vertical' : 'horizontal';
			$params['start_position'] = '' !== $params['start_position'] ? min( 100, max( 0, floatval( $params['start_position'] ) ) ) : 50;
			$params['image_size']     = extensive_vc_get_image_size_value( $params['image_size'] );
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-comparison', 'templates/image-comparison', '', $params );

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
			$holderClasses[] = 'evc-ic-' . esc_attr( $params['orientation'] );
			$holderClasses[] = 'hover' === $params['labels_visibility'] ? 'evc-ic-labels-on-hover' : '';
			$holderClasses[] = 'yes' === $params['move_on_hover'] ? 'evc-ic-move-on-hover' : '';

			return implode( ' ', $holderClasses );
		}

		/**
		 * Get shortcode holder styles, position and colors are set through css variables
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderStyles( $params ) {
			$styles    = array( '--evc-ic-position: ' . $params['start_position'] . '%' );
			$variables = array(
				'handle_color'    => '--evc-ic-handle-color',
				'labels_bg_color' => '--evc-ic-labels-bg-color',
				'labels_color'    => '--evc-ic-labels-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			return $styles;
		}
	}
}

EVCImageComparison::getInstance();
