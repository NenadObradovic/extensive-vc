<?php

namespace ExtensiveVC\Shortcodes\EVCPostCarousel;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPostCarousel' ) ) {
	class EVCPostCarousel extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_post_carousel' );
			$this->setShortcodeName( esc_html__( 'Post Carousel', 'extensive-vc-addon' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				
				// Category filter
				add_filter( 'vc_autocomplete_evc_post_carousel_category_callback', array( $this, 'categoryAutocompleteSuggester' ), 10, 1 ); // Get suggestion(find). Must return an array
				
				// Category render
				add_filter( 'vc_autocomplete_evc_post_carousel_category_render', array( $this, 'categoryAutocompleteRender' ), 10, 1 ); // Get suggestion(find). Must return an array
				
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
						esc_html__( 'Centered', 'extensive-vc-addon' )        => 'centered',
						esc_html__( 'Sliding Excerpt', 'extensive-vc-addon' ) => 'sliding-excerpt'
					),
					'save_always' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'number_of_posts',
					'heading'    => esc_html__( 'Number of Posts', 'extensive-vc-addon' )
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
					'value'      => array_flip( extensive_vc_get_query_order_by_array() )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'order',
					'heading'    => esc_html__( 'Order', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_query_order_array() )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'skin',
					'heading'    => esc_html__( 'Skin', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc-addon' ) => '',
						esc_html__( 'Light', 'extensive-vc-addon' )   => 'light'
					),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'image_proportions',
					'heading'    => esc_html__( 'Image Proportions', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Original', 'extensive-vc-addon' )  => 'full',
						esc_html__( 'Large', 'extensive-vc-addon' )     => 'large',
						esc_html__( 'Medium', 'extensive-vc-addon' )    => 'medium',
						esc_html__( 'Thumbnail', 'extensive-vc-addon' ) => 'thumbnail',
					),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_excerpt',
					'heading'    => esc_html__( 'Enable Excerpt', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'excerpt_length',
					'heading'     => esc_html__( 'Excerpt Length', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Set number of characters', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'enable_excerpt', 'value' => array( 'yes' ) ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_date',
					'heading'    => esc_html__( 'Enable Date', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'sliding-excerpt' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
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
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_loop',
					'heading'    => esc_html__( 'Enable Slider Loop', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay',
					'heading'    => esc_html__( 'Enable Slider Autoplay', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_autoplay_pause',
					'heading'    => esc_html__( 'Enable Slider Autoplay Hover Pause', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed',
					'heading'     => esc_html__( 'Slide Duration (ms)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Speed of slide in milliseconds. Default value is 5000', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_speed_animation',
					'heading'     => esc_html__( 'Slide Animation Duration (ms)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Speed of slide animation in milliseconds. Default value is 600', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'carousel_margin',
					'heading'     => esc_html__( 'Slide Margin (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Define right margin for slide items. Default value is 0', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_navigation',
					'heading'    => esc_html__( 'Enable Slider Navigation', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_pagination',
					'heading'    => esc_html__( 'Enable Slider Pagination', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Slider Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'carousel_navigation_skin',
					'heading'    => esc_html__( 'Slider Navigation Skin', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc-addon' ) => '',
						esc_html__( 'Light', 'extensive-vc-addon' )   => 'light'
					),
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
				'type'                     => 'centered',
				'number_of_posts'          => '-1',
				'category'                 => '',
				'orderby'                  => 'date',
				'order'                    => 'ASC',
				'skin'                     => '',
				'image_proportions'        => 'full',
				'title_tag'                => 'h4',
				'enable_excerpt'           => 'yes',
				'excerpt_length'           => '',
				'enable_date'              => 'yes',
				'number_of_visible_items'  => '1',
				'carousel_loop'            => 'yes',
				'carousel_autoplay'        => 'yes',
				'carousel_autoplay_pause'  => 'no',
				'carousel_speed'           => '5000',
				'carousel_speed_animation' => '600',
				'carousel_margin'          => '30',
				'carousel_navigation'      => 'yes',
				'carousel_pagination'      => 'yes',
				'carousel_navigation_skin' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['query_results']  = new \WP_Query( extensive_vc_get_shortcode_query_params( $params ) );
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			$params['slider_data']    = $this->getSliderData( $params, $args );
			
			$params['title_tag'] = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'post-carousel', 'templates/post-carousel', '', $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-pc-' . $params['type'] : 'evc-pc-' . $args['type'];
			$holderClasses[] = ! empty( $params['skin'] ) ? 'evc-pc-skin-' . $params['skin'] : '';
			$holderClasses[] = ! empty( $params['carousel_navigation_skin'] ) ? 'evc-carousel-skin-' . esc_attr( $params['carousel_navigation_skin'] ) : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get slider data
		 *
		 * @param $params array - shortcode parameters value
		 * @param $args array - default shortcode parameters value
		 *
		 * @return array
		 */
		private function getSliderData( $params, $args ) {
			$data = array();
			
			$data['data-number-of-items']             = $params['number_of_visible_items'] !== '' ? $params['number_of_visible_items'] : $args['number_of_visible_items'];
			$data['data-enable-loop']                 = ! empty( $params['carousel_loop'] ) ? $params['carousel_loop'] : $args['carousel_loop'];
			$data['data-enable-autoplay']             = ! empty( $params['carousel_autoplay'] ) ? $params['carousel_autoplay'] : $args['carousel_autoplay'];
			$data['data-enable-autoplay-hover-pause'] = ! empty( $params['carousel_autoplay_pause'] ) ? $params['carousel_autoplay_pause'] : $args['carousel_autoplay_pause'];
			$data['data-carousel-speed']              = ! empty( $params['carousel_speed'] ) ? $params['carousel_speed'] : $args['carousel_speed'];
			$data['data-carousel-speed-animation']    = ! empty( $params['carousel_speed_animation'] ) ? $params['carousel_speed_animation'] : $args['carousel_speed_animation'];
			$data['data-carousel-margin']             = ! empty( $params['carousel_margin'] ) ? $params['carousel_margin'] : $args['carousel_margin'];
			$data['data-enable-navigation']           = ! empty( $params['carousel_navigation'] ) ? $params['carousel_navigation'] : $args['carousel_navigation'];
			$data['data-enable-pagination']           = ! empty( $params['carousel_pagination'] ) ? $params['carousel_pagination'] : $args['carousel_pagination'];
			
			if ( $params['type'] === 'centered' ) {
				$data['data-enable-center'] = 'yes';
			}
			
			return $data;
		}
		
		/**
		 * Filter shortcode categories
		 *
		 * @param $query
		 *
		 * @return array
		 */
		public function categoryAutocompleteSuggester( $query ) {
			return extensive_vc_get_taxonomy_autocomplete_suggestions( $query, 'category' );
		}
		
		/**
		 * Find shortcode category by slug
		 * @since 4.4
		 *
		 * @param $query
		 *
		 * @return boolean|array
		 */
		public function categoryAutocompleteRender( $query ) {
			$query = trim( $query['value'] ); // get value from requested
			if ( ! empty( $query ) ) {
				// get portfolio category
				$category = get_term_by( 'slug', $query, 'category' );
				if ( is_object( $category ) ) {
					
					$category_slug = $category->slug;
					$category_title = $category->name;
					
					$category_title_display = '';
					if ( ! empty( $category_title ) ) {
						$category_title_display = esc_html__( 'Category', 'extensive-vc-addon' ) . ': ' . $category_title;
					}
					
					$data          = array();
					$data['value'] = $category_slug;
					$data['label'] = $category_title_display;
					
					return ! empty( $data ) ? $data : false;
				}
				
				return false;
			}
			
			return false;
		}
	}
}

EVCPostCarousel::getInstance();