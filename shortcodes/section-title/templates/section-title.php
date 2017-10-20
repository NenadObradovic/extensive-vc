<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-section-title evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-st-inner">
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( $title_tag ); ?> class="evc-st-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( $title_tag ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-st-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
</div>