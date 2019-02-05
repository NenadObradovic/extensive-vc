<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_render_admin_options_fields' ) ) {
	/**
	 * Renders admin options fields
	 */
	function extensive_vc_render_admin_options_fields() {
		$evc_options = extensive_vc_get_global_options();
		
		$evc_options->options->addSection(
			array(
				'id' => 'evc_options_general_page'
			)
		);
		
		$evc_options->options->addField(
			array(
				'page'        => 'evc_options_general_page',
				'type'        => 'select',
				'id'          => 'evc_predefined_style',
				'title'       => esc_html__( 'Predefined Style', 'extensive-vc' ),
				'description' => esc_html__( 'Enabling this option will set predefined styles for elements (typography, shortcodes, post types etc)', 'extensive-vc' ),
				'sb_options'  => extensive_vc_get_yes_no_select_array( false, true )
	        )
		);
		
		$evc_options->options->addField(
			array(
				'page'        => 'evc_options_general_page',
				'type'        => 'colorpicker',
				'id'          => 'evc_main_color',
				'title'       => esc_html__( 'Main Color', 'extensive-vc' ),
				'description' => esc_html__( 'Set main color. Default color is #00bbb3', 'extensive-vc' )
			)
		);
	
		$evc_options->options->addField(
			array(
				'page'        => 'evc_options_general_page',
				'type'        => 'checkboxes',
				'id'          => 'evc_disable_shortcodes',
				'title'       => esc_html__( 'Disable Shortcodes', 'extensive-vc' ),
				'description' => esc_html__( 'Disable shortcodes which you will not use on your site to increase site performance', 'extensive-vc' ),
				'cb_options'  => extensive_vc_return_shortcodes_label_array()
			)
		);
		
		$evc_options->options->addField(
			array(
				'page'        => 'evc_options_general_page',
				'type'        => 'checkboxes',
				'id'          => 'evc_disable_widgets',
				'title'       => esc_html__( 'Disable Widgets', 'extensive-vc' ),
				'description' => esc_html__( 'Disable widgets which you will not use on your site to increase site performance', 'extensive-vc' ),
				'cb_options'  => extensive_vc_return_widgets_label_array()
			)
		);
		
		$evc_options->options->addField(
			array(
				'page'        => 'evc_options_general_page',
				'type'        => 'select',
				'id'          => 'evc_disable_ionicons_font',
				'title'       => esc_html__( 'Disable Ionicons Font', 'extensive-vc' ),
				'description' => esc_html__( 'Disable Ionicons font pack if you don\'t want to use it to increase site performance', 'extensive-vc' ),
				'sb_options'  => extensive_vc_get_yes_no_select_array( false )
			)
		);
	}
	
	add_action( 'admin_init', 'extensive_vc_render_admin_options_fields', 999 );
}
