<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-text-marquee" <?php extensive_vc_print_inline_style( $text_styles ); ?>>
	<span class="evc-tm-element evc-tm-original"><?php echo esc_html( $text ) ?></span>
	<span class="evc-tm-element evc-tm-aux"><?php echo esc_html( $text ) ?></span>
</div>
