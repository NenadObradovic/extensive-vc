<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_add_plugin_version_class' ) ) {
	/**
	 * Add plugin version class to body
	 *
	 * @param $classes array
	 *
	 * @return array
	 */
	function extensive_vc_add_plugin_version_class( $classes ) {
		$classes[] = 'extensive-vc-' . EXTENSIVE_VC_VERSION;

		return $classes;
	}

	add_filter( 'body_class', 'extensive_vc_add_plugin_version_class' );
}

if ( ! function_exists( 'extensive_vc_get_module_template_part' ) ) {
	/**
	 * Loads shortcode module template part
	 *
	 * @param $module string - name of the module to load
	 * @param $shortcode string - name of the shortcode folder
	 * @param $template_path string - name of the template to load
	 * @param $slug string - name of the template suffix to load different file
	 * @param $params array - array of parameters to pass to template
	 *
	 * @return string/html
	 */
	function extensive_vc_get_module_template_part( $module, $shortcode, $template_path, $slug = '', $params = array() ) {
		$available_characters = '/[^A-Za-z0-9\_\-\/]/';

		$module        = is_scalar( $module ) ? preg_replace( $available_characters, '', $module ) : '';
		$shortcode     = is_scalar( $shortcode ) ? preg_replace( $available_characters, '', $shortcode ) : '';
		$template_path = is_scalar( $template_path ) ? preg_replace( $available_characters, '', $template_path ) : '';
		$slug          = is_scalar( $slug ) ? preg_replace( $available_characters, '', $slug ) : '';

		if ( empty( $shortcode ) || empty( $template_path ) ) {
			return '';
		}

		if ( is_array( $params ) && count( $params ) ) {
			extract( $params, EXTR_SKIP ); // @codingStandardsIgnoreLine
		}

		switch ( $module ) {
			case 'woocommerce':
				$module = EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce/shortcodes';
				break;
			case 'woocommerce-part':
				$module = EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce';
				break;
			case 'cpt':
				$module        = EXTENSIVE_VC_CPT_ABS_PATH;
				$template_path = 'shortcodes/' . $template_path;
				break;
			default:
				$module = EXTENSIVE_VC_SHORTCODES_ABS_PATH;
				break;
		}

		$file_extension = '.php';

		$theme_file_path           = EXTENSIVE_VC_THEME_ROOT_PATH . '/extensive-vc/' . $shortcode . '/' . $template_path;
		$theme_file_with_slug_path = "{$theme_file_path}-{$slug}{$file_extension}";
		$full_theme_path           = $theme_file_path . $file_extension;

		$file_path           = file_exists( $full_theme_path ) ? $theme_file_path : $module . '/' . $shortcode . '/' . $template_path;
		$file_with_slug_path = file_exists( $theme_file_with_slug_path ) ? $theme_file_with_slug_path : "{$file_path}-{$slug}{$file_extension}";
		$full_file_path      = $file_path . $file_extension;

		$template = '';

		if ( file_exists( $full_file_path ) || file_exists( $file_with_slug_path ) ) {
			$template = file_exists( $file_with_slug_path ) ? $file_with_slug_path : $full_file_path;
		}

		$html = '';

		if ( $template ) {
			ob_start();
			include $template;

			$html = ob_get_clean();
		}

		return $html;
	}
}

if ( ! function_exists( 'extensive_vc_template_part' ) ) {
	/**
	 * Print shortcode module template part
	 *
	 * @param $module string - name of the module to load
	 * @param $shortcode string - name of the shortcode folder
	 * @param $template_path string - name of the template to load
	 * @param $slug string - name of the template suffix to load different file
	 * @param $params array - array of parameters to pass to template
	 *
	 * @see extensive_vc_get_module_template_part()
	 */
	function extensive_vc_template_part( $module, $shortcode, $template_path, $slug = '', $params = array() ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Template output is escaped in the template files.
		echo extensive_vc_get_module_template_part( $module, $shortcode, $template_path, $slug, $params );
	}
}

if ( ! function_exists( 'extensive_vc_print_link_attributes' ) ) {
	/**
	 * Print custom link attributes
	 *
	 * @param $link_attributes array - list of link attributes
	 *
	 * @see extensive_vc_get_custom_link_attributes()
	 */
	function extensive_vc_print_link_attributes( $link_attributes ) {
		if ( is_array( $link_attributes ) && ! empty( $link_attributes ) ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Attribute values are escaped in extensive_vc_get_custom_link_attributes function.
			echo implode( ' ', $link_attributes );
		}
	}
}

if ( ! function_exists( 'extensive_vc_get_ajax_status' ) ) {
	/**
	 * Return response status from ajax functions
	 *
	 * @param $status string - success or error
	 * @param $message string - ajax response message
	 * @param $data string|array - response data
	 * @param $redirect string - url address
	 *
	 * @return void
	 */
	function extensive_vc_get_ajax_status( $status, $message, $data = null, $redirect = '' ) {
		$response = array(
			'status'   => esc_attr( $status ),
			'message'  => esc_html( $message ),
			'data'     => $data,
			'redirect' => ! empty( $redirect ) ? esc_url_raw( $redirect ) : '',
		);

		$response = apply_filters( 'extensive_vc_filter_ajax_status', $response );

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON response.
		exit( wp_json_encode( $response ) );
	}
}

