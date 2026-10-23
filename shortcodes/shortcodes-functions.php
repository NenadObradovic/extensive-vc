<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_shortcodes' ) ) {
	/**
	 * Include main shortcodes load file
	 */
	function extensive_vc_include_shortcodes() {
		
		foreach ( glob( EXTENSIVE_VC_SHORTCODES_ABS_PATH . '/*/load.php' ) as $shortcode_load ) {
			require_once $shortcode_load;
		}

		// Hook to include additional shortcodes
		do_action( 'extensive_vc_action_include_shortcodes_file' );
	}
	
	add_action( 'init', 'extensive_vc_include_shortcodes', 5 ); // permission 5 is set to be before vc_before_init hook that has permission 9
}

if ( ! function_exists( 'extensive_vc_load_shortcodes' ) ) {
	/**
	 * Register/load all shortcodes
	 */
	function extensive_vc_load_shortcodes() {
		require_once __DIR__ . '/shortcodes-class.php';
		
		ExtensiveVC\Shortcodes\ShortcodesClass::getInstance()->load();
	}
	
	add_action( 'init', 'extensive_vc_load_shortcodes', 6 ); // permission 6 is set to be before vc_before_init hook that has permission 9 and after extensive_vc_include_shortcodes hook
}

if ( ! function_exists( 'extensive_vc_add_admin_shortcodes_icon_styles' ) ) {
	/**
	 * Print custom styles for WPBakery Page Builder shortcodes panel
	 */
	function extensive_vc_add_admin_shortcodes_custom_styles() {
		$style      = apply_filters( 'extensive_vc_filter_add_vc_shortcodes_custom_style', $style = '' );
		$iconStyles = array();
		
		$icons = apply_filters( 'extensive_vc_filter_add_vc_shortcodes_custom_icon', $icons = array() );
		
		if ( ! empty( $icons ) ) {
			foreach ( $icons as $icon ) {
				$module_path = EXTENSIVE_VC_SHORTCODES_URL_PATH;
				$module      = isset( $icon['module'] ) && ! empty( $icon['module'] ) ? esc_attr( $icon['module'] ) : '';
				
				if ( ! empty( $module ) ) {
					switch ( $module ) {
						case 'in_cpt':
							$module_path = EXTENSIVE_VC_CPT_URL_PATH;
							break;
						case 'in_plugins':
							$plugin_module = isset( $icon['plugin_module'] ) && ! empty( $icon['plugin_module'] ) ? esc_attr( $icon['plugin_module'] ) : '';
							
							if ( ! empty( $plugin_module ) ) {
								switch ( $plugin_module ) {
									case 'woocommerce':
										$plugin_module = '/woocommerce/shortcodes';
										break;
								}
							}
							$module_path   = EXTENSIVE_VC_PLUGINS_URL_PATH . $plugin_module;
							break;
					}
				}
				
				$icon_name  = isset( $icon['child_item'] ) && $icon['child_item'] === true ? 'admin_child_icon' : 'admin_icon';
				$admin_icon = $module_path . '/' . esc_attr( $icon['shortcode'] ) . '/assets/img/' . $icon_name . '.png';
				
				if ( ! empty( $admin_icon ) ) {
					$iconStyles[] = '.vc_element-icon.evc-vc-custom-icon' . esc_attr( $icon['class'] ) . ' { background-image: url(' . esc_url( $admin_icon ) . ') !important; }';
				}
			}
		}
		
		if ( ! empty( $iconStyles ) ) {
			$style .= implode( ' ', $iconStyles );
		}
		
		if ( ! empty( $style ) ) {
			wp_add_inline_style( 'extensive-vc-main-admin-style', $style );
		}
	}
	
	add_action( 'extensive_vc_enqueue_additional_admin_scripts', 'extensive_vc_add_admin_shortcodes_custom_styles' );
}

if ( ! function_exists( 'extensive_vc_return_shortcodes_label_array' ) ) {
	/**
	 * Returns array of shortcodes label array
	 *
	 * @return array
	 */
	function extensive_vc_return_shortcodes_label_array() {
		$shortcodes = apply_filters( 'extensive_vc_filter_shortcodes_list', $shortcodes = array() );
		ksort( $shortcodes );
		
		return $shortcodes;
	}
}

