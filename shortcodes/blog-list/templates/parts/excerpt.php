<?php

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

$excerpt = get_the_excerpt();

if ( ! isset( $excerpt_length ) || ( isset( $excerpt_length ) && $excerpt_length === '' ) ) {
	$excerpt_length = 100;
}

if ( $enable_excerpt !== 'no' && ! empty( $excerpt ) && ! empty( $excerpt_length ) ) {
	$new_excerpt = ( $excerpt_length > 0 ) ? substr( $excerpt, 0, intval( $excerpt_length ) ) : $excerpt;
	?>
	<p class="evc-bli-excerpt"><?php echo esc_html( $new_excerpt ); ?></p>
<?php } ?>