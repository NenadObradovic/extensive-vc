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
			$params = array(
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
					'param_name' => 'button_text',
					'heading'    => esc_html__( 'Button Text', 'extensive-vc' )
				),
				array(
					'type'       => 'vc_link',
					'param_name' => 'button_custom_link',
					'heading'    => esc_html__( 'Button Custom Link', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_text', 'not_empty' => true )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'button_type',
					'heading'     => esc_html__( 'Type', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Solid', 'extensive-vc' )                       => 'solid',
						esc_html__( 'Outline', 'extensive-vc' )                     => 'outline',
						esc_html__( 'Simple', 'extensive-vc' )                      => 'simple',
						esc_html__( 'Simple Fill Line On Hover', 'extensive-vc' )   => 'fill-line',
						esc_html__( 'Simple Fill Text On Hover', 'extensive-vc' )   => 'fill-text',
						esc_html__( 'Simple Strike Line On Hover', 'extensive-vc' ) => 'strike-line',
						esc_html__( 'Simple Switch Line On Hover', 'extensive-vc' ) => 'switch-line'
					),
					'save_always' => true,
					'dependency'  => array( 'element' => 'button_text', 'not_empty' => true ),
					'group'       => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'button_size',
					'heading'     => esc_html__( 'Size', 'extensive-vc' ),
					'value'       => array(
						esc_html__( 'Large', 'extensive-vc' )  => 'large',
						esc_html__( 'Medium', 'extensive-vc' ) => 'medium',
						esc_html__( 'Normal', 'extensive-vc' ) => 'normal',
						esc_html__( 'Small', 'extensive-vc' )  => 'small',
						esc_html__( 'Tiny', 'extensive-vc' )   => 'tiny'
					),
					'save_always' => true,
					'dependency'  => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
					'group'       => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'button_font_size',
					'heading'    => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_hover_color',
					'heading'    => esc_html__( 'Hover Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_bg_color',
					'heading'    => esc_html__( 'Background Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_hover_bg_color',
					'heading'    => esc_html__( 'Hover Background Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_border_color',
					'heading'    => esc_html__( 'Border Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_hover_border_color',
					'heading'    => esc_html__( 'Hover Border Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'button_border_width',
					'heading'    => esc_html__( 'Border Width (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_line_color',
					'heading'    => esc_html__( 'Line Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value'   => array( 'fill-line', 'strike-line', 'switch-line' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_switch_line_color',
					'heading'    => esc_html__( 'Switch Line Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'button_type', 'value' => array( 'switch-line' ) ),
					'group'      => esc_html__( 'Button Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textarea_html',
					'param_name' => 'content',
					'heading'    => esc_html__( 'Content', 'extensive-vc' ),
					'value'      => '<li>' . esc_html__( 'This is pricing table item content', 'extensive-vc' ) . '</li><li>' . esc_html__( 'This is pricing table item content', 'extensive-vc' ) . '</li><li>' . esc_html__( 'This is pricing table item content', 'extensive-vc' ) . '</li>'
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
				'currency'                  => '$',
				'currency_color'            => '',
				'price_period'              => 'monthly',
				'price_period_color'        => '',
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
				'button_switch_line_color'  => ''
			);
			$params = shortcode_atts( $args, $atts );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			
			$params['title_tag']           = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles']        = $this->getTitleStyles( $params );
			$params['price_styles']        = $this->getPriceStyles( $params );
			$params['price_holder_styles'] = $this->getPriceHolderStyles( $params );
			$params['price_period_styles'] = $this->getPricePeriodStyles( $params );
			$params['currency_styles']     = $this->getCurrencyStyles( $params );
			$params['button_params']       = $this->getButtonParameters( $params );
			
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
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get button shortcode parameters
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getButtonParameters( $params ) {
			$item_params = array();
			$button_text = $params['button_text'];
			$button_link = $params['button_custom_link'];
			
			if ( ! empty( $button_text ) && ! empty( $button_link ) ) {
				$item_params['text'] = esc_attr( $button_text );
				$item_params['link'] = esc_attr( $button_link );
				
				if ( ! empty( $params['button_type'] ) ) {
					$item_params['type'] = esc_attr( $params['button_type'] );
				}
				
				if ( ! empty( $params['button_size'] ) ) {
					$item_params['size'] = esc_attr( $params['button_size'] );
				}
				
				if ( ! empty( $params['button_font_size'] ) ) {
					$item_params['font_size'] = esc_attr( $params['button_font_size'] );
				}
				
				if ( ! empty( $params['button_color'] ) ) {
					$item_params['color'] = esc_attr( $params['button_color'] );
				}
				
				if ( ! empty( $params['button_hover_color'] ) ) {
					$item_params['hover_color'] = esc_attr( $params['button_hover_color'] );
				}
				
				if ( ! empty( $params['button_bg_color'] ) ) {
					$item_params['bg_color'] = esc_attr( $params['button_bg_color'] );
				}
				
				if ( ! empty( $params['button_hover_bg_color'] ) ) {
					$item_params['hover_bg_color'] = esc_attr( $params['button_hover_bg_color'] );
				}
				
				if ( ! empty( $params['button_border_color'] ) ) {
					$item_params['border_color'] = esc_attr( $params['button_border_color'] );
				}
				
				if ( ! empty( $params['button_hover_border_color'] ) ) {
					$item_params['hover_border_color'] = esc_attr( $params['button_hover_border_color'] );
				}
				
				if ( ! empty( $params['button_border_width'] ) ) {
					$item_params['border_width'] = esc_attr( $params['button_border_width'] );
				}
				
				if ( ! empty( $params['button_line_color'] ) ) {
					$item_params['line_color'] = esc_attr( $params['button_line_color'] );
				}
				
				if ( ! empty( $params['button_switch_line_color'] ) ) {
					$item_params['switch_line_color'] = esc_attr( $params['button_switch_line_color'] );
				}
				
				if ( ! empty( $params['button_margin'] ) ) {
					$item_params['margin'] = esc_attr( $params['button_margin'] );
				}
			}
			
			return $item_params;
		}
	}
}

EVCPricingTableItem::getInstance();