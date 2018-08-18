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
			include_once $shortcode_load;
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
		include_once 'shortcodes-class.php';
		
		ExtensiveVC\Shortcodes\ShortcodesClass::getInstance()->load();
	}
	
	add_action( 'init', 'extensive_vc_load_shortcodes', 6 ); // permission 6 is set to be before vc_before_init hook that has permission 9 and after extensive_vc_include_shortcodes hook
}

if ( ! function_exists( 'extensive_vc_add_admin_shortcodes_icon_styles' ) ) {
	/**
	 * Print custom styles for Visual Composer shortcodes panel
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
		sort( $shortcodes );
		
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
		
		if ( ! isset( $_POST ) || empty( $_POST ) ) {
			extensive_vc_get_ajax_status( 'error', esc_html__( 'Post is invalid', 'extensive-vc' ) );
		} else {
			$shortcodeOptions = isset( $_POST['options'] ) ? $_POST['options'] : '';
			
			if ( ! empty( $shortcodeOptions ) ) {
				$moduleName    = $shortcodeOptions['module'];
				$shortcodeName = $shortcodeOptions['shortcode_name'];
				$query_args    = extensive_vc_get_shortcode_query_params( $shortcodeOptions );
				
				$shortcodeOptions['query_results'] = new \WP_Query( $query_args );
				
				ob_start();
				
				echo extensive_vc_get_module_template_part( $moduleName, $shortcodeName, 'templates/' . $shortcodeName . '-query', '', $shortcodeOptions );
				
				$html = ob_get_contents();
				
				ob_end_clean();
				
				extensive_vc_get_ajax_status( 'success', esc_html__( 'Items are loaded', 'extensive-vc' ), $html );
			} else {
				extensive_vc_get_ajax_status( 'error', esc_html__( 'Options are invalid', 'extensive-vc' ) );
			}
		}
	}
	
	add_action( 'wp_ajax_nopriv_extensive_vc_init_shortcode_pagination', 'extensive_vc_init_shortcode_pagination' );
	add_action( 'wp_ajax_extensive_vc_init_shortcode_pagination', 'extensive_vc_init_shortcode_pagination' );
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
	 * @return void
	 */
	function extensive_vc_get_shortcode_pagination_data( $module, $shortcode_name, $post_type, $params ) {
		$data = array();
		
		if ( ! empty( $post_type ) && ! empty( $params ) ) {
			$additional_params = array(
				'module'         => esc_attr( $module ),
				'shortcode_name' => strtolower( str_replace( ' ', '-', esc_attr( $shortcode_name ) ) ),
				'post_type'      => esc_attr( $post_type ),
				'next_page'      => '2',
				'max_pages_num'  => $params['query_results']->max_num_pages
			);
			
			unset( $params['query_results'] );
			
			$data = json_encode( array_filter( array_merge( $additional_params, $params ) ) );
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
		$post_type = isset( $params['post_type'] ) && ! empty( $params['post_type'] ) ? $params['post_type'] : $default_post_type;
		
		$args = array(
			'post_status'         => 'publish',
			'post_type'           => esc_attr( $post_type ),
			'posts_per_page'      => $default_post_type === 'post' ? $params['number_of_posts'] : $params['number'],
			'ignore_sticky_posts' => 1,
			'orderby'             => $params['orderby'],
			'order'               => $params['order']
		);
		
		if ( ! empty( $params['category'] ) ) {
			if ( $default_post_type === 'post' ) {
				$args['category'] = $params['category'];
			} else {
				$args[ $default_post_type . '-category' ] = $params['category'];
			}
		}
		
		if ( ! empty( $params['next_page'] ) ) {
			$args['paged'] = $params['next_page'];
		} else {
			$args['paged'] = 1;
		}
		
		return $args;
	}
}
