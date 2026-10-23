<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_custom_font', $params ); ?>
<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-custom-font evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_custom_font_before_content', $params ); ?>
	<?php echo esc_html( $title ); ?>
	<?php do_action( 'extensive_vc_action_custom_font_after_content', $params ); ?>
</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
<?php do_action( 'extensive_vc_action_after_custom_font', $params ); ?>
