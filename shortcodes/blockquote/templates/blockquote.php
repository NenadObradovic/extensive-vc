<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_blockquote', $params ); ?>
<blockquote class="evc-blockquote evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_blockquote_before_content', $params ); ?>
	<span class="evc-b-text"><?php echo esc_html( $text ); ?></span>
	<?php do_action( 'extensive_vc_action_blockquote_after_content', $params ); ?>
</blockquote>
<?php do_action( 'extensive_vc_action_after_blockquote', $params ); ?>
