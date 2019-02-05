<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! function_exists( 'extensive_vc_init_admin_options_style' ) ) {
	/**
	 * Generate admin options style
	 *
	 * @param $style string - current custom inline style
	 *
	 * @return string
	 */
	function extensive_vc_init_admin_options_style( $style ) {
		$evc_options = extensive_vc_get_global_options();
		
		$current_styles = '';
		
		$main_color = $evc_options->options->getOptionValueById( 'evc_main_color' );
		
		if ( ! empty( $main_color ) ) {
			$main_color_c_selector = array(
				'.evc-owl-carousel .owl-nav .owl-next:hover',
				'.evc-owl-carousel .owl-nav .owl-prev:hover',
				'.evc-button.evc-btn-simple:hover',
				'.evc-button.evc-btn-fill-text .evc-btn-hover-text',
				'.evc-counter .evc-c-digit',
				'.evc-dropcaps.evc-d-simple .evc-d-letter',
				'.evc-icon-list .evc-ili-icon-wrapper',
				'.evc-icon-progress-bar .evc-ipb-icon.evc-active',
				'.evc-icon-with-text .evc-iwt-icon',
				'.evc-full-screen-sections .evc-fss-nav-holder a:hover',
				'.evc-carousel-skin-light .evc-owl-carousel .owl-nav .owl-next:hover',
				'.evc-carousel-skin-light .evc-owl-carousel .owl-nav .owl-prev:hover',
				'.evc-blog-list .evc-bli-post-info > * a:hover'
			);
			
			$main_color_bg_selector = array(
				'.evc-button.evc-btn-solid:hover',
				'.evc-button.evc-btn-strike-line .evc-btn-strike-line',
				'.evc-dropcaps.evc-d-circle .evc-d-letter',
				'.evc-dropcaps.evc-d-square .evc-d-letter',
				'.evc-process .evc-p-circle',
				'.evc-process .evc-p-line',
				'.evc-progress-bar .evc-pb-active-bar',
				'.evc-pricing-table .evc-pti-inner li.evc-pti-prices',
				'.evc-pli-add-to-cart a:hover',
				'.evc-pli-mark .onsale'
			);
			
			$main_color_b_selector = array(
				'.evc-blockquote.evc-b-left-line',
				'.evc-button.evc-btn-fill-line .evc-btn-fill-line',
				'.evc-button.evc-btn-switch-line .evc-btn-switch-line-2',
				'.evc-shortcode .evc-ib-bordered:after'
			);
			
			$current_styles .= extensive_vc_generate_dynamic_css( $main_color_c_selector, array( 'color' => $main_color ) );
			$current_styles .= extensive_vc_generate_dynamic_css( $main_color_bg_selector, array( 'background-color' => $main_color ) );
			$current_styles .= extensive_vc_generate_dynamic_css( $main_color_b_selector, array( 'border-color' => $main_color ) );
		}
		
		$current_styles = $current_styles . $style;
		
		return $current_styles;
	}
	
	add_filter( 'extensive_vc_filter_main_custom_style', 'extensive_vc_init_admin_options_style' );
}
