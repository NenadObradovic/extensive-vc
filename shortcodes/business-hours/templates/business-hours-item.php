<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<li class="evc-bh-item <?php echo esc_attr( $holder_classes ); ?>" <?php echo ! empty( $days_list ) ? 'data-days="' . esc_attr( $days_list ) . '"' : ''; ?>>
	<?php do_action( 'extensive_vc_action_business_hours_item_before_content', $params ); ?>
	<span class="evc-bh-day"><?php echo esc_html( $label ); ?></span>
	<span class="evc-bh-line" aria-hidden="true"></span>
	<span class="evc-bh-hours"><?php echo esc_html( $hours_text ); ?></span>
	<?php do_action( 'extensive_vc_action_business_hours_item_after_content', $params ); ?>
</li>
