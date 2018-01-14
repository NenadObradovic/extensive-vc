<?php

namespace ExtensiveVC\CPT\Clients;

use ExtensiveVC\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'ClientsClass' ) ) {
	class ClientsClass implements CPT\PostTypesInterface {
		
		/**
		 * Singleton variables
		 */
		private $base;
		private $taxBase;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->base    = 'clients';
			$this->taxBase = 'clients-category';
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
		 * Getter for private param
		 *
		 * @return string
		 */
		function getBaseTaxonomy() {
			return $this->taxBase;
		}
		
		/**
		 * Register cpt
		 */
		function register() {
			$this->registerPostType();
			$this->registerTax();
		}
		
		/**
		 * Register custom post type
		 */
		private function registerPostType() {
			register_post_type( $this->getBase(),
				array(
					'labels'        => array(
						'menu_name'     => esc_html__( 'EVC Clients', 'extensive-vc' ),
						'name'          => esc_html__( 'EVC Clients', 'extensive-vc' ),
						'singular_name' => esc_html__( 'Client', 'extensive-vc' ),
						'add_item'      => esc_html__( 'New Client', 'extensive-vc' ),
						'add_new_item'  => esc_html__( 'Add New Client', 'extensive-vc' ),
						'edit_item'     => esc_html__( 'Edit Client', 'extensive-vc' )
					),
					'public'        => false,
					'show_in_menu'  => true,
					'rewrite'       => array( 'slug' => $this->getBase() ),
					'menu_position' => 6,
					'menu_icon'     => 'dashicons-groups',
					'show_ui'       => true,
					'has_archive'   => false,
					'hierarchical'  => false,
					'supports'      => array( 'title' )
				)
			);
		}
		
		/**
		 * Register custom post type taxonomy
		 */
		private function registerTax() {
			$labels = array(
				'name'              => esc_html__( 'Clients Categories', 'extensive-vc' ),
				'singular_name'     => esc_html__( 'Client Category', 'extensive-vc' ),
				'search_items'      => esc_html__( 'Search Clients Categories', 'extensive-vc' ),
				'all_items'         => esc_html__( 'All Clients Categories', 'extensive-vc' ),
				'parent_item'       => esc_html__( 'Parent Client Category', 'extensive-vc' ),
				'parent_item_colon' => esc_html__( 'Parent Client Category:', 'extensive-vc' ),
				'edit_item'         => esc_html__( 'Edit Clients Category', 'extensive-vc' ),
				'update_item'       => esc_html__( 'Update Clients Category', 'extensive-vc' ),
				'add_new_item'      => esc_html__( 'Add New Clients Category', 'extensive-vc' ),
				'new_item_name'     => esc_html__( 'New Clients Category Name', 'extensive-vc' ),
				'menu_name'         => esc_html__( 'Clients Categories', 'extensive-vc' )
			);
			
			register_taxonomy( $this->getBaseTaxonomy(), array( $this->getBase() ),
				array(
					'hierarchical'      => true,
					'labels'            => $labels,
					'show_ui'           => true,
					'query_var'         => true,
					'show_admin_column' => true,
					'rewrite'           => array( 'slug' => $this->getBaseTaxonomy() )
				)
			);
		}
	}
}