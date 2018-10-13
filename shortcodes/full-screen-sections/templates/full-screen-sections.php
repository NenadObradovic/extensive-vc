<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'fullPage' );
?>
<div class="evc-full-screen-sections <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_attrs( $holder_data ); ?>>
	<div class="evc-fss-wrapper">
		<?php echo do_shortcode( $content ); ?>
	</div>
	<?php if ( $enable_navigation === 'yes' ) { ?>
		<div class="evc-fss-nav-holder">
			<a id="evc-fss-nav-up" href="#"><span class='ion-chevron-up'></span></a>
			<a id="evc-fss-nav-down" href="#"><span class='ion-chevron-down'></span></a>
		</div>
	<?php } ?>
</div>
