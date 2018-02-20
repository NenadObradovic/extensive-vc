<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<blockquote class="evc-blockquote evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<span class="evc-b-text"><?php echo esc_html( $text ); ?></span>
</blockquote>
