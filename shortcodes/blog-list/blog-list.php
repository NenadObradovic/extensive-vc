<?php

namespace ExtensiveVC\Shortcodes\EVCBlogList;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCBlogList' ) ) {
	class EVCBlogList extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_blog_list' );
			$this->setShortcodeName( esc_html__( 'Blog List', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				
				// Category filter
				add_filter( 'vc_autocomplete_evc_blog_list_category_callback', array( $this, 'categoryAutocompleteSuggester' ), 10, 1 ); // Get suggestion(find). Must return an array
				
				// Category render
				add_filter( 'vc_autocomplete_evc_blog_list_category_render', array( $this, 'categoryAutocompleteRender' ), 10, 1 ); // Get suggestion(find). Must return an array
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
		 * Set shortcode parameters for Visual Composer shortcodes options panel
		 */
		function shortcodeParameters() {
			$params = array_merge(
				array(
					array(
						'type'        => 'textfield',
						'param_name'  => 'custom_class',
						'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc' ),
						'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'type',
						'heading'     => esc_html__( 'Type', 'extensive-vc' ),
						'value'       => array(
							esc_html__( 'Standard', 'extensive-vc' ) => 'standard',
							esc_html__( 'Gallery', 'extensive-vc' )  => 'gallery',
							esc_html__( 'Simple', 'extensive-vc' )   => 'simple',
							esc_html__( 'Minimal', 'extensive-vc' )  => 'minimal'
						),
						'save_always' => true
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'layout_collections',
						'heading'     => esc_html__( 'Layout Collections', 'extensive-vc' ),
						'value'       => array(
							esc_html__( 'Default', 'extensive-vc' )       => '',
							esc_html__( 'Date On Image', 'extensive-vc' ) => 'date-on-image',
							esc_html__( 'Boxed Layout', 'extensive-vc' )  => 'boxed'
						),
						'save_always' => true,
						'dependency'  => array( 'element' => 'type', 'value' => array( 'standard' ) )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'number_of_posts',
						'heading'    => esc_html__( 'Number of Posts', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'number_of_columns',
						'heading'    => esc_html__( 'Number of Columns', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_number_of_columns_array() )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'space_between_items',
						'heading'    => esc_html__( 'Space Between Items', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_space_between_items_array() )
					),
					array(
						'type'        => 'autocomplete',
						'param_name'  => 'category',
						'heading'     => esc_html__( 'Category', 'extensive-vc' ),
						'description' => esc_html__( 'Enter one category slug or leave empty for showing all categories', 'extensive-vc' )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'orderby',
						'heading'     => esc_html__( 'Order By', 'extensive-vc' ),
						'value'       => array_flip( extensive_vc_get_query_order_by_array() )
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'order',
						'heading'     => esc_html__( 'Order', 'extensive-vc' ),
						'value'       => array_flip( extensive_vc_get_query_order_array() )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'pagination_type',
						'heading'    => esc_html__( 'Pagination Type', 'extensive-vc' ),
						'value'      => array(
							esc_html__( 'No Pagination', 'extensive-vc' ) => '',
							esc_html__( 'Load More', 'extensive-vc' )     => 'load-more'
						)
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'skin',
						'heading'    => esc_html__( 'Skin', 'extensive-vc' ),
						'value'      => array(
							esc_html__( 'Default', 'extensive-vc' ) => '',
							esc_html__( 'Light', 'extensive-vc' )   => 'light'
						),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'image_proportions',
						'heading'    => esc_html__( 'Image Proportions', 'extensive-vc' ),
						'value'      => array(
							esc_html__( 'Original', 'extensive-vc' )  => 'full',
							esc_html__( 'Large', 'extensive-vc' )     => 'large',
							esc_html__( 'Medium', 'extensive-vc' )    => 'medium',
							esc_html__( 'Thumbnail', 'extensive-vc' ) => 'thumbnail',
						),
						'dependency' => array( 'element' => 'type', 'value' => array( 'standard', 'gallery' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'title_tag',
						'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'enable_excerpt',
						'heading'    => esc_html__( 'Enable Excerpt', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
						'dependency' => array( 'element' => 'type', 'value' => array( 'standard', 'gallery' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'excerpt_length',
						'heading'     => esc_html__( 'Excerpt Length', 'extensive-vc' ),
						'description' => esc_html__( 'Set number of characters', 'extensive-vc' ),
						'dependency'  => array( 'element' => 'enable_excerpt', 'value' => array( 'yes' ) ),
						'group'       => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'enable_category',
						'heading'    => esc_html__( 'Enable Category', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
						'dependency' => array( 'element' => 'type', 'value' => array( 'standard' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'enable_author',
						'heading'    => esc_html__( 'Enable Author', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
						'dependency' => array( 'element' => 'type', 'value' => array( 'standard' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'enable_date',
						'heading'    => esc_html__( 'Enable Date', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'button_text',
						'heading'    => esc_html__( 'Button Text', 'extensive-vc' ),
						'dependency' => array( 'element' => 'pagination_type', 'not_empty' => true ),
						'group'      => esc_html__( 'Button Options', 'extensive-vc' )
					)
				),
				extensive_vc_get_button_shortcode_options_array( true )
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
				'custom_class'              => '',
				'type'                      => 'standard',
				'layout_collections'        => '',
				'number_of_posts'           => '-1',
				'number_of_columns'         => 'three',
				'space_between_items'       => 'normal',
				'category'                  => '',
				'orderby'                   => 'date',
				'order'                     => 'ASC',
				'pagination_type'           => '',
				'skin'                      => '',
				'image_proportions'         => 'full',
				'title_tag'                 => 'h4',
				'enable_excerpt'            => 'yes',
				'excerpt_length'            => '',
				'enable_category'           => 'yes',
				'enable_author'             => 'yes',
				'enable_date'               => 'yes',
				'button_text'               => esc_html__( 'Load More', 'extensive-vc' ),
				'button_type'               => '',
				'button_size'               => '',
				'button_font_size'          => '',
				'button_color'              => '',
				'button_hover_color'        => '',
				'button_bg_color'           => '',
				'button_hover_bg_color'     => '',
				'button_border_color'       => '',
				'button_hover_border_color' => '',
				'button_border_width'       => '',
				'button_line_color'         => '',
				'button_switch_line_color'  => '',
				'button_margin'             => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['query_results']   = new \WP_Query( extensive_vc_get_shortcode_query_params( $params ) );
			$params['pagination_data'] = extensive_vc_get_shortcode_pagination_data( 'shortcodes', $this->getShortcodeName(), 'post', $params );
			$params['holder_classes']  = $this->getHolderClasses( $params, $args );
			
			$params['title_tag']     = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['button_params'] = extensive_vc_get_button_shortcode_params( array_merge( $params, array( 'button_custom_class' => 'evc-load-more-button' ) ) );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/blog-list', '', $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-bl-' . $params['type'] : 'evc-bl-' . $args['type'];
			$holderClasses[] = $params['type'] === 'standard' && ! empty( $params['layout_collections'] ) ? 'evc-bl-layout-' . $params['layout_collections'] : '';
			$holderClasses[] = ! empty( $params['number_of_columns'] ) ? 'evc-' . $params['number_of_columns'] . '-columns' : 'evc-' . $args['number_of_columns'] . '-columns';
			$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'evc-' . $params['space_between_items'] . '-space' : 'evc-' . $args['space_between_items'] . '-space';
			$holderClasses[] = ! empty( $params['pagination_type'] ) ? 'evc-has-pagination evc-' . $params['pagination_type'] : '';
			$holderClasses[] = ! empty( $params['skin'] ) ? 'evc-bl-skin-' . $params['skin'] : '';
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Filter shortcode categories
		 *
		 * @param $query
		 *
		 * @return array
		 */
		public function categoryAutocompleteSuggester( $query ) {
			global $wpdb;
			$post_meta_infos       = $wpdb->get_results( $wpdb->prepare( "SELECT a.slug AS slug, a.name AS category_title
					FROM {$wpdb->terms} AS a
					LEFT JOIN ( SELECT term_id, taxonomy  FROM {$wpdb->term_taxonomy} ) AS b ON b.term_id = a.term_id
					WHERE b.taxonomy = 'category' AND a.name LIKE '%%%s%%'", stripslashes( $query ) ), ARRAY_A );
			
			$results = array();
			if ( is_array( $post_meta_infos ) && ! empty( $post_meta_infos ) ) {
				foreach ( $post_meta_infos as $value ) {
					$data          = array();
					$data['value'] = $value['slug'];
					$data['label'] = ( ( strlen( $value['category_title'] ) > 0 ) ? esc_html__( 'Category', 'extensive-vc' ) . ': ' . $value['category_title'] : '' );
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
						$category_title_display = esc_html__( 'Category', 'extensive-vc' ) . ': ' . $category_title;
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

EVCBlogList::getInstance();