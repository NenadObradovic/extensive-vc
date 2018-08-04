<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $enable_date !== 'no' ) { ?>
	<div class="evc-bli-date evc-bli-date-on-image entry-date published updated">
		<span class="evc-bli-date-day"><?php the_time('d'); ?></span>
		<span class="evc-bli-date-month"><?php the_time('M'); ?></span>
	</div>
<?php } ?>