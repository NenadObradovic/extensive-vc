<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $type === 'standard' ) {
	echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/layouts/post', $layout_collections, $params );
} else {
	echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/layouts/post', $type, $params );
}
