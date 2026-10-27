<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_business_hours', $params ); ?>
<div class="evc-business-hours evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_business_hours_before_content', $params ); ?>
	<ul class="evc-bh-list">
		<?php echo $items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Items output is escaped in business hours item template. ?>
	</ul>
	<?php do_action( 'extensive_vc_action_business_hours_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_business_hours', $params ); ?>
