<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<<?php echo esc_attr( $title_tag ); ?> class="evc-custom-font evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php echo esc_html( $title ); ?>
</<?php echo esc_attr( $title_tag ); ?>>
