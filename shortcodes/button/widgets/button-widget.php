<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCButtonWidget' ) ) {
	class EVCButtonWidget extends EVCWidget {
		
		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				'evc_button_widget',
				esc_html__( 'EVC Button', 'extensive-vc' ),
				array( 'description' => esc_html__( 'Add button element to widget areas', 'extensive-vc' ) )
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
					'type'          => 'dropdown',
					'param_name'    => 'type',
					'heading'       => esc_html__( 'Type', 'extensive-vc' ),
					'value'         => array(
						esc_html__( 'Solid', 'extensive-vc' )                         => 'solid',
						esc_html__( 'Outline', 'extensive-vc' )                       => 'outline',
						esc_html__( 'Simple', 'extensive-vc' )                        => 'simple',
						esc_html__( 'Simple Fill Line On Hover', 'extensive-vc' )     => 'fill-line',
						esc_html__( 'Simple Fill Text On Hover', 'extensive-vc' )     => 'fill-text',
						esc_html__( 'Simple Strike Line On Hover', 'extensive-vc' )   => 'strike-line',
						esc_html__( 'Simple Strike Line On Hover 2', 'extensive-vc' ) => 'strike-line-2',
						esc_html__( 'Simple Switch Line On Hover', 'extensive-vc' )   => 'switch-line'
					),
					'inverse_value' => true
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'size',
					'heading'       => esc_html__( 'Size', 'extensive-vc' ),
					'description'   => esc_html__( 'Only for solid and outline button types', 'extensive-vc' ),
					'value'         => array(
						esc_html__( 'Large', 'extensive-vc' )  => 'large',
						esc_html__( 'Medium', 'extensive-vc' ) => 'medium',
						esc_html__( 'Normal', 'extensive-vc' ) => 'normal',
						esc_html__( 'Small', 'extensive-vc' )  => 'small',
						esc_html__( 'Tiny', 'extensive-vc' )   => 'tiny'
					),
					'inverse_value' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'custom_link_target',
					'heading'    => esc_html__( 'Custom Link Target', 'extensive-vc' ),
					'value'      => extensive_vc_get_link_target_array()
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
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'hover_color',
					'heading'    => esc_html__( 'Hover Color', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'bg_color',
					'heading'     => esc_html__( 'Background Color', 'extensive-vc' ),
					'description' => esc_html__( 'Only for solid and outline button types', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'hover_bg_color',
					'heading'     => esc_html__( 'Hover Background Color', 'extensive-vc' ),
					'description' => esc_html__( 'Only for solid and outline button types', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'border_color',
					'heading'     => esc_html__( 'Border Color', 'extensive-vc' ),
					'description' => esc_html__( 'Only for solid and outline button types', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'hover_border_color',
					'heading'     => esc_html__( 'Hover Border Color', 'extensive-vc' ),
					'description' => esc_html__( 'Only for solid and outline button types', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'border_width',
					'heading'     => esc_html__( 'Border Width (px)', 'extensive-vc' ),
					'description' => esc_html__( 'Only for solid and outline button types', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'line_color',
					'heading'     => esc_html__( 'Line Color', 'extensive-vc' ),
					'description' => esc_html__( 'Only for fill line, strike line and switch line button types', 'extensive-vc' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'switch_line_color',
					'heading'     => esc_html__( 'Switch Line Color', 'extensive-vc' ),
					'description' => esc_html__( 'Only for switch line button type', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'margin',
					'heading'     => esc_html__( 'Margin', 'extensive-vc' ),
					'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc' )
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'button_alignment',
					'heading'       => esc_html__( 'Button Alignment', 'extensive-vc' ),
					'value'         => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Left', 'extensive-vc' )    => 'left',
						esc_html__( 'Right', 'extensive-vc' )   => 'right',
						esc_html__( 'Center', 'extensive-vc' )  => 'center'
					),
					'inverse_value' => true
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
			
			if ( isset( $instance['custom_link'] ) && ! empty( $instance['custom_link'] ) ) {
				if ( isset( $instance['custom_link_target'] ) && ! empty( $instance['custom_link_target'] ) ) {
					$instance['custom_link'] = 'url:' . urlencode( esc_url( $instance['custom_link'] ) ) . '|target:' . esc_attr( $instance['custom_link_target'] );
				} else {
					$instance['custom_link'] = 'url:' . urlencode( esc_url( $instance['custom_link'] ) );
				}
			}
			
			echo '<div class="widget evc-widget evc-button-widget">';
				if ( ! empty( $instance['widget_title'] ) ) {
					echo wp_kses_post( $args['before_title'] ) . esc_html( $instance['widget_title'] ) . wp_kses_post( $args['after_title'] );
				}
				
				echo extensive_vc_render_shortcode( 'evc_button', $instance ); // XSS OK
			echo '</div>';
		}
	}
}
