<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'counter' );
?>
<div class="evc-progress-bar evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<div class="evc-pb-inactive-bar" <?php extensive_vc_print_inline_style( $inactive_bar_styles ); ?>>
		<div class="evc-pb-active-bar" <?php extensive_vc_print_inline_style( $active_bar_styles ); ?> <?php extensive_vc_print_inline_attrs( $progress_bar_data ); ?>></div>
	</div>
	<div class="evc-pb-percent" <?php extensive_vc_print_inline_style( $percent_styles ); ?>>0</div>
	<?php if( ! empty( $title ) ) { ?>
		<<?php echo esc_attr( $title_tag ); ?> class="evc-pb-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
			<?php echo esc_html( $title ); ?>
		</<?php echo esc_attr( $title_tag ); ?>>
	<?php } ?>
</div>
