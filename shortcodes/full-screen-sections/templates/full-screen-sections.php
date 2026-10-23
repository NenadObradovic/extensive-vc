<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'fullpage' );
?>
<?php do_action( 'extensive_vc_action_before_full_screen_sections', $params ); ?>
<div class="evc-full-screen-sections <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_attrs( $holder_data ); ?>>
	<?php do_action( 'extensive_vc_action_full_screen_sections_before_content', $params ); ?>
	<div class="evc-fss-wrapper">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php if ( $enable_navigation === 'yes' ) { ?>
		<div class="evc-fss-nav-holder">
			<a id="evc-fss-nav-up" href="#" aria-label="<?php esc_attr_e( 'Previous section', 'extensive-vc-addon' ); ?>"><span><?php extensive_vc_print_svg_icon( 'chevron-up' ); ?></span></a>
			<a id="evc-fss-nav-down" href="#" aria-label="<?php esc_attr_e( 'Next section', 'extensive-vc-addon' ); ?>"><span><?php extensive_vc_print_svg_icon( 'chevron-down' ); ?></span></a>
		</div>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_full_screen_sections_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_full_screen_sections', $params ); ?>
