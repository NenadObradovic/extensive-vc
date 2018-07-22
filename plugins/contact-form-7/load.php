<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once 'cf7-functions.php';

if ( extensive_vc_contact_form_7_installed() ) {
	include_once 'widgets/load.php';
}
