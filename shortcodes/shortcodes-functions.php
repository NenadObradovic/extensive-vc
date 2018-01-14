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
		include_once EXTENSIVE_VC_SHORTCODES_ABS_PATH . '/shortcodes-class.php';
		
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
				$module     = isset( $icon['module'] ) && $icon['module'] === true ? EXTENSIVE_VC_CPT_URL_PATH : EXTENSIVE_VC_SHORTCODES_URL_PATH;
				$icon_name  = isset( $icon['child_item'] ) && $icon['child_item'] === true ? 'admin_child_icon' : 'admin_icon';
				$admin_icon = $module . '/' . esc_attr( $icon['shortcode'] ) . '/assets/img/' . $icon_name . '.png';
				
				if ( ! empty( $admin_icon ) ) {
					$iconStyles[] = '.vc_element-icon.evc-vc-custom-icon' . esc_attr( $icon['class'] ) . ' {
						background-image: url(' . esc_url( $admin_icon ) . ') !important;
					}';
				}
			}
		}
		
		if ( ! empty( $iconStyles ) ) {
			$style .= implode( ' ', $iconStyles );
		}
		
		if ( ! empty( $style ) ) {
			wp_add_inline_style( 'extensive_vc_main_admin_style', $style );
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