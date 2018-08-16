<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $enable_date !== 'no' ) { ?>
	<div class="evc-pci-date evc-pci-date-on-image entry-date published updated">
		<span class="evc-pci-date-day"><?php the_time('d'); ?></span>
		<span class="evc-pci-date-month"><?php the_time('M'); ?></span>
	</div>
<?php } ?>