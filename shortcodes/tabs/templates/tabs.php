<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'jquery-ui-tabs' );
?>
<div class="evc-tabs <?php echo esc_attr( $holder_classes ); ?>">
	<ul class="evc-tabs-nav">
		<?php foreach ( $tab_titles as $tab_title ) { ?>
			<li>
				<?php if ( ! empty( $tab_title ) ) { ?>
					<a href="#tab-<?php echo sanitize_title( $tab_title ) ?>"><?php echo esc_html( $tab_title ); ?></a>
				<?php } ?>
			</li>
		<?php } ?>
	</ul>
	<?php echo do_shortcode( $content ); ?>
</div>
