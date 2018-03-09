<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_include_widgets' ) ) {
	/**
	 * Include main widgets load file
	 */
	function extensive_vc_include_widgets() {
		
		foreach ( glob( EXTENSIVE_VC_SHORTCODES_ABS_PATH . '/*/widgets/load.php' ) as $widget_load ) {
			include_once $widget_load;
		}
		
		// Hook to include additional widgets
		do_action( 'extensive_vc_action_include_widgets_file' );
	}
	
	add_action( 'init', 'extensive_vc_include_widgets', 0 ); // permission 0 is set to be before widgets_init hook
}

if ( ! function_exists( 'extensive_vc_register_widgets' ) ) {
	/**
	 * Register all widgets
	 */
	function extensive_vc_register_widgets() {
		$widgets = apply_filters( 'extensive_vc_filter_register_widgets', $widgets = array() );
		
		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				$isEnabled = extensive_vc_check_is_widget_enabled( $widget );
				
				if ( $isEnabled ) {
					register_widget( $widget );
				}
			}
		}
	}
	
	add_action( 'widgets_init', 'extensive_vc_register_widgets' );
}

if ( ! function_exists( 'extensive_vc_return_widgets_label_array' ) ) {
	/**
	 * Returns array of widgets label array
	 *
	 * @return array
	 */
	function extensive_vc_return_widgets_label_array() {
		$widgets         = apply_filters( 'extensive_vc_filter_widgets_list', $widgets = array() );
		$modifiedWidgets = array();
		
		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget ) {
				$removeStrings = str_replace( 'EVC', '', $widget );
				$optionValue   = ltrim( preg_replace( '/(?<!\ )[A-Z]/', ' $0', $removeStrings ) );
				
				$modifiedWidgets[ $widget ] = $optionValue;
			}
			
			ksort( $modifiedWidgets );
		}
		
		return $modifiedWidgets;
	}
}

if ( ! function_exists( 'extensive_vc_check_is_widget_enabled' ) ) {
	/**
	 * Check is widget enabled throw plugin option
	 *
	 * @param $widget string - widget class name
	 *
	 * @return boolean
	 */
	function extensive_vc_check_is_widget_enabled( $widget ) {
		$evcOptions = get_option( 'evc_options' );
		
		$optionValue = ! empty( $evcOptions ) && array_key_exists( $widget, $evcOptions ) ? $evcOptions[ $widget ] : '';
		$returnValue = $optionValue !== '' && intval( $optionValue ) === 1 ? false : true;
		
		return $returnValue;
	}
}