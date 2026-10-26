<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_get_elements_map' ) ) {
	/**
	 * Returns list of all enabled elements, where key is shortcode base and value is array with element slug and child flag
	 *
	 * @return array
	 */
	function extensive_vc_get_elements_map() {
		static $elements = null;

		if ( null === $elements ) {
			$map = apply_filters( 'extensive_vc_filter_elements_map', array() );

			// Elements are added on init hook, so don't cache incomplete list
			if ( ! did_action( 'init' ) ) {
				return $map;
			}

			$elements = $map;
		}

		return $elements;
	}
}

if ( ! function_exists( 'extensive_vc_get_assets_loading_mode' ) ) {
	/**
	 * Returns assets loading mode
	 *
	 * @return string - elements (load assets only for used elements) or all (load all assets on every page)
	 */
	function extensive_vc_get_assets_loading_mode() {
		$mode = 'elements';

		$evc_options = extensive_vc_get_global_options();

		if ( ! empty( $evc_options ) && 'all' === $evc_options->options->getOptionValueById( 'evc_assets_loading' ) ) {
			$mode = 'all';
		}

		// Readable scripts and styles are bundled into one file
		if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
			$mode = 'all';
		}

		// Elements are added dynamically in WPBakery Page Builder front-end editor, so all assets are needed
		if ( extensive_vc_is_page_builder_editor() ) {
			$mode = 'all';
		}

		return apply_filters( 'extensive_vc_filter_assets_loading_mode', $mode );
	}
}

if ( ! function_exists( 'extensive_vc_is_page_builder_editor' ) ) {
	/**
	 * Checks if current page is opened in WPBakery Page Builder front-end editor
	 *
	 * @return boolean
	 */
	function extensive_vc_is_page_builder_editor() {
		return ( function_exists( 'vc_is_inline' ) && vc_is_inline() ) || ( function_exists( 'vc_is_page_editable' ) && vc_is_page_editable() );
	}
}

if ( ! function_exists( 'extensive_vc_get_element_assets_dependencies' ) ) {
	/**
	 * Returns 3rd party script dependencies for element script
	 *
	 * @param $slug string - element slug
	 *
	 * @return array
	 */
	function extensive_vc_get_element_assets_dependencies( $slug ) {
		$dependencies = array(
			'counter'              => array( 'jquery-countto' ),
			'progress-bar'         => array( 'jquery-countto' ),
			'doughnut-chart'       => array( 'chart-js' ),
			'pie-chart'            => array( 'chart-js' ),
			'line-graph'           => array( 'chart-js' ),
			'full-screen-sections' => array( 'fullpage' ),
			'tabs'                 => array( 'jquery-ui-tabs' ),
		);

		$dependencies = apply_filters( 'extensive_vc_filter_element_assets_dependencies', $dependencies );

		return isset( $dependencies[ $slug ] ) ? (array) $dependencies[ $slug ] : array();
	}
}

if ( ! function_exists( 'extensive_vc_enqueue_element_assets' ) ) {
	/**
	 * Enqueue element stylesheet and script, used when assets are loaded only for used elements
	 *
	 * @param $base string - element shortcode base
	 */
	function extensive_vc_enqueue_element_assets( $base ) {
		static $enqueued = array();

		$elements = extensive_vc_get_elements_map();

		if ( ! isset( $elements[ $base ]['slug'] ) || 'all' === extensive_vc_get_assets_loading_mode() ) {
			return;
		}

		$slug = sanitize_key( $elements[ $base ]['slug'] );

		if ( isset( $enqueued[ $slug ] ) ) {
			return;
		}

		$enqueued[ $slug ] = true;

		wp_enqueue_style( 'extensive-vc-main-style' );
		wp_enqueue_script( 'extensive-vc-main-script' );

		$style = '/css/elements/' . $slug . '.min.css';

		if ( file_exists( EXTENSIVE_VC_ASSETS_ABS_PATH . $style ) ) {
			wp_enqueue_style( 'extensive-vc-' . $slug, EXTENSIVE_VC_ASSETS_URL_PATH . $style, array( 'extensive-vc-main-style' ), EXTENSIVE_VC_VERSION );
		}

		$script = '/js/elements/' . $slug . '.min.js';

		if ( file_exists( EXTENSIVE_VC_ASSETS_ABS_PATH . $script ) ) {
			wp_enqueue_script( 'extensive-vc-' . $slug, EXTENSIVE_VC_ASSETS_URL_PATH . $script, array_merge( array( 'extensive-vc-main-script' ), extensive_vc_get_element_assets_dependencies( $slug ) ), EXTENSIVE_VC_VERSION, true );
		}

		// Hook to enqueue additional assets for element
		do_action( 'extensive_vc_action_enqueue_element_assets', $slug, $base );
	}
}

if ( ! function_exists( 'extensive_vc_enqueue_used_elements_assets' ) ) {
	/**
	 * Enqueue assets in the page head for elements which are used in the current page content,
	 * elements rendered from other places (widgets, templates...) load their assets in the footer
	 */
	function extensive_vc_enqueue_used_elements_assets() {
		if ( 'all' === extensive_vc_get_assets_loading_mode() ) {
			return;
		}

		$content = '';

		if ( is_singular() ) {
			$content = (string) get_post_field( 'post_content', get_queried_object_id() );
		}

		// Hook to add additional content which should be checked for elements, e.g. content of custom templates
		$content = apply_filters( 'extensive_vc_filter_elements_assets_content', $content );

		if ( empty( $content ) || false === strpos( $content, '[evc_' ) ) {
			return;
		}

		preg_match_all( '/\[(evc_[a-z0-9_]+)/', $content, $matches );

		foreach ( array_unique( $matches[1] ) as $base ) {
			extensive_vc_enqueue_element_assets( $base );
		}
	}

	add_action( 'wp_enqueue_scripts', 'extensive_vc_enqueue_used_elements_assets', 20 );
}

if ( ! function_exists( 'extensive_vc_enqueue_element_assets_on_render' ) ) {
	/**
	 * Enqueue element assets before element is rendered, used for elements which are not detected in the page content
	 *
	 * @param $return false|string - short-circuit return value
	 * @param $tag string - shortcode name
	 *
	 * @return false|string
	 */
	function extensive_vc_enqueue_element_assets_on_render( $return, $tag ) {
		if ( 0 === strpos( $tag, 'evc_' ) ) {
			extensive_vc_enqueue_element_assets( $tag );
		}

		return $return;
	}

	add_filter( 'pre_do_shortcode_tag', 'extensive_vc_enqueue_element_assets_on_render', 10, 2 );
}
