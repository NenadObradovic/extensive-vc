<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_dropcaps', $params ); ?>
<div class="evc-dropcaps evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_dropcaps_before_content', $params ); ?>
	<?php if ( ! empty( $letter ) ) { ?>
		<span class="evc-d-letter" <?php extensive_vc_print_inline_style( $letter_styles ); ?>><?php echo esc_html( $letter ); ?></span>
	<?php } ?>
	<?php if ( ! empty( $text ) ) { ?>
		<?php echo esc_html( $text ); ?>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_dropcaps_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_dropcaps', $params ); ?>
