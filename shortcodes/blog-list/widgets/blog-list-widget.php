<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCBlogListWidget' ) ) {
	class EVCBlogListWidget extends EVCWidget {
		
		/**
		 * Constructor
		 */
		public function __construct() {
			parent::__construct(
				'evc_blog_list_widget',
				esc_html__( 'EVC Blog List', 'extensive-vc-addon' ),
				array( 'description' => esc_html__( 'Add blog list element to widget areas', 'extensive-vc-addon' ) )
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
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'widget_title',
					'heading'    => esc_html__( 'Widget Title', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc-addon' )
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'type',
					'heading'       => esc_html__( 'Type', 'extensive-vc-addon' ),
					'value'         => array(
						esc_html__( 'Standard', 'extensive-vc-addon' ) => 'standard',
						esc_html__( 'Gallery', 'extensive-vc-addon' )  => 'gallery',
						esc_html__( 'Simple', 'extensive-vc-addon' )   => 'simple',
						esc_html__( 'Minimal', 'extensive-vc-addon' )  => 'minimal'
					),
					'inverse_value' => true
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'layout_collections',
					'heading'       => esc_html__( 'Layout Collections', 'extensive-vc-addon' ),
					'description'   => esc_html__( 'Only for standard layout', 'extensive-vc-addon' ),
					'value'         => array(
						esc_html__( 'Default', 'extensive-vc-addon' )       => '',
						esc_html__( 'Date On Image', 'extensive-vc-addon' ) => 'date-on-image',
						esc_html__( 'Boxed Layout', 'extensive-vc-addon' )  => 'boxed'
					),
					'inverse_value' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'number_of_posts',
					'heading'    => esc_html__( 'Number of Posts', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'number_of_columns',
					'heading'    => esc_html__( 'Number of Columns', 'extensive-vc-addon' ),
					'value'      => extensive_vc_get_number_of_columns_array()
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'space_between_items',
					'heading'    => esc_html__( 'Space Between Items', 'extensive-vc-addon' ),
					'value'      => extensive_vc_get_space_between_items_array()
				),
				array(
					'type'        => 'autocomplete',
					'param_name'  => 'category',
					'heading'     => esc_html__( 'Category', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Enter one category slug or leave empty for showing all categories', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'orderby',
					'heading'    => esc_html__( 'Order By', 'extensive-vc-addon' ),
					'value'      => extensive_vc_get_query_order_by_array()
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'order',
					'heading'    => esc_html__( 'Order', 'extensive-vc-addon' ),
					'value'      => extensive_vc_get_query_order_array()
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'skin',
					'heading'       => esc_html__( 'Skin', 'extensive-vc-addon' ),
					'value'         => array(
						esc_html__( 'Default', 'extensive-vc-addon' ) => '',
						esc_html__( 'Light', 'extensive-vc-addon' )   => 'light'
					),
					'inverse_value' => true
				),
				array(
					'type'          => 'dropdown',
					'param_name'    => 'image_proportions',
					'heading'       => esc_html__( 'Image Proportions', 'extensive-vc-addon' ),
					'description'   => esc_html__( 'Only for standard and gallery layout', 'extensive-vc-addon' ),
					'value'         => array(
						esc_html__( 'Original', 'extensive-vc-addon' )  => 'full',
						esc_html__( 'Large', 'extensive-vc-addon' )     => 'large',
						esc_html__( 'Medium', 'extensive-vc-addon' )    => 'medium',
						esc_html__( 'Thumbnail', 'extensive-vc-addon' ) => 'thumbnail',
					),
					'inverse_value' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_excerpt',
					'heading'     => esc_html__( 'Enable Excerpt', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Only for standard and gallery layout', 'extensive-vc-addon' ),
					'value'       => extensive_vc_get_yes_no_select_array( false, true )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'excerpt_length',
					'heading'     => esc_html__( 'Excerpt Length', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Set number of characters', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_category',
					'heading'     => esc_html__( 'Enable Category', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Only for standard layout', 'extensive-vc-addon' ),
					'value'       => extensive_vc_get_yes_no_select_array( false, true )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_author',
					'heading'     => esc_html__( 'Enable Author', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Only for standard layout', 'extensive-vc-addon' ),
					'value'       => extensive_vc_get_yes_no_select_array( false, true )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_date',
					'heading'    => esc_html__( 'Enable Date', 'extensive-vc-addon' ),
					'value'      => extensive_vc_get_yes_no_select_array( false, true )
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
			
			echo '<div class="widget evc-widget evc-blog-list-widget">';
				if ( ! empty( $instance['widget_title'] ) ) {
					echo wp_kses_post( $args['before_title'] ) . esc_html( $instance['widget_title'] ) . wp_kses_post( $args['after_title'] );
				}
				
				// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Shortcode output is escaped in its template.
				echo extensive_vc_render_shortcode( 'evc_blog_list', $instance ); // XSS OK
			echo '</div>';
		}
	}
}
