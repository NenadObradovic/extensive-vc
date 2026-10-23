<?php

namespace ExtensiveVC\Shortcodes\EVCClients;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCClients' ) ) {
	class EVCClients extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_clients' );
			$this->setShortcodeName( esc_html__( 'Clients', 'extensive-vc-addon' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'isInCPT' => true ) );
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				
				// Category filter
				add_filter( 'vc_autocomplete_evc_clients_category_callback', array( $this, 'clientsCategoryAutocompleteSuggester' ), 10, 1 ); // Get suggestion(find). Must return an array
				
				// Category render
				add_filter( 'vc_autocomplete_evc_clients_category_render', array( $this, 'clientsCategoryAutocompleteRender' ), 10, 1 ); // Get suggestion(find). Must return an array
			}
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
					'type'        => 'dropdown',
					'param_name'  => 'type',
					'heading'     => esc_html__( 'Type', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Slider', 'extensive-vc-addon' )  => 'slider',
						esc_html__( 'Gallery', 'extensive-vc-addon' ) => 'gallery'
					),
					'save_always' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'number',
					'heading'     => esc_html__( 'Number of Clients', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Enter number of clients or leave empty for showing all clients', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'autocomplete',
					'param_name'  => 'category',
					'heading'     => esc_html__( 'Category', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Enter one category slug or leave empty for showing all categories', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'orderby',
					'heading'     => esc_html__( 'Order By', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_query_order_by_array() )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'order',
					'heading'     => esc_html__( 'Order', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_query_order_array() )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'custom_link_target',
					'heading'    => esc_html__( 'Custom Link Target', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_link_target_array() )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'items_hover_animation',
					'heading'     => esc_html__( 'Items Hover Animation', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Switch Images', 'extensive-vc-addon' ) => 'switch-images',
						esc_html__( 'Roll Over', 'extensive-vc-addon' )     => 'roll-over'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_title',
					'heading'    => esc_html__( 'Enable Title Text', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'dependency' => array( 'element' => 'enable_title', 'value' => array( 'yes') ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'enable_title', 'value' => array( 'yes') ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title_top_margin',
					'heading'    => esc_html__( 'Title Top Margin (px)', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'enable_title', 'value' => array( 'yes') ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title_bottom_margin',
					'heading'    => esc_html__( 'Title Bottom Margin (px)', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'enable_title', 'value' => array( 'yes') ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'number_of_columns',
					'heading'    => esc_html__( 'Number of Columns', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_number_of_columns_array() ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'gallery' ) ),
					'group'      => esc_html__( 'Gallery Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'space_between_items',
					'heading'    => esc_html__( 'Space Between Items', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_space_between_items_array() ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'gallery' ) ),
					'group'      => esc_html__( 'Gallery Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'number_of_visible_items',
					'heading'    => esc_html__( 'Number of Visible Items', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'One', 'extensive-vc-addon' )   => '1',
						esc_html__( 'Two', 'extensive-vc-addon' )   => '2',
						esc_html__( 'Three', 'extensive-vc-addon' ) => '3',
						esc_html__( 'Four', 'extensive-vc-addon' )  => '4',
						esc_html__( 'Five', 'extensive-vc-addon' )  => '5',
						esc_html__( 'Six', 'extensive-vc-addon' )   => '6'
					),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_loop',
					'heading'    => esc_html__( 'Enable Slider Loop', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay',
					'heading'    => esc_html__( 'Enable Slider Autoplay', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay_pause',
					'heading'    => esc_html__( 'Enable Slider Autoplay Hover Pause', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed',
					'heading'     => esc_html__( 'Slide Duration (ms)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Speed of slide in milliseconds. Default value is 5000', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed_animation',
					'heading'     => esc_html__( 'Slide Animation Duration (ms)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 600', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_margin',
					'heading'     => esc_html__( 'Slide Margin (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Define right margin for slide items. Default value is 0', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_navigation',
					'heading'    => esc_html__( 'Enable Slider Navigation', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_pagination',
					'heading'    => esc_html__( 'Enable Slider Pagination', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'carousel_navigation_skin',
					'heading'     => esc_html__( 'Slider Navigation Skin', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc-addon' ) => '',
						esc_html__( 'Light', 'extensive-vc-addon' )   => 'light'
					),
					'dependency' => array( 'element' => 'type', 'value' => array( 'slider' ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				)
			);
			
			return $params;
		}
		
		/**
		 * Renders shortcodes HTML
		 *
		 * @param $atts array - shortcode params
		 * @param $content string - shortcode content
		 *
		 * @return html
		 */
		function render( $atts, $content = null ) {
			$args   = array(
				'custom_class'             => '',
				'type'                     => 'slider',
				'number'                   => '-1',
				'category'                 => '',
				'orderby'                  => 'date',
				'order'                    => 'ASC',
				'custom_link_target'       => '_self',
				'items_hover_animation'    => 'switch-images',
				'enable_title'             => 'no',
				'title_tag'                => 'h4',
				'title_color'              => '',
				'title_top_margin'         => '',
				'title_bottom_margin'      => '',
				'number_of_columns'        => 'three',
				'space_between_items'      => 'normal',
				'number_of_visible_items'  => '4',
				'carousel_loop'            => 'yes',
				'carousel_autoplay'        => 'yes',
				'carousel_autoplay_pause'  => 'no',
				'carousel_speed'           => '5000',
				'carousel_speed_animation' => '600',
				'carousel_margin'          => '',
				'carousel_navigation'      => 'yes',
				'carousel_pagination'      => 'yes',
				'carousel_navigation_skin' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['query_results']  = new \WP_Query( extensive_vc_get_shortcode_query_params( $params, 'clients' ) );
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['slider_data']    = $this->getSliderData( $params, $args );
			
			$params['type']               = ! empty( $params['type'] ) ? $params['type'] : $args['type'];
			$params['title_tag']          = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles']       = $this->getTitleStyles( $params );
			$params['custom_link_target'] = ! empty( $params['custom_link_target'] ) ? $params['custom_link_target'] : $args['custom_link_target'];
			
			$html = extensive_vc_get_module_template_part( 'cpt', 'clients', 'templates/clients-holder', $params['type'], $params );
			
			return $html;
		}
		
		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters value
		 * @param $args array - default shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderClasses( $params, $args ) {
			$holderClasses = array();
			
			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			$holderClasses[] = ! empty( $params['items_hover_animation'] ) ? 'evc-c-' . esc_attr( $params['items_hover_animation'] ) : 'evc-c-' . esc_attr( $args['items_hover_animation'] );
			$holderClasses[] = ! empty( $params['number_of_columns'] ) ? 'evc-' . $params['number_of_columns'] . '-columns' : 'evc-' . $args['number_of_columns'] . '-columns';
			$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'evc-' . $params['space_between_items'] . '-space' : 'evc-' . $args['space_between_items'] . '-space';
			$holderClasses[] = ! empty( $params['carousel_navigation_skin'] ) ? 'evc-carousel-skin-' . esc_attr( $params['carousel_navigation_skin'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode slider data
		 *
		 * @param $params array - shortcode parameters value
		 * @param $args array - default shortcode parameters value
		 *
		 * @return array
		 */
		private function getSliderData( $params, $args ) {
			$data = array();
			
			$data['data-number-of-items']             = ! empty( $params['number_of_visible_items'] ) ? $params['number_of_visible_items'] : $args['number_of_visible_items'];
			$data['data-enable-loop']                 = ! empty( $params['carousel_loop'] ) ? $params['carousel_loop'] : $args['carousel_loop'];
			$data['data-enable-autoplay']             = ! empty( $params['carousel_autoplay'] ) ? $params['carousel_autoplay'] : $args['carousel_autoplay'];
			$data['data-enable-autoplay-hover-pause'] = ! empty( $params['carousel_autoplay_pause'] ) ? $params['carousel_autoplay_pause'] : $args['carousel_autoplay_pause'];
			$data['data-carousel-speed']              = ! empty( $params['carousel_speed'] ) ? $params['carousel_speed'] : $args['carousel_speed'];
			$data['data-carousel-speed-animation']    = ! empty( $params['carousel_speed_animation'] ) ? $params['carousel_speed_animation'] : $args['carousel_speed_animation'];
			$data['data-carousel-margin']             = ! empty( $params['carousel_margin'] ) ? $params['carousel_margin'] : $args['carousel_margin'];
			$data['data-enable-navigation']           = ! empty( $params['carousel_navigation'] ) ? $params['carousel_navigation'] : $args['carousel_navigation'];
			$data['data-enable-pagination']           = ! empty( $params['carousel_pagination'] ) ? $params['carousel_pagination'] : $args['carousel_pagination'];
			
			return $data;
		}
		
		/**
		 * Get title styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getTitleStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['title_color'] ) ) {
				$styles[] = 'color: ' . $params['title_color'];
			}
			
			if ( $params['title_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . intval( $params['title_top_margin'] ) . 'px';
			}
			
			if ( $params['title_bottom_margin'] !== '' ) {
				$styles[] = 'margin-bottom: ' . intval( $params['title_bottom_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Filter shortcode categories
		 *
		 * @param $query
		 *
		 * @return array
		 */
		function clientsCategoryAutocompleteSuggester( $query ) {
			return extensive_vc_get_taxonomy_autocomplete_suggestions( $query, 'clients-category' );
		}
		
		/**
		 * Find shortcode category by slug
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return boolean|array
		 */
		function clientsCategoryAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			
			if ( ! empty( $query ) ) {
				// get portfolio category
				$clients_category = get_term_by( 'slug', $query, 'clients-category' );
				
				if ( is_object( $clients_category ) ) {
					$clients_category_slug  = $clients_category->slug;
					$clients_category_title = $clients_category->name;
					
					$clients_category_title_display = '';
					
					if ( ! empty( $clients_category_title ) ) {
						$clients_category_title_display = esc_html__( 'Category', 'extensive-vc-addon' ) . ': ' . $clients_category_title;
					}
					
					$data          = array();
					$data['value'] = $clients_category_slug;
					$data['label'] = $clients_category_title_display;
					
					return ! empty( $data ) ? $data : false;
				}
				
				return false;
			}
			
			return false;
		}
	}
}

EVCClients::getInstance();