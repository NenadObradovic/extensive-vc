<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once EXTENSIVE_VC_PLUGINS_ABS_PATH . '/contact-form-7/cf7-functions.php';

if ( extensive_vc_contact_form_7_installed() ) {
	include_once EXTENSIVE_VC_PLUGINS_ABS_PATH . '/contact-form-7/widgets/load.php';
}
