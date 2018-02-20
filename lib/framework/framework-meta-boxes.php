<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_enqueue_admin_meta_box_scripts' ) ) {
	/**
	 * Enqueue plugin scripts for meta boxes page
	 */
	function extensive_vc_enqueue_admin_meta_box_scripts() {
		wp_enqueue_media();
		
		// Hook to enqueue additional scripts for meta boxes page
		do_action( 'extensive_vc_enqueue_additional_admin_meta_box_scripts' );
	}
}

if ( ! function_exists( 'extensive_vc_add_admin_meta_boxes' ) ) {
	/**
	 * Add admin custom meta boxes fields
	 *
	 * @see add_meta_box function
	 */
	function extensive_vc_add_admin_meta_boxes() {
		$meta_boxes = apply_filters( 'extensive_vc_filter_add_meta_boxes', array() );
		
		foreach ( $meta_boxes as $meta_box ) {
			add_meta_box(
				'evc-meta-box-' . $meta_box['id'],
				$meta_box['title'],
				'extensive_vc_render_admin_meta_boxes',
				$meta_box['screen'],
				$meta_box['context'],
				$meta_box['priority'],
				array( 'meta_box' => $meta_box )
			);
		}
		
		add_action( 'admin_enqueue_scripts', 'extensive_vc_enqueue_admin_meta_box_scripts' );
	}
	
	add_action( 'add_meta_boxes', 'extensive_vc_add_admin_meta_boxes' );
}

if ( ! function_exists( 'extensive_vc_render_admin_meta_boxes' ) ) {
	/**
	 * Renders admin meta box
	 *
	 * @param $post WP_Post - post object
	 * @param $meta_box array - array of current meta box parameters
	 */
	function extensive_vc_render_admin_meta_boxes( $post, $meta_box ) { ?>
		<div class="evc-meta-box-page">
			<?php
				$meta_box['args']['meta_box']['function']();
				wp_nonce_field( 'extensive_vc_meta_box_' . esc_attr( $meta_box['args']['meta_box']['id'] ) . '_save', 'extensive_vc_meta_box_' . esc_attr( $meta_box['args']['meta_box']['id'] ) . '_save' );
			?>
		</div>
		<?php
	}
}

if ( ! function_exists( 'extensive_vc_save_admin_meta_boxes' ) ) {
	/**
	 * Saves admin meta box to postmeta table
	 *
	 * @param $post_id int - id of post that meta box is being saved
	 * @param $post WP_Post - current post object
	 */
	function extensive_vc_save_admin_meta_boxes( $post_id, $post ) {
		global $evc_options;
		
		// If we're doing an auto save, bail
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		
		// If our nonce isn't there, or we can't verify it, bail
		$meta_boxes  = apply_filters( 'extensive_vc_filter_add_meta_boxes', array() );
		$nonce_array = array();
		
		if ( is_array( $meta_boxes ) && ! empty( $meta_boxes ) ) {
			foreach ( $meta_boxes as $meta_box ) {
				$id = esc_attr( $meta_box['id'] );
				
				$nonce_array[] = array(
					'nonce'     => 'extensive_vc_meta_box_' . $id . '_save',
					'post_type' => $id
				);
			}
		}
		
		if ( is_array( $nonce_array ) && count( $nonce_array ) ) {
			foreach ( $nonce_array as $nonce ) {
				if ( extensive_vc_is_forwarded_admin_page_active( $nonce['post_type'] ) ) {
					if ( ! isset( $_POST[ $nonce['nonce'] ] ) || ! wp_verify_nonce( $_POST[ $nonce['nonce'] ], $nonce['nonce'] ) ) {
						return;
					}
				}
			}
		}
		
		// If default wp nonce isn't there, bail
		if ( ! isset( $_POST['_wpnonce'] ) ) {
			return;
		}
		
		// If current user can't edit this post, bail
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		
		// If current post type are not in list, bail
		$post_types = apply_filters( 'extensive_vc_filter_allowed_post_types_meta_boxes', array( 'post', 'page' ) );
		
		if ( ! in_array( $post->post_type, $post_types ) ) {
			return;
		}
		
		// Make sure your data is set before trying to save it
		foreach ( $evc_options->metaBoxes->getOptions() as $key => $value ) {
			$field_key = $_POST[ $key ];
			
			if ( isset( $field_key ) && trim( $field_key !== '' ) ) {
				update_post_meta( $post_id, $key, sanitize_text_field( $field_key ) );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
	}
	
	add_action( 'save_post', 'extensive_vc_save_admin_meta_boxes', 10, 2 );
}
