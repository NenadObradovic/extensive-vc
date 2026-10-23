<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-bli-title entry-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>