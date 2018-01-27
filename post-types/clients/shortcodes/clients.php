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
			$this->setShortcodeName( esc_html__( 'Clients', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'isInCPT' => true ) );
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				add_action( 'extensive_vc_enqueue_additional_scripts_before_main_js', array( $this, 'enqueueShortcodeAdditionalScripts' ) );
				
				//Clients category filter
				add_filter( 'vc_autocomplete_evc_clients_category_callback', array( $this, 'clientsCategoryAutocompleteSuggester' ), 10, 1 ); // Get suggestion(find). Must return an array
				
				//Clients category render
				add_filter( 'vc_autocomplete_evc_clients_category_render', array( $this, 'clientsCategoryAutocompleteRender' ), 10, 1 ); // Get suggestion(find). Must return an array
			}
		}
		
		/**
		 * Get the instance of ExtensiveVCFramework
		 *
		 * @return self
		 */
		public static function getInstance() {
			if ( self::$instance == null ) {
				return new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Include necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_style( 'owl-carousel', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/owl-carousel/owl.carousel.min.css' );
			wp_enqueue_script( 'owl-carousel', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/owl-carousel/owl.carousel.min.js', array( 'jquery' ), false, true );
		}
		
		/**
		 * Set shortcode parameters for Visual Composer shortcodes options panel
		 */
		function shortcodeParameters() {
			$params = array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'number',
					'heading'     => esc_html__( 'Number of Clients', 'extensive-vc' ),
					'description' => esc_html__( 'Enter number of clients or leave empty for showing all clients', 'extensive-vc' )
				),
				array(
					'type'        => 'autocomplete',
					'param_name'  => 'category',
					'heading'     => esc_html__( 'Category', 'extensive-vc' ),
					'description' => esc_html__( 'Enter one category slug or leave empty for showing all categories', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'custom_link_target',
					'heading'    => esc_html__( 'Custom Link Target', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_link_target_array() )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'items_hover_animation',
					'heading'     => esc_html__( 'Items Hover Animation', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Switch Images', 'extensive-vc' ) => 'switch-images',
						esc_html__( 'Roll Over', 'extensive-vc' )     => 'roll-over'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'number_of_visible_items',
					'heading'    => esc_html__( 'Number Of Visible Items', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'One', 'extensive-vc' )   => '1',
						esc_html__( 'Two', 'extensive-vc' )   => '2',
						esc_html__( 'Three', 'extensive-vc' ) => '3',
						esc_html__( 'Four', 'extensive-vc' )  => '4',
						esc_html__( 'Five', 'extensive-vc' )  => '5',
						esc_html__( 'Six', 'extensive-vc' )   => '6'
					),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_loop',
					'heading'    => esc_html__( 'Enable Slider Loop', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay',
					'heading'    => esc_html__( 'Enable Slider Autoplay', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed',
					'heading'     => esc_html__( 'Slide Duration (ms)', 'extensive-vc' ),
					'description' => esc_html__( 'Speed of slide in milliseconds. Default value is 5000', 'extensive-vc' ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed_animation',
					'heading'     => esc_html__( 'Slide Animation Duration (ms)', 'extensive-vc' ),
					'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 600', 'extensive-vc' ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_navigation',
					'heading'    => esc_html__( 'Enable Slider Navigation', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_pagination',
					'heading'    => esc_html__( 'Enable Slider Pagination', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'carousel_navigation_skin',
					'heading'     => esc_html__( 'Slider Navigation Skin', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Light', 'extensive-vc' )   => 'light'
					),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc' )
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
				'number'                   => '-1',
				'category'                 => '',
				'custom_link_target'       => '_self',
				'items_hover_animation'    => 'switch-images',
				'number_of_visible_items'  => '4',
				'carousel_loop'            => 'yes',
				'carousel_autoplay'        => 'yes',
				'carousel_speed'           => '5000',
				'carousel_speed_animation' => '600',
				'carousel_navigation'      => 'yes',
				'carousel_pagination'      => 'yes',
				'carousel_navigation_skin' => ''
			);
			$params = shortcode_atts( $args, $atts );
			
			$params['query_results']  = new \WP_Query( $this->getQueryParams( $params ) );
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['slider_data']    = $this->getSliderData( $params, $args );
			
			$params['custom_link_target'] = ! empty( $params['custom_link_target'] ) ? $params['custom_link_target'] : $args['custom_link_target'];
			
			$html = extensive_vc_get_module_template_part( 'cpt', 'clients', 'templates/clients-holder', '', $params );
			
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
			$holderClasses[] = ! empty( $params['carousel_navigation_skin'] ) ? 'evc-carousel-skin-' . esc_attr( $params['carousel_navigation_skin'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode query parameters
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getQueryParams( $params ) {
			$args = array(
				'post_status'    => 'publish',
				'post_type'      => 'clients',
				'posts_per_page' => $params['number'],
				'orderby'        => 'date',
				'order'          => 'ASC'
			);
			
			if ( $params['category'] != '' ) {
				$args['clients-category'] = $params['category'];
			}
			
			return $args;
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
			$data['data-enable-autoplay-hover-pause'] = 'no';
			$data['data-carousel-speed']              = ! empty( $params['carousel_speed'] ) ? $params['carousel_speed'] : $args['carousel_speed'];
			$data['data-carousel-speed-animation']    = ! empty( $params['carousel_speed_animation'] ) ? $params['carousel_speed_animation'] : $args['carousel_speed_animation'];
			$data['data-enable-navigation']           = ! empty( $params['carousel_navigation'] ) ? $params['carousel_navigation'] : $args['carousel_navigation'];
			$data['data-enable-pagination']           = ! empty( $params['carousel_pagination'] ) ? $params['carousel_pagination'] : $args['carousel_pagination'];
			
			return $data;
		}
		
		/**
		 * Filter shortcode categories
		 *
		 * @param $query
		 *
		 * @return array
		 */
		function clientsCategoryAutocompleteSuggester( $query ) {
			global $wpdb;
			
			$post_meta_infos = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS clients_category_title
				FROM {$wpdb->terms} AS a
				LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
				WHERE b.taxonomy = 'clients-category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );
			
			$results = array();
			
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['slug'];
					$data['label'] = ( ( strlen( $value['clients_category_title'] ) > 0 ) ? esc_html__( 'Category', 'extensive-vc' ) . ': ' . $value['clients_category_title'] : '' );
					$results[]     = $data;
				}
			}
			
			return $results;
		}
		
		/**
		 * Find shortcode category by slug
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return bool|array
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
						$clients_category_title_display = esc_html__( 'Category', 'extensive-vc' ) . ': ' . $clients_category_title;
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