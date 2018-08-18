<?php

namespace ExtensiveVC\Shortcodes\EVCProgressBar;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCProgressBar' ) ) {
	class EVCProgressBar extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_progress_bar' );
			$this->setShortcodeName( esc_html__( 'Progress Bar', 'extensive-vc' ) );
			$this->setShortcodeParameters( $this->shortcodeParameters() );
			
			// Parent constructor need to be loaded after setter's method initialization
			parent::__construct();
			
			// Additional methods need to be loaded after parent constructor loaded if we used methods from the parent class
			if ( $this->getIsShortcodeEnabled() ) {
				add_action( 'extensive_vc_enqueue_additional_scripts_before_main_js', array( $this, 'enqueueShortcodeAdditionalScripts' ) );
			}
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
		 * Enqueue necessary 3rd party scripts for this shortcode
		 */
		function enqueueShortcodeAdditionalScripts() {
			wp_enqueue_script( 'counter', EXTENSIVE_VC_SHORTCODES_URL_PATH . '/progress-bar/assets/js/plugins/counter.js', array( 'jquery' ), false, true );
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
					'param_name'  => 'type',
					'heading'     => esc_html__( 'Type', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Horizontal', 'extensive-vc' ) => 'horizontal',
						esc_html__( 'Vertical', 'extensive-vc' )   => 'vertical'
					),
					'save_always' => true,
					'admin_label' => true
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'percent',
					'heading'    => esc_html__( 'Percentage', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'percent_color',
					'heading'    => esc_html__( 'Percentage Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'percent', 'not_empty' => true ),
					'group'      => esc_html__( 'Percentage Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'title_bottom_margin',
					'heading'    => esc_html__( 'Title Bottom Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Title Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'active_bar_color',
					'heading'    => esc_html__( 'Active Bar Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Bar Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'inactive_bar_color',
					'heading'    => esc_html__( 'Inactive Bar Color', 'extensive-vc' ),
					'group'      => esc_html__( 'Bar Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'bar_height',
					'heading'    => esc_html__( 'Bar Height (px)', 'extensive-vc' ),
					'group'      => esc_html__( 'Bar Settings', 'extensive-vc' )
				)
			);
			
			return $params;
		}
		
		/**
		 * Renders shortcodes HTML
		 *
		 * @param $atts array - shortcode params
		 * @param $content string - shortcode content
		 *
		 * @return html
		 */
		function render( $atts, $content = null ) {
			$args   = array(
				'custom_class'        => '',
				'type'                => 'horizontal',
				'percent'             => '100',
				'percent_color'       => '',
				'title'               => '',
				'title_tag'           => 'h6',
				'title_color'         => '',
				'title_bottom_margin' => '',
				'active_bar_color'    => '',
				'inactive_bar_color'  => '',
				'bar_height'          => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes']    = $this->getHolderClasses( $params, $args );
			$params['progress_bar_data'] = $this->getProgressBarData( $params );
			
			$params['percent_styles'] = $this->getPercentStyles( $params );
			$params['title_tag']      = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles']   = $this->getTitleStyles( $params );
			
			$params['active_bar_styles']   = $this->getActiveColor( $params );
			$params['inactive_bar_styles'] = $this->getInactiveColor( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'progress-bar', 'templates/progress-bar', $params['type'], $params );
			
			return $html;
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-pb-' . $params['type'] : 'evc-pb-' . $args['type'];
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode progress bar data
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getProgressBarData( $params ) {
			$data = array();
			
			$data['data-percentage'] = ! empty( $params['percent'] ) ? $params['percent'] : '';
			
			return $data;
		}
		
		/**
		 * Get percent styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getPercentStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['percent_color'] ) ) {
				$styles[] = 'color: ' . $params['percent_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get title styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getTitleStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['title_color'] ) ) {
				$styles[] = 'color: ' . $params['title_color'];
			}
			
			if ( $params['title_bottom_margin'] !== '' ) {
				$styles[] = 'margin-bottom: ' . extensive_vc_filter_px( $params['title_bottom_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get active bar styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getActiveColor( $params ) {
			$styles = array();
			
			if ( ! empty( $params['active_bar_color'] ) ) {
				$styles[] = 'background-color: ' . $params['active_bar_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get inactive bar styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getInactiveColor( $params ) {
			$styles = array();
			
			if ( ! empty( $params['inactive_bar_color'] ) ) {
				$styles[] = 'background-color: ' . $params['inactive_bar_color'];
			}
			
			if ( ! empty( $params['bar_height'] ) ) {
				$styles[] = 'height: ' . extensive_vc_filter_px( $params['bar_height'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCProgressBar::getInstance();