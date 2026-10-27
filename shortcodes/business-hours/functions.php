<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( class_exists( 'WPBakeryShortCodesContainer' ) ) {
	class WPBakeryShortCode_Evc_Business_Hours extends WPBakeryShortCodesContainer {
	}
}

if ( ! function_exists( 'extensive_vc_get_week_days_array' ) ) {
	/**
	 * Returns array of week days, keys are ISO-8601 day numbers (1 for Monday through 7 for Sunday)
	 *
	 * @return array
	 */
	function extensive_vc_get_week_days_array() {
		return array(
			'1' => esc_html__( 'Monday', 'extensive-vc-addon' ),
			'2' => esc_html__( 'Tuesday', 'extensive-vc-addon' ),
			'3' => esc_html__( 'Wednesday', 'extensive-vc-addon' ),
			'4' => esc_html__( 'Thursday', 'extensive-vc-addon' ),
			'5' => esc_html__( 'Friday', 'extensive-vc-addon' ),
			'6' => esc_html__( 'Saturday', 'extensive-vc-addon' ),
			'7' => esc_html__( 'Sunday', 'extensive-vc-addon' ),
		);
	}
}
