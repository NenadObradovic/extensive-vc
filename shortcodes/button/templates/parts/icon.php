<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( function_exists( 'vc_icon_element_fonts_enqueue' ) && ! empty( $icon_library ) ) {
	vc_icon_element_fonts_enqueue( $icon_library );
	
	$iconClass = isset( ${'icon_' . $icon_library} ) ? ${'icon_' . $icon_library} : 'fa fa-adjust';
	?>
	
	<span class="evc-btn-icon <?php echo esc_attr( $iconClass ); ?>"></span>
<?php } ?>
