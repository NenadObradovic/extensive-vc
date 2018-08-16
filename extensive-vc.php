<?php
/*
Plugin Name: Extensive VC Pack
Plugin URI: http://wprealize.com/
Author: Nenad Obradovic
Author URI: http://wprealize.com/introduced/
Version: 1.7.2
Description: WordPress plugin which allows you to add unique, flexible and fully responsive shortcodes. It is an addon for premium plugin WPBakery page builder Visual Composer.
Text Domain: extensive-vc
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
			if ( ! ( self::$instance instanceof self ) ) {
				self::$instance = new self();
			}
			
			return self::$instance;
		}
		
		/**
		 * Cloning disabled
		 */
		private function __clone() {
		}
		
		/**
		 * Serialization disabled
		 */
		private function __sleep() {
		}
		
		/**
		 * De-serialization disabled
		 */
		private function __wakeup() {
		}
		
		/**
		 * Constructor
		 */
		private function __construct() {
			include_once 'constants.php';
			
			// Plugin activation hook
			register_activation_hook( __FILE__, array( $this, 'initActivationHook' ) );
			
			// Plugin init hook
			add_action( 'plugins_loaded', array( $this, 'init' ) );
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
			include_once 'load.php';
			
			add_action( 'init', array( $this, 'setTextDomain' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueStyles' ) );
			
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueueInlineStyles' ) );
			
			add_action( 'admin_init', array( $this, 'enqueueAdminScripts' ) );
			
			add_filter( 'plugin_action_links', array( $this, 'setPluginActionLinks' ), 10, 2 );
			
			if ( ( is_multisite() && is_network_admin() ) || ! is_multisite() ) {
				add_action( 'admin_init', array( $this, 'initAdminNotice' ) );
			}
		}
		
		/**
		 * Register plugin text domain
		 */
		function setTextDomain() {
			load_plugin_textdomain( 'extensive-vc', false, EXTENSIVE_VC_REL_PATH . '/languages' );
		}
		
		/**
		 * Enqueue plugin scripts
		 */
		function enqueueStyles() {
			global $evc_options;
			
			// Hook to enqueue additional scripts before main css
			do_action( 'extensive_vc_enqueue_additional_scripts_before_main_css' );
			
			// Enqueue main plugin css
			wp_enqueue_style( 'extensive-vc-main-style', EXTENSIVE_VC_ASSETS_URL_PATH . '/css/main.min.css' );
			
			// Enqueue ion icons font pack css
			$is_icon_font_disabled = $evc_options->options->getOptionValueById( 'evc_disable_ionicons_font' );
			
			if ( $is_icon_font_disabled !== 'yes' ) {
				wp_enqueue_style( 'ionicons', EXTENSIVE_VC_ASSETS_URL_PATH . '/css/ion-icons/css/ionicons.min.css' );
			}
			
			// Enqueue core jquery script and 3rd part libraries
			wp_enqueue_script( 'appear', EXTENSIVE_VC_ASSETS_URL_PATH . '/plugins/appear/jquery.appear.js', array( 'jquery' ), false, true );
			
			// Hook to enqueue additional scripts before main js
			do_action( 'extensive_vc_enqueue_additional_scripts_before_main_js' );
			
			// Enqueue main plugin js
			wp_enqueue_script( 'extensive-vc-main-script', EXTENSIVE_VC_ASSETS_URL_PATH . '/js/main.min.js', array( 'jquery' ), false, true );
			
			// Extend main plugin js with additional variables
			$evc_global_variables = array(
				'adminAjaxUrl'       => esc_url( admin_url( 'admin-ajax.php' ) ),
				'sliderNavPrevArrow' => 'ion-ios-arrow-left',
				'sliderNavNextArrow' => 'ion-ios-arrow-right'
			);
			
			$evc_global_variables = apply_filters( 'extensive_vc_filter_main_js_global_variables', $evc_global_variables );
			
			wp_localize_script( 'extensive-vc-main-script', 'evcVars', array(
				'global' => $evc_global_variables
			) );
			
			// Hook to enqueue additional scripts
			do_action( 'extensive_vc_enqueue_additional_scripts' );
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
			wp_enqueue_style( 'extensive-vc-main-admin-style', EXTENSIVE_VC_ASSETS_URL_PATH . '/css/admin/main-admin.min.css' );
			
			// Enqueue main plugin admin js
			wp_enqueue_script( 'extensive-vc-main-admin-script', EXTENSIVE_VC_ASSETS_URL_PATH . '/js/admin/main-admin.js', array( 'jquery' ), false, true );
			
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
				$title = esc_html__( 'Extensive VC Settings', 'extensive-vc' );
				$url   = add_query_arg( array( 'page' => 'evc-admin-options-page' ), admin_url( 'admin.php' ) );
				$label = esc_html__( 'Settings', 'extensive-vc' );
				
				$link = '<a title="' . esc_attr( $title ) . '" href="' . esc_url( $url ) . '">' . $label . '</a>';
				
				array_unshift( $links, $link );
			}
			
			return $links;
		}
		
		/**
		 * Display admin notices
		 */
		function initAdminNotice() {
			
			if ( ! extensive_vc_is_visual_composer_installed() ) {
				add_action( 'admin_notices', array( $this, 'adminNoticeWhenVisualComposerNotInstalled' ) );
			}
		}
		
		/**
		 * Display admin notice error if Visual Composer plugin not installed
		 *
		 * @return html/string
		 */
		function adminNoticeWhenVisualComposerNotInstalled() {
			
			echo sprintf( '<div class="notice notice-error evc-admin-notice evc-notice-vc-not-installed"><p>%s<strong>%s</strong>%s</p></div>',
				esc_html__( 'The', 'extensive-vc' ),
				esc_html__( ' Extensive Addons', 'extensive-vc' ),
				esc_html__( ' plugin requires WPBakery page builder (formerly Visual Composer) plugin. Please installed/activated it.', 'extensive-vc' )
			);
			
			if ( function_exists( 'deactivate_plugins' ) ) {
				deactivate_plugins( plugin_basename( __FILE__ ) );
			}
		}
	}
}

Extensive_VC_Addon::getInstance();

if ( ! function_exists( 'extensive_vc_is_visual_composer_installed' ) ) {
	/**
	 * Checks if WPBakery page builder plugin installed
	 *
	 * @return boolean
	 */
	function extensive_vc_is_visual_composer_installed() {
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