if ( ! function_exists( 'extensive_vc_init_shortcode_pagination' ) ) {
	/**
	 * Init shortcode pagination ajax functionality
	 *
	 * @return void
	 */
	function extensive_vc_init_shortcode_pagination() {
		$shortcodeOptions = isset( $_POST['options'] ) && is_array( $_POST['options'] ) ? wp_unslash( $_POST['options'] ) : array(); // phpcs:ignore WordPress.Security.NonceVerification.Missing, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Public read-only endpoint used by cached front-end pages, options are sanitized below.

		if ( empty( $shortcodeOptions ) ) {
			extensive_vc_get_ajax_status( 'error', esc_html__( 'Options are invalid', 'extensive-vc-addon' ) );
		}

		$shortcodeName     = isset( $shortcodeOptions['shortcode_name'] ) && is_scalar( $shortcodeOptions['shortcode_name'] ) ? sanitize_key( $shortcodeOptions['shortcode_name'] ) : '';
		$allowedShortcodes = extensive_vc_get_pagination_shortcodes();

		if ( empty( $shortcodeName ) || ! isset( $allowedShortcodes[ $shortcodeName ] ) ) {
			extensive_vc_get_ajax_status( 'error', esc_html__( 'Options are invalid', 'extensive-vc-addon' ) );
		}

		$shortcode = $allowedShortcodes[ $shortcodeName ];
		$params    = call_user_func( $shortcode['sanitize_callback'], $shortcodeOptions );

		$params['post_type']     = $shortcode['post_type'];
		$params['lang']          = isset( $shortcodeOptions['lang'] ) && is_scalar( $shortcodeOptions['lang'] ) ? sanitize_key( $shortcodeOptions['lang'] ) : '';
		$params['query_results'] = new \WP_Query( extensive_vc_get_shortcode_query_params( $params, $shortcode['post_type'] ) );

		$html = extensive_vc_get_module_template_part( $shortcode['module'], $shortcodeName, 'templates/' . $shortcodeName . '-query', '', $params );

		$data = apply_filters(
			'extensive_vc_filter_pagination_data_return',
			array(
				'html'          => $html,
				'max_pages_num' => intval( $params['query_results']->max_num_pages ),
				'next_page'     => intval( $params['next_page'] ) + 1,
			),
			$params
		);

		extensive_vc_get_ajax_status( 'success', esc_html__( 'Items are loaded', 'extensive-vc-addon' ), $data );
	}
	
	add_action( 'wp_ajax_nopriv_extensive_vc_init_shortcode_pagination', 'extensive_vc_init_shortcode_pagination' );
	add_action( 'wp_ajax_extensive_vc_init_shortcode_pagination', 'extensive_vc_init_shortcode_pagination' );
}

if ( ! function_exists( 'extensive_vc_get_pagination_shortcodes' ) ) {
	/**
	 * Returns list of shortcodes which are allowed to load items through pagination ajax request
	 *
	 * @return array
	 */
	function extensive_vc_get_pagination_shortcodes() {
		$shortcodes = array(
			'blog-list' => array(
				'module'            => 'shortcodes',
				'post_type'         => 'post',
				'sanitize_callback' => 'extensive_vc_sanitize_blog_list_pagination_options',
			),
		);

		$shortcodes = apply_filters( 'extensive_vc_filter_pagination_shortcodes', $shortcodes );

		foreach ( $shortcodes as $key => $shortcode ) {
			if ( ! is_array( $shortcode ) || empty( $shortcode['module'] ) || empty( $shortcode['post_type'] ) || empty( $shortcode['sanitize_callback'] ) || ! is_callable( $shortcode['sanitize_callback'] ) ) {
				unset( $shortcodes[ $key ] );
			}
		}

		return $shortcodes;
	}
}

if ( ! function_exists( 'extensive_vc_sanitize_blog_list_pagination_options' ) ) {
	/**
	 * Sanitize blog list options which are sent through pagination ajax request
	 *
	 * @param $options array - raw options value
	 *
	 * @return array
	 */
	function extensive_vc_sanitize_blog_list_pagination_options( $options ) {
		$get_option = function ( $key, $default = '' ) use ( $options ) {
			return isset( $options[ $key ] ) && is_scalar( $options[ $key ] ) ? (string) $options[ $key ] : $default;
		};

		$type            = $get_option( 'type', 'standard' );
		$order           = strtoupper( $get_option( 'order', 'ASC' ) );
		$orderby         = $get_option( 'orderby', 'date' );
		$number_of_posts = intval( $get_option( 'number_of_posts', get_option( 'posts_per_page' ) ) );
		$excerpt_length  = $get_option( 'excerpt_length' );

		$params = array(
			'type'               => in_array( $type, array( 'standard', 'gallery', 'simple', 'minimal' ), true ) ? $type : 'standard',
			'layout_collections' => sanitize_key( $get_option( 'layout_collections' ) ),
			'number_of_posts'    => $number_of_posts > 0 ? min( $number_of_posts, 100 ) : intval( get_option( 'posts_per_page' ) ),
			'category'           => sanitize_text_field( $get_option( 'category' ) ),
			'orderby'            => array_key_exists( $orderby, extensive_vc_get_query_order_by_array() ) ? $orderby : 'date',
			'order'              => in_array( $order, array( 'ASC', 'DESC' ), true ) ? $order : 'ASC',
			'next_page'          => max( 1, absint( $get_option( 'next_page', 1 ) ) ),
			'image_proportions'  => sanitize_key( $get_option( 'image_proportions', 'full' ) ),
			'title_tag'          => extensive_vc_escape_title_tag( $get_option( 'title_tag', 'h4' ), 'h4' ),
			'excerpt_length'     => '' === $excerpt_length ? '' : intval( $excerpt_length ),
		);

		foreach ( array( 'enable_excerpt', 'enable_category', 'enable_author', 'enable_date' ) as $key ) {
			$params[ $key ] = 'no' === $get_option( $key ) ? 'no' : 'yes';
		}

		return $params;
	}
}

