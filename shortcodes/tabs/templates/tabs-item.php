<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-tabs-item" id="tab-<?php echo sanitize_title( $tab_title ); ?>">
	<?php echo do_shortcode( $content ); ?>
</div>
