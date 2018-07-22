<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-bl-item evc-element-item">
	<div class="evc-bli-inner">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/image', '', $params ); ?>
		<div class="evc-bli-content">
			<div class="evc-bli-post-info evc-bli-post-info-top">
				<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/category', '', $params ); ?>
				<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/date', '', $params ); ?>
			</div>
			<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/title', '', $params ); ?>
			<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/excerpt', '', $params ); ?>
			<div class="evc-bli-post-info evc-bli-post-info-bottom">
				<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/author', '', $params ); ?>
			</div>
		</div>
	</div>
</div>