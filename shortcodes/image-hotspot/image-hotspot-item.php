<?php

namespace ExtensiveVC\Shortcodes\EVCImageHotspotItem;

use ExtensiveVC\Shortcodes;
use ExtensiveVC\Shortcodes\EVCImageHotspot\EVCImageHotspot;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCImageHotspotItem' ) ) {
	class EVCImageHotspotItem extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_image_hotspot_item' );
			$this->setParentBase( 'evc_image_hotspot' );
			$this->setShortcodeName( esc_html__( 'Image Hotspot Item', 'extensive-vc-addon' ) );
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
					'param_name'  => 'position_x',
					'heading'     => esc_html__( 'Horizontal Position (%)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Distance from the left edge of the image, from 0 to 100', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'position_y',
					'heading'     => esc_html__( 'Vertical Position (%)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Distance from the top edge of the image, from 0 to 100', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'marker_type',
					'heading'    => esc_html__( 'Marker Type', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Plus', 'extensive-vc-addon' )   => 'plus',
						esc_html__( 'Dot', 'extensive-vc-addon' )    => 'dot',
						esc_html__( 'Number', 'extensive-vc-addon' ) => 'number',
						esc_html__( 'Text', 'extensive-vc-addon' )   => 'text'
					)
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'marker_text',
					'heading'    => esc_html__( 'Marker Text', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'marker_type', 'value' => array( 'text' ) )
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
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'tooltip_position',
					'heading'    => esc_html__( 'Tooltip Position', 'extensive-vc-addon' ),
					'value'      => array_merge( array( esc_html__( 'Default', 'extensive-vc-addon' ) => '' ), array_flip( EVCImageHotspot::getTooltipPositions() ) )
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
				'custom_class'     => '',
				'position_x'       => '50',
				'position_y'       => '50',
				'marker_type'      => 'plus',
				'marker_text'      => '',
				'title'            => '',
				'text'             => '',
				'custom_link'      => '',
				'link_text'        => '',
				'tooltip_position' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$settings = EVCImageHotspot::registerItem();

			$params['marker_type']      = in_array( $params['marker_type'], array( 'plus', 'dot', 'number', 'text' ), true ) ? $params['marker_type'] : $args['marker_type'];
			$params['tooltip_position'] = array_key_exists( $params['tooltip_position'], EVCImageHotspot::getTooltipPositions() ) ? $params['tooltip_position'] : $settings['tooltip_position'];
			$params['index']            = $settings['index'];
			$params['item_id']          = wp_unique_id( 'evc-ih-item-' );
			$params['has_tooltip']      = '' !== trim( $params['title'] . $params['text'] ) || ! empty( $params['custom_link'] );
			$params['link_attributes']  = extensive_vc_get_custom_link_attributes( $params['custom_link'], 'evc-ih-link' );
			$params['link_text']        = '' !== trim( $params['link_text'] ) ? $params['link_text'] : esc_html__( 'Read More', 'extensive-vc-addon' );
			/* translators: %d: hotspot number */
			$params['marker_label']     = '' !== trim( $params['title'] ) ? $params['title'] : sprintf( esc_html__( 'Hotspot %d', 'extensive-vc-addon' ), $params['index'] );
			$params['holder_classes']   = trim( ( ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '' ) . ' evc-ih-tooltip-' . esc_attr( $params['tooltip_position'] ) );
			$params['holder_styles']    = array(
				'left: ' . $this->getPosition( $params['position_x'] ) . '%',
				'top: ' . $this->getPosition( $params['position_y'] ) . '%',
			);

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'image-hotspot', 'templates/image-hotspot-item', '', $params );

			return $html;
		}

		/**
		 * Get position value in percentages, from 0 to 100
		 *
		 * @param $value string - position option value
		 *
		 * @return float
		 */
		private function getPosition( $value ) {
			$value = (float) str_replace( array( '%', ',' ), array( '', '.' ), (string) $value );

			return round( min( 100, max( 0, $value ) ), 2 );
		}
	}
}

EVCImageHotspotItem::getInstance();
