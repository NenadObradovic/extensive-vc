<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-fss-item <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-fssi-bg" <?php extensive_vc_print_inline_style( $background_styles ); ?>></div>
	<div class="evc-fssi-inner">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php echo implode( ' ', $link_attributes ); ?>></a>
	<?php } ?>
</div>
