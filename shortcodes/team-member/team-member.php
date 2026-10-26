<?php

namespace ExtensiveVC\Shortcodes\EVCTeamMember;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCTeamMember' ) ) {
	class EVCTeamMember extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_team_member' );
			$this->setShortcodeName( esc_html__( 'Team Member', 'extensive-vc-addon' ) );
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
						esc_html__( 'Info Below Image', 'extensive-vc-addon' )   => 'info-below',
						esc_html__( 'Info On Image Hover', 'extensive-vc-addon' ) => 'info-on-hover'
					),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'text_alignment',
					'heading'     => esc_html__( 'Text Alignment', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Center', 'extensive-vc-addon' ) => 'center',
						esc_html__( 'Left', 'extensive-vc-addon' )   => 'left',
						esc_html__( 'Right', 'extensive-vc-addon' )  => 'right'
					)
				),
				array(
					'type'        => 'attach_image',
					'param_name'  => 'image',
					'heading'     => esc_html__( 'Image', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Select image from media library', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'image_size',
					'heading'     => esc_html__( 'Image Size', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Fill your image size (thumbnail, medium, large, full or any registered image size) or enter image size in pixels: 200x100 (width x height). Leave empty to use original image size', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'image', 'not_empty' => true )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'name',
					'heading'     => esc_html__( 'Name', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'position',
					'heading'     => esc_html__( 'Position', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'       => 'textarea',
					'param_name' => 'text',
					'heading'    => esc_html__( 'Text', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'vc_link',
					'param_name'  => 'custom_link',
					'heading'     => esc_html__( 'Custom Link', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Link for image and name, e.g. team member page', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'name_tag',
					'heading'    => esc_html__( 'Name Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'dependency' => array( 'element' => 'name', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'name_color',
					'heading'    => esc_html__( 'Name Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'name', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'position_color',
					'heading'    => esc_html__( 'Position Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'position', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'text_color',
					'heading'    => esc_html__( 'Text Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'text', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'overlay_color',
					'heading'    => esc_html__( 'Overlay Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'type', 'value' => array( 'info-on-hover' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'social_color',
					'heading'    => esc_html__( 'Social Icons Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Social Links', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'social_hover_color',
					'heading'    => esc_html__( 'Social Icons Hover Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Social Links', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'social_size',
					'heading'    => esc_html__( 'Social Icons Size (px)', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Social Links', 'extensive-vc-addon' )
				)
			);

			foreach ( extensive_vc_get_social_icons() as $slug => $icon ) {
				$params[] = array(
					'type'        => 'textfield',
					'param_name'  => 'social_' . $slug,
					'heading'     => $icon['label'],
					'description' => 'email' === $slug ? esc_html__( 'Enter email address', 'extensive-vc-addon' ) : '',
					'group'       => esc_html__( 'Social Links', 'extensive-vc-addon' )
				);
			}

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
				'type'               => 'info-below',
				'text_alignment'     => 'center',
				'image'              => '',
				'image_size'         => 'full',
				'name'               => '',
				'position'           => '',
				'text'               => '',
				'custom_link'        => '',
				'name_tag'           => 'h4',
				'name_color'         => '',
				'position_color'     => '',
				'text_color'         => '',
				'overlay_color'      => '',
				'social_color'       => '',
				'social_hover_color' => '',
				'social_size'        => ''
			);

			foreach ( array_keys( extensive_vc_get_social_icons() ) as $slug ) {
				$args[ 'social_' . $slug ] = '';
			}

			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['type']            = in_array( $params['type'], array( 'info-below', 'info-on-hover' ), true ) ? $params['type'] : $args['type'];
			$params['holder_classes']  = $this->getHolderClasses( $params );
			$params['holder_styles']   = $this->getHolderStyles( $params );
			$params['image_size']      = extensive_vc_get_image_size_value( $params['image_size'] );
			$params['link_attributes'] = extensive_vc_get_custom_link_attributes( $params['custom_link'] );
			$params['name_tag']        = ! empty( $params['name_tag'] ) ? $params['name_tag'] : $args['name_tag'];
			$params['social_links']    = $this->getSocialLinks( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'team-member', 'templates/team-member', '', $params );

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
			$holderClasses[] = 'evc-tm-' . esc_attr( $params['type'] );
			$holderClasses[] = 'evc-tm-align-' . esc_attr( in_array( $params['text_alignment'], array( 'left', 'center', 'right' ), true ) ? $params['text_alignment'] : 'center' );
			$holderClasses[] = ! empty( $params['image'] ) ? 'evc-tm-has-image' : '';

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
				'name_color'         => '--evc-tm-name-color',
				'position_color'     => '--evc-tm-position-color',
				'text_color'         => '--evc-tm-text-color',
				'overlay_color'      => '--evc-tm-overlay-color',
				'social_color'       => '--evc-tm-social-color',
				'social_hover_color' => '--evc-tm-social-hover-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['social_size'] ) {
				$styles[] = '--evc-tm-social-size: ' . absint( $params['social_size'] ) . 'px';
			}

			return $styles;
		}

		/**
		 * Get social links
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getSocialLinks( $params ) {
			$links = array();

			foreach ( extensive_vc_get_social_icons() as $slug => $icon ) {
				$value = trim( (string) $params[ 'social_' . $slug ] );

				if ( '' === $value ) {
					continue;
				}

				if ( 'email' === $slug ) {
					$email = sanitize_email( str_replace( 'mailto:', '', $value ) );
					$url   = is_email( $email ) ? 'mailto:' . $email : '';
				} else {
					$url = esc_url_raw( $value );
				}

				if ( ! empty( $url ) ) {
					$links[ $slug ] = array(
						'url'      => $url,
						'label'    => $icon['label'],
						'external' => 'email' !== $slug,
					);
				}
			}

			return $links;
		}
	}
}

EVCTeamMember::getInstance();
