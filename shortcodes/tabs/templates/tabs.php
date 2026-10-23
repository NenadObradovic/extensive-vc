<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'jquery-ui-tabs' );
?>
<?php do_action( 'extensive_vc_action_before_tabs', $params ); ?>
<div class="evc-tabs <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_tabs_before_content', $params ); ?>
	<ul class="evc-tabs-nav">
		<?php foreach ( $tab_titles as $tab_title ) { ?>
			<li>
				<?php if ( ! empty( $tab_title ) ) { ?>
					<a href="#tab-<?php echo esc_attr( sanitize_title( $tab_title ) ); ?>"><?php echo esc_html( $tab_title ); ?></a>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
	<?php echo do_shortcode( $content ); ?>
	<?php do_action( 'extensive_vc_action_tabs_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_tabs', $params ); ?>
