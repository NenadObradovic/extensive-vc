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
		
		switch ( $module ) {
			case 'woocommerce':
				$module        = EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce/shortcodes';
				break;
			case 'woocommerce-part':
				$module        = EXTENSIVE_VC_PLUGINS_ABS_PATH . '/woocommerce';
				break;
			case 'cpt':
				$module        = EXTENSIVE_VC_CPT_ABS_PATH;
				$template_path = 'shortcodes/' . $template_path;
				break;
			case 'shortcodes':
				$module = EXTENSIVE_VC_SHORTCODES_ABS_PATH;
				break;
			default:
				$module = EXTENSIVE_VC_SHORTCODES_ABS_PATH;
				break;
		}
		
		$file_extension            = '.php';
		
		$theme_file_path           = EXTENSIVE_VC_THEME_ROOT_PATH . '/extensive-vc/' . $shortcode . '/' . $template_path;
		$theme_file_with_slug_path = "{$theme_file_path}-{$slug}{$file_extension}";
		$full_theme_path           = $theme_file_path . $file_extension;
		
		$file_path                 = file_exists( $full_theme_path ) ? $theme_file_path : $module . '/' . $shortcode . '/' . $template_path;
		$file_with_slug_path       = file_exists( $theme_file_with_slug_path ) ? $theme_file_with_slug_path : "{$file_path}-{$slug}{$file_extension}";
		$full_file_path            = $file_path . $file_extension;
		
		
		$template = '';
		
		if ( file_exists( $full_file_path ) || file_exists( $file_with_slug_path ) ) {
			$template = file_exists( $file_with_slug_path ) ? $file_with_slug_path : $full_file_path;
		}
		
		if ( is_array( $params ) && count( $params ) ) {
			extract( $params );
		}
		
		$html = '';
		
		if ( $template ) {
			ob_start();
			include( $template );
			
			$html = ob_get_clean();
		}
		
		return $html;
	}
}

