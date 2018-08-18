<?php

namespace ExtensiveVC\Shortcodes\EVCPricingTable;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCPricingTableItem' ) ) {
	class EVCPricingTableItem extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_pricing_table_item' );
			$this->setParentBase( 'evc_pricing_table' );
			$this->setShortcodeName( esc_html__( 'Pricing Table Item', 'extensive-vc' ) );
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
			if ( self::$instance == null ) {
				return new self;
			}
			
			return self::$instance;
		}
		
		/**
		 * Set shortcode parameters for Visual Composer shortcodes options panel
		 */
		function shortcodeParameters() {
			$params = array_merge(
				array(
					array(
						'type'        => 'textfield',
						'param_name'  => 'custom_class',
						'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc' ),
						'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'title',
						'heading'    => esc_html__( 'Title', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'title_tag',
						'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
						'dependency' => array( 'element' => 'title', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'title_color',
						'heading'    => esc_html__( 'Title Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'title', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'price',
						'heading'    => esc_html__( 'Price', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'price_bg_color',
						'heading'    => esc_html__( 'Price Background Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'price', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'attach_image',
						'param_name' => 'price_bg_image',
						'heading'    => esc_html__( 'Price Background Image', 'extensive-vc' ),
						'dependency' => array( 'element' => 'price', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'price_color',
						'heading'    => esc_html__( 'Price Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'price', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'price_size',
						'heading'    => esc_html__( 'Price Size (px)', 'extensive-vc' ),
						'dependency' => array( 'element' => 'price', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'currency',
						'heading'     => esc_html__( 'Currency', 'extensive-vc' ),
						'description' => esc_html__( 'Default mark is $', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'currency_color',
						'heading'    => esc_html__( 'Currency Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'currency', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'currency_size',
						'heading'    => esc_html__( 'Currency Size (px)', 'extensive-vc' ),
						'dependency' => array( 'element' => 'currency', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'price_period',
						'heading'     => esc_html__( 'Price Period', 'extensive-vc' ),
						'description' => esc_html__( 'Default label is monthly', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'price_period_color',
						'heading'    => esc_html__( 'Price Period Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'price_period', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'price_period_size',
						'heading'    => esc_html__( 'Price Period Size (px)', 'extensive-vc' ),
						'dependency' => array( 'element' => 'price_period', 'not_empty' => true ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'button_text',
						'heading'    => esc_html__( 'Button Text', 'extensive-vc' )
					)
				),
				extensive_vc_get_button_shortcode_options_array(),
				array(
					array(
						'type'       => 'textarea_html',
						'param_name' => 'content',
						'heading'    => esc_html__( 'Content', 'extensive-vc' ),
						'value'      => '<li>' . esc_html__( 'This is pricing table item content', 'extensive-vc' ) . '</li><li>' . esc_html__( 'This is pricing table item content', 'extensive-vc' ) . '</li><li>' . esc_html__( 'This is pricing table item content', 'extensive-vc' ) . '</li>'
					)
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
				'custom_class'              => '',
				'title'                     => '',
				'title_tag'                 => 'h4',
				'title_color'               => '',
				'price'                     => '129',
				'price_bg_color'            => '',
				'price_bg_image'	        => '',
				'price_color'               => '',
				'price_size'                => '',
				'currency'                  => '$',
				'currency_color'            => '',
				'currency_size'             => '',
				'price_period'              => 'monthly',
				'price_period_color'        => '',
				'price_period_size'         => '',
				'button_text'               => '',
				'button_custom_link'        => '',
				'button_type'               => '',
				'button_size'               => '',
				'button_font_size'          => '',
				'button_color'              => '',
				'button_hover_color'        => '',
				'button_bg_color'           => '',
				'button_hover_bg_color'     => '',
				'button_border_color'       => '',
				'button_hover_border_color' => '',
				'button_border_width'       => '',
				'button_line_color'         => '',
				'button_switch_line_color'  => '',
				'button_margin'             => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			
			$params['title_tag']           = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles']        = $this->getTitleStyles( $params );
			$params['price_styles']        = $this->getPriceStyles( $params );
			$params['price_holder_styles'] = $this->getPriceHolderStyles( $params );
			$params['currency_styles']     = $this->getCurrencyStyles( $params );
			$params['price_period_styles'] = $this->getPricePeriodStyles( $params );
			$params['button_params']       = extensive_vc_get_button_shortcode_params( $params );
			
			$params['content'] = $content;
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'pricing-table', 'templates/pricing-table-item', '', $params );
			
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
			$holderClasses[] = ! empty( $params['price_bg_image'] ) ? 'evc-pti-has-image' : '';
			
			return implode( ' ', $holderClasses );
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
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get price styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getPriceStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['price_color'] ) ) {
				$styles[] = 'color: ' . $params['price_color'];
			}
			
			if ( ! empty( $params['price_size'] ) ) {
				$styles[] = 'font-size: ' . extensive_vc_filter_px( $params['price_size'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get price holder styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getPriceHolderStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['price_bg_color'] ) ) {
				$styles[] = 'background-color: ' . $params['price_bg_color'];
			}
			
			if ( ! empty( $params['price_bg_image'] ) ) {
				$styles[] = 'background-image: url(' . wp_get_attachment_image_url( $params['price_bg_image'], 'full' ) . ')';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get currency styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getCurrencyStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['currency_color'] ) ) {
				$styles[] = 'color: ' . $params['currency_color'];
			}
			
			if ( ! empty( $params['currency_size'] ) ) {
				$styles[] = 'font-size: ' . extensive_vc_filter_px( $params['currency_size'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get price period styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getPricePeriodStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['price_period_color'] ) ) {
				$styles[] = 'color: ' . $params['price_period_color'];
			}
			
			if ( ! empty( $params['price_period_size'] ) ) {
				$styles[] = 'font-size: ' . extensive_vc_filter_px( $params['price_period_size'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCPricingTableItem::getInstance();