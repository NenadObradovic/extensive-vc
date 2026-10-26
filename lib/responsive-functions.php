<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_get_responsive_devices' ) ) {
	/**
	 * Returns list of devices used for responsive options
	 *
	 * @return array - key is device slug and value is device label
	 */
	function extensive_vc_get_responsive_devices() {
		return array(
			'desktop' => esc_html__( 'Desktop', 'extensive-vc-addon' ),
			'tablet'  => esc_html__( 'Tablet (up to 1024px)', 'extensive-vc-addon' ),
			'mobile'  => esc_html__( 'Mobile (up to 680px)', 'extensive-vc-addon' ),
		);
	}
}

if ( ! function_exists( 'extensive_vc_get_responsive_shortcode_params' ) ) {
	/**
	 * Returns responsive options for shortcodes panel, these options are added to all main elements
	 *
	 * @return array
	 */
	function extensive_vc_get_responsive_shortcode_params() {
		$params = array();
		$group  = esc_html__( 'Responsive Options', 'extensive-vc-addon' );

		foreach ( extensive_vc_get_responsive_devices() as $device => $label ) {
			$params[] = array(
				'type'        => 'checkbox',
				'param_name'  => 'evc_hide_on_' . $device,
				/* translators: %s: device name */
				'heading'     => sprintf( esc_html__( 'Hide on %s', 'extensive-vc-addon' ), $label ),
				'value'       => array( esc_html__( 'Yes', 'extensive-vc-addon' ) => 'yes' ),
				'group'       => $group,
			);
		}

		foreach ( extensive_vc_get_responsive_devices() as $device => $label ) {
			$params[] = array(
				'type'        => 'textfield',
				'param_name'  => 'evc_margin_' . $device,
				/* translators: %s: device name */
				'heading'     => sprintf( esc_html__( 'Margin on %s', 'extensive-vc-addon' ), $label ),
				'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 0 20px 0). Tablet value is used on mobile if mobile value is empty', 'extensive-vc-addon' ),
				'group'       => $group,
			);
		}

		return apply_filters( 'extensive_vc_filter_responsive_shortcode_params', $params );
	}
}

if ( ! function_exists( 'extensive_vc_sanitize_css_spacing' ) ) {
	/**
	 * Sanitize css spacing value (margin or padding) e.g. 10px 0 20px auto
	 *
	 * @param $value string - spacing value
	 *
	 * @return string
	 */
	function extensive_vc_sanitize_css_spacing( $value ) {
		if ( ! is_scalar( $value ) ) {
			return '';
		}

		$value = trim( preg_replace( '/[^0-9a-zA-Z%.\s\-]/', '', (string) $value ) );

		// Numbers without units are set in pixels
		return trim( preg_replace( '/(^|\s)(-?\d*\.?\d+)(?=\s|$)/', '$1$2px', $value ) );
	}
}

if ( ! function_exists( 'extensive_vc_get_responsive_attributes' ) ) {
	/**
	 * Returns responsive classes and css variables from shortcode attributes
	 *
	 * @param $atts array - shortcode attributes
	 *
	 * @return array - array with classes and styles keys
	 */
	function extensive_vc_get_responsive_attributes( $atts ) {
		$classes = array();
		$styles  = array();

		foreach ( array_keys( extensive_vc_get_responsive_devices() ) as $device ) {
			if ( ! empty( $atts[ 'evc_hide_on_' . $device ] ) && 'yes' === $atts[ 'evc_hide_on_' . $device ] ) {
				$classes[] = 'evc-hide-on-' . $device;
			}

			$margin = ! empty( $atts[ 'evc_margin_' . $device ] ) ? extensive_vc_sanitize_css_spacing( $atts[ 'evc_margin_' . $device ] ) : '';

			if ( '' !== $margin ) {
				$classes[] = 'evc-has-margin-' . $device;
				$styles[]  = '--evc-margin-' . $device . ': ' . $margin;
			}
		}

		return apply_filters(
			'extensive_vc_filter_responsive_attributes',
			array(
				'classes' => $classes,
				'styles'  => $styles,
			),
			$atts
		);
	}
}

if ( ! function_exists( 'extensive_vc_add_responsive_attributes_to_element' ) ) {
	/**
	 * Adds responsive classes and css variables to the root html element of rendered element
	 *
	 * @param $output string - shortcode output
	 * @param $tag string - shortcode name
	 * @param $atts array|string - shortcode attributes
	 *
	 * @return string
	 */
	function extensive_vc_add_responsive_attributes_to_element( $output, $tag, $atts ) {
		if ( 0 !== strpos( $tag, 'evc_' ) || ! is_array( $atts ) || empty( $output ) ) {
			return $output;
		}

		$elements = extensive_vc_get_elements_map();

		// Child elements don't have responsive options
		if ( ! isset( $elements[ $tag ] ) || ! empty( $elements[ $tag ]['is_child'] ) ) {
			return $output;
		}

		$attributes = extensive_vc_get_responsive_attributes( $atts );

		if ( empty( $attributes['classes'] ) && empty( $attributes['styles'] ) ) {
			return $output;
		}

		// Find the first html tag in the output (skipping comments, styles and scripts), which is element holder
		return preg_replace_callback(
			'/^(\s*(?:(?:<!--.*?-->|<style\b[^>]*>.*?<\/style>|<script\b[^>]*>.*?<\/script>)\s*)*)<([a-zA-Z][a-zA-Z0-9-]*)(\s[^>]*)?>/s',
			function ( $match ) use ( $attributes ) {
				$tag_attributes = isset( $match[3] ) ? $match[3] : '';

				if ( ! empty( $attributes['classes'] ) ) {
					$classes = esc_attr( implode( ' ', $attributes['classes'] ) );

					if ( preg_match( '/\sclass="[^"]*"/', $tag_attributes ) ) {
						$tag_attributes = preg_replace( '/\sclass="([^"]*)"/', ' class="$1 ' . $classes . '"', $tag_attributes, 1 );
					} else {
						$tag_attributes .= ' class="' . $classes . '"';
					}
				}

				if ( ! empty( $attributes['styles'] ) ) {
					$styles = esc_attr( implode( '; ', $attributes['styles'] ) );

					if ( preg_match( '/\sstyle="[^"]*"/', $tag_attributes ) ) {
						$tag_attributes = preg_replace( '/\sstyle="([^"]*)"/', ' style="' . $styles . '; $1"', $tag_attributes, 1 );
					} else {
						$tag_attributes .= ' style="' . $styles . '"';
					}
				}

				return $match[1] . '<' . $match[2] . $tag_attributes . '>';
			},
			$output,
			1
		);
	}

	add_filter( 'do_shortcode_tag', 'extensive_vc_add_responsive_attributes_to_element', 10, 3 );
}
