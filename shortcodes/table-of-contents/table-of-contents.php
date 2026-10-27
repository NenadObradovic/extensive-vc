<?php

namespace ExtensiveVC\Shortcodes\EVCTableOfContents;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCTableOfContents' ) ) {
	class EVCTableOfContents extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_table_of_contents' );
			$this->setShortcodeName( esc_html__( 'Table of Contents', 'extensive-vc-addon' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );

			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
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
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is Table of Contents', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'checkbox',
					'param_name'  => 'headings',
					'heading'     => esc_html__( 'Include Headings', 'extensive-vc-addon' ),
					'value'       => array(
						'H2' => 'h2',
						'H3' => 'h3',
						'H4' => 'h4',
						'H5' => 'h5',
						'H6' => 'h6'
					),
					'std'         => 'h2,h3',
					'description' => esc_html__( 'Headings are collected from the page content. Add evc-toc-exclude class to a heading or its row to exclude it from the list', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'hierarchical',
					'heading'    => esc_html__( 'Hierarchical View', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'list_style',
					'heading'    => esc_html__( 'List Style', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Numbers', 'extensive-vc-addon' ) => 'numbers',
						esc_html__( 'Bullets', 'extensive-vc-addon' ) => 'bullets',
						esc_html__( 'None', 'extensive-vc-addon' )    => 'none'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'collapsible',
					'heading'    => esc_html__( 'Collapsible', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'collapsed',
					'heading'    => esc_html__( 'Collapsed By Default', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'dependency' => array( 'element' => 'collapsible', 'value' => array( 'yes' ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'highlight_active',
					'heading'    => esc_html__( 'Highlight Current Section', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'sticky',
					'heading'     => esc_html__( 'Sticky', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'description' => esc_html__( 'Element follows page scroll inside its column, e.g. in sidebar column. Set Equal height option for the row, so column is as tall as the content', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'scroll_offset',
					'heading'     => esc_html__( 'Scroll Offset (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Space above the heading after scroll, e.g. height of the sticky header. Default value is 30', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'container',
					'heading'     => esc_html__( 'Content Selector', 'extensive-vc-addon' ),
					'description' => esc_html__( 'CSS selector of the content which contains headings, e.g. .entry-content. Leave empty to use page content', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'link_color',
					'heading'    => esc_html__( 'Link Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'link_active_color',
					'heading'    => esc_html__( 'Link Hover/Active Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'background_color',
					'heading'    => esc_html__( 'Background Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'border_color',
					'heading'    => esc_html__( 'Border Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
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
				'custom_class'      => '',
				'title'             => '',
				'headings'          => 'h2,h3',
				'hierarchical'      => 'yes',
				'list_style'        => 'numbers',
				'collapsible'       => 'no',
				'collapsed'         => 'no',
				'highlight_active'  => 'yes',
				'sticky'            => 'no',
				'scroll_offset'     => '',
				'container'         => '',
				'title_tag'         => 'p',
				'title_color'       => '',
				'link_color'        => '',
				'link_active_color' => '',
				'background_color'  => '',
				'border_color'      => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['list_style']   = in_array( $params['list_style'], array( 'numbers', 'bullets', 'none' ), true ) ? $params['list_style'] : $args['list_style'];
			$params['list_tag']     = 'numbers' === $params['list_style'] ? 'ol' : 'ul';
			$params['title']        = '' !== trim( $params['title'] ) ? $params['title'] : esc_html__( 'Table of Contents', 'extensive-vc-addon' );
			$params['title_tag']    = extensive_vc_escape_title_tag( ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'], 'p' );
			$params['is_collapsed'] = 'yes' === $params['collapsible'] && 'yes' === $params['collapsed'];
			$params['element_id']   = wp_unique_id( 'evc-toc-' );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			$params['holder_data']    = $this->getHolderData( $params, $args );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'table-of-contents', 'templates/table-of-contents', '', $params );

			return $html;
		}

		/**
		 * Get shortcode holder classes
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderClasses( $params ) {
			$holderClasses = array();

			$holderClasses[] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';
			$holderClasses[] = 'evc-toc-list-' . esc_attr( $params['list_style'] );
			$holderClasses[] = 'yes' === $params['sticky'] ? 'evc-toc-sticky' : '';
			$holderClasses[] = 'yes' === $params['collapsible'] ? 'evc-toc-collapsible' : '';

			return implode( ' ', $holderClasses );
		}

		/**
		 * Get shortcode holder styles, colors are set through css variables
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderStyles( $params ) {
			$styles    = array();
			$variables = array(
				'title_color'       => '--evc-toc-title-color',
				'link_color'        => '--evc-toc-link-color',
				'link_active_color' => '--evc-toc-link-active-color',
				'background_color'  => '--evc-toc-bg-color',
				'border_color'      => '--evc-toc-border-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['scroll_offset'] ) {
				$styles[] = '--evc-toc-offset: ' . absint( $params['scroll_offset'] ) . 'px';
			}

			return $styles;
		}

		/**
		 * Get shortcode holder data attributes
		 *
		 * @param $params array - shortcode parameters value
		 * @param $args array - default shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderData( $params, $args ) {
			$headings = array_intersect( array_map( 'trim', explode( ',', strtolower( (string) $params['headings'] ) ) ), array( 'h2', 'h3', 'h4', 'h5', 'h6' ) );

			return array(
				'data-headings'      => implode( ',', ! empty( $headings ) ? $headings : explode( ',', $args['headings'] ) ),
				'data-hierarchical'  => 'no' === $params['hierarchical'] ? 'no' : 'yes',
				'data-highlight'     => 'no' === $params['highlight_active'] ? 'no' : 'yes',
				'data-scroll-offset' => '' !== $params['scroll_offset'] ? absint( $params['scroll_offset'] ) : 30,
				'data-container'     => sanitize_text_field( $params['container'] ),
			);
		}
	}
}

EVCTableOfContents::getInstance();
