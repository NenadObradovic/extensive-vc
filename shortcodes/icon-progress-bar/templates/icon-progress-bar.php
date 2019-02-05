<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-icon-progress-bar evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_attrs( $holder_data ); ?>>
	<?php if ( ! empty( $title ) ) { ?>
		<<?php echo esc_attr( $title_tag ); ?> class="evc-ipb-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
			<?php echo esc_html( $title ); ?>
		</<?php echo esc_attr( $title_tag ); ?>>
	<?php } ?>
	<?php if ( function_exists( 'vc_icon_element_fonts_enqueue' ) && ! empty( $icon_library ) ) {
		vc_icon_element_fonts_enqueue( $icon_library );
		
		$iconClass = isset( ${'icon_' . $icon_library} ) ? ${'icon_' . $icon_library} : 'fa fa-adjust';
		
		if ( ! empty( $number_of_icons ) ) {
			for ( $i = 0; $i < $number_of_icons; $i++ ) { ?>
				<span class="evc-ipb-icon <?php echo esc_attr( $iconClass ); ?>" <?php extensive_vc_print_inline_style( $icon_styles ); ?>></span>
			<?php }
		}
	} ?>
</div>
