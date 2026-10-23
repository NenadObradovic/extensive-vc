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

if ( ! function_exists( 'extensive_vc_sanitize_meta_box_field' ) ) {
	/**
	 * Sanitize meta box field value based on field type
	 *
	 * @param $value string|array - field value
	 * @param $type string - field type
	 * @param $key string - field name
	 *
	 * @return string|int|array
	 */
	function extensive_vc_sanitize_meta_box_field( $value, $type, $key = '' ) {
		$sanitized_value = '';
		$has_value       = is_array( $value ) ? ! empty( array_filter( $value, 'strlen' ) ) : '' !== trim( (string) $value );

		if ( $has_value ) {
			if ( is_array( $value ) ) {
				$sanitized_value = map_deep( $value, 'sanitize_text_field' );
			} else {
				switch ( $type ) {
					case 'image':
						$sanitized_value = absint( $value ) > 0 ? absint( $value ) : '';
						break;
					case 'textarea':
						$sanitized_value = trim( sanitize_textarea_field( $value ) );
						break;
					default:
						$sanitized_value = trim( sanitize_text_field( $value ) );
						break;
				}
			}
		}

		return apply_filters( 'extensive_vc_filter_sanitize_meta_box_field', $sanitized_value, $value, $type, $key );
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
		
		// If this is a revision, bail
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// If current post type are not in list, bail
		$post_types = apply_filters( 'extensive_vc_filter_allowed_post_types_meta_boxes', array() );

		if ( empty( $post ) || ! in_array( $post->post_type, $post_types, true ) ) {
			return;
		}

		// If our nonce isn't there, or we can't verify it, bail
		$meta_boxes  = apply_filters( 'extensive_vc_filter_add_meta_boxes', array() );
		$nonce_valid = false;

		if ( is_array( $meta_boxes ) && ! empty( $meta_boxes ) ) {
			foreach ( $meta_boxes as $meta_box ) {
				if ( empty( $meta_box['id'] ) || empty( $meta_box['screen'] ) || ! in_array( $post->post_type, (array) $meta_box['screen'], true ) ) {
					continue;
				}

				$nonce = 'extensive_vc_meta_box_' . esc_attr( $meta_box['id'] ) . '_save';

				if ( isset( $_POST[ $nonce ] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ $nonce ] ) ), $nonce ) ) {
					$nonce_valid = true;
					break;
				}
			}
		}

		if ( ! $nonce_valid ) {
			return;
		}

		// If current user can't edit this post, bail
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// Hook to allow 3rd party elements to save their own fields
		do_action( 'extensive_vc_action_save_post_meta_box', $post_id, $post );

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- Sanitized by field type.
		foreach ( $evc_options->metaBoxes->getOptions() as $key => $value ) {
			// Fields which are not part of the submitted meta box form are skipped, so other post types meta is not touched
			if ( ! array_key_exists( $key, $_POST ) ) {
				continue;
			}

			$field_value = extensive_vc_sanitize_meta_box_field( wp_unslash( $_POST[ $key ] ), $evc_options->metaBoxes->getOptionType( $key ), $key );

			if ( '' !== $field_value && array() !== $field_value ) {
				update_post_meta( $post_id, $key, $field_value );
			} else {
				delete_post_meta( $post_id, $key );
			}
		}
		// phpcs:enable

		// Hook to do additional actions after meta box fields are saved
		do_action( 'extensive_vc_action_after_save_post_meta_box', $post_id, $post );
	}
	
	add_action( 'save_post', 'extensive_vc_save_admin_meta_boxes', 10, 2 );
}
