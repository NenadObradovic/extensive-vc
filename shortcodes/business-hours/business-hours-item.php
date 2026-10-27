<?php

namespace ExtensiveVC\Shortcodes\EVCBusinessHoursItem;

use ExtensiveVC\Shortcodes;
use ExtensiveVC\Shortcodes\EVCBusinessHours\EVCBusinessHours;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCBusinessHoursItem' ) ) {
	class EVCBusinessHoursItem extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_business_hours_item' );
			$this->setParentBase( 'evc_business_hours' );
			$this->setShortcodeName( esc_html__( 'Business Hours Item', 'extensive-vc-addon' ) );
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
					'param_name'  => 'label',
					'heading'     => esc_html__( 'Day Label', 'extensive-vc-addon' ),
					'description' => esc_html__( 'E.g. Monday or Monday - Friday', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'hours',
					'heading'     => esc_html__( 'Working Hours', 'extensive-vc-addon' ),
					'description' => esc_html__( 'E.g. 09:00 - 17:00', 'extensive-vc-addon' ),
					'admin_label' => true,
					'dependency'  => array( 'element' => 'closed', 'value' => array( 'no' ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'closed',
					'heading'    => esc_html__( 'Closed', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) )
				),
				array(
					'type'        => 'checkbox',
					'param_name'  => 'days',
					'heading'     => esc_html__( 'Days', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_week_days_array() ),
					'description' => esc_html__( 'Select days which this item contains, they are used to highlight current day', 'extensive-vc-addon' )
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
				'label'        => '',
				'hours'        => '',
				'closed'       => 'no',
				'days'         => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$settings = EVCBusinessHours::getCurrentSettings();

			$params['is_closed']      = 'yes' === $params['closed'];
			$params['hours_text']     = $params['is_closed'] ? $settings['closed_text'] : $params['hours'];
			$params['days_list']      = $this->getDays( $params['days'] );
			$params['holder_classes'] = trim( ( ! empty( $params['custom_class'] ) ? esc_attr( $params['custom_class'] ) : '' ) . ( $params['is_closed'] ? ' evc-bh-item-closed' : '' ) );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'business-hours', 'templates/business-hours-item', '', $params );

			return $html;
		}

		/**
		 * Get list of valid day numbers from option value
		 *
		 * @param $days string - comma separated day numbers
		 *
		 * @return string
		 */
		private function getDays( $days ) {
			$days = array_intersect( array_map( 'trim', explode( ',', (string) $days ) ), array_keys( extensive_vc_get_week_days_array() ) );

			return implode( ',', array_unique( $days ) );
		}
	}
}

EVCBusinessHoursItem::getInstance();
