<?php
/*
Plugin Name: Extensive VC Addons for WPBakery Page Builder
Plugin URI: http://wprealize.com/
Author: Nenad Obradovic
Author URI: http://wprealize.com/introduced/
Version: 2.0
Requires at least: 6.0
Requires PHP: 8.2
WC requires at least: 8.0
WC tested up to: 11.2
Description: WordPress plugin which allows you to add unique, flexible and fully responsive shortcodes. It is an addon for premium plugin WPBakery page builder.
Text Domain: extensive-vc-addon
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'Extensive_VC_Addon' ) ) {
	class Extensive_VC_Addon {
		
		/**
		 * Singleton class
		 */
		private static $instance;
		
		/**
		 * Get the instance of Extensive_VC_Addon
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
		 * Constructor
		 */
		private function __construct() {
			require_once __DIR__ . '/constants.php';
			
			// Plugin activation hook
			register_activation_hook( __FILE__, array( $this, 'initActivationHook' ) );
			
			// Plugin init hook
			add_action( 'plugins_loaded', array( $this, 'init' ) );

			// Declare compatibility with WooCommerce features
			add_action( 'before_woocommerce_init', array( $this, 'declareWooCommerceCompatibility' ) );
		}

		/**
		 * Declare compatibility with WooCommerce High-Performance Order Storage and Cart/Checkout blocks
		 */
		function declareWooCommerceCompatibility() {
			if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'cart_checkout_blocks', __FILE__, true );
			}
		}

		/**
		 * Init hooks on plugin activation
		 */
		function initActivationHook() {
			
			if ( ! is_network_admin() ) {
				set_transient( '_extensive_vc_admin_about_page_redirect', 1, 30 );
			}
		}
		
		/**
		 * Init plugin
		 */
		function init() {
			// Plugin functionality depends on WPBakery Page Builder, so without it only admin notice is displayed
			if ( ! extensive_vc_is_wpbakery_page_builder_installed() ) {
				if ( ( is_multisite() && is_network_admin() ) || ! is_multisite() ) {
					add_action( 'admin_notices', array( $this, 'adminNoticeWhenWPBakeryNotInstalled' ) );
				}

				return;
			}

			require_once EXTENSIVE_VC_ABS_PATH . '/load.php';

			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyles' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueInlineStyles' ) );
			
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueueAdminScripts' ) );
			
			add_filter( 'plugin_action_links', array( $this, 'setPluginActionLinks' ), 10, 2 );
		}
		
