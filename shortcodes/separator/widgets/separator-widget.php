<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCSeparatorWidget' ) ) {
	class EVCSeparatorWidget extends EVCWidget {
		
		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				'evc_separator_widget',
				esc_html__( 'EVC Separator', 'extensive-vc' ),
				array( 'description' => esc_html__( 'Add separator element to widget areas', 'extensive-vc' ) )
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
					'type'          => 'dropdown',
					'param_name'    => 'position',
					'heading'       => esc_html__( 'Position', 'extensive-vc' ),
					'value'         => array(
						esc_html__( 'Center', 'extensive-vc' ) => 'center',
						esc_html__( 'Left', 'extensive-vc' )   => 'left',
						esc_html__( 'Right', 'extensive-vc' )  => 'right'
					),
					'inverse_value' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'width',
					'heading'    => esc_html__( 'Width (px or %)', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'thickness',
					'heading'    => esc_html__( 'Thickness (px)', 'extensive-vc' )
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'style',
					'heading'       => esc_html__( 'Style', 'extensive-vc' ),
					'value'         => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Dashed', 'extensive-vc' )  => 'dashed',
						esc_html__( 'Solid', 'extensive-vc' )   => 'solid',
						esc_html__( 'Dotted', 'extensive-vc' )  => 'dotted'
					),
					'inverse_value' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'top_margin',
					'heading'    => esc_html__( 'Top Margin (px or %)', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'bottom_margin',
					'heading'    => esc_html__( 'Bottom Margin (px or %)', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' )
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
			
			echo '<div class="widget evc-widget evc-separator-widget">';
				echo extensive_vc_render_shortcode( 'evc_separator', $instance ); // XSS OK
			echo '</div>';
		}
	}
}
