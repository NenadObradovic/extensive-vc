<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCContactForm7Widget' ) ) {
	class EVCContactForm7Widget extends EVCWidget {
		
		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				'evc_contact_form_7_widget',
				esc_html__( 'EVC Contact Form 7', 'extensive-vc' ),
				array( 'description' => esc_html__( 'Add contact form 7 element to widget areas', 'extensive-vc' ) )
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
					'type'       => 'dropdown',
					'param_name' => 'contact_forms_7',
					'heading'    => esc_html__( 'Select Contact Form 7', 'extensive-vc' ),
					'value'      => extensive_vc_get_contact_forms_7_array()
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
			
			echo '<div class="widget evc-widget evc-contact-form-7-widget">';
				if ( ! empty( $instance['widget_title'] ) ) {
					echo wp_kses_post( $args['before_title'] ) . esc_html( $instance['widget_title'] ) . wp_kses_post( $args['after_title'] );
				}
			
				if ( ! empty( $instance['contact_forms_7'] ) ) {
					echo do_shortcode( '[contact-form-7 id="' . esc_attr( $instance['contact_forms_7'] ) . '"]' );
				}
			echo '</div>';
		}
	}
}
