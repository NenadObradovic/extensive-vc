<?php

namespace ExtensiveVC\CPT\Testimonials;

use ExtensiveVC\CPT;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TestimonialsClass' ) ) {
	class TestimonialsClass implements CPT\PostTypesInterface {
		
		/**
		 * Singleton variables
		 */
		private $base;
		private $taxBase;
		
		/**
		 * Constructor
		 */
		function __construct() {
			$this->base    = 'testimonials';
			$this->taxBase = 'testimonials-category';
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
						'menu_name'     => esc_html__( 'EVC Testimonials', 'extensive-vc' ),
						'name'          => esc_html__( 'EVC Testimonials', 'extensive-vc' ),
						'singular_name' => esc_html__( 'Testimonial', 'extensive-vc' ),
						'add_item'      => esc_html__( 'New Testimonial', 'extensive-vc' ),
						'add_new_item'  => esc_html__( 'Add New Testimonial', 'extensive-vc' ),
						'edit_item'     => esc_html__( 'Edit Testimonial', 'extensive-vc' )
					),
					'public'        => false,
					'show_in_menu'  => true,
					'rewrite'       => array( 'slug' => $this->getBase() ),
					'menu_position' => 5,
					'menu_icon'     => 'dashicons-format-quote',
					'show_ui'       => true,
					'has_archive'   => false,
					'hierarchical'  => false,
					'supports'      => array( 'title', 'thumbnail' )
				)
			);
		}
		
		/**
		 * Register custom post type taxonomy
		 */
		private function registerTax() {
			$labels = array(
				'name'              => esc_html__( 'Testimonials Categories', 'extensive-vc' ),
				'singular_name'     => esc_html__( 'Testimonial Category', 'extensive-vc' ),
				'search_items'      => esc_html__( 'Search Testimonials Categories', 'extensive-vc' ),
				'all_items'         => esc_html__( 'All Testimonials Categories', 'extensive-vc' ),
				'parent_item'       => esc_html__( 'Parent Testimonial Category', 'extensive-vc' ),
				'parent_item_colon' => esc_html__( 'Parent Testimonial Category:', 'extensive-vc' ),
				'edit_item'         => esc_html__( 'Edit Testimonials Category', 'extensive-vc' ),
				'update_item'       => esc_html__( 'Update Testimonials Category', 'extensive-vc' ),
				'add_new_item'      => esc_html__( 'Add New Testimonials Category', 'extensive-vc' ),
				'new_item_name'     => esc_html__( 'New Testimonials Category Name', 'extensive-vc' ),
				'menu_name'         => esc_html__( 'Testimonials Categories', 'extensive-vc' )
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