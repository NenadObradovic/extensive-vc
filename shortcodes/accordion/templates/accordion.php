<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_accordion', $params ); ?>
<div class="evc-accordion evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" data-behavior="<?php echo esc_attr( $behavior ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_accordion_before_content', $params ); ?>
	<?php echo $items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Items output is escaped in accordion item template. ?>
	<?php do_action( 'extensive_vc_action_accordion_after_content', $params ); ?>
</div>
<?php if ( ! empty( $faq_schema ) ) { ?>
	<script type="application/ld+json"><?php echo $faq_schema; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- JSON encoded data. ?></script>
<?php } ?>
<?php do_action( 'extensive_vc_action_after_accordion', $params ); ?>
