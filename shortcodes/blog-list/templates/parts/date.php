<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $enable_date !== 'no' ) {
	$title     = get_the_title();
	$date_link = ! empty( $title ) ? get_month_link( get_the_time( 'm' ), get_the_time( 'Y' ) ) : get_the_permalink();
	?>
	<div class="evc-bli-date entry-date published updated">
		<a href="<?php echo esc_url( $date_link ); ?>"><?php the_time( get_option( 'date_format' ) ); ?></a>
	</div>
<?php } ?>