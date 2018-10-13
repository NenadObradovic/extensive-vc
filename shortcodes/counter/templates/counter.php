<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'counter' );
?>
<div class="evc-counter evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-c-inner">
		<?php if ( ! empty( $digit ) ) { ?>
			<span class="evc-c-digit" <?php extensive_vc_print_inline_style( $digit_styles ); ?>><?php echo esc_html( $digit ); ?></span>
		<?php } ?>
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( $title_tag ); ?> class="evc-c-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-c-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
</div>
