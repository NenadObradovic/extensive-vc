<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_register_contact_form_7_widget' ) ) {
	/**
	 * Register widget element
	 */
	function extensive_vc_register_contact_form_7_widget( $widgets ) {
		$widgets[] = 'EVCContactForm7Widget';
		
		return $widgets;
	}
	
	add_filter( 'extensive_vc_filter_register_widgets', 'extensive_vc_register_contact_form_7_widget' );
	add_filter( 'extensive_vc_filter_widgets_list', 'extensive_vc_register_contact_form_7_widget' );
}
