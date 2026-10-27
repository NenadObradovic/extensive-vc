<?php

namespace ExtensiveVC\Shortcodes\EVCPriceMenuItem;

use ExtensiveVC\Shortcodes;
use ExtensiveVC\Shortcodes\EVCPriceMenu\EVCPriceMenu;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPriceMenuItem' ) ) {
	class EVCPriceMenuItem extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_price_menu_item' );
			$this->setParentBase( 'evc_price_menu' );
			$this->setShortcodeName( esc_html__( 'Price Menu Item', 'extensive-vc-addon' ) );
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
					'type'        => 'textfield',
					'param_name'  => 'price',
					'heading'     => esc_html__( 'Price', 'extensive-vc-addon' ),
					'description' => esc_html__( 'E.g. $12 or 12 EUR', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'old_price',
					'heading'     => esc_html__( 'Old Price', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Displayed as crossed out price before the current price', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Description', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'badge',
					'heading'     => esc_html__( 'Badge', 'extensive-vc-addon' ),
					'description' => esc_html__( 'E.g. New, Popular or Vegan', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'vc_link',
					'param_name' => 'custom_link',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc-addon' )
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
				'price'        => '',
				'old_price'    => '',
				'text'         => '',
				'badge'        => '',
				'custom_link'  => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$settings = EVCPriceMenu::getCurrentSettings();

			$params['title_tag']       = $settings['title_tag'];
			$params['image_size']      = $settings['image_size'];
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'] );
			$params['holder_classes']  = trim( ( ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '' ) . ( ! empty( $params['image'] ) ? ' evc-pm-has-image' : '' ) );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'price-menu', 'templates/price-menu-item', '', $params );

			return $html;
		}
	}
}

EVCPriceMenuItem::getInstance();
