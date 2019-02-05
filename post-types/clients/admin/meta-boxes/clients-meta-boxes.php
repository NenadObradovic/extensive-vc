<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_register_clients_meta_boxes' ) ) {
	/**
	 * Register meta boxes for clients custom post type
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
	function extensive_vc_register_clients_meta_boxes( $metaBoxes ) {
		
		$metaBoxes[] = array(
			'id'       => 'clients',
			'title'    => esc_html__( 'Clients', 'extensive-vc' ),
			'function' => 'extensive_vc_add_clients_meta_boxes',
			'screen'   => 'clients',
			'context'  => 'advanced',
			'priority' => 'high'
		);
		
		return $metaBoxes;
	}
	
	add_filter( 'extensive_vc_filter_add_meta_boxes', 'extensive_vc_register_clients_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_allowed_clients_meta_boxes' ) ) {
	/**
	 * Add cpt into allowed list for save meta boxes function
	 *
	 * @param $postTypes array - array of post types
	 *
	 * @return array
	 */
	function extensive_vc_allowed_clients_meta_boxes( $postTypes ) {
		$postTypes[] = 'clients';
		
		return $postTypes;
	}
	
	add_filter( 'extensive_vc_filter_allowed_post_types_meta_boxes', 'extensive_vc_allowed_clients_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_set_clients_meta_boxes' ) ) {
	/**
	 * Set clients meta boxes fields into local storage
	 */
	function extensive_vc_set_clients_meta_boxes() {
		extensive_vc_add_clients_meta_boxes( true );
	}
	
	add_action( 'init', 'extensive_vc_set_clients_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_add_clients_meta_boxes' ) ) {
	/**
	 * Add clients meta boxes fields
	 *
	 * @param $just_render_fields boolean - use to store fields into local storage or to display fields html
	 */
	function extensive_vc_add_clients_meta_boxes( $just_render_fields = false ) {
		$evc_options = extensive_vc_get_global_options();
		
		$evc_options->metaBoxes->renderField(
			array(
				'type'               => 'image',
				'name'               => 'evc_client_image',
				'label'              => esc_html__( 'Image', 'extensive-vc' ),
				'just_render_fields' => $just_render_fields
			)
		);
		
		$evc_options->metaBoxes->renderField(
			array(
				'type'               => 'image',
				'name'               => 'evc_client_hover_image',
				'label'              => esc_html__( 'Hover Image', 'extensive-vc' ),
				'just_render_fields' => $just_render_fields
			)
		);
		
		$evc_options->metaBoxes->renderField(
			array(
				'type'               => 'text',
				'name'               => 'evc_client_link',
				'label'              => esc_html__( 'Link', 'extensive-vc' ),
				'just_render_fields' => $just_render_fields
			)
		);
	}
}