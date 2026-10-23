<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! empty( $text ) ) { ?>
	<span class="evc-ili-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo wp_kses( $text, extensive_vc_get_inline_html_allowed_tags() ); ?></span>
<?php } ?>
