<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-bl-item evc-element-item">
	<div class="evc-bli-inner">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/title', '', $params ); ?>
		<div class="evc-bli-post-info">
			<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/date', '', $params ); ?>
		</div>
	</div>
</div>