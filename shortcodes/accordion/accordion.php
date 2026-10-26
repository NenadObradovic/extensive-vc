<?php

namespace ExtensiveVC\Shortcodes\EVCAccordion;

use ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCAccordion' ) ) {
	class EVCAccordion extends Shortcodes\EVCShortcode {

		/**
		 * Singleton variables
		 */
		private static $instance;

		/**
		 * Stack of accordions which are currently rendered, items are registered into the last one (supports nested accordions)
		 */
		private static $renderStack = array();

		/**
		 * Constructor
		 */
		function __construct() {
			$this->setBase( 'evc_accordion' );
			$this->setChildBase( 'evc_accordion_item' );
			$this->setShortcodeName( esc_html__( 'Accordion', 'extensive-vc-addon' ) );
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
		 * Register accordion item into currently rendered accordion
		 *
		 * @param $title string - item title
		 * @param $answer string - item content html
		 *
		 * @return array - item settings (open state and title tag)
		 */
		public static function registerItem( $title, $answer ) {
			$last = count( self::$renderStack ) - 1;

			// Item is rendered outside of accordion element
			if ( $last < 0 ) {
				return array(
					'open'      => false,
					'title_tag' => 'h5',
				);
			}

			$accordion = &self::$renderStack[ $last ];
			$index     = count( $accordion['items'] );

			$accordion['items'][] = array(
				'title'  => $title,
				'answer' => $answer,
			);

			return array(
				'open'      => 0 === $index && 'yes' === $accordion['open_first'],
				'title_tag' => $accordion['title_tag'],
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
					'param_name'  => 'style',
					'heading'     => esc_html__( 'Style', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Boxed', 'extensive-vc-addon' )  => 'boxed',
						esc_html__( 'Simple', 'extensive-vc-addon' ) => 'simple'
					),
					'admin_label' => true
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'behavior',
					'heading'     => esc_html__( 'Behavior', 'extensive-vc-addon' ),
					'value'       => array(
						esc_html__( 'Accordion - one item is open at a time', 'extensive-vc-addon' ) => 'accordion',
						esc_html__( 'Toggle - multiple items can be open', 'extensive-vc-addon' )     => 'toggle'
					)
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'open_first',
					'heading'    => esc_html__( 'Open First Item', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_yes_no_select_array( false, true ) )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'icon_position',
					'heading'    => esc_html__( 'Icon Position', 'extensive-vc-addon' ),
					'value'      => array(
						esc_html__( 'Right', 'extensive-vc-addon' ) => 'right',
						esc_html__( 'Left', 'extensive-vc-addon' )  => 'left'
					)
				),
				array(
					'type'        => 'dropdown',
					'param_name'  => 'enable_faq_schema',
					'heading'     => esc_html__( 'Enable FAQ Schema', 'extensive-vc-addon' ),
					'value'       => array_flip( extensive_vc_get_yes_no_select_array( false ) ),
					'description' => esc_html__( 'Add FAQPage structured data (JSON-LD) for search engines. Use it only for questions and answers, once per page', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'dropdown',
					'param_name' => 'title_tag',
					'heading'    => esc_html__( 'Title Tag', 'extensive-vc-addon' ),
					'value'      => array_flip( extensive_vc_get_title_tag_array( true, array( 'p' => 'p' ) ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_color',
					'heading'    => esc_html__( 'Title Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_active_color',
					'heading'    => esc_html__( 'Title Active Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'title_bg_color',
					'heading'    => esc_html__( 'Title Background Color', 'extensive-vc-addon' ),
					'dependency' => array( 'element' => 'style', 'value' => array( 'boxed' ) ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'content_color',
					'heading'    => esc_html__( 'Content Color', 'extensive-vc-addon' ),
					'group'      => esc_html__( 'Design Options', 'extensive-vc-addon' )
				),
				array(
					'type'       => 'colorpicker',
					'param_name' => 'border_color',
					'heading'    => esc_html__( 'Border Color', 'extensive-vc-addon' ),
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
				'custom_class'       => '',
				'style'              => 'boxed',
				'behavior'           => 'accordion',
				'open_first'         => 'yes',
				'icon_position'      => 'right',
				'enable_faq_schema'  => 'no',
				'title_tag'          => 'h5',
				'title_color'        => '',
				'title_active_color' => '',
				'title_bg_color'     => '',
				'content_color'      => '',
				'border_color'       => ''
			);
			$params = shortcode_atts( $args, $atts, $this->getBase() );

			$params['style']     = 'simple' === $params['style'] ? 'simple' : 'boxed';
			$params['behavior']  = 'toggle' === $params['behavior'] ? 'toggle' : 'accordion';
			$params['title_tag'] = extensive_vc_escape_title_tag( ! empty( $params['title_tag'] ) ? $params['title_tag'] : $args['title_tag'], 'h5' );

			// Items are rendered first, so they can be registered for first item state and FAQ schema
			self::$renderStack[] = array(
				'open_first' => $params['open_first'],
				'title_tag'  => $params['title_tag'],
				'items'      => array(),
			);

			$params['items_html'] = do_shortcode( (string) $content );

			$accordion = array_pop( self::$renderStack );

			$params['holder_classes'] = $this->getHolderClasses( $params );
			$params['holder_styles']  = $this->getHolderStyles( $params );
			$params['faq_schema']     = 'yes' === $params['enable_faq_schema'] ? $this->getFaqSchema( $accordion['items'] ) : '';

			$html = extensive_vc_get_module_template_part( 'shortcodes', 'accordion', 'templates/accordion', '', $params );

			return $html;
		}

		/**
		 * Get FAQPage structured data
		 *
		 * @param $items array - accordion items with title and answer
		 *
		 * @return string - json encoded data
		 */
		private function getFaqSchema( $items ) {
			$questions = array();

			// Allowed html tags in answers by Google FAQ structured data guidelines
			$allowed_tags = array(
				'h1'     => array(),
				'h2'     => array(),
				'h3'     => array(),
				'h4'     => array(),
				'h5'     => array(),
				'h6'     => array(),
				'br'     => array(),
				'ol'     => array(),
				'ul'     => array(),
				'li'     => array(),
				'a'      => array( 'href' => true ),
				'p'      => array(),
				'b'      => array(),
				'strong' => array(),
				'i'      => array(),
				'em'     => array(),
			);

			foreach ( $items as $item ) {
				$question = trim( wp_strip_all_tags( $item['title'] ) );
				$answer   = trim( wp_kses( $item['answer'], $allowed_tags ) );

				if ( '' === $question || '' === $answer ) {
					continue;
				}

				$questions[] = array(
					'@type'          => 'Question',
					'name'           => $question,
					'acceptedAnswer' => array(
						'@type' => 'Answer',
						'text'  => $answer,
					),
				);
			}

			if ( empty( $questions ) ) {
				return '';
			}

			$schema = apply_filters(
				'extensive_vc_filter_accordion_faq_schema',
				array(
					'@context'   => 'https://schema.org',
					'@type'      => 'FAQPage',
					'mainEntity' => $questions,
				),
				$items
			);

			// Slashes are escaped, so closing script tag can't be added through the content
			return wp_json_encode( $schema, JSON_UNESCAPED_UNICODE );
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
			$holderClasses[] = 'evc-acc-' . esc_attr( $params['style'] );
			$holderClasses[] = 'evc-acc-icon-' . ( 'left' === $params['icon_position'] ? 'left' : 'right' );

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
				'title_color'        => '--evc-acc-title-color',
				'title_active_color' => '--evc-acc-title-active-color',
				'title_bg_color'     => '--evc-acc-title-bg-color',
				'content_color'      => '--evc-acc-content-color',
				'border_color'       => '--evc-acc-border-color',
			);

			foreach ( $variables as $key => $variable ) {
				if ( ! empty( $params[ $key ] ) ) {
					$styles[] = $variable . ': ' . $params[ $key ];
				}
			}

			return $styles;
		}
	}
}

EVCAccordion::getInstance();
