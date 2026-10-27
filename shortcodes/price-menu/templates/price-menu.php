<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_price_menu', $params ); ?>
<div class="evc-price-menu evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_price_menu_before_content', $params ); ?>
	<ul class="evc-pm-list">
		<?php echo $items_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Items output is escaped in price menu item template. ?>
	</ul>
	<?php do_action( 'extensive_vc_action_price_menu_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_price_menu', $params ); ?>
