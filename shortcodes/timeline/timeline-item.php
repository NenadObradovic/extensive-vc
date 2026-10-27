<?php

namespace ExtensiveVC\Shortcodes\EVCTimelineItem;

use ExtensiveVC\Shortcodes;
use ExtensiveVC\Shortcodes\EVCTimeline\EVCTimeline;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCTimelineItem' ) ) {
	class EVCTimelineItem extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_timeline_item' );
			$this->setParentBase( 'evc_timeline' );
			$this->setShortcodeName( esc_html__( 'Timeline Item', 'extensive-vc-addon' ) );
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
					'param_name'  => 'date_label',
					'heading'     => esc_html__( 'Date', 'extensive-vc-addon' ),
					'description' => esc_html__( 'E.g. 2026 or March 2026', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'heading'     => esc_html__( 'Image', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Select image from media library', 'extensive-vc-addon' )
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
				'date_label'   => '',
				'title'        => '',
				'image'        => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$settings = EVCTimeline::getCurrentSettings();

			$params['title_tag']      = $settings['title_tag'];
			$params['image_size']     = $settings['image_size'];
			$params['content_html']   = function_exists( 'wpb_js_remove_wpautop' ) ? wpb_js_remove_wpautop( (string) $content, true ) : wpautop( do_shortcode( (string) $content ) );
			$params['holder_classes'] = ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '';

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'timeline', 'templates/timeline-item', '', $params );

			return $html;
		}
	}
}

EVCTimelineItem::getInstance();
