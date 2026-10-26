<?php

namespace ExtensiveVC\Shortcodes\EVCCountdown;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCCountdown' ) ) {
	class EVCCountdown extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_countdown' );
			$this->setShortcodeName( esc_html__( 'Countdown', 'extensive-vc-addon' ) );
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
		 * Returns countdown units with default labels
		 *
		 * @return array
		 */
		private function getUnits() {
			return array(
				'days'    => esc_html__( 'Days', 'extensive-vc-addon' ),
				'hours'   => esc_html__( 'Hours', 'extensive-vc-addon' ),
				'minutes' => esc_html__( 'Minutes', 'extensive-vc-addon' ),
				'seconds' => esc_html__( 'Seconds', 'extensive-vc-addon' ),
			);
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
					'param_name'  => 'type',
					'heading'     => esc_html__( 'Type', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Fixed Date', 'extensive-vc-addon' ) => 'fixed',
						esc_html__( 'Evergreen', 'extensive-vc-addon' )  => 'evergreen'
					),
					'description' => esc_html__( 'Evergreen countdown starts when visitor opens the page for the first time and lasts for the set duration', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'date',
					'heading'     => esc_html__( 'Date And Time', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Enter date and time in format YYYY-MM-DD HH:MM (e.g. 2027-01-31 18:00). Site timezone from Settings -> General is used', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'fixed' ) ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'evergreen_hours',
					'heading'     => esc_html__( 'Duration Hours', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'evergreen' ) )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'evergreen_minutes',
					'heading'     => esc_html__( 'Duration Minutes', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'type', 'value' => array( 'evergreen' ) )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'style',
					'heading'     => esc_html__( 'Style', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Boxed', 'extensive-vc-addon' )     => 'boxed',
						esc_html__( 'Simple', 'extensive-vc-addon' )    => 'simple',
						esc_html__( 'Separated', 'extensive-vc-addon' ) => 'separated'
					)
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'alignment',
					'heading'     => esc_html__( 'Alignment', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Center', 'extensive-vc-addon' ) => 'center',
						esc_html__( 'Left', 'extensive-vc-addon' )   => 'left',
						esc_html__( 'Right', 'extensive-vc-addon' )  => 'right'
					)
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'expire_action',
					'heading'     => esc_html__( 'When Countdown Ends', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Show Message', 'extensive-vc-addon' )     => 'message',
						esc_html__( 'Hide Countdown', 'extensive-vc-addon' )   => 'hide',
						esc_html__( 'Show Zero Values', 'extensive-vc-addon' ) => 'zero'
					)
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'expired_message',
					'heading'    => esc_html__( 'Message', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'expire_action', 'value' => array( 'message' ) )
				)
			);

			foreach ( $this->getUnits() as $unit => $label ) {
				$params[] = array(
					'type'       => 'dropdown',
					'param_name' => 'show_' . $unit,
					/* translators: %s: countdown unit, e.g. Days */
					'heading'    => sprintf( esc_html__( 'Show %s', 'extensive-vc-addon' ), $label ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) ),
					'group'      => esc_html__( 'Units Options', 'extensive-vc-addon' )
				);

				$params[] = array(
					'type'        => 'textfield',
					'param_name'  => 'label_' . $unit,
					/* translators: %s: countdown unit, e.g. Days */
					'heading'     => sprintf( esc_html__( '%s Label', 'extensive-vc-addon' ), $label ),
					'description' => esc_html__( 'Leave empty to use default translated label', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'show_' . $unit, 'value' => array( 'yes' ) ),
					'group'       => esc_html__( 'Units Options', 'extensive-vc-addon' )
				);
			}

			$params = array_merge(
				$params,
				array(
					array(
						'type'       => 'colorpicker',
						'param_name' => 'digit_color',
						'heading'    => esc_html__( 'Digits Color', 'extensive-vc-addon' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'digit_font_size',
						'heading'    => esc_html__( 'Digits Font Size (px or em)', 'extensive-vc-addon' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'label_color',
						'heading'    => esc_html__( 'Labels Color', 'extensive-vc-addon' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'box_bg_color',
						'heading'    => esc_html__( 'Box Background Color', 'extensive-vc-addon' ),
						'dependency' => array( 'element' => 'style', 'value' => array( 'boxed' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'box_border_color',
						'heading'    => esc_html__( 'Box Border Color', 'extensive-vc-addon' ),
						'dependency' => array( 'element' => 'style', 'value' => array( 'boxed' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
					)
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
			$args = array(
				'custom_class'      => '',
				'type'              => 'fixed',
				'date'              => '',
				'evergreen_hours'   => '24',
				'evergreen_minutes' => '',
				'style'             => 'boxed',
				'alignment'         => 'center',
				'expire_action'     => 'message',
				'expired_message'   => '',
				'digit_color'       => '',
				'digit_font_size'   => '',
				'label_color'       => '',
				'box_bg_color'      => '',
				'box_border_color'  => ''
			);

			foreach ( array_keys( $this->getUnits() ) as $unit ) {
				$args[ 'show_' . $unit ]  = 'yes';
				$args[ 'label_' . $unit ] = '';
			}

			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['type']          = 'evergreen' === $params['type'] ? 'evergreen' : 'fixed';
			$params['style']         = in_array( $params['style'], array( 'boxed', 'simple', 'separated' ), true ) ? $params['style'] : $args['style'];
			$params['expire_action'] = in_array( $params['expire_action'], array( 'message', 'hide', 'zero' ), true ) ? $params['expire_action'] : $args['expire_action'];
			$params['end_time']      = $this->getEndTime( $params );
			$params['duration']      = $this->getEvergreenDuration( $params );
			$params['is_expired']    = 'fixed' === $params['type'] && $params['end_time'] <= time();

			// Expired countdown is hidden without waiting for script
			if ( $params['is_expired'] && 'hide' === $params['expire_action'] ) {
				return '';
			}

			$params['units']          = $this->getVisibleUnits( $params );
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			$params['holder_data']    = $this->getHolderData( $params, $atts );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'countdown', 'templates/countdown', '', $params );

			return $html;
		}

		/**
		 * Get countdown end timestamp for fixed date type
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return int
		 */
		private function getEndTime( $params ) {
			if ( 'fixed' !== $params['type'] || empty( $params['date'] ) ) {
				return 0;
			}

			try {
				$date = new \DateTime( sanitize_text_field( $params['date'] ), wp_timezone() );

				return $date->getTimestamp();
			} catch ( \Exception $exception ) {
				return 0;
			}
		}

		/**
		 * Get evergreen countdown duration in seconds
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return int
		 */
		private function getEvergreenDuration( $params ) {
			return absint( $params['evergreen_hours'] ) * HOUR_IN_SECONDS + absint( $params['evergreen_minutes'] ) * MINUTE_IN_SECONDS;
		}

		/**
		 * Get visible units with labels and initial values
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getVisibleUnits( $params ) {
			$units     = array();
			$seconds   = array(
				'days'    => DAY_IN_SECONDS,
				'hours'   => HOUR_IN_SECONDS,
				'minutes' => MINUTE_IN_SECONDS,
				'seconds' => 1,
			);
			$remaining = 'fixed' === $params['type'] ? max( 0, $params['end_time'] - time() ) : $params['duration'];

			foreach ( $this->getUnits() as $unit => $label ) {
				if ( 'no' === $params[ 'show_' . $unit ] ) {
					continue;
				}

				// Values of hidden units are added to the next visible unit
				$value      = (int) floor( $remaining / $seconds[ $unit ] );
				$remaining -= $value * $seconds[ $unit ];

				$units[ $unit ] = array(
					'label' => ! empty( $params[ 'label_' . $unit ] ) ? $params[ 'label_' . $unit ] : $label,
					'value' => str_pad( (string) $value, 2, '0', STR_PAD_LEFT ),
				);
			}

			return $units;
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
			$holderClasses[] = 'evc-cd-' . esc_attr( $params['style'] );
			$holderClasses[] = 'evc-cd-align-' . esc_attr( in_array( $params['alignment'], array( 'left', 'center', 'right' ), true ) ? $params['alignment'] : 'center' );
			$holderClasses[] = $params['is_expired'] && 'message' === $params['expire_action'] ? 'evc-cd-expired' : '';

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
				'digit_color'      => '--evc-cd-digit-color',
				'label_color'      => '--evc-cd-label-color',
				'box_bg_color'     => '--evc-cd-box-bg-color',
				'box_border_color' => '--evc-cd-box-border-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( ! empty( $params['digit_font_size'] ) ) {
				$font_size = $params['digit_font_size'];
				$styles[]  = '--evc-cd-digit-font-size: ' . ( extensive_vc_string_ends_with_typography_units( $font_size ) ? $font_size : intval( $font_size ) . 'px' );
			}

			return $styles;
		}

		/**
		 * Get shortcode holder data attributes
		 *
		 * @param $params array - shortcode parameters value
		 * @param $atts array - shortcode attributes, used for evergreen countdown unique id
		 *
		 * @return array
		 */
		private function getHolderData( $params, $atts ) {
			$data = array(
				'data-type'          => $params['type'],
				'data-expire-action' => $params['expire_action'],
			);

			if ( 'fixed' === $params['type'] ) {
				$data['data-end'] = $params['end_time'] * 1000;
			} else {
				$data['data-duration'] = $params['duration'];
				$data['data-id']       = substr( md5( wp_json_encode( $atts ) . get_the_ID() ), 0, 12 );
			}

			return $data;
		}
	}
}

EVCCountdown::getInstance();
