<?php

namespace ExtensiveVC\Shortcodes\EVCButton;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCButton' ) ) {
	class EVCButton extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_button' );
			$this->setShortcodeName( esc_html__( 'Button', 'extensive-vc' ) );
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
			$params = array_merge(
				array(
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
							esc_html__( 'Solid', 'extensive-vc' )                       => 'solid',
							esc_html__( 'Outline', 'extensive-vc' )                     => 'outline',
							esc_html__( 'Simple', 'extensive-vc' )                      => 'simple',
							esc_html__( 'Simple Fill Line On Hover', 'extensive-vc' )   => 'fill-line',
							esc_html__( 'Simple Fill Text On Hover', 'extensive-vc' )   => 'fill-text',
							esc_html__( 'Simple Strike Line On Hover', 'extensive-vc' ) => 'strike-line',
							esc_html__( 'Simple Switch Line On Hover', 'extensive-vc' ) => 'switch-line'
						),
						'save_always' => true,
						'admin_label' => true
					),
					array(
						'type'        => 'dropdown',
						'param_name'  => 'size',
						'heading'     => esc_html__( 'Size', 'extensive-vc' ),
						'value'       => array(
							esc_html__( 'Large', 'extensive-vc' )  => 'large',
							esc_html__( 'Medium', 'extensive-vc' ) => 'medium',
							esc_html__( 'Normal', 'extensive-vc' ) => 'normal',
							esc_html__( 'Small', 'extensive-vc' )  => 'small',
							esc_html__( 'Tiny', 'extensive-vc' )   => 'tiny'
						),
						'save_always' => true,
						'admin_label' => true,
						'dependency'  => array( 'element' => 'type', 'value'   => array( 'solid', 'outline' ) )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'text',
						'heading'    => esc_html__( 'Text', 'extensive-vc' )
					),
					array(
						'type'       => 'vc_link',
						'param_name' => 'custom_link',
						'heading'    => esc_html__( 'Custom Link', 'extensive-vc' )
					)
				),
				extensive_vc_get_shortcode_icon_options_array(),
				array(
					array(
						'type'       => 'textfield',
						'param_name' => 'font_family',
						'heading'    => esc_html__( 'Font Family', 'extensive-vc' ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'font_size',
						'heading'    => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'line_height',
						'heading'    => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'font_weight',
						'heading'    => esc_html__( 'Font Weight', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_font_weight_array( true ) ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'font_style',
						'heading'    => esc_html__( 'Font Style', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_font_style_array( true ) ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'letter_spacing',
						'heading'    => esc_html__( 'Letter Spacing (px or em)', 'extensive-vc' ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'text_transform',
						'heading'    => esc_html__( 'Text Transform', 'extensive-vc' ),
						'value'      => array_flip( extensive_vc_get_text_transform_array( true ) ),
						'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'color',
						'heading'    => esc_html__( 'Color', 'extensive-vc' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'hover_color',
						'heading'    => esc_html__( 'Hover Color', 'extensive-vc' ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'bg_color',
						'heading'    => esc_html__( 'Background Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value' => array( 'solid' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'hover_bg_color',
						'heading'    => esc_html__( 'Hover Background Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value'   => array( 'solid', 'outline' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'border_color',
						'heading'    => esc_html__( 'Border Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value'   => array( 'solid', 'outline' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'hover_border_color',
						'heading'    => esc_html__( 'Hover Border Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value'   => array( 'solid', 'outline' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'textfield',
						'param_name' => 'border_width',
						'heading'    => esc_html__( 'Border Width (px)', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value'   => array( 'solid', 'outline' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'line_color',
						'heading'    => esc_html__( 'Line Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value'   => array( 'fill-line', 'strike-line', 'switch-line' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'colorpicker',
						'param_name' => 'switch_line_color',
						'heading'    => esc_html__( 'Switch Line Color', 'extensive-vc' ),
						'dependency' => array( 'element' => 'type', 'value' => array( 'switch-line' ) ),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'        => 'textfield',
						'param_name'  => 'margin',
						'heading'     => esc_html__( 'Margin', 'extensive-vc' ),
						'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc' ),
						'group'       => esc_html__( 'Design Options', 'extensive-vc' )
					),
					array(
						'type'       => 'dropdown',
						'param_name' => 'button_alignment',
						'heading'    => esc_html__( 'Button Alignment', 'extensive-vc' ),
						'value'      => array(
							esc_html__( 'Default', 'extensive-vc' ) => '',
							esc_html__( 'Left', 'extensive-vc' )    => 'left',
							esc_html__( 'Right', 'extensive-vc' )   => 'right',
							esc_html__( 'Center', 'extensive-vc' )  => 'center'
						),
						'group'      => esc_html__( 'Design Options', 'extensive-vc' )
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
				'custom_class'       => '',
				'type'               => 'solid',
				'size'               => 'normal',
				'text'               => '',
				'custom_link'        => '',
				'icon_library'       => '',
				'icon_fontawesome'   => '',
				'icon_openiconic'    => '',
				'icon_typicons'      => '',
				'icon_entypo'        => '',
				'icon_linecons'      => '',
				'icon_monosocial'    => '',
				'icon_material'      => '',
				'font_family'        => '',
				'font_size'          => '',
				'line_height'        => '',
				'font_weight'        => '',
				'font_style'         => '',
				'letter_spacing'     => '',
				'text_transform'     => '',
				'color'              => '',
				'hover_color'        => '',
				'bg_color'           => '',
				'hover_bg_color'     => '',
				'border_color'       => '',
				'hover_border_color' => '',
				'border_width'       => '',
				'line_color'         => '',
				'switch_line_color'  => '',
				'margin'             => '',
				'button_alignment'   => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['button_has_alignment'] = isset( $params['button_alignment'] ) && ! empty( $params['button_alignment'] );
			
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			$params['holder_data']    = $this->getHolderData( $params );
			
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'] );
			
			$params['fill_line_styles']            = $this->getFillLineStyles( $params );
			$params['fill_text_original_styles']   = $this->getFillTextOriginalStyles( $params );
			$params['fill_text_hover_styles']      = $this->getFillTextHoverStyles( $params );
			$params['strike_line_styles']          = $this->getStrikeLineStyles( $params );
			$params['switch_line_original_styles'] = $this->getSwitchLineOriginalStyles( $params );
			$params['switch_line_hover_styles']    = $this->getSwitchLineHoverStyles( $params );
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'button', 'templates/button', $params['type'], $params );
			
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
			$holderClasses[] = ! empty( $params['type'] ) ? 'evc-btn-' . $params['type'] : '';
			$holderClasses[] = ! empty( $params['size'] ) ? 'evc-btn-' . $params['size'] : '';
			$holderClasses[] = ! empty( $params['icon_library'] ) ? 'evc-btn-has-icon' : '';
			
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
			
			if ( $params['font_family'] !== '' ) {
				$styles[] = 'font-family: ' . $params['font_family'];
			}
			
			if ( ! empty( $params['font_size'] ) ) {
				if ( extensive_vc_string_ends_with( $params['font_size'], 'px' ) || extensive_vc_string_ends_with( $params['font_size'], 'em' ) ) {
					$styles[] = 'font-size: ' . $params['font_size'];
				} else {
					$styles[] = 'font-size: ' . $params['font_size'] . 'px';
				}
			}
			
			if ( ! empty( $params['line_height'] ) ) {
				if ( extensive_vc_string_ends_with( $params['line_height'], 'px' ) || extensive_vc_string_ends_with( $params['line_height'], 'em' ) ) {
					$styles[] = 'line-height: ' . $params['line_height'];
				} else {
					$styles[] = 'line-height: ' . $params['line_height'] . 'px';
				}
			}
			
			if ( ! empty( $params['font_weight'] ) ) {
				$styles[] = 'font-weight: ' . $params['font_weight'];
			}
			
			if ( ! empty( $params['font_style'] ) ) {
				$styles[] = 'font-style: ' . $params['font_style'];
			}
			
			if ( ! empty( $params['letter_spacing'] ) ) {
				if ( extensive_vc_string_ends_with( $params['letter_spacing'], 'px' ) || extensive_vc_string_ends_with( $params['letter_spacing'], 'em' ) ) {
					$styles[] = 'letter-spacing: ' . $params['letter_spacing'];
				} else {
					$styles[] = 'letter-spacing: ' . $params['letter_spacing'] . 'px';
				}
			}
			
			if ( ! empty( $params['text_transform'] ) ) {
				$styles[] = 'text-transform: ' . $params['text_transform'];
			}
			
			if ( ! empty( $params['color'] ) ) {
				$styles[] = 'color: ' . $params['color'];
			}
			
			if ( ! empty( $params['bg_color'] ) ) {
				$styles[] = 'background-color: ' . $params['bg_color'];
			}
			
			if ( ! empty( $params['border_color'] ) ) {
				$styles[] = 'border-color: ' . $params['border_color'];
			}
			
			if ( ! empty( $params['border_width'] ) ) {
				$styles[] = 'border-width: ' . extensive_vc_filter_px( $params['border_width'] ) . 'px';
			}
			
			if ( $params['margin'] !== '' ) {
				$styles[] = 'margin: ' . $params['margin'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get shortcode holder data
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getHolderData( $params ) {
			$data = array();
			
			if ( ! empty( $params['hover_color'] ) ) {
				$data['data-hover-color'] = $params['hover_color'];
			}
			
			if ( ! empty( $params['hover_bg_color'] ) ) {
				$data['data-hover-background-color'] = $params['hover_bg_color'];
			}
			
			if ( ! empty( $params['hover_border_color'] ) ) {
				$data['data-hover-border-color'] = $params['hover_border_color'];
			}
			
			return $data;
		}
		
		/**
		 * Get fill line styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getFillLineStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['line_color'] ) ) {
				$styles[] = 'border-bottom-color: ' . $params['line_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get original fill text styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getFillTextOriginalStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['color'] ) ) {
				$styles[] = 'color: ' . $params['color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get hover fill text styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getFillTextHoverStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['hover_color'] ) ) {
				$styles[] = 'color: ' . $params['hover_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get strike line styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getStrikeLineStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['line_color'] ) ) {
				$styles[] = 'background-color: ' . $params['line_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get original switch line styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getSwitchLineOriginalStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['line_color'] ) ) {
				$styles[] = 'border-bottom-color: ' . $params['line_color'];
			}
			
			return implode( ';', $styles );
		}
		
		/**
		 * Get hover switch line styles
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return string
		 */
		private function getSwitchLineHoverStyles( $params ) {
			$styles = array();
			
			if ( ! empty( $params['switch_line_color'] ) ) {
				$styles[] = 'border-bottom-color: ' . $params['switch_line_color'];
			}
			
			return implode( ';', $styles );
		}
	}
}

EVCButton::getInstance();