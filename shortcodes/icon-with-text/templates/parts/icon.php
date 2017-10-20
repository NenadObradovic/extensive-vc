<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'vc_icon_element_fonts_enqueue' ) && ! empty( $icon_library ) ) {
	vc_icon_element_fonts_enqueue( $icon_library );
	
	$iconClass = isset( ${'icon_' . $icon_library} ) ? ${'icon_' . $icon_library} : 'fa fa-adjust';
	?>
	
	<span class="evc-iwt-icon <?php echo esc_attr( $iconClass ); ?>" <?php extensive_vc_print_inline_style( $icon_styles ); ?>></span>
<?php } ?>
