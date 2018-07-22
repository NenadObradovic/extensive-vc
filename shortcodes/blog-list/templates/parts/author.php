<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $enable_author !== 'no' ) { ?>
	<div class="evc-bli-author">
		<a class="evc-bli-author-link" href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
			<span class="evc-bli-author-label"><?php esc_html_e( 'by', 'extensive-vc' ); ?></span>
			<span class="evc-bli-author-name"><?php the_author_meta( 'display_name' ); ?></span>
		</a>
	</div>
<?php } ?>