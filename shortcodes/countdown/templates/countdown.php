<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$is_expired_message = $is_expired && 'message' === $expire_action;
?>
<?php do_action( 'extensive_vc_action_before_countdown', $params ); ?>
<div class="evc-countdown evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_countdown_before_content', $params ); ?>
	<div class="evc-cd-timer" role="timer" <?php echo $is_expired_message ? 'hidden' : ''; ?>>
		<?php foreach ( $units as $unit => $unit_data ) { ?>
			<div class="evc-cd-item evc-cd-<?php echo esc_attr( $unit ); ?>">
				<span class="evc-cd-digit" data-unit="<?php echo esc_attr( $unit ); ?>"><?php echo esc_html( $unit_data['value'] ); ?></span>
				<span class="evc-cd-label"><?php echo esc_html( $unit_data['label'] ); ?></span>
			</div>
		<?php } ?>
	</div>
	<?php if ( 'message' === $expire_action && ! empty( $expired_message ) ) { ?>
		<div class="evc-cd-expired-message" <?php echo $is_expired_message ? '' : 'hidden'; ?>><?php echo wp_kses( $expired_message, extensive_vc_get_inline_html_allowed_tags() ); ?></div>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_countdown_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_countdown', $params ); ?>
