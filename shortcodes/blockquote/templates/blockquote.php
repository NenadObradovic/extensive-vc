<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<blockquote class="evc-blockquote evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<span class="evc-b-text"><?php echo esc_html( $text ); ?></span>
</blockquote>