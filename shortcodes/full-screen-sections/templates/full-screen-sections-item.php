<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-fss-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_full_screen_sections_item_before_content', $params ); ?>
	<div class="evc-fssi-bg" <?php extensive_vc_print_inline_style( $background_styles ); ?>></div>
	<div class="evc-fssi-inner">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>></a>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_full_screen_sections_item_after_content', $params ); ?>
</div>
