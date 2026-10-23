<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $type === 'standard' ) {
	extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/layouts/post', $layout_collections, $params );
} else {
	extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/layouts/post', $type, $params );
}
