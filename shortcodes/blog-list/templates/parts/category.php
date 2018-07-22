<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( $enable_category !== 'no' ) { ?>
	<div class="evc-bli-category"><?php the_category( ', ' ); ?></div>
<?php } ?>