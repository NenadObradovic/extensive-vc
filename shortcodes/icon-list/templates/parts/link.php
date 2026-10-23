<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$link_attributes = extensive_vc_get_custom_link_attributes( $custom_link, 'evc-ili-link' );

if ( ! empty( $link_attributes ) ) { ?>
	<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>></a>
<?php } ?>
