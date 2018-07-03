<?php

namespace ExtensiveVC\Shortcodes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EVCShortcode' ) ) {
	/**
	 * EVCShortcode class is main class for shortcode where we implemented generic methods for all shortcodes
	 */
	class EVCShortcode {
		
		/**
		 * Singleton variables
		 */
		private $shortcodeConstructor;
		private $base;
		private $parentBase;
		private $childBase;
		private $shortcodeName;
		private $isShortcodeEnabled;
		private $iconClass;
		private $shortcodeParameters;
		
		/**
		 * Constructor
		 *
		 * @param $shortcodeParams array - set specific shortcode behavior
		 */
		function __construct( $shortcodeParams = array() ) {
			$hasChild     = false;
			$hasParent    = false;
			$isNested     = false;
			$isInCPT      = false;
			$isInPlugins  = false;
			$pluginModule = '';
			
			extract( $shortcodeParams );
			
			$mainBase = $hasParent ? $this->getParentBase() : $this->getBase();
			$this->setIsShortcodeEnabled( $this->checkIsShortcodeEnabled( $mainBase ) );
			
			if ( $this->getIsShortcodeEnabled() ) {
				$this->setShortcodeConstructor(
					array(
						'has_child'     => $hasChild,
						'has_parent'    => $hasParent,
						'is_nested'     => $isNested,
						'in_cpt'        => $isInCPT,
						'in_plugins'    => $isInPlugins,
						'plugin_module' => $pluginModule
				    )
				);
				$this->setIconClass( 'icon-wpb-' . strtolower( str_replace( ' ', '-', $this->getShortcodeName() ) ) );
				
				add_filter( 'extensive_vc_filter_add_vc_shortcode', array( $this, 'addShortcode' ) );
				
				add_filter( 'extensive_vc_filter_add_vc_shortcodes_custom_icon', array( $this, 'addShortcodeIcon' ) );
				
				add_action( 'vc_before_init', array( $this, 'vcMap' ) );
			}
			
			if ( ! $hasParent ) {
				add_filter( 'extensive_vc_filter_shortcodes_list', array( $this, 'addShortcodeIntoOptions' ) );
			}
		}
		
		/**
		 * Getter for private param
		 *
		 * @return array
		 */
		public function getShortcodeConstructor() {
			return $this->shortcodeConstructor;
		}
		
		/**
		 * Setter for private param
		 */
		public function setShortcodeConstructor( $shortcodeConstructor ) {
			$this->shortcodeConstructor = $shortcodeConstructor;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return string
		 */
		function getBase() {
			return $this->base;
		}
		
		/**
		 * Setter for private param
		 */
		function setBase( $base ) {
			$this->base = $base;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return string
		 */
		function getParentBase() {
			return $this->parentBase;
		}
		
		/**
		 * Setter for private param
		 */
		function setParentBase( $parentBase ) {
			$this->parentBase = $parentBase;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return string
		 */
		function getChildBase() {
			return $this->childBase;
		}
		
		/**
		 * Setter for private param
		 */
		function setChildBase( $childBase ) {
			$this->childBase = $childBase;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return string
		 */
		function getShortcodeName() {
			return $this->shortcodeName;
		}
		
		/**
		 * Setter for private param
		 */
		function setShortcodeName( $shortcodeName ) {
			$this->shortcodeName = $shortcodeName;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return string
		 */
		function getIsShortcodeEnabled() {
			return $this->isShortcodeEnabled;
		}
		
		/**
		 * Setter for private param
		 */
		function setIsShortcodeEnabled( $isShortcodeEnabled ) {
			$this->isShortcodeEnabled = $isShortcodeEnabled;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return string
		 */
		function getIconClass() {
			return $this->iconClass;
		}
		
		/**
		 * Setter for private param
		 */
		function setIconClass( $iconClass ) {
			$this->iconClass = $iconClass;
		}
		
		/**
		 * Getter for private param
		 *
		 * @return array
		 */
		function getShortcodeParameters() {
			return $this->shortcodeParameters;
		}
		
		/**
		 * Setter for private param
		 */
		function setShortcodeParameters( $shortcodeParameters ) {
			$this->shortcodeParameters = $shortcodeParameters;
		}
		
		/**
		 * Check is shortcode enabled throw plugin option
		 *
		 * @param $mainBase string - shortcode base name, if shortcode is child element then mainBase is base from parent shortcode
		 *
		 * @return boolean
		 */
		private function checkIsShortcodeEnabled( $mainBase ) {
			$evcOptions  = get_option( 'evc_options' );
			$optionValue = ! empty( $evcOptions ) && array_key_exists( $mainBase, $evcOptions ) ? $evcOptions[ $mainBase ] : '';
			$returnValue = $optionValue !== '' && intval( $optionValue ) === 1 ? false : true;
			
			return $returnValue;
		}
		
		/**
		 * Add shortcode into shortcodes list
		 *
		 * @param $shortcodesInstance array - array of shortcodes instance
		 *
		 * @return array
		 */
		function addShortcode( $shortcodesInstance ) {
			$shortcodesInstance[ $this->getBase() ] = array( $this, 'render' );
			
			return $shortcodesInstance;
		}
		
		/**
		 * Add shortcode custom icon for Visual Composer shortcodes panel
		 *
		 * @param $icon array - list of shortcodes custom icon
		 *
		 * @return array
		 */
		function addShortcodeIcon( $icon ) {
			$shortcodeConstructor = $this->getShortcodeConstructor();
			
			$module       = false;
			$pluginModule = '';
			if ( $shortcodeConstructor['in_cpt'] === true ) {
				$module = 'in_cpt';
			} else if ( $shortcodeConstructor['in_plugins'] === true ) {
				$module       = 'in_plugins';
				$pluginModule = ! empty( $shortcodeConstructor['plugin_module'] ) ? $shortcodeConstructor['plugin_module'] : '';
			}
			
			$icon[] = array(
				'module'        => $module,
				'plugin_module' => $pluginModule,
				'class'         => '.' . $this->getIconClass(),
				'shortcode'     => $shortcodeConstructor['has_parent'] === true ? str_replace( array( ' ', '-item' ), array( '-', '' ), strtolower( $this->getShortcodeName() ) ) : strtolower( str_replace( ' ', '-', $this->getShortcodeName() ) ),
				'child_item'    => $shortcodeConstructor['has_parent'] === true
			);
			
			return apply_filters( 'extensive_vc_filter_add_shortcodes_custom_icon_object', $icon );
		}
		
		/**
		 * Add shortcode into shortcodes list for admin settings options
		 *
		 * @param $shortcodes array - list of shortcodes
		 *
		 * @return array
		 */
		function addShortcodeIntoOptions( $shortcodes ) {
			$base = $this->getBase();
			
			if ( ! empty( $base ) ) {
				$shortcodes[ $base ] = $this->getShortcodeName();
			}
			
			return $shortcodes;
		}
		
		/**
		 * Maps shortcode to Visual Composer. Hooked on vc_before_init
		 *
		 * @param $params array - contains parent and child values to check is shortcode parent or child for nested shortcodes
		 */
		function vcMap( $params ) {
			
			if ( function_exists( 'vc_map' ) ) {
				$shortcodeConstructor = $this->getShortcodeConstructor();
				
				$getParent      = $this->getChildBase();
				$parentSettings = array();
				if ( ! empty( $getParent ) ) {
					$parentSettings = array(
						'as_parent'       => array( 'only' => $this->getChildBase() ),
						'content_element' => true,
						'is_container'    => true,
						'js_view'         => 'VcColumnView'
					);
				}
				
				$getChild      = $this->getParentBase();
				$childSettings = array();
				if ( ! empty( $getChild ) ) {
					$childSettings = array(
						'as_child' => array( 'only' => $this->getParentBase() )
					);
					
					if ( $shortcodeConstructor['is_nested'] ) {
						$additionalChildSettings = array(
							'content_element' => true,
							'is_container'    => true,
							'js_view'         => 'VcColumnView'
						);
						
						$childSettings = array_merge( $childSettings, $additionalChildSettings );
					}
				}
				
				vc_map(
					array_merge(
						array(
							'name'     => sprintf( esc_html__( 'EVC ', 'extensive-vc' ) . '%s', $this->getShortcodeName() ),
							'base'     => $this->getBase(),
							'category' => esc_html__( 'Extensive VC', 'extensive-vc' ),
							'icon'     => 'evc-vc-custom-icon ' . $this->getIconClass(),
							'params'   => apply_filters( 'extensive_vc_filter_shortcode_params', $this->getShortcodeParameters(), $this->getBase() )
						),
						$parentSettings,
						$childSettings
					)
				);
			}
		}
	}
}