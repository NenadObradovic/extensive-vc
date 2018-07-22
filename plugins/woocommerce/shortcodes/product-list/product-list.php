<?php

namespace ExtensiveVC\Shortcodes\EVCProductList;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCProductList' ) ) {
	class EVCProductList extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_product_list' );
			$this->setShortcodeName( esc_html__( 'Product List', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'isInPlugins' => true, 'pluginModule' => 'woocommerce' ) );
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
			$params = array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'layout_collections',
					'heading'     => esc_html__( 'Layout Collections', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Standard', 'extensive-vc' )                  => 'standard',
						esc_html__( 'Standard - Button Sliding', 'extensive-vc' ) => 'standard-button-sliding',
						esc_html__( 'Gallery', 'extensive-vc' )                   => 'gallery'
					),
					'save_always' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'number_of_products',
					'heading'    => esc_html__( 'Number of Products', 'extensive-vc' )
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
					'type'        => 'dropdown',
					'param_name'  => 'orderby',
					'heading'     => esc_html__( 'Order By', 'extensive-vc' ),
					'value'       => array_flip( extensive_vc_get_query_order_by_array( false, array( 'on-sale' => esc_html__( 'On Sale', 'extensive-vc' ) ) ) )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'order',
					'heading'     => esc_html__( 'Order', 'extensive-vc' ),
					'value'       => array_flip( extensive_vc_get_query_order_array() )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'taxonomy_to_display',
					'heading'     => esc_html__( 'Choose Sorting Taxonomy', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc' )  => '',
						esc_html__( 'Category', 'extensive-vc' ) => 'category',
						esc_html__( 'Tag', 'extensive-vc' )      => 'tag',
						esc_html__( 'ID', 'extensive-vc' )       => 'id'
					),
					'description' => esc_html__( 'If you would like to display only certain products, this is where you can select the criteria to choose which products to display', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'taxonomy_values',
					'heading'     => esc_html__( 'Enter Taxonomy Values', 'extensive-vc' ),
					'description' => esc_html__( 'Separate values (category slugs, tags, or post IDs) with a comma', 'extensive-vc' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'image_proportions',
					'heading'     => esc_html__( 'Image Proportions', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Default', 'extensive-vc' )        => '',
						esc_html__( 'Original', 'extensive-vc' )       => 'full',
						esc_html__( 'Medium', 'extensive-vc' )         => 'medium',
						esc_html__( 'Large', 'extensive-vc' )          => 'large',
						esc_html__( 'Shop Single', 'extensive-vc' )    => 'woocommerce_single',
						esc_html__( 'Shop Thumbnail', 'extensive-vc' ) => 'woocommerce_thumbnail'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_title',
					'heading'    => esc_html__( 'Enable Title', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'dependency' => array( 'element' => 'enable_title', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_ratings',
					'heading'    => esc_html__( 'Enable Ratings', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_price',
					'heading'    => esc_html__( 'Enable Price', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'enable_category',
					'heading'    => esc_html__( 'Enable Category', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc' )
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
				'custom_class'        => '',
				'layout_collections'  => 'standard',
				'number_of_products'  => '-1',
				'number_of_columns'   => 'three',
				'space_between_items' => 'normal',
				'orderby'             => 'date',
				'order'               => 'ASC',
				'taxonomy_to_display' => 'category',
				'taxonomy_values'     => '',
				'image_proportions'   => 'full',
				'enable_title'        => 'yes',
				'title_tag'           => 'h4',
				'enable_ratings'      => 'yes',
				'enable_price'        => 'yes',
				'enable_category'     => 'no'
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['query_results']  = new \WP_Query( $this->getQueryParams( $params ) );
			$params['holder_classes'] = $this->getHolderClasses( $params, $args );
			
			$params['title_tag'] = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			
			$html = extensive_vc_get_module_template_part( 'woocommerce', 'product-list', 'templates/product-list', '', $params );
			
			return $html;
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
				'post_status'         => 'publish',
				'post_type'           => 'product',
				'ignore_sticky_posts' => 1,
				'posts_per_page'      => $params['number_of_products'],
				'orderby'             => $params['orderby'],
				'order'               => $params['order']
			);
			
			if ( $params['orderby'] === 'on-sale' && function_exists( 'wc_get_product_ids_on_sale' ) ) {
				$args['no_found_rows'] = 1;
				$args['post__in']      = array_merge( array( 0 ), wc_get_product_ids_on_sale() );
			}
			
			$taxonomyType   = $params['taxonomy_to_display'];
			$taxonomyValues = $params['taxonomy_values'];
			
			if ( ! empty( $taxonomyType ) && ! empty( $taxonomyValues ) ) {
				$taxonomyValues = str_replace( ' ', '', $taxonomyValues );
				
				switch ( $taxonomyType ) {
					case 'category':
						$args['product_cat'] = $taxonomyValues;
						break;
					case 'tag':
						$args['product_tag'] = $taxonomyValues;
						break;
					case 'id':
						$idArray          = $taxonomyValues;
						$ids              = explode( ',', $idArray );
						$args['post__in'] = $ids;
						break;
				}
			}
			
			return $args;
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
			$holderClasses[] = ! empty( $params['number_of_columns'] ) ? 'evc-' . $params['number_of_columns'] . '-columns' : 'evc-' . $args['number_of_columns'] . '-columns';
			$holderClasses[] = ! empty( $params['space_between_items'] ) ? 'evc-' . $params['space_between_items'] . '-space' : 'evc-' . $args['space_between_items'] . '-space';
			$holderClasses[] = ! empty( $params['layout_collections'] ) ? 'evc-layout-' . $params['layout_collections'] : 'evc-layout-' . $args['layout_collections'];
			
			return implode( ' ', $holderClasses );
		}
	}
}

EVCProductList::getInstance();