if ( ! function_exists( 'extensive_vc_escape_title_tag' ) ) {
	/**
	 * Escape title tag variable for shortcodes
	 *
	 * @param $title_tag string - html tag name
	 * @param $default_tag string - tag which will be used if $title_tag is not allowed
	 *
	 * @return string
	 */
	function extensive_vc_escape_title_tag( $title_tag, $default_tag = 'div' ) {
		$allowed_tags = apply_filters(
			'extensive_vc_filter_allowed_title_tags',
			array(
				'h1',
				'h2',
				'h3',
				'h4',
				'h5',
				'h6',
				'p',
				'span',
				'div',
			)
		);

		$title_tag = is_scalar( $title_tag ) ? strtolower( sanitize_key( $title_tag ) ) : '';

		return in_array( $title_tag, $allowed_tags, true ) ? $title_tag : $default_tag;
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_html_allowed_tags' ) ) {
	/**
	 * Returns allowed inline html tags and attributes for wp_kses function, used for short text options
	 *
	 * @return array
	 */
	function extensive_vc_get_inline_html_allowed_tags() {
		$common_attributes = array(
			'class' => true,
		);

		return apply_filters(
			'extensive_vc_filter_inline_html_allowed_tags',
			array(
				'a'      => array_merge(
					$common_attributes,
					array(
						'href'   => true,
						'target' => true,
						'rel'    => true,
						'title'  => true,
					)
				),
				'br'     => array(),
				'b'      => $common_attributes,
				'strong' => $common_attributes,
				'i'      => $common_attributes,
				'em'     => $common_attributes,
				'u'      => $common_attributes,
				'span'   => $common_attributes,
				'small'  => $common_attributes,
				'mark'   => $common_attributes,
				'sup'    => $common_attributes,
				'sub'    => $common_attributes,
			)
		);
	}
}

if ( ! function_exists( 'extensive_vc_get_svg_icon' ) ) {
	/**
	 * Returns svg icon html
	 *
	 * @param $icon string - icon name
	 * @param $class string - additional icon classes
	 *
	 * @return string
	 */
	function extensive_vc_get_svg_icon( $icon, $class = '' ) {
		$icons = apply_filters(
			'extensive_vc_filter_svg_icons',
			array(
				'arrow-left'   => array(
					'width' => 192,
					'path'  => 'M192 333l-150 -141l150 -141l-21 -19l-171 160l171 160z',
				),
				'arrow-right'  => array(
					'width' => 192,
					'path'  => 'M0 333l21 19l171 -160l-171 -160l-21 19l150 141z',
				),
				'chevron-up'   => array(
					'width' => 448,
					'path'  => 'M224 234l-174 -167c-4 -4 -12 -4 -16 0l-31 30c-4 4 -4 12 0 16l213 204c2 2 5 3 8 3s6 -1 8 -3l213 -204c4 -4 4 -12 0 -16l-31 -30c-4 -4 -12 -4 -16 0l-174 167v0v0z',
				),
				'chevron-down' => array(
					'width' => 448,
					'path'  => 'M224 150v0v0l174 167c4 4 12 4 16 0l31 -30c4 -4 4 -12 0 -16l-213 -204c-2 -2 -5 -3 -8 -3s-6 1 -8 3l-213 204c-4 4 -4 12 0 16l31 30c4 4 12 4 16 0z',
				),
			)
		);

		if ( ! isset( $icons[ $icon ]['width'], $icons[ $icon ]['path'] ) ) {
			return '';
		}

		$width   = absint( $icons[ $icon ]['width'] );
		$classes = trim( 'evc-svg-icon evc-svg-icon-' . $icon . ' ' . $class );

		return sprintf(
			'<svg class="%1$s" xmlns="http://www.w3.org/2000/svg" width="%2$sem" height="1em" viewBox="0 0 %3$d 512" aria-hidden="true" focusable="false"><path transform="matrix(1 0 0 -1 0 448)" d="%4$s"/></svg>',
			esc_attr( $classes ),
			esc_attr( round( $width / 512, 4 ) ),
			$width,
			esc_attr( $icons[ $icon ]['path'] )
		);
	}
}

if ( ! function_exists( 'extensive_vc_print_svg_icon' ) ) {
	/**
	 * Print svg icon html
	 *
	 * @param $icon string - icon name
	 * @param $class string - additional icon classes
	 *
	 * @see extensive_vc_get_svg_icon()
	 */
	function extensive_vc_print_svg_icon( $icon, $class = '' ) {
		echo wp_kses( extensive_vc_get_svg_icon( $icon, $class ), extensive_vc_get_svg_allowed_html() );
	}
}

if ( ! function_exists( 'extensive_vc_get_svg_allowed_html' ) ) {
	/**
	 * Returns allowed svg html tags and attributes for wp_kses function
	 *
	 * @return array
	 */
	function extensive_vc_get_svg_allowed_html() {
		return array(
			'svg'  => array(
				'class'       => true,
				'xmlns'       => true,
				'width'       => true,
				'height'      => true,
				'viewbox'     => true,
				'aria-hidden' => true,
				'focusable'   => true,
			),
			'path' => array(
				'd'         => true,
				'transform' => true,
			),
		);
	}
}

if ( ! function_exists( 'extensive_vc_generate_dynamic_css' ) ) {
	/**
	 * Generates css output based on selector and css rules that are provided
	 *
	 * @param $selector array|string - css selector for which to generate styles
	 * @param $rules array - css rules
	 *
	 * @return string
	 */
	function extensive_vc_generate_dynamic_css( $selector, $rules ) {
		$styles = '';

		if ( ! empty( $selector ) && ! empty( $rules ) ) {

			if ( is_array( $selector ) && count( $selector ) ) {
				$styles .= implode( ', ', $selector );
			} else {
				$styles .= $selector;
			}

			$styles .= ' { ';
			foreach ( $rules as $key => $value ) {
				if ( ! empty( $key ) ) {
					$styles .= esc_attr( $key ) . ': ' . esc_attr( $value ) . ';';
				}
			}

			$styles .= '}' . "\n\n";
		}

		return $styles;
	}
}

if ( ! function_exists( 'extensive_vc_print_inline_style' ) ) {
	/**
	 * Print generated style attribute
	 *
	 * @param $value string|array - attribute value
	 *
	 * @see extensive_vc_get_inline_style()
	 */
	function extensive_vc_print_inline_style( $value ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in extensive_vc_get_inline_attr function.
		echo extensive_vc_get_inline_style( $value );
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_style' ) ) {
	/**
	 * Generates style attribute and returns generated string
	 *
	 * @param $value string|array - value of style attribute
	 *
	 * @return string generated style attribute
	 *
	 * @see extensive_vc_get_inline_attr()
	 */
	function extensive_vc_get_inline_style( $value ) {
		return extensive_vc_get_inline_attr( $value, 'style', ';' );
	}
}

if ( ! function_exists( 'extensive_vc_print_class_attribute' ) ) {
	/**
	 * Print generated class attribute
	 *
	 * @param $value string|array - value of class attribute
	 *
	 * @see extensive_vc_get_class_attribute()
	 */
	function extensive_vc_print_class_attribute( $value ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in extensive_vc_get_inline_attr function.
		echo extensive_vc_get_class_attribute( $value );
	}
}

if ( ! function_exists( 'extensive_vc_get_class_attribute' ) ) {
	/**
	 * Generates class attribute and returns generated string
	 *
	 * @param $value string|array - value of class attribute
	 *
	 * @return string generated class attribute
	 *
	 * @see extensive_vc_get_inline_attr()
	 */
	function extensive_vc_get_class_attribute( $value ) {
		return extensive_vc_get_inline_attr( $value, 'class', ' ' );
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_attr' ) ) {
	/**
	 * Generates html attribute
	 *
	 * @param $value string|array - value of html attribute
	 * @param $attr string - name of html attribute to generate
	 * @param $glue string - glue with which to implode $attr. Used only when $attr is array
	 * @param $allow_zero_values boolean - allow data to have zero value
	 *
	 * @return string generated html attribute
	 */
	function extensive_vc_get_inline_attr( $value, $attr, $glue = '', $allow_zero_values = false ) {
		$attr = sanitize_key( $attr );

		if ( empty( $attr ) || is_null( $value ) || is_object( $value ) ) {
			return '';
		}

		if ( $allow_zero_values ) {
			if ( '' !== $value && array() !== $value ) {
				$properties = is_array( $value ) ? implode( $glue, $value ) : $value;

				return $attr . '="' . esc_attr( $properties ) . '"';
			}
		} elseif ( ! empty( $value ) ) {
			$properties = is_array( $value ) ? implode( $glue, $value ) : $value;

			if ( true === $properties ) {
				$properties = '1';
			}

			return $attr . '="' . esc_attr( sanitize_text_field( html_entity_decode( (string) $properties, ENT_QUOTES, get_bloginfo( 'charset' ) ) ) ) . '"';
		}

		return '';
	}
}

if ( ! function_exists( 'extensive_vc_print_inline_attr' ) ) {
	/**
	 * Print generated html attribute
	 *
	 * @param $value string|array - value of html attribute
	 * @param $attr string - name of html attribute to generate
	 * @param $glue string - glue with which to implode $attr. Used only when $attr is array
	 *
	 * @see extensive_vc_get_inline_attr()
	 */
	function extensive_vc_print_inline_attr( $value, $attr, $glue = '' ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in extensive_vc_get_inline_attr function.
		echo extensive_vc_get_inline_attr( $value, $attr, $glue );
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_attrs' ) ) {
	/**
	 * Generate multiple inline attributes
	 *
	 * @param $attributes array - list of attributes where key is attribute name
	 * @param $allow_zero_values boolean - allow data to have zero value
	 *
	 * @return string
	 */
	function extensive_vc_get_inline_attrs( $attributes, $allow_zero_values = false ) {
		$output = '';

		if ( is_array( $attributes ) && count( $attributes ) ) {
			foreach ( $attributes as $attr => $value ) {
				$output .= ' ' . extensive_vc_get_inline_attr( $value, $attr, '', $allow_zero_values );
			}
		}

		return trim( preg_replace( '/\s+/', ' ', $output ) );
	}
}

if ( ! function_exists( 'extensive_vc_print_inline_attrs' ) ) {
	/**
	 * Print multiple inline attributes
	 *
	 * @param $attributes array - list of attributes where key is attribute name
	 * @param $allow_zero_values boolean - allow data to have zero value
	 *
	 * @see extensive_vc_get_inline_attrs()
	 */
	function extensive_vc_print_inline_attrs( $attributes, $allow_zero_values = false ) {
		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Escaped in extensive_vc_get_inline_attr function.
		echo extensive_vc_get_inline_attrs( $attributes, $allow_zero_values );
	}
}

if ( ! function_exists( 'extensive_vc_string_ends_with' ) ) {
	/**
	 * Checks if $haystack ends with $needle and returns proper boolean value
	 *
	 * @param $haystack string - to check
	 * @param $needle string - with which $haystack needs to end
	 *
	 * @return boolean
	 */
	function extensive_vc_string_ends_with( $haystack, $needle ) {
		if ( ! is_scalar( $haystack ) || ! is_scalar( $needle ) || '' === (string) $haystack || '' === (string) $needle ) {
			return false;
		}

		return substr( (string) $haystack, - strlen( (string) $needle ) ) === (string) $needle;
	}
}

if ( ! function_exists( 'extensive_vc_string_ends_with_typography_units' ) ) {
	/**
	 * Checks if $haystack ends with typography units (px, em, rem, vh, vw)
	 *
	 * @param $haystack string - to check
	 *
	 * @return boolean
	 */
	function extensive_vc_string_ends_with_typography_units( $haystack ) {
		$needles = apply_filters( 'extensive_vc_filter_typography_units', array( 'px', 'em', 'rem', 'vh', 'vw' ) );

		foreach ( $needles as $needle ) {
			if ( extensive_vc_string_ends_with( $haystack, $needle ) ) {
				return true;
			}
		}

		return false;
	}
}

if ( ! function_exists( 'extensive_vc_filter_suffix' ) ) {
	/**
	 * Removes suffix from given value, e.g. px at the end of string
	 *
	 * @param $value string - value to filter
	 * @param $suffix string - suffix to remove
	 *
	 * @return string
	 */
	function extensive_vc_filter_suffix( $value, $suffix ) {
		if ( extensive_vc_string_ends_with( $value, $suffix ) ) {
			$value = substr( $value, 0, - strlen( $suffix ) );
		}

		return $value;
	}
}

if ( ! function_exists( 'extensive_vc_resize_image' ) ) {
	/**
	 * Generates custom thumbnail for given attachment
	 *
	 * @param $attach_id null - id of attachment
	 * @param $width int - desired height of custom thumbnail
	 * @param $height int - desired width of custom thumbnail
	 * @param $crop boolean - whether to crop image or not
	 *
	 * @return array returns array containing img_url, width and height
	 *
	 * @see get_attached_file()
	 * @see wp_get_attachment_url()
	 * @see wp_get_image_editor()
	 */
	function extensive_vc_resize_image( $attach_id = null, $width = null, $height = null, $crop = true ) {
		$return_array = array();

		if ( ! empty( $attach_id ) && ( isset( $width ) && isset( $height ) ) ) {

			//get file path of the attachment
			$img_path = get_attached_file( $attach_id );

			//get attachment url
			$img_url = wp_get_attachment_url( $attach_id );

			//break down img path to array so we can use it's components in building thumbnail path
			$img_path_array = pathinfo( $img_path );

			//build thumbnail path
			$new_img_path = $img_path_array['dirname'] . '/' . $img_path_array['filename'] . '-' . $width . 'x' . $height . '.' . $img_path_array['extension'];

			//build thumbnail url
			$new_img_url = str_replace( $img_path_array['filename'], $img_path_array['filename'] . '-' . $width . 'x' . $height, $img_url );

			//check if thumbnail exists by it's path
			if ( ! file_exists( $new_img_path ) ) {
				//get image manipulation object
				$image_object = wp_get_image_editor( $img_path );

				if ( ! is_wp_error( $image_object ) ) {
					//resize image and save it new to path
					$image_object->resize( $width, $height, $crop );
					$image_object->save( $new_img_path );

					//get sizes of newly created thumbnail.
					///we don't use $width and $height because those might differ from end result based on $crop parameter
					$image_sizes = $image_object->get_size();

					$width  = $image_sizes['width'];
					$height = $image_sizes['height'];
				}
			}

			$return_array = array(
				'img_url'    => $new_img_url,
				'img_width'  => $width,
				'img_height' => $height,
			);
		}

		return $return_array;
	}
}

if ( ! function_exists( 'extensive_vc_get_image_size_value' ) ) {
	/**
	 * Returns image size value from shortcode option. Registered image size name or custom size in format width x height
	 *
	 * @param $image_size string - image size option value (thumbnail, medium, large, full, any registered size or e.g. 200x100)
	 *
	 * @return string|array - image size name or array with width and height values
	 */
	function extensive_vc_get_image_size_value( $image_size ) {
		$image_size = is_scalar( $image_size ) ? trim( (string) $image_size ) : '';

		if ( '' === $image_size ) {
			return 'full';
		}

		if ( 'full' === $image_size || in_array( $image_size, get_intermediate_image_sizes(), true ) ) {
			return $image_size;
		}

		preg_match_all( '/\d+/', $image_size, $matches );

		if ( ! empty( $matches[0] ) ) {
			$width  = absint( $matches[0][0] );
			$height = isset( $matches[0][1] ) ? absint( $matches[0][1] ) : $width;

			if ( $width > 0 && $height > 0 ) {
				return array( $width, $height );
			}
		}

		return 'full';
	}
}

if ( ! function_exists( 'extensive_vc_get_attachment_image' ) ) {
	/**
	 * Returns image html for attachment, custom image sizes are generated on the fly
	 *
	 * @param $attachment_id int - attachment id
	 * @param $image_size string|array - image size name or array with width and height values
	 *
	 * @return string
	 */
	function extensive_vc_get_attachment_image( $attachment_id, $image_size = 'full' ) {
		$attachment_id = absint( $attachment_id );

		if ( empty( $attachment_id ) ) {
			return '';
		}

		if ( is_array( $image_size ) && 2 === count( $image_size ) ) {
			return extensive_vc_generate_thumbnail( $attachment_id, $image_size[0], $image_size[1] );
		}

		return wp_get_attachment_image( $attachment_id, is_string( $image_size ) && '' !== $image_size ? $image_size : 'full' );
	}
}

if ( ! function_exists( 'extensive_vc_generate_thumbnail' ) ) {
	/**
	 * Generates thumbnail img tag. It calls extensive_vc_resize_image function which resize img on the fly
	 *
	 * @param $attach_id null - attachment id
	 * @param $width int - width of thumbnail
	 * @param $height int - height of thumbnail
	 * @param $crop boolean - whether to crop thumbnail or not
	 *
	 * @return string generated img tag
	 *
	 * @see extensive_vc_resize_image()
	 */
	function extensive_vc_generate_thumbnail( $attach_id = null, $width = null, $height = null, $crop = true ) {

		if ( ! empty( $attach_id ) ) {
			$img_info = extensive_vc_resize_image( $attach_id, $width, $height, $crop );
			$img_alt  = ! empty( $attach_id ) ? get_post_meta( $attach_id, '_wp_attachment_image_alt', true ) : '';

			if ( is_array( $img_info ) && count( $img_info ) ) {
				return '<img src="' . esc_url( $img_info['img_url'] ) . '" alt="' . esc_attr( $img_alt ) . '" width="' . esc_attr( $img_info['img_width'] ) . '" height="' . esc_attr( $img_info['img_height'] ) . '" />';
			}
		}

		return '';
	}
}

if ( ! function_exists( 'extensive_vc_render_shortcode' ) ) {
	/**
	 * Execute render shortcode function and display forward shortcode element
	 *
	 * @param $shortcode_tag - shortcode base
	 * @param $atts - shortcode attributes
	 *
	 * @return mixed|string
	 */
	function extensive_vc_render_shortcode( $shortcode_tag, $atts ) {
		global $shortcode_tags;

		$content = null;

		if ( ! isset( $shortcode_tags[ $shortcode_tag ] ) ) {
			return;
		}

		if ( is_array( $shortcode_tags[ $shortcode_tag ] ) ) {
			$shortcode_array = $shortcode_tags[ $shortcode_tag ];

			return call_user_func(
				array(
					$shortcode_array[0],
					$shortcode_array[1],
				),
				$atts,
				$content,
				$shortcode_tag
			);
		}

		return call_user_func( $shortcode_tags[ $shortcode_tag ], $atts, $content, $shortcode_tag );
	}
}

if ( ! function_exists( 'extensive_vc_get_custom_link_attributes' ) ) {
	/**
	 * Get custom link attributes
	 *
	 * @param $custom_link array - link parameters value
	 * @param $custom_classes string - custom class value
	 *
	 * @return array
	 */
	function extensive_vc_get_custom_link_attributes( $custom_link = array(), $custom_classes = '' ) {
		$attributes = array();

		if ( ! empty( $custom_link ) ) {
			$link = function_exists( 'vc_build_link' ) ? vc_build_link( $custom_link ) : array();

			if ( ! empty( $link ) && ! empty( $link['url'] ) ) {
				if ( ! empty( $custom_classes ) ) {
					$attributes[] = 'class="' . esc_attr( $custom_classes ) . '"';
				}

				$attributes[] = 'href="' . esc_url( trim( $link['url'] ) ) . '"';

				if ( ! empty( $link['target'] ) ) {
					$attributes[] = 'target="' . esc_attr( trim( $link['target'] ) ) . '"';
				}

				if ( ! empty( $link['title'] ) ) {
					$attributes[] = 'title="' . esc_attr( trim( $link['title'] ) ) . '"';
				}

				if ( ! empty( $link['rel'] ) ) {
					$attributes[] = 'rel="' . esc_attr( trim( $link['rel'] ) ) . '"';
				}
			}
		}

		return $attributes;
	}
}

if ( ! function_exists( 'extensive_vc_get_taxonomy_autocomplete_suggestions' ) ) {
	/**
	 * Returns taxonomy terms for WPBakery Page Builder autocomplete field
	 *
	 * @param $query string - searched term name
	 * @param $taxonomy string - taxonomy name
	 *
	 * @return array
	 */
	function extensive_vc_get_taxonomy_autocomplete_suggestions( $query, $taxonomy ) {
		$results = array();
		$terms   = get_terms(
			array(
				'taxonomy'   => $taxonomy,
				'name__like' => is_scalar( $query ) ? sanitize_text_field( wp_unslash( $query ) ) : '',
				'hide_empty' => false,
				'number'     => 50,
			)
		);

		if ( ! is_wp_error( $terms ) && ! empty( $terms ) ) {
			foreach ( $terms as $term ) {
				$results[] = array(
					'value' => $term->slug,
					'label' => esc_html__( 'Category', 'extensive-vc-addon' ) . ': ' . $term->name,
				);
			}
		}

		return $results;
	}
}

if ( ! function_exists( 'extensive_vc_get_number_of_columns_array' ) ) {
	/**
	 * Returns array of number of columns options
	 *
	 * @param $disable_by_keys array
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_number_of_columns_array( $disable_by_keys = array(), $first_empty = true ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['one']   = esc_html__( 'One', 'extensive-vc-addon' );
		$options['two']   = esc_html__( 'Two', 'extensive-vc-addon' );
		$options['three'] = esc_html__( 'Three', 'extensive-vc-addon' );
		$options['four']  = esc_html__( 'Four', 'extensive-vc-addon' );
		$options['five']  = esc_html__( 'Five', 'extensive-vc-addon' );
		$options['six']   = esc_html__( 'Six', 'extensive-vc-addon' );

		if ( ! empty( $disable_by_keys ) ) {
			foreach ( $disable_by_keys as $key ) {
				if ( array_key_exists( $key, $options ) ) {
					unset( $options[ $key ] );
				}
			}
		}

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_space_between_items_array' ) ) {
	/**
	 * Returns array of space between items options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_space_between_items_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['large']  = esc_html__( 'Large (50px)', 'extensive-vc-addon' );
		$options['medium'] = esc_html__( 'Medium (40px)', 'extensive-vc-addon' );
		$options['normal'] = esc_html__( 'Normal (30px)', 'extensive-vc-addon' );
		$options['small']  = esc_html__( 'Small (20px)', 'extensive-vc-addon' );
		$options['tiny']   = esc_html__( 'Tiny (10px)', 'extensive-vc-addon' );
		$options['no']     = esc_html__( 'No', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_query_order_by_array' ) ) {
	/**
	 * Returns array of query order by
	 *
	 * @param $first_empty boolean
	 * @param $additional_options array
	 *
	 * @return array
	 */
	function extensive_vc_get_query_order_by_array( $first_empty = false, $additional_options = array() ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['date']       = esc_html__( 'Date', 'extensive-vc-addon' );
		$options['ID']         = esc_html__( 'ID', 'extensive-vc-addon' );
		$options['menu_order'] = esc_html__( 'Menu Order', 'extensive-vc-addon' );
		$options['name']       = esc_html__( 'Post Name', 'extensive-vc-addon' );
		$options['rand']       = esc_html__( 'Random', 'extensive-vc-addon' );
		$options['title']      = esc_html__( 'Title', 'extensive-vc-addon' );

		if ( ! empty( $additional_options ) ) {
			$options = array_merge( $options, $additional_options );
		}

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_query_order_array' ) ) {
	/**
	 * Returns array of query order
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_query_order_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['ASC']  = esc_html__( 'Ascending', 'extensive-vc-addon' );
		$options['DESC'] = esc_html__( 'Descending', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_yes_no_select_array' ) ) {
	/**
	 * Returns array of yes/no options
	 *
	 * @param $first_empty boolean
	 * @param $set_yes_to_be_first boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_yes_no_select_array( $first_empty = true, $set_yes_to_be_first = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		if ( $set_yes_to_be_first ) {
			$options['yes'] = esc_html__( 'Yes', 'extensive-vc-addon' );
			$options['no']  = esc_html__( 'No', 'extensive-vc-addon' );
		} else {
			$options['no']  = esc_html__( 'No', 'extensive-vc-addon' );
			$options['yes'] = esc_html__( 'Yes', 'extensive-vc-addon' );
		}

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_link_target_array' ) ) {
	/**
	 * Returns array of link target options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_link_target_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['_self']  = esc_html__( 'Same Window', 'extensive-vc-addon' );
		$options['_blank'] = esc_html__( 'New Window', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_title_tag_array' ) ) {
	/**
	 * Returns array of title tags options
	 *
	 * @param $first_empty boolean
	 * @param $additional_options array
	 *
	 * @return array
	 */
	function extensive_vc_get_title_tag_array( $first_empty = false, $additional_options = array() ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['h1'] = 'h1';
		$options['h2'] = 'h2';
		$options['h3'] = 'h3';
		$options['h4'] = 'h4';
		$options['h5'] = 'h5';
		$options['h6'] = 'h6';

		if ( ! empty( $additional_options ) ) {
			$options = array_merge( $options, $additional_options );
		}

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_font_weight_array' ) ) {
	/**
	 * Returns array of font weights options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_font_weight_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['100'] = esc_html__( '100 Thin', 'extensive-vc-addon' );
		$options['200'] = esc_html__( '200 Thin-Light', 'extensive-vc-addon' );
		$options['300'] = esc_html__( '300 Light', 'extensive-vc-addon' );
		$options['400'] = esc_html__( '400 Normal', 'extensive-vc-addon' );
		$options['500'] = esc_html__( '500 Medium', 'extensive-vc-addon' );
		$options['600'] = esc_html__( '600 Semi-Bold', 'extensive-vc-addon' );
		$options['700'] = esc_html__( '700 Bold', 'extensive-vc-addon' );
		$options['800'] = esc_html__( '800 Extra-Bold', 'extensive-vc-addon' );
		$options['900'] = esc_html__( '900 Ultra-Bold', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_font_style_array' ) ) {
	/**
	 * Returns array of font styles options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_font_style_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['normal']  = esc_html__( 'Normal', 'extensive-vc-addon' );
		$options['italic']  = esc_html__( 'Italic', 'extensive-vc-addon' );
		$options['oblique'] = esc_html__( 'Oblique', 'extensive-vc-addon' );
		$options['initial'] = esc_html__( 'Initial', 'extensive-vc-addon' );
		$options['inherit'] = esc_html__( 'Inherit', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_text_transform_array' ) ) {
	/**
	 * Returns array of text transforms options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_text_transform_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['none']       = esc_html__( 'None', 'extensive-vc-addon' );
		$options['capitalize'] = esc_html__( 'Capitalize', 'extensive-vc-addon' );
		$options['uppercase']  = esc_html__( 'Uppercase', 'extensive-vc-addon' );
		$options['lowercase']  = esc_html__( 'Lowercase', 'extensive-vc-addon' );
		$options['initial']    = esc_html__( 'Initial', 'extensive-vc-addon' );
		$options['inherit']    = esc_html__( 'Inherit', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_text_decorations_array' ) ) {
	/**
	 * Returns array of text transforms options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_text_decorations_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['none']         = esc_html__( 'None', 'extensive-vc-addon' );
		$options['underline']    = esc_html__( 'Underline', 'extensive-vc-addon' );
		$options['overline']     = esc_html__( 'Overline', 'extensive-vc-addon' );
		$options['line-through'] = esc_html__( 'Line-Through', 'extensive-vc-addon' );
		$options['initial']      = esc_html__( 'Initial', 'extensive-vc-addon' );
		$options['inherit']      = esc_html__( 'Inherit', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_image_behavior_array' ) ) {
	/**
	 * Returns array of image behavior options
	 *
	 * @return array
	 */
	function extensive_vc_get_image_behavior_array() {
		$options = array();

		$options['']                = esc_html__( 'None', 'extensive-vc-addon' );
		$options['overlay']         = esc_html__( 'Overlay', 'extensive-vc-addon' );
		$options['zoom']            = esc_html__( 'Zoom', 'extensive-vc-addon' );
		$options['lightbox']        = esc_html__( 'Lightbox', 'extensive-vc-addon' );
		$options['top-moving']      = esc_html__( 'Top Moving', 'extensive-vc-addon' );
		$options['circle-fade-out'] = esc_html__( 'Circle Fade Out', 'extensive-vc-addon' );
		$options['bordered']        = esc_html__( 'Bordered', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_shortcode_icon_library_array' ) ) {
	/**
	 * Returns array of WPBakery Page Builder icon libraries
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_shortcode_icon_library_array( $first_empty = false ) {
		$options = array();

		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc-addon' );
		}

		$options['fontawesome'] = esc_html__( 'Font Awesome', 'extensive-vc-addon' );
		$options['openiconic']  = esc_html__( 'Open Iconic', 'extensive-vc-addon' );
		$options['typicons']    = esc_html__( 'Typicons', 'extensive-vc-addon' );
		$options['entypo']      = esc_html__( 'Entypo', 'extensive-vc-addon' );
		$options['linecons']    = esc_html__( 'Linecons', 'extensive-vc-addon' );
		$options['monosocial']  = esc_html__( 'Mono Social', 'extensive-vc-addon' );
		$options['material']    = esc_html__( 'Material', 'extensive-vc-addon' );

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_vc_icon_options_array' ) ) {
	/**
	 * Returns array of WPBakery Page Builder icon options for shortcodes panel
	 *
	 * @return array
	 */
	function extensive_vc_get_shortcode_icon_options_array() {

		$options = array(
			array(
				'type'        => 'dropdown',
				'param_name'  => 'icon_library',
				'heading'     => esc_html__( 'Icon Library', 'extensive-vc-addon' ),
				'value'       => array_flip( extensive_vc_get_shortcode_icon_library_array( true ) ),
				'description' => esc_html__( 'Choose icon library', 'extensive-vc-addon' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_fontawesome',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'fontawesome',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'fontawesome' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_openiconic',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'openiconic',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'openiconic' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_typicons',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'typicons',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'typicons' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_entypo',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'entypo',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'entypo' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_linecons',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'linecons',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'linecons' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_monosocial',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'monosocial',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'monosocial' ),
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_material',
				'heading'     => esc_html__( 'Icon', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc-addon' ),
				'settings'    => array(
					'type'         => 'material',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'material' ),
			),
		);

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_button_shortcode_options_array' ) ) {
	/**
	 * Returns array of button shortcode options for shortcodes panel
	 *
	 * @param $without_link boolean - unset link option
	 *
	 * @return array
	 */
	function extensive_vc_get_button_shortcode_options_array( $without_link = false ) {

		$options = array(
			array(
				'type'       => 'vc_link',
				'param_name' => 'button_custom_link',
				'heading'    => esc_html__( 'Button Custom Link', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
			),
			array(
				'type'        => 'dropdown',
				'param_name'  => 'button_type',
				'heading'     => esc_html__( 'Type', 'extensive-vc-addon' ),
				'value'       => array(
					esc_html__( 'Solid', 'extensive-vc-addon' )                       => 'solid',
					esc_html__( 'Outline', 'extensive-vc-addon' )                     => 'outline',
					esc_html__( 'Simple', 'extensive-vc-addon' )                      => 'simple',
					esc_html__( 'Simple Fill Line On Hover', 'extensive-vc-addon' )   => 'fill-line',
					esc_html__( 'Simple Fill Text On Hover', 'extensive-vc-addon' )   => 'fill-text',
					esc_html__( 'Simple Strike Line On Hover', 'extensive-vc-addon' ) => 'strike-line',
					esc_html__( 'Simple Switch Line On Hover', 'extensive-vc-addon' ) => 'switch-line',
				),
				'save_always' => true,
				'dependency'  => array( 'element' => 'button_text', 'not_empty' => true ),
				'group'       => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'        => 'dropdown',
				'param_name'  => 'button_size',
				'heading'     => esc_html__( 'Size', 'extensive-vc-addon' ),
				'value'       => array(
					esc_html__( 'Large', 'extensive-vc-addon' )  => 'large',
					esc_html__( 'Medium', 'extensive-vc-addon' ) => 'medium',
					esc_html__( 'Normal', 'extensive-vc-addon' ) => 'normal',
					esc_html__( 'Small', 'extensive-vc-addon' )  => 'small',
					esc_html__( 'Tiny', 'extensive-vc-addon' )   => 'tiny',
				),
				'save_always' => true,
				'dependency'  => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
				'group'       => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'button_font_size',
				'heading'    => esc_html__( 'Font Size (px or em)', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_color',
				'heading'    => esc_html__( 'Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_hover_color',
				'heading'    => esc_html__( 'Hover Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_text', 'not_empty' => true ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_bg_color',
				'heading'    => esc_html__( 'Background Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid' ) ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_hover_bg_color',
				'heading'    => esc_html__( 'Hover Background Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_border_color',
				'heading'    => esc_html__( 'Border Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_hover_border_color',
				'heading'    => esc_html__( 'Hover Border Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'textfield',
				'param_name' => 'button_border_width',
				'heading'    => esc_html__( 'Border Width (px)', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_type', 'value' => array( 'solid', 'outline' ) ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_line_color',
				'heading'    => esc_html__( 'Line Color', 'extensive-vc-addon' ),
				'dependency' => array(
					'element' => 'button_type',
					'value'   => array( 'fill-line', 'strike-line', 'switch-line' ),
				),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'       => 'colorpicker',
				'param_name' => 'button_switch_line_color',
				'heading'    => esc_html__( 'Switch Line Color', 'extensive-vc-addon' ),
				'dependency' => array( 'element' => 'button_type', 'value' => array( 'switch-line' ) ),
				'group'      => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
			array(
				'type'        => 'textfield',
				'param_name'  => 'button_margin',
				'heading'     => esc_html__( 'Margin', 'extensive-vc-addon' ),
				'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc-addon' ),
				'dependency'  => array( 'element' => 'button_text', 'not_empty' => true ),
				'group'       => esc_html__( 'Button Options', 'extensive-vc-addon' ),
			),
		);

		if ( $without_link ) {
			unset( $options[0] );
		}

		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_button_shortcode_params' ) ) {
	/**
	 * Get button shortcode params
	 *
	 * @param $params array - shortcode parameters value
	 *
	 * @return array
	 */
	function extensive_vc_get_button_shortcode_params( $params ) {
		$options     = array();
		$button_text = $params['button_text'];
		$button_link = isset( $params['button_custom_link'] ) ? $params['button_custom_link'] : '';

		if ( ! empty( $button_text ) ) {
			$options['custom_class'] = ! empty( $params['button_custom_class'] ) ? esc_attr( $params['button_custom_class'] ) : '';
			$options['text']         = esc_attr( $button_text );
			$options['custom_link']  = ! empty( $button_link ) ? $button_link : '#';

			if ( ! empty( $params['button_type'] ) ) {
				$options['type'] = $params['button_type'];
			}

			if ( ! empty( $params['button_size'] ) ) {
				$options['size'] = $params['button_size'];
			}

			if ( ! empty( $params['button_font_size'] ) ) {
				$options['font_size'] = esc_attr( $params['button_font_size'] );
			}

			if ( ! empty( $params['button_color'] ) ) {
				$options['color'] = esc_attr( $params['button_color'] );
			}

			if ( ! empty( $params['button_hover_color'] ) ) {
				$options['hover_color'] = esc_attr( $params['button_hover_color'] );
			}

			if ( ! empty( $params['button_bg_color'] ) ) {
				$options['bg_color'] = esc_attr( $params['button_bg_color'] );
			}

			if ( ! empty( $params['button_hover_bg_color'] ) ) {
				$options['hover_bg_color'] = esc_attr( $params['button_hover_bg_color'] );
			}

			if ( ! empty( $params['button_border_color'] ) ) {
				$options['border_color'] = esc_attr( $params['button_border_color'] );
			}

			if ( ! empty( $params['button_hover_border_color'] ) ) {
				$options['hover_border_color'] = esc_attr( $params['button_hover_border_color'] );
			}

			if ( ! empty( $params['button_border_width'] ) ) {
				$options['border_width'] = esc_attr( $params['button_border_width'] );
			}

			if ( ! empty( $params['button_line_color'] ) ) {
				$options['line_color'] = esc_attr( $params['button_line_color'] );
			}

			if ( ! empty( $params['button_switch_line_color'] ) ) {
				$options['switch_line_color'] = esc_attr( $params['button_switch_line_color'] );
			}

			if ( ! empty( $params['button_margin'] ) ) {
				$options['margin'] = esc_attr( $params['button_margin'] );
			}
		}

		return $options;
	}
}
