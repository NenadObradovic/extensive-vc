<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! isset( $enable_title ) ) {
	$enable_title = 'yes';
}

if ( ! isset( $title_tag ) ) {
	$title_tag = 'h4';
}

if ( $enable_title === 'yes' ) { ?>
	<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-pli-title entry-title">
		<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
	</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
<?php } ?>
