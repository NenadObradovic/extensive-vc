<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

require_once __DIR__ . '/cf7-functions.php';

if ( extensive_vc_contact_form_7_installed() ) {
	require_once __DIR__ . '/widgets/load.php';
}
