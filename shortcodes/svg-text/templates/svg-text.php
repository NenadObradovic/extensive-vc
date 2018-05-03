<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-svg-text evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<svg class="evc-st-svg"><text x="0" y="82%"><?php echo esc_html( $text ); ?></text></svg>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php echo implode( ' ', $link_attributes ); ?>></a>
	<?php } ?>
</div>