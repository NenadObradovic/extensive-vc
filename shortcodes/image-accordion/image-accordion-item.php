<?php

namespace ExtensiveVC\Shortcodes\EVCImageAccordionItem;

use ExtensiveVC\Shortcodes;
use ExtensiveVC\Shortcodes\EVCImageAccordion\EVCImageAccordion;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageAccordionItem' ) ) {
	class EVCImageAccordionItem extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_accordion_item' );
			$this->setParentBase( 'evc_image_accordion' );
			$this->setShortcodeName( esc_html__( 'Image Accordion Item', 'extensive-vc-addon' ) );
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
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'heading'     => esc_html__( 'Image', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Select image from media library', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'vc_link',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'link_text',
					'heading'     => esc_html__( 'Link Text', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is Read More', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'custom_link', 'not_empty' => true )
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
				'image'        => '',
				'title'        => '',
				'text'         => '',
				'custom_link'  => '',
				'link_text'    => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$settings = EVCImageAccordion::registerItem();

			$params['is_active']       = $settings['active'];
			$params['title_tag']       = $settings['title_tag'];
			$params['image_size']      = $settings['image_size'];
			$params['item_id']         = wp_unique_id( 'evc-ia-item-' );
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-ia-link' );
			$params['link_text']       = '' !== trim( $params['link_text'] ) ? $params['link_text'] : esc_html__( 'Read More', 'extensive-vc-addon' );
			$params['holder_classes']  = trim( ( ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '' ) . ( $params['is_active'] ? ' evc-ia-active' : '' ) );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-accordion', 'templates/image-accordion-item', '', $params );

			return $html;
		}
	}
}

EVCImageAccordionItem::getInstance();