/**
		 * Enqueue plugin scripts
		 */
		function enqueueStyles() {
			// Hook to enqueue additional scripts before main css
			do_action( 'extensive_vc_enqueue_additional_scripts_before_main_css' );
			
			// Enqueue main plugin css
			wp_enqueue_style( 'extensive-vc-main-style', EXTENSIVE_VC_ASSETS_URL_PATH . '/css/main' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.css', array(), EXTENSIVE_VC_VERSION );
			
			// Register 3rd party libraries, shortcodes enqueue them only when they are rendered
			$this->registerThirdPartyScripts();

			// Hook to enqueue additional scripts before main js
			do_action( 'extensive_vc_enqueue_additional_scripts_before_main_js' );
			
			// Enqueue main plugin js
			wp_enqueue_script( 'extensive-vc-main-script', EXTENSIVE_VC_ASSETS_URL_PATH . '/js/main' . ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' ) . '.js', array( 'jquery' ), EXTENSIVE_VC_VERSION, true );
			
			// Extend main plugin js with additional variables
			$evc_global_variables = array(
				'adminAjaxUrl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
				'sliderNavPrevArrow' => extensive_vc_get_svg_icon( 'arrow-left' ),
				'sliderNavNextArrow' => extensive_vc_get_svg_icon( 'arrow-right' ),
			);
			
			$evc_global_variables = apply_filters( 'extensive_vc_filter_main_js_global_variables', $evc_global_variables );
			
			wp_localize_script( 'extensive-vc-main-script', 'evcVars', array(
				'global' => $evc_global_variables
			) );
			
			// Hook to enqueue additional scripts
			do_action( 'extensive_vc_enqueue_additional_scripts' );
		}
		
		/**
		 * Register 3rd party scripts and styles
		 */
		function registerThirdPartyScripts() {
			$plugins_url = EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins';

			wp_register_script( 'jquery-countto', $plugins_url . '/counter/counter.js', array( 'jquery' ), EXTENSIVE_VC_VERSION, true );
			wp_register_script( 'chart-js', $plugins_url . '/chart/chart.umd.min.js', array(), '4.5.1', true );
			wp_register_script( 'fullpage', $plugins_url . '/fullpage/jquery.fullPage.min.js', array( 'jquery' ), '2.9.7', true );

			wp_register_style( 'owl-carousel', $plugins_url . '/owl-carousel/owl.carousel.min.css', array(), '2.3.4' );
			wp_register_script( 'owl-carousel', $plugins_url . '/owl-carousel/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );

			wp_register_style( 'lightbox', $plugins_url . '/lightbox/lightbox.min.css', array(), '2.12.0' );
			wp_register_script( 'lightbox', $plugins_url . '/lightbox/lightbox.min.js', array( 'jquery' ), '2.12.0', true );
		}

		/**
		 * Print custom css style
		 */
		function enqueueInlineStyles() {
			
			// Hook to add custom inline css style after main css
			$style = apply_filters( 'extensive_vc_filter_main_custom_style', $style = '' );
			
			if ( ! empty( $style ) ) {
				wp_add_inline_style( 'extensive-vc-main-style', $style );
			}
		}
		
		/**
		 * Enqueue plugin scripts for admin panel
		 */
		function enqueueAdminScripts() {
			
			// Enqueue color picker plugin css and js files
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			
			// Enqueue main plugin admin css
			wp_enqueue_style( 'extensive-vc-main-admin-style', EXTENSIVE_VC_ASSETS_URL_PATH . '/css/admin/main-admin.min.css', array(), EXTENSIVE_VC_VERSION );
			
			// Enqueue main plugin admin js
			wp_enqueue_script( 'extensive-vc-main-admin-script', EXTENSIVE_VC_ASSETS_URL_PATH . '/js/admin/main-admin.min.js', array( 'jquery' ), EXTENSIVE_VC_VERSION, true );
			
			// Hook to enqueue additional scripts for admin panel
			do_action( 'extensive_vc_enqueue_additional_admin_scripts' );
		}
		
		/**
		 * Add Settings link in plugin's page
		 *
		 * @param array $links
		 * @param string $file - path of main plugin file
		 *
		 * @return array
		 */
		public function setPluginActionLinks( $links, $file ) {
			
			if ( plugin_basename( __FILE__ ) === $file ) {
				$title = esc_html__( 'Extensive VC Settings', 'extensive-vc-addon' );
				$url   = add_query_arg( array( 'page' => 'evc-admin-options-page' ), admin_url( 'admin.php' ) );
				$label = esc_html__( 'Settings', 'extensive-vc-addon' );
				
				$link = '<a title="' . esc_attr( $title ) . '" href="' . esc_url( $url ) . '">' . $label . '</a>';
				
				array_unshift( $links, $link );
			}
			
			return $links;
		}
		
		/**
		 * Display admin notice error if WPBakery Page Builder plugin is not installed
		 */
		function adminNoticeWhenWPBakeryNotInstalled() {
			printf(
				'<div class="notice notice-error evc-admin-notice evc-notice-vc-not-installed"><p>%s<strong>%s</strong>%s</p></div>',
				esc_html__( 'The', 'extensive-vc-addon' ),
				esc_html__( ' Extensive VC Addons', 'extensive-vc-addon' ),
				esc_html__( ' plugin requires WPBakery Page Builder plugin. Please install and activate it.', 'extensive-vc-addon' )
			);
		}
	}
}

Extensive_VC_Addon::getInstance();

if ( ! function_exists( 'extensive_vc_is_wpbakery_page_builder_installed' ) ) {
	/**
	 * Checks if WPBakery page builder plugin installed
	 *
	 * @return boolean
	 */
	function extensive_vc_is_wpbakery_page_builder_installed() {
		return class_exists( 'WPBakeryVisualComposerAbstract' );
	}
}

if ( ! function_exists( 'extensive_vc_is_evc_installed' ) ) {
	/**
	 * Checks if our plugin installed
	 *
	 * @return boolean
	 */
	function extensive_vc_is_evc_installed() {
		return class_exists( 'Extensive_VC_Addon' );
	}
}
