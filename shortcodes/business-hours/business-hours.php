<?php

namespace ExtensiveVC\Shortcodes\EVCBusinessHours;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCBusinessHours' ) ) {
	class EVCBusinessHours extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Stack of business hours elements which are currently rendered, items read settings from the last one
		 */
		private static $renderStack = array();

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_business_hours' );
			$this->setChildBase( 'evc_business_hours_item' );
			$this->setShortcodeName( esc_html__( 'Business Hours', 'extensive-vc-addon' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );

			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct( array( 'hasChild' => true ) );
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
		 * Get settings of currently rendered business hours element
		 *
		 * @return array
		 */
		public static function getCurrentSettings() {
			$last = count( self::$renderStack ) - 1;

			return $last < 0 ? array( 'closed_text' => esc_html__( 'Closed', 'extensive-vc-addon' ) ) : self::$renderStack[ $last ];
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
					'param_name'  => 'style',
					'heading'     => esc_html__( 'Style', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Simple', 'extensive-vc-addon' ) => 'simple',
						esc_html__( 'Boxed', 'extensive-vc-addon' )  => 'boxed'
					),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'divider',
					'heading'    => esc_html__( 'Divider', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Line Between Items', 'extensive-vc-addon' )   => 'line',
						esc_html__( 'Dotted Line Between Day And Hours', 'extensive-vc-addon' ) => 'dotted',
						esc_html__( 'None', 'extensive-vc-addon' )                 => 'none'
					)
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'highlight_today',
					'heading'     => esc_html__( 'Highlight Current Day', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'description' => esc_html__( 'Item which contains current day (by site timezone from Settings -> General) is highlighted. Set days for each item in item options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'closed_text',
					'heading'     => esc_html__( 'Closed Text', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Text displayed for items marked as closed. Default value is Closed', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'day_color',
					'heading'    => esc_html__( 'Day Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'hours_color',
					'heading'    => esc_html__( 'Hours Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'closed_color',
					'heading'    => esc_html__( 'Closed Text Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'today_color',
					'heading'    => esc_html__( 'Current Day Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'highlight_today', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'divider_color',
					'heading'    => esc_html__( 'Divider Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'divider', 'value' => array( 'line', 'dotted' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'background_color',
					'heading'    => esc_html__( 'Background Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'boxed' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'item_spacing',
					'heading'     => esc_html__( 'Space Between Items (px)', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is 12', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
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
				'style'            => 'simple',
				'divider'          => 'line',
				'highlight_today'  => 'yes',
				'closed_text'      => '',
				'day_color'        => '',
				'hours_color'      => '',
				'closed_color'     => '',
				'today_color'      => '',
				'divider_color'    => '',
				'background_color' => '',
				'item_spacing'     => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['style']   = 'boxed' === $params['style'] ? 'boxed' : 'simple';
			$params['divider'] = in_array( $params['divider'], array( 'line', 'dotted', 'none' ), true ) ? $params['divider'] : $args['divider'];

			self::$renderStack[] = array(
				'closed_text' => '' !== trim( $params['closed_text'] ) ? $params['closed_text'] : esc_html__( 'Closed', 'extensive-vc-addon' ),
			);

			$params['items_html'] = do_shortcode( (string) $content );

			array_pop( self::$renderStack );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			$params['holder_data']    = $this->getHolderData( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'business-hours', 'templates/business-hours', '', $params );

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
			$holderClasses[] = 'evc-bh-' . esc_attr( $params['style'] );
			$holderClasses[] = 'evc-bh-divider-' . esc_attr( $params['divider'] );

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
				'day_color'        => '--evc-bh-day-color',
				'hours_color'      => '--evc-bh-hours-color',
				'closed_color'     => '--evc-bh-closed-color',
				'today_color'      => '--evc-bh-today-color',
				'divider_color'    => '--evc-bh-divider-color',
				'background_color' => '--evc-bh-bg-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['item_spacing'] ) {
				$styles[] = '--evc-bh-item-spacing: ' . absint( $params['item_spacing'] ) . 'px';
			}

			return $styles;
		}

		/**
		 * Get shortcode holder data attributes
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderData( $params ) {
			$data = array();

			if ( 'yes' === $params['highlight_today'] ) {
				// Site timezone offset in minutes, current day is calculated in browser so it works with page caching
				$data['data-timezone-offset'] = (int) round( wp_timezone()->getOffset( new \DateTime( 'now' ) ) / 60 );
			}

			return $data;
		}
	}
}

EVCBusinessHours::getInstance();
