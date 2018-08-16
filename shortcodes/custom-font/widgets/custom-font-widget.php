<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCCustomFontWidget' ) ) {
	class EVCCustomFontWidget extends EVCWidget {
		
		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				'evc_custom_font_widget',
				esc_html__( 'EVC Custom Font', 'extensive-vc' ),
				array( 'description' => esc_html__( 'Add custom font element to widget areas', 'extensive-vc' ) )
			);
			
			$this->setWidgetParameters();
		}
		
		/**
		 * Set widget parameters
		 */
		protected function setWidgetParameters() {
			$this->params = array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'widget_title',
					'heading'    => esc_html__( 'Widget Title', 'extensive-vc' )
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
					'value'      => extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'margin',
					'heading'     => esc_html__( 'Margin (px or %)', 'extensive-vc' ),
					'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'font_family',
					'heading'    => esc_html__( 'Font Family', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'font_size',
					'heading'    => esc_html__( 'Font Size (px or em)', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'line_height',
					'heading'    => esc_html__( 'Line Height (px or em)', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'font_weight',
					'heading'    => esc_html__( 'Font Weight', 'extensive-vc' ),
					'value'      => extensive_vc_get_font_weight_array( true )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'font_style',
					'heading'    => esc_html__( 'Font Style', 'extensive-vc' ),
					'value'      => extensive_vc_get_font_style_array( true )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'letter_spacing',
					'heading'    => esc_html__( 'Letter Spacing (px or em)', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'text_transform',
					'heading'    => esc_html__( 'Text Transform', 'extensive-vc' ),
					'value'      => extensive_vc_get_text_transform_array( true )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'text_decoration',
					'heading'    => esc_html__( 'Text Decoration', 'extensive-vc' ),
					'value'      => extensive_vc_get_text_decorations_array( true )
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'text_align',
					'heading'       => esc_html__( 'Text Align', 'extensive-vc' ),
					'value'         => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Left', 'extensive-vc' )    => 'left',
						esc_html__( 'Center', 'extensive-vc' )  => 'center',
						esc_html__( 'Right', 'extensive-vc' )   => 'right',
						esc_html__( 'Justify', 'extensive-vc' ) => 'justify'
					),
					'inverse_value' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1440',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1440px screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1440',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for 1440px screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1366',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1366px screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1366',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for 1366px screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1280',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1280px screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1280',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for 1280px screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1024',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for tablet landscape screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1024',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for tablet landscape screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_768',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for tablet portrait screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_768',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for tablet portrait screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_680',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for mobiles screen size', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_680',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for mobiles screen size', 'extensive-vc' )
				)
			);
		}
		
		/**
		 * Generates widget's HTML
		 *
		 * @param $args array - args from widget area
		 * @param $instance array - widget's options
		 */
		public function widget( $args, $instance ) {
			if ( ! is_array( $instance ) ) {
				$instance = array();
			}
			
			// Filter out all empty params
			$instance = array_filter( $instance, function ( $array_value ) {
				return trim( $array_value ) !== '';
			} );
			
			echo '<div class="widget evc-widget evc-custom-font-widget">';
				if ( ! empty( $instance['widget_title'] ) ) {
					echo wp_kses_post( $args['before_title'] ) . esc_html( $instance['widget_title'] ) . wp_kses_post( $args['after_title'] );
				}
				
				echo extensive_vc_render_shortcode( 'evc_custom_font', $instance ); // XSS OK
			echo '</div>';
		}
	}
}
