<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-dropcaps evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php if ( ! empty( $letter ) ) { ?>
		<span class="evc-d-letter" <?php extensive_vc_print_inline_style( $letter_styles ); ?>><?php echo esc_html( $letter ); ?></span>
	<?php } ?>
	<?php if ( ! empty( $text ) ) { ?>
		<?php echo esc_html( $text ); ?>
	<?php } ?>
</div>
