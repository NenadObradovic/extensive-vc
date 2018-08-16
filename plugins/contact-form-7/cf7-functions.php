<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_contact_form_7_installed' ) ) {
	/**
	 * Checks if Contact Form 7 plugin installed
	 *
	 * @return boolean
	 */
	function extensive_vc_contact_form_7_installed() {
		return defined( 'WPCF7_VERSION' );
	}
}

if ( ! function_exists( 'extensive_vc_get_contact_forms_7_array' ) ) {
	/**
	 * Returns array of contact form 7 options
	 *
	 * @param $first_empty boolean
	 *
	 * @return array
	 */
	function extensive_vc_get_contact_forms_7_array( $first_empty = false ) {
		$cf7_forms = get_posts( 'post_type="wpcf7_contact_form"&numberposts=-1' );
		$options   = array();
		
		if ( $first_empty ) {
			$options[''] = esc_html__( 'Default', 'extensive-vc' );
		}
		
		if ( ! empty( $cf7_forms ) ) {
			foreach ( $cf7_forms as $cf7_form ) {
				$options[ $cf7_form->ID ] = $cf7_form->post_title;
			}
		} else {
			$options[] = esc_html__( 'No contact forms 7 found', 'extensive-vc' );
		}
		
		return $options;
	}
}
