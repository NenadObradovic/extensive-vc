<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_pricing_table', $params ); ?>
<div class="evc-pricing-table evc-shortcode evc-element-has-columns <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_pricing_table_before_content', $params ); ?>
	<div class="evc-pt-wrapper evc-element-wrapper">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php do_action( 'extensive_vc_action_pricing_table_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_pricing_table', $params ); ?>
