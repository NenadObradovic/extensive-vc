<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-icon-list evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php echo do_shortcode( $content ); ?>
</div>
