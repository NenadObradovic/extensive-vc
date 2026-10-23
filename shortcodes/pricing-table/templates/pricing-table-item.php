<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pt-item evc-element-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_pricing_table_item_before_content', $params ); ?>
	<div class="evc-pti-inner" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
		<ul>
			<li class="evc-pti-prices" <?php extensive_vc_print_inline_style( $price_holder_styles ); ?>>
				<span class="evc-pti-value" <?php extensive_vc_print_inline_style( $currency_styles ); ?>><?php echo esc_html( $currency ); ?></span>
				<span class="evc-pti-price" <?php extensive_vc_print_inline_style( $price_styles ); ?>><?php echo esc_html( $price ); ?></span>
				<h6 class="evc-pti-mark" <?php extensive_vc_print_inline_style( $price_period_styles ); ?>><?php echo esc_html( $price_period ); ?></h6>
			</li>
			<li class="evc-pti-title-holder">
				<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-pti-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>><?php echo esc_html( $title ); ?></<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
			</li>
			<li class="evc-pti-content">
				<?php echo do_shortcode( $content ); ?>
			</li>
			<?php if ( ! empty( $button_text ) && ! empty( $button_custom_link ) ) { ?>
				<li class="evc-pti-button">
					<?php echo extensive_vc_render_shortcode( 'evc_button', $button_params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				</li>
			<?php } ?>
		</ul>
	</div>
	<?php do_action( 'extensive_vc_action_pricing_table_item_after_content', $params ); ?>
</div>
