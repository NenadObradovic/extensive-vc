<?php

namespace ExtensiveVC\Shortcodes\EVCAccordionItem;

use ExtensiveVC\Shortcodes;
use ExtensiveVC\Shortcodes\EVCAccordion\EVCAccordion;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCAccordionItem' ) ) {
	class EVCAccordionItem extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_accordion_item' );
			$this->setParentBase( 'evc_accordion' );
			$this->setShortcodeName( esc_html__( 'Accordion Item', 'extensive-vc-addon' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );

			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasParent' => true ) );
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
					'admin_label' => true
				),
				array(
					'type'       => 'textarea_html',
					'param_name' => 'content',
					'heading'    => esc_html__( 'Content', 'extensive-vc-addon' )
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
				'custom_class' => '',
				'title'        => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['content_html'] = function_exists( 'wpb_js_remove_wpautop' ) ? wpb_js_remove_wpautop( (string) $content, true ) : wpautop( do_shortcode( (string) $content ) );

			$settings = EVCAccordion::registerItem( $params['title'], $params['content_html'] );

			$params['is_open']        = $settings['open'];
			$params['title_tag']      = $settings['title_tag'];
			$params['item_id']        = wp_unique_id( 'evc-acc-item-' );
			$params['holder_classes'] = trim( ( ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '' ) . ( $params['is_open'] ? ' evc-acc-item-open' : '' ) );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'accordion', 'templates/accordion-item', '', $params );

			return $html;
		}
	}
}

EVCAccordionItem::getInstance();
