<?php

namespace ExtensiveVC\Shortcodes\EVCSectionTitle;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCSectionTitle' ) ) {
	class EVCSectionTitle extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
	
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_section_title' );
			$this->setShortcodeName( esc_html__( 'Section Title', 'extensive-vc' ) );
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
					'type'       => 'dropdown',
					'param_name' => 'text_alignment',
					'heading'    => esc_html__( 'Text Alignment', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc' ) => 'top',
						esc_html__( 'Left', 'extensive-vc' )    => 'left',
						esc_html__( 'Center', 'extensive-vc' )  => 'center',
						esc_html__( 'Right', 'extensive-vc' )   => 'right'
					)
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
					'value'      => array_flip( extensive_vc_get_title_tag_array( true ) ),
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
					'type'       => 'dropdown',
					'param_name' => 'enable_separator',
					'heading'    => esc_html__( 'Enable Separator', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false ) )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'separator_color',
					'heading'    => esc_html__( 'Separator Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'enable_separator', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Separator Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'separator_width',
					'heading'    => esc_html__( 'Separator Width (px or %)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'enable_separator', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Separator Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'separator_thickness',
					'heading'    => esc_html__( 'Separator Thickness (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'enable_separator', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Separator Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'separator_top_margin',
					'heading'    => esc_html__( 'Separator Top Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'enable_separator', 'value' => array( 'yes' ) ),
					'group'      => esc_html__( 'Separator Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'text_tag',
					'heading'    => esc_html__( 'Text Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Text Options', 'extensive-vc' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'text_color',
					'heading'    => esc_html__( 'Text Color', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Text Options', 'extensive-vc' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'text_top_margin',
					'heading'    => esc_html__( 'Text Top Margin (px)', 'extensive-vc' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Text Options', 'extensive-vc' )
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
					'dependency' => array(
						'element' => 'button_type',
						'value'   => array( 'fill-line', 'strike-line', 'switch-line' )
					),
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
					'type'        => 'textfield',
					'param_name'  => 'button_margin',
					'heading'     => esc_html__( 'Margin', 'extensive-vc' ),
					'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc' ),
					'dependency'  => array( 'element' => 'button_text', 'not_empty' => true ),
					'group'       => esc_html__( 'Button Options', 'extensive-vc' )
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
				'text_alignment'            => '',
				'title'                     => '',
				'title_tag'                 => 'h2',
				'title_color'               => '',
				'enable_separator'          => 'no',
				'separator_color'           => '',
				'separator_width'           => '',
				'separator_thickness'       => '',
				'separator_top_margin'      => '',
				'text'                      => '',
				'text_tag'                  => 'p',
				'text_color'                => '',
				'text_top_margin'           => '',
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
			$params = shortcode_atts( $args, $atts );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			
			$params['title_tag']        = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			$params['title_styles']     = $this->getTitleStyles( $params );
			$params['separator_styles'] = $this->getSeparatorStyles( $params );
			$params['text_tag']         = ! empty( $params['text_tag'] ) ? $params['text_tag'] : $args['text_tag'];
			$params['text_styles']      = $this->getTextStyles( $params );
			$params['button_params']    = $this->getButtonParameters( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'section-title', 'templates/section-title', '', $params );
			
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
			
			return implode( ' ', $holderClasses );
		}
		
		/**
		 * Get shortcode holder styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getHolderStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['text_alignment'] ) ) {
				$styles[] = 'text-align: ' . $params['text_alignment'];
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
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get separator styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getSeparatorStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['separator_color'] ) ) {
				$styles[] = 'background-color: ' . $params['separator_color'];
			}
			
			if ( $params['separator_width'] !== '' ) {
				if ( extensive_vc_string_ends_with( $params['separator_width'], '%' ) || extensive_vc_string_ends_with( $params['separator_width'], 'px' ) ) {
					$styles[] = 'width: ' . $params['separator_width'];
				} else {
					$styles[] = 'width: ' . extensive_vc_filter_px( $params['separator_width'] ) . 'px';
				}
			}
			
			if ( $params['separator_thickness'] !== '' ) {
				$styles[] = 'height: ' . extensive_vc_filter_px( $params['separator_thickness'] ) . 'px';
			}
			
			if ( $params['separator_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['separator_top_margin'] ) . 'px';
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get text styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getTextStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['text_color'] ) ) {
				$styles[] = 'color: ' . $params['text_color'];
			}
			
			if ( $params['text_top_margin'] !== '' ) {
				$styles[] = 'margin-top: ' . extensive_vc_filter_px( $params['text_top_margin'] ) . 'px';
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

EVCSectionTitle::getInstance();