if ( ! function_exists( 'extensive_vc_get_active_language_slug' ) ) {
	/**
	 * Returns current Polylang or WPML language slug, when a multilingual plugin is active
	 *
	 * @return string
	 */
	function extensive_vc_get_active_language_slug() {
		if ( function_exists( 'pll_current_language' ) ) {
			$lang = pll_current_language( 'slug' );

			if ( is_string( $lang ) && '' !== $lang ) {
				return sanitize_key( $lang );
			}
		}

		if ( defined( 'ICL_LANGUAGE_CODE' ) && is_string( ICL_LANGUAGE_CODE ) && '' !== ICL_LANGUAGE_CODE && 'all' !== ICL_LANGUAGE_CODE ) {
			return sanitize_key( ICL_LANGUAGE_CODE );
		}

		return '';
	}
}

if ( ! function_exists( 'extensive_vc_validate_pagination_data' ) ) {
	/**
	 * Validate pagination data element, empty values are removed but zero values are kept
	 *
	 * @param $value mixed
	 *
	 * @return boolean
	 */
	function extensive_vc_validate_pagination_data( $value ) {
		return ! in_array( $value, array( null, false, '' ), true ) && ! is_object( $value );
	}
}

if ( ! function_exists( 'extensive_vc_get_shortcode_pagination_data' ) ) {
	/**
	 * Return array of shortcode pagination data
	 *
	 * @param $module string - name of the module to load
	 * @param $shortcode_name string - shortcode name
	 * @param $post_type string - post type value
	 * @param $params array - shortcode params
	 *
	 * @return string
	 */
	function extensive_vc_get_shortcode_pagination_data( $module, $shortcode_name, $post_type, $params ) {
		$data = '';

		if ( ! empty( $post_type ) && ! empty( $params ) ) {
			$additional_params = array(
				'module'         => sanitize_key( $module ),
				'shortcode_name' => sanitize_key( str_replace( ' ', '-', $shortcode_name ) ),
				'post_type'      => sanitize_key( $post_type ),
				'next_page'      => '2',
				'max_pages_num'  => isset( $params['query_results'] ) ? intval( $params['query_results']->max_num_pages ) : 1,
			);

			$lang = extensive_vc_get_active_language_slug();

			if ( '' !== $lang ) {
				$additional_params['lang'] = $lang;
			}

			// Remove values which are not needed for the pagination request
			foreach ( array( 'query_results', 'holder_classes', 'pagination_data', 'button_params' ) as $key ) {
				unset( $params[ $key ] );
			}

			$data = wp_json_encode( array_filter( array_merge( $additional_params, $params ), 'extensive_vc_validate_pagination_data' ) );
		}

		return $data;
	}
}

if ( ! function_exists( 'extensive_vc_get_shortcode_query_params' ) ) {
	/**
	 * Get shortcode query parameters
	 *
	 * @param $params array - shortcode parameters value
	 * @param $default_post_type string - post type name
	 *
	 * @return array
	 */
	function extensive_vc_get_shortcode_query_params( $params, $default_post_type = 'post' ) {
		$post_type      = isset( $params['post_type'] ) && ! empty( $params['post_type'] ) ? $params['post_type'] : $default_post_type;
		$posts_per_page = $default_post_type === 'post' ? $params['number_of_posts'] : $params['number'];

		$args = array(
			'post_status'         => 'publish',
			'post_type'           => sanitize_key( $post_type ),
			'posts_per_page'      => '' !== $posts_per_page ? intval( $posts_per_page ) : intval( get_option( 'posts_per_page' ) ),
			'ignore_sticky_posts' => 1,
			'orderby'             => $params['orderby'],
			'order'               => $params['order']
		);
		
		if ( ! empty( $params['category'] ) ) {
			if ( $default_post_type === 'post' ) {
				$args['category_name'] = $params['category'];
			} else {
				$args[ $default_post_type . '-category' ] = $params['category'];
			}
		}
	
		// Ajax pagination is using next_page value, page query var belongs to the main query
		$args['paged'] = ! empty( $params['next_page'] ) ? max( 1, intval( $params['next_page'] ) ) : 1;

		// Multilingual plugins support (Polylang and WPML)
		$lang = ! empty( $params['lang'] ) && is_string( $params['lang'] ) ? sanitize_key( $params['lang'] ) : extensive_vc_get_active_language_slug();

		if ( '' !== $lang ) {
			$args['lang'] = $lang;

			if ( has_action( 'wpml_switch_language' ) ) {
				do_action( 'wpml_switch_language', $lang ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- WPML hook.
			}
		}

		return apply_filters( 'extensive_vc_filter_query_params', $args, $params, $default_post_type );
	}
}
