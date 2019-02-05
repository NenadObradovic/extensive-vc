<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_register_testimonials_meta_boxes' ) ) {
	/**
	 * Register meta boxes for testimonials custom post type
	 *
	 * @param $metaBoxes array
	 *
	 * string id - Meta box ID
	 * string title - Title of the meta box
	 * string function - Function that fills the box with the desired content. The function should echo its output
	 * string screen - The screen or screens on which to show the box
	 * string context - The context within the screen where the boxes should display. Available contexts vary from screen to screen
	 * string priority - The priority within the context where the boxes should show ('high', 'low')
	 *
	 * @return array
	 */
	function extensive_vc_register_testimonials_meta_boxes( $metaBoxes ) {
		
		$metaBoxes[] = array(
			'id'       => 'testimonials',
			'title'    => esc_html__( 'Testimonials', 'extensive-vc' ),
			'function' => 'extensive_vc_add_testimonials_meta_boxes',
			'screen'   => 'testimonials',
			'context'  => 'advanced',
			'priority' => 'high'
		);
		
		return $metaBoxes;
	}
	
	add_filter( 'extensive_vc_filter_add_meta_boxes', 'extensive_vc_register_testimonials_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_allowed_testimonials_meta_boxes' ) ) {
	/**
	 * Add cpt into allowed list for save meta boxes function
	 *
	 * @param $postTypes array - array of post types
	 *
	 * @return array
	 */
	function extensive_vc_allowed_testimonials_meta_boxes( $postTypes ) {
		$postTypes[] = 'testimonials';
		
		return $postTypes;
	}
	
	add_filter( 'extensive_vc_filter_allowed_post_types_meta_boxes', 'extensive_vc_allowed_testimonials_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_set_testimonials_meta_boxes' ) ) {
	/**
	 * Set testimonials meta boxes fields into local storage
	 */
	function extensive_vc_set_testimonials_meta_boxes() {
		extensive_vc_add_testimonials_meta_boxes( true );
	}
	
	add_action( 'init', 'extensive_vc_set_testimonials_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_add_testimonials_meta_boxes' ) ) {
	/**
	 * Add testimonials meta boxes fields
	 *
	 * @param $just_render_fields boolean - use to store fields into local storage or to display fields html
	 */
	function extensive_vc_add_testimonials_meta_boxes( $just_render_fields = false ) {
		$evc_options = extensive_vc_get_global_options();
		
		$evc_options->metaBoxes->renderField(
			array(
				'type'               => 'textarea',
				'name'               => 'evc_testimonial_text',
				'label'              => esc_html__( 'Text', 'extensive-vc' ),
				'just_render_fields' => $just_render_fields
			)
		);
		
		$evc_options->metaBoxes->renderField(
			array(
				'type'               => 'text',
				'name'               => 'evc_testimonial_author',
				'label'              => esc_html__( 'Author', 'extensive-vc' ),
				'just_render_fields' => $just_render_fields
			)
		);
		
		$evc_options->metaBoxes->renderField(
			array(
				'type'               => 'text',
				'name'               => 'evc_testimonial_position',
				'label'              => esc_html__( 'Author Job Position', 'extensive-vc' ),
				'just_render_fields' => $just_render_fields
			)
		);
	}
}