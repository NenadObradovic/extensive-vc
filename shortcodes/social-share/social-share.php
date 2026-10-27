<?php

namespace ExtensiveVC\Shortcodes\EVCSocialShare;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCSocialShare' ) ) {
	class EVCSocialShare extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_social_share' );
			$this->setShortcodeName( esc_html__( 'Social Share', 'extensive-vc-addon' ) );
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
		 * Get share networks, share links are plain links so no 3rd party scripts are loaded
		 *
		 * @return array - network slug => label and share url pattern (%url%, %title% and %image% are replaced with encoded values)
		 */
		private function getNetworks() {
			return apply_filters(
				'extensive_vc_filter_social_share_networks',
				array(
					'facebook'  => array(
						'label' => 'Facebook',
						'url'   => 'https://www.facebook.com/sharer/sharer.php?u=%url%',
					),
					'x'         => array(
						'label' => 'X',
						'url'   => 'https://x.com/intent/post?url=%url%&text=%title%',
					),
					'linkedin'  => array(
						'label' => 'LinkedIn',
						'url'   => 'https://www.linkedin.com/sharing/share-offsite/?url=%url%',
					),
					'pinterest' => array(
						'label' => 'Pinterest',
						'url'   => 'https://pinterest.com/pin/create/button/?url=%url%&media=%image%&description=%title%',
					),
					'whatsapp'  => array(
						'label' => 'WhatsApp',
						'url'   => 'https://api.whatsapp.com/send?text=%title%%20%url%',
					),
					'telegram'  => array(
						'label' => 'Telegram',
						'url'   => 'https://t.me/share/url?url=%url%&text=%title%',
					),
					'reddit'    => array(
						'label' => 'Reddit',
						'url'   => 'https://www.reddit.com/submit?url=%url%&title=%title%',
					),
					'email'     => array(
						'label' => esc_html__( 'Email', 'extensive-vc-addon' ),
						'url'   => 'mailto:?subject=%title%&body=%url%',
					),
				)
			);
		}

		/**
		 * Set shortcode parameters for WPBakery Page Builder shortcodes options panel
		 */
		function shortcodeParameters() {
			$networks = array();

			foreach ( $this->getNetworks() as $slug => $network ) {
				$networks[ $network['label'] ] = $slug;
			}

			$networks[ esc_html__( 'Copy Link', 'extensive-vc-addon' ) ]                  = 'copy';
			$networks[ esc_html__( 'Device Share Menu (mobile)', 'extensive-vc-addon' ) ] = 'native';

			$params = array(
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_class',
					'heading'     => esc_html__( 'Custom CSS Class', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Style particular content element differently - add a class name and refer to it in custom CSS', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'checkbox',
					'param_name'  => 'networks',
					'heading'     => esc_html__( 'Share Buttons', 'extensive-vc-addon' ),
					'value'       => $networks,
					'std'         => 'facebook,x,linkedin,whatsapp,email,copy',
					'description' => esc_html__( 'Device share menu button is displayed only in browsers which support it (mostly on mobile devices)', 'extensive-vc-addon' ),
					'admin_label' => true
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'share_url',
					'heading'    => esc_html__( 'Shared Link', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Current Page', 'extensive-vc-addon' ) => 'current',
						esc_html__( 'Custom Link', 'extensive-vc-addon' )  => 'custom'
					)
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'custom_url',
					'heading'    => esc_html__( 'Custom Link', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'share_url', 'value' => array( 'custom' ) )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'custom_title',
					'heading'     => esc_html__( 'Shared Title', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Leave empty to use current page title', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'title',
					'heading'     => esc_html__( 'Title', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Text displayed before share buttons, e.g. Share:', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'layout',
					'heading'    => esc_html__( 'Layout', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Icons', 'extensive-vc-addon' )            => 'icons',
						esc_html__( 'Icons With Labels', 'extensive-vc-addon' ) => 'icons-labels'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'skin',
					'heading'    => esc_html__( 'Skin', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Brand Colors', 'extensive-vc-addon' ) => 'brand',
						esc_html__( 'Outline', 'extensive-vc-addon' )      => 'outline',
						esc_html__( 'Minimal', 'extensive-vc-addon' )      => 'minimal'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'shape',
					'heading'    => esc_html__( 'Shape', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Square', 'extensive-vc-addon' )  => 'square',
						esc_html__( 'Rounded', 'extensive-vc-addon' ) => 'rounded',
						esc_html__( 'Circle', 'extensive-vc-addon' )  => 'circle'
					),
					'dependency' => array( 'element' => 'skin', 'value' => array( 'brand', 'outline' ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'size',
					'heading'    => esc_html__( 'Size', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Normal', 'extensive-vc-addon' ) => 'normal',
						esc_html__( 'Small', 'extensive-vc-addon' )  => 'small',
						esc_html__( 'Large', 'extensive-vc-addon' )  => 'large'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'alignment',
					'heading'    => esc_html__( 'Alignment', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Left', 'extensive-vc-addon' )   => 'left',
						esc_html__( 'Center', 'extensive-vc-addon' ) => 'center',
						esc_html__( 'Right', 'extensive-vc-addon' )  => 'right'
					)
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'copy_label',
					'heading'     => esc_html__( 'Copy Link Label', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is Copy Link', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Labels', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'copied_label',
					'heading'     => esc_html__( 'Link Copied Message', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is Link copied', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Labels', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'textfield',
					'param_name'  => 'native_label',
					'heading'     => esc_html__( 'Device Share Menu Label', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Default value is Share', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Labels', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'title', 'not_empty' => true ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'button_color',
					'heading'     => esc_html__( 'Buttons Color', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Leave empty to use brand colors', 'extensive-vc-addon' ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'button_hover_color',
					'heading'    => esc_html__( 'Buttons Hover Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'        => 'colorpicker',
					'param_name'  => 'icon_color',
					'heading'     => esc_html__( 'Icons Color', 'extensive-vc-addon' ),
					'description' => esc_html__( 'Icons and labels color for brand colors skin', 'extensive-vc-addon' ),
					'dependency'  => array( 'element' => 'skin', 'value' => array( 'brand' ) ),
					'group'       => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'textfield',
					'param_name' => 'space_between_items',
					'heading'    => esc_html__( 'Space Between Buttons (px)', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
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
				'custom_class'        => '',
				'networks'            => 'facebook,x,linkedin,whatsapp,email,copy',
				'share_url'           => 'current',
				'custom_url'          => '',
				'custom_title'        => '',
				'title'               => '',
				'layout'              => 'icons',
				'skin'                => 'brand',
				'shape'               => 'square',
				'size'                => 'normal',
				'alignment'           => 'left',
				'copy_label'          => '',
				'copied_label'        => '',
				'native_label'        => '',
				'title_color'         => '',
				'button_color'        => '',
				'button_hover_color'  => '',
				'icon_color'          => '',
				'space_between_items' => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['layout']    = 'icons-labels' === $params['layout'] ? 'icons-labels' : 'icons';
			$params['skin']      = in_array( $params['skin'], array( 'brand', 'outline', 'minimal' ), true ) ? $params['skin'] : $args['skin'];
			$params['shape']     = in_array( $params['shape'], array( 'square', 'rounded', 'circle' ), true ) ? $params['shape'] : $args['shape'];
			$params['size']      = in_array( $params['size'], array( 'normal', 'small', 'large' ), true ) ? $params['size'] : $args['size'];
			$params['alignment'] = in_array( $params['alignment'], array( 'left', 'center', 'right' ), true ) ? $params['alignment'] : $args['alignment'];

			$share_data = $this->getShareData( $params );

			$params['share_page_url'] = $share_data['url'];
			$params['share_title']    = $share_data['title'];
			$params['buttons']        = $this->getButtons( $params, $share_data );
			$params['copy_label']     = '' !== trim( $params['copy_label'] ) ? $params['copy_label'] : esc_html__( 'Copy Link', 'extensive-vc-addon' );
			$params['copied_label']   = '' !== trim( $params['copied_label'] ) ? $params['copied_label'] : esc_html__( 'Link copied', 'extensive-vc-addon' );
			$params['native_label']   = '' !== trim( $params['native_label'] ) ? $params['native_label'] : esc_html__( 'Share', 'extensive-vc-addon' );
			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'social-share', 'templates/social-share', '', $params );

			return $html;
		}

		/**
		 * Get shared page url, title and image
		 *
		 * @param $params array - shortcode parameters value
		 *
		 * @return array
		 */
		private function getShareData( $params ) {
			$post_id = get_the_ID();

			if ( 'custom' === $params['share_url'] && '' !== trim( $params['custom_url'] ) ) {
				$url = esc_url_raw( trim( $params['custom_url'] ) );
			} elseif ( is_singular() || in_the_loop() ) {
				$url = (string) get_permalink( $post_id );
			} else {
				global $wp;

				$url = home_url( isset( $wp->request ) ? trailingslashit( $wp->request ) : '/' );
			}

			if ( '' !== trim( $params['custom_title'] ) ) {
				$title = $params['custom_title'];
			} elseif ( ! empty( $post_id ) && ( is_singular() || in_the_loop() ) ) {
				$title = get_the_title( $post_id );
			} else {
				$title = wp_get_document_title();
			}

			$image = ! empty( $post_id ) && has_post_thumbnail( $post_id ) ? (string) get_the_post_thumbnail_url( $post_id, 'large' ) : '';

			return apply_filters(
				'extensive_vc_filter_social_share_data',
				array(
					'url'   => $url,
					'title' => html_entity_decode( wp_strip_all_tags( (string) $title ), ENT_QUOTES, get_bloginfo( 'charset' ) ),
					'image' => $image,
				),
				$params
			);
		}

		/**
		 * Get share buttons for selected networks
		 *
		 * @param $params array - shortcode parameters value
		 * @param $share_data array - shared url, title and image
		 *
		 * @return array
		 */
		private function getButtons( $params, $share_data ) {
			$buttons  = array();
			$networks = $this->getNetworks();
			$selected = array_unique( array_filter( array_map( 'trim', explode( ',', (string) $params['networks'] ) ) ) );

			foreach ( $selected as $slug ) {
				if ( isset( $networks[ $slug ] ) ) {
					/* translators: %s: social network name */
					$aria_label = sprintf( esc_html__( 'Share on %s', 'extensive-vc-addon' ), $networks[ $slug ]['label'] );

					$buttons[ $slug ] = array(
						'type'       => 'link',
						'label'      => $networks[ $slug ]['label'],
						'aria_label' => 'email' === $slug ? esc_html__( 'Share by Email', 'extensive-vc-addon' ) : $aria_label,
						'url'        => str_replace(
							array( '%url%', '%title%', '%image%' ),
							array( rawurlencode( $share_data['url'] ), rawurlencode( $share_data['title'] ), rawurlencode( $share_data['image'] ) ),
							$networks[ $slug ]['url']
						),
					);
				} elseif ( in_array( $slug, array( 'copy', 'native' ), true ) ) {
					$buttons[ $slug ] = array(
						'type' => $slug,
					);
				}
			}

			return $buttons;
		}

		/**
		 * Get icon html for share button
		 *
		 * @param $slug string - network slug
		 *
		 * @return string
		 */
		public static function getIcon( $slug ) {
			$custom_icons = array(
				'copy'   => 'M4 4h12v2H6v10H4V4zm4 4h12v12H8V8zm2 2v8h8v-8h-8z',
				'native' => 'M18 2a3 3 0 1 1 0 6 3 3 0 1 1 0-6zM6 9a3 3 0 1 1 0 6 3 3 0 1 1 0-6zm12 7a3 3 0 1 1 0 6 3 3 0 1 1 0-6zM6.4 12.69l12-7-.8-1.38-12 7 .8 1.38zm-.8-1.38 12 7 .8-1.38-12-7-.8 1.38z',
			);

			if ( isset( $custom_icons[ $slug ] ) ) {
				return sprintf(
					'<svg class="evc-svg-icon evc-social-icon evc-social-icon-%1$s" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24" aria-hidden="true" focusable="false"><path d="%2$s"/></svg>',
					esc_attr( $slug ),
					esc_attr( $custom_icons[ $slug ] )
				);
			}

			return extensive_vc_get_social_icon( $slug );
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
			$holderClasses[] = 'evc-ss-layout-' . esc_attr( $params['layout'] );
			$holderClasses[] = 'evc-ss-skin-' . esc_attr( $params['skin'] );
			$holderClasses[] = 'evc-ss-shape-' . esc_attr( $params['shape'] );
			$holderClasses[] = 'evc-ss-size-' . esc_attr( $params['size'] );
			$holderClasses[] = 'evc-ss-align-' . esc_attr( $params['alignment'] );
			$holderClasses[] = ! empty( $params['button_color'] ) ? 'evc-ss-custom-color' : '';

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
				'title_color'        => '--evc-ss-title-color',
				'button_color'       => '--evc-ss-color',
				'button_hover_color' => '--evc-ss-hover-color',
				'icon_color'         => '--evc-ss-icon-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			if ( '' !== $params['space_between_items'] ) {
				$styles[] = '--evc-ss-gap: ' . absint( $params['space_between_items'] ) . 'px';
			}

			return $styles;
		}
	}
}

EVCSocialShare::getInstance();