if ( ! function_exists( 'extensive_vc_get_ajax_status' ) ) {
	/**
	 * Return response status from ajax functions
	 *
	 * @param $status string - success or error
	 * @param $message string - ajax response message
	 * @param $data string|html - response data
	 *
	 * @return void
	 */
	function extensive_vc_get_ajax_status( $status, $message, $data = null ) {
		$response = array(
			'status'   => $status,
			'message'  => $message,
			'data'     => $data
		);
		
		$output = json_encode( $response );
		
		exit( $output );
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
				if ( ! empty ( $key ) ) {
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
	 * @param $value string | array - attribute value
	 *
	 * @see extensive_vc_get_inline_style()
	 */
	function extensive_vc_print_inline_style( $value ) {
		echo extensive_vc_get_inline_style( $value );
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_style' ) ) {
	/**
	 * Generates style attribute and returns generated string
	 *
	 * @param $value string | array - value of style attribute
	 *
	 * @return string generated style attribute
	 *
	 * @see extensive_vc_get_inline_style()
	 */
	function extensive_vc_get_inline_style( $value ) {
		return extensive_vc_get_inline_attr( $value, 'style', ';' );
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_attr' ) ) {
	/**
	 * Generates html attribute
	 *
	 * @param $value string | array - value of html attribute
	 * @param $attr string - name of html attribute to generate
	 * @param $glue string - glue with which to implode $attr. Used only when $attr is array
	 * @param $allow_zero_values boolean - allow data to have zero value
	 *
	 * @return string generated html attribute
	 */
	function extensive_vc_get_inline_attr( $value, $attr, $glue = '', $allow_zero_values = false ) {
		$flag = $allow_zero_values ? $value !== '' : ! empty( $value );
		
		if ( $flag ) {
			if ( is_array( $value ) && count( $value ) ) {
				$properties = implode( $glue, $value );
			} elseif ( $value !== '' ) {
				$properties = $value;
			}
			
			return $attr . '="' . esc_attr( $properties ) . '"';
		}
		
		return '';
	}
}

if ( ! function_exists( 'extensive_vc_get_inline_attrs' ) ) {
	/**
	 * Generate multiple inline attributes
	 *
	 * @param $attributes array
	 * @param $allow_zero_values boolean
	 *
	 * @return string
	 */
	function extensive_vc_get_inline_attrs( $attributes, $allow_zero_values = false ) {
		$output = '';
		
		if ( is_array( $attributes ) && count( $attributes ) ) {
			if ( $allow_zero_values ) {
				foreach ( $attributes as $attr => $value ) {
					$output .= ' ' . extensive_vc_get_inline_attr( $value, $attr, '', true );
				}
			} else {
				foreach ( $attributes as $attr => $value ) {
					$output .= ' ' . extensive_vc_get_inline_attr( $value, $attr );
				}
			}
		}
		
		$output = ltrim( $output );
		
		return $output;
	}
}

if ( ! function_exists( 'extensive_vc_print_inline_attrs' ) ) {
	/**
	 * Print generated attribute
	 *
	 * @param $attributes array
	 * @param $allow_zero_values boolean
	 *
	 * @return string
	 *
	 * @see extensive_vc_get_inline_attrs()
	 */
	function extensive_vc_print_inline_attrs( $attributes, $allow_zero_values = false ) {
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
		if ( $haystack !== '' && $needle !== '' ) {
			return ( substr( $haystack, - strlen( $needle ), strlen( $needle ) ) == $needle );
		}
		
		return true;
	}
}

if ( ! function_exists( 'extensive_vc_filter_px' ) ) {
	/**
	 * Removes px in provided value if value ends with px
	 *
	 * @param $value string
	 *
	 * @return string
	 */
	function extensive_vc_filter_px( $value ) {
		$suffix = 'px';
		
		if ( $value !== '' && extensive_vc_string_ends_with( $value, $suffix ) ) {
			$value = substr( $value, 0, strpos( $value, $suffix ) );
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
				'img_height' => $height
			);
		}
		
		return $return_array;
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
			
			return call_user_func( array(
				$shortcode_array[0],
				$shortcode_array[1]
			), $atts, $content, $shortcode_tag );
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
			
			if ( ! empty( $link ) ) {
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['one']   = esc_html__( 'One', 'extensive-vc' );
		$options['two']   = esc_html__( 'Two', 'extensive-vc' );
		$options['three'] = esc_html__( 'Three', 'extensive-vc' );
		$options['four']  = esc_html__( 'Four', 'extensive-vc' );
		$options['five']  = esc_html__( 'Five', 'extensive-vc' );
		$options['six']   = esc_html__( 'Six', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['large']  = esc_html__( 'Large (50px)', 'extensive-vc' );
		$options['medium'] = esc_html__( 'Medium (40px)', 'extensive-vc' );
		$options['normal'] = esc_html__( 'Normal (30px)', 'extensive-vc' );
		$options['small']  = esc_html__( 'Small (20px)', 'extensive-vc' );
		$options['tiny']   = esc_html__( 'Tiny (10px)', 'extensive-vc' );
		$options['no']     = esc_html__( 'No', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['date']       = esc_html__( 'Date', 'extensive-vc' );
		$options['ID']         = esc_html__( 'ID', 'extensive-vc' );
		$options['menu_order'] = esc_html__( 'Menu Order', 'extensive-vc' );
		$options['name']       = esc_html__( 'Post Name', 'extensive-vc' );
		$options['rand']       = esc_html__( 'Random', 'extensive-vc' );
		$options['title']      = esc_html__( 'Title', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['ASC']  = esc_html__( 'Ascending', 'extensive-vc' );
		$options['DESC'] = esc_html__( 'Descending', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		if ( $set_yes_to_be_first ) {
			$options['yes'] = esc_html__( 'Yes', 'extensive-vc' );
			$options['no']  = esc_html__( 'No', 'extensive-vc' );
		} else {
			$options['no']  = esc_html__( 'No', 'extensive-vc' );
			$options['yes'] = esc_html__( 'Yes', 'extensive-vc' );
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['_self']  = esc_html__( 'Same Window', 'extensive-vc' );
		$options['_blank'] = esc_html__( 'New Window', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['100'] = esc_html__( '100 Thin', 'extensive-vc' );
		$options['200'] = esc_html__( '200 Thin-Light', 'extensive-vc' );
		$options['300'] = esc_html__( '300 Light', 'extensive-vc' );
		$options['400'] = esc_html__( '400 Normal', 'extensive-vc' );
		$options['500'] = esc_html__( '500 Medium', 'extensive-vc' );
		$options['600'] = esc_html__( '600 Semi-Bold', 'extensive-vc' );
		$options['700'] = esc_html__( '700 Bold', 'extensive-vc' );
		$options['800'] = esc_html__( '800 Extra-Bold', 'extensive-vc' );
		$options['900'] = esc_html__( '900 Ultra-Bold', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['normal']  = esc_html__( 'Normal', 'extensive-vc' );
		$options['italic']  = esc_html__( 'Italic', 'extensive-vc' );
		$options['oblique'] = esc_html__( 'Oblique', 'extensive-vc' );
		$options['initial'] = esc_html__( 'Initial', 'extensive-vc' );
		$options['inherit'] = esc_html__( 'Inherit', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['none']       = esc_html__( 'None', 'extensive-vc' );
		$options['capitalize'] = esc_html__( 'Capitalize', 'extensive-vc' );
		$options['uppercase']  = esc_html__( 'Uppercase', 'extensive-vc' );
		$options['lowercase']  = esc_html__( 'Lowercase', 'extensive-vc' );
		$options['initial']    = esc_html__( 'Initial', 'extensive-vc' );
		$options['inherit']    = esc_html__( 'Inherit', 'extensive-vc' );
		
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
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['none']         = esc_html__( 'None', 'extensive-vc' );
		$options['underline']    = esc_html__( 'Underline', 'extensive-vc' );
		$options['overline']     = esc_html__( 'Overline', 'extensive-vc' );
		$options['line-through'] = esc_html__( 'Line-Through', 'extensive-vc' );
		$options['initial']      = esc_html__( 'Initial', 'extensive-vc' );
		$options['inherit']      = esc_html__( 'Inherit', 'extensive-vc' );
		
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
		
		$options['']                = esc_html__( 'None', 'extensive-vc' );
		$options['overlay']         = esc_html__( 'Overlay', 'extensive-vc' );
		$options['zoom']            = esc_html__( 'Zoom', 'extensive-vc' );
		$options['lightbox']        = esc_html__( 'Lightbox', 'extensive-vc' );
		$options['top-moving']      = esc_html__( 'Top Moving', 'extensive-vc' );
		$options['circle-fade-out'] = esc_html__( 'Circle Fade Out', 'extensive-vc' );
		$options['bordered']        = esc_html__( 'Bordered', 'extensive-vc' );
		
		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_shortcode_icon_library_array' ) ) {
	/**
	 * Returns array of Visual Composer icon libraries
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_shortcode_icon_library_array( $first_empty = false ) {
		$options = array();
		
		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		$options['fontawesome'] = esc_html__( 'Font Awesome', 'extensive-vc' );
		$options['openiconic']  = esc_html__( 'Open Iconic', 'extensive-vc' );
		$options['typicons']    = esc_html__( 'Typicons', 'extensive-vc' );
		$options['entypo']      = esc_html__( 'Entypo', 'extensive-vc' );
		$options['linecons']    = esc_html__( 'Linecons', 'extensive-vc' );
		$options['monosocial']  = esc_html__( 'Mono Social', 'extensive-vc' );
		$options['material']    = esc_html__( 'Material', 'extensive-vc' );
		
		return $options;
	}
}

if ( ! function_exists( 'extensive_vc_get_vc_icon_options_array' ) ) {
	/**
	 * Returns array of Visual Composer icon options for shortcodes panel
	 *
	 * @return array
	 */
	function extensive_vc_get_shortcode_icon_options_array() {
		
		$options = array(
			array(
				'type'        => 'dropdown',
				'param_name'  => 'icon_library',
				'heading'     => esc_html__( 'Icon Library', 'extensive-vc' ),
				'value'       => array_flip( extensive_vc_get_shortcode_icon_library_array( true ) ),
				'description' => esc_html__( 'Choose icon library', 'extensive-vc' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_fontawesome',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'fontawesome',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'fontawesome' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_openiconic',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'openiconic',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'openiconic' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_typicons',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'typicons',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'typicons' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_entypo',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'entypo',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'entypo' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_linecons',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'linecons',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'linecons' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_monosocial',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'monosocial',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'monosocial' )
			),
			array(
				'type'        => 'iconpicker',
				'param_name'  => 'icon_material',
				'heading'     => esc_html__( 'Icon', 'extensive-vc' ),
				'description' => esc_html__( 'Select icon from library', 'extensive-vc' ),
				'settings'    => array(
					'type'         => 'material',
					'emptyIcon'    => false, // default true, display an "EMPTY" icon?
					'iconsPerPage' => 200, // default 100, how many icons per/page to display
				),
				'dependency'  => array( 'element' => 'icon_library', 'value' => 'material' )
			)
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
				'type'        => 'textfield',
				'param_name'  => 'button_margin',
				'heading'     => esc_html__( 'Margin', 'extensive-vc' ),
				'description' => esc_html__( 'Insert margin in format: top right bottom left (e.g. 10px 5px 10px 5px)', 'extensive-vc' ),
				'dependency'  => array( 'element' => 'button_text', 'not_empty' => true ),
				'group'       => esc_html__( 'Button Options', 'extensive-vc' )
			)
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
		$options = array();
		$button_text = $params['button_text'];
		$button_link = isset( $params['button_custom_link'] ) ? $params['button_custom_link'] : '';
		
		if ( ! empty( $button_text ) ) {
			$options['custom_class'] = ! empty( $params['button_custom_class'] ) ? esc_attr( $params['button_custom_class'] ) : '';
			$options['text']         = esc_attr( $button_text );
			$options['custom_link']  = ! empty( $button_link ) ? esc_url( $button_link ) : '#';
			
			if ( ! empty( $params['button_type'] ) ) {
				$options['type'] = esc_attr( $params['button_type'] );
			}
			
			if ( ! empty( $params['button_size'] ) ) {
				$options['size'] = esc_attr( $params['button_size'] );
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
