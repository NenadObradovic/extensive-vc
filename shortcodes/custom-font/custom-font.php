<?php

namespace ExtensiveVC\Shortcodes\EVCCustomFont;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCCustomFont' ) ) {
	class EVCCustomFont extends Shortcodes\EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private static $instance;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_custom_font' );
			$this->setShortcodeName( esc_html__( 'Custom Font', 'extensive-vc' ) );
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
					'type'       => 'textfield',
					'param_name' => 'title',
					'heading'    => esc_html__( 'Title', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'color',
					'heading'    => esc_html__( 'Color', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'margin',
					'heading'     => esc_html__( 'Margin (px or %)', 'extensive-vc' ),
					'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc' )
				),
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
					'type'       => 'dropdown',
					'param_name' => 'text_decoration',
					'heading'    => esc_html__( 'Text Decoration', 'extensive-vc' ),
					'value'      => array_flip( extensive_vc_get_text_decorations_array( true ) ),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'text_align',
					'heading'    => esc_html__( 'Text Align', 'extensive-vc' ),
					'value'      => array(
						esc_html__( 'Default', 'extensive-vc' ) => '',
						esc_html__( 'Left', 'extensive-vc' )    => 'left',
						esc_html__( 'Center', 'extensive-vc' )  => 'center',
						esc_html__( 'Right', 'extensive-vc' )   => 'right',
						esc_html__( 'Justify', 'extensive-vc' ) => 'justify'
					),
					'group'      => esc_html__( 'Typography Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1440',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1440px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1440',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for 1440px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1366',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1366px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1366',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for 1366px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1280',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for 1280px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1280',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for 1280px screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_1024',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for tablet landscape screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_1024',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for tablet landscape screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_768',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for tablet portrait screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_768',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for tablet portrait screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'font_size_680',
					'heading'     => esc_html__( 'Font Size (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set font size for mobiles screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'line_height_680',
					'heading'     => esc_html__( 'Line Height (px or em)', 'extensive-vc' ),
					'description' => esc_html__( 'Set line height for mobiles screen size', 'extensive-vc' ),
					'group'       => esc_html__( 'Responsive Options', 'extensive-vc' )
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
				'title'            => '',
				'title_tag'        => 'h2',
				'font_family'      => '',
				'font_size'        => '',
				'line_height'      => '',
				'font_weight'      => '',
				'font_style'       => '',
				'letter_spacing'   => '',
				'text_transform'   => '',
				'text_decoration'  => '',
				'color'            => '',
				'text_align'       => '',
				'margin'           => '',
				'font_size_1440'   => '',
				'line_height_1440' => '',
				'font_size_1366'   => '',
				'line_height_1366' => '',
				'font_size_1280'   => '',
				'line_height_1280' => '',
				'font_size_1024'   => '',
				'line_height_1024' => '',
				'font_size_768'    => '',
				'line_height_768'  => '',
				'font_size_680'    => '',
				'line_height_680'  => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );
			
			$params['holder_rand_class'] = 'evc-cf-' . mt_rand( 500, 10000 );
			$params['holder_classes']    = $this->getHolderClasses( $params );
			$params['holder_styles']     = $this->getHolderStyles( $params );
			$params['holder_data']       = $this->getHolderData( $params );
			
			$params['title_tag'] = ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'];
			
			$html = extensive_vc_get_module_template_part( 'shortcodes', 'custom-font', 'templates/custom-font', '', $params );
			
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
			$holderClasses[] = ! empty( $params['holder_rand_class'] ) ? esc_attr( $params['holder_rand_class'] ) : '';
			
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
			
			if ( ! empty( $params['text_decoration'] ) ) {
				$styles[] = 'text-decoration: ' . $params['text_decoration'];
			}
			
			if ( ! empty( $params['text_align'] ) ) {
				$styles[] = 'text-align: ' . $params['text_align'];
			}
			
			if ( ! empty( $params['color'] ) ) {
				$styles[] = 'color: ' . $params['color'];
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
			$data                    = array();
			$data['data-item-class'] = $params['holder_rand_class'];
			
			$laptopLargeFS = $params['font_size_1440'];
			if ( $laptopLargeFS !== '' ) {
				if ( extensive_vc_string_ends_with( $laptopLargeFS, 'px' ) || extensive_vc_string_ends_with( $laptopLargeFS, 'em' ) ) {
					$data['data-font-size-1440'] = $laptopLargeFS;
				} else {
					$data['data-font-size-1440'] = $laptopLargeFS . 'px';
				}
			}
			
			$laptopSmallFS = $params['font_size_1366'];
			if ( $laptopSmallFS !== '' ) {
				if ( extensive_vc_string_ends_with( $laptopSmallFS, 'px' ) || extensive_vc_string_ends_with( $laptopSmallFS, 'em' ) ) {
					$data['data-font-size-1366'] = $laptopSmallFS;
				} else {
					$data['data-font-size-1366'] = $laptopSmallFS . 'px';
				}
			}
			
			$laptopMacFS = $params['font_size_1280'];
			if ( $laptopMacFS !== '' ) {
				if ( extensive_vc_string_ends_with( $laptopMacFS, 'px' ) || extensive_vc_string_ends_with( $laptopMacFS, 'em' ) ) {
					$data['data-font-size-1280'] = $laptopMacFS;
				} else {
					$data['data-font-size-1280'] = $laptopMacFS . 'px';
				}
			}
			
			$tabletLandscapeFS = $params['font_size_1024'];
			if ( $tabletLandscapeFS !== '' ) {
				if ( extensive_vc_string_ends_with( $tabletLandscapeFS, 'px' ) || extensive_vc_string_ends_with( $tabletLandscapeFS, 'em' ) ) {
					$data['data-font-size-1024'] = $tabletLandscapeFS;
				} else {
					$data['data-font-size-1024'] = $tabletLandscapeFS . 'px';
				}
			}
			
			$tabletPortraitFS = $params['font_size_768'];
			if ( $tabletPortraitFS !== '' ) {
				if ( extensive_vc_string_ends_with( $tabletPortraitFS, 'px' ) || extensive_vc_string_ends_with( $tabletPortraitFS, 'em' ) ) {
					$data['data-font-size-768'] = $tabletPortraitFS;
				} else {
					$data['data-font-size-768'] = $tabletPortraitFS . 'px';
				}
			}
			
			$mobilesFS = $params['font_size_680'];
			if ( $mobilesFS !== '' ) {
				if ( extensive_vc_string_ends_with( $mobilesFS, 'px' ) || extensive_vc_string_ends_with( $mobilesFS, 'em' ) ) {
					$data['data-font-size-680'] = $mobilesFS;
				} else {
					$data['data-font-size-680'] = $mobilesFS . 'px';
				}
			}
			
			$laptopLargeLH = $params['line_height_1440'];
			if ( $laptopLargeLH !== '' ) {
				if ( extensive_vc_string_ends_with( $laptopLargeLH, 'px' ) || extensive_vc_string_ends_with( $laptopLargeLH, 'em' ) ) {
					$data['data-line-height-1440'] = $laptopLargeLH;
				} else {
					$data['data-line-height-1440'] = $laptopLargeLH . 'px';
				}
			}
			
			$laptopSmallLH = $params['line_height_1366'];
			if ( $laptopSmallLH !== '' ) {
				if ( extensive_vc_string_ends_with( $laptopSmallLH, 'px' ) || extensive_vc_string_ends_with( $laptopSmallLH, 'em' ) ) {
					$data['data-line-height-1366'] = $laptopSmallLH;
				} else {
					$data['data-line-height-1366'] = $laptopSmallLH . 'px';
				}
			}
			
			$laptopMacLH = $params['line_height_1280'];
			if ( $laptopMacLH !== '' ) {
				if ( extensive_vc_string_ends_with( $laptopMacLH, 'px' ) || extensive_vc_string_ends_with( $laptopMacLH, 'em' ) ) {
					$data['data-line-height-1280'] = $laptopMacLH;
				} else {
					$data['data-line-height-1280'] = $laptopMacLH . 'px';
				}
			}
			
			$tabletLandscapeLH = $params['line_height_1024'];
			if ( $tabletLandscapeLH !== '' ) {
				if ( extensive_vc_string_ends_with( $tabletLandscapeLH, 'px' ) || extensive_vc_string_ends_with( $tabletLandscapeLH, 'em' ) ) {
					$data['data-line-height-1024'] = $tabletLandscapeLH;
				} else {
					$data['data-line-height-1024'] = $tabletLandscapeLH . 'px';
				}
			}
			
			$tabletPortraitLH = $params['line_height_768'];
			if ( $tabletPortraitLH !== '' ) {
				if ( extensive_vc_string_ends_with( $tabletPortraitLH, 'px' ) || extensive_vc_string_ends_with( $tabletPortraitLH, 'em' ) ) {
					$data['data-line-height-768'] = $tabletPortraitLH;
				} else {
					$data['data-line-height-768'] = $tabletPortraitLH . 'px';
				}
			}
			
			$mobilesLH = $params['line_height_680'];
			if ( $mobilesLH !== '' ) {
				if ( extensive_vc_string_ends_with( $mobilesLH, 'px' ) || extensive_vc_string_ends_with( $mobilesLH, 'em' ) ) {
					$data['data-line-height-680'] = $mobilesLH;
				} else {
					$data['data-line-height-680'] = $mobilesLH . 'px';
				}
			}
			
			return $data;
		}
	}
}

EVCCustomFont::getInstance();