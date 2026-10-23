<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_svg_text', $params ); ?>
<div class="evc-svg-text evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_svg_text_before_content', $params ); ?>
	<svg class="evc-st-svg"><text x="0" y="82%"><?php echo esc_html( $text ); ?></text></svg>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>></a>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_svg_text_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_svg_text', $params ); ?>