<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_text_marquee', $params ); ?>
<div class="evc-text-marquee <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $text_styles ); ?>>
	<?php do_action( 'extensive_vc_action_text_marquee_before_content', $params ); ?>
	<span class="evc-tm-element evc-tm-original"><?php echo esc_html( $text ) ?></span>
	<span class="evc-tm-element evc-tm-aux"><?php echo esc_html( $text ) ?></span>
	<?php do_action( 'extensive_vc_action_text_marquee_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_text_marquee', $params ); ?>
