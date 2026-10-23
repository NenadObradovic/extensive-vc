<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-bl-item evc-element-item">
	<div class="evc-bli-inner">
		<?php extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/parts/title', '', $params ); ?>
		<div class="evc-bli-post-info">
			<?php extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/parts/date', '', $params ); ?>
		</div>
	</div>
</div>