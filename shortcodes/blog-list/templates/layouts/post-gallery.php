<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-bl-item evc-element-item">
	<div class="evc-bli-inner">
		<?php
		$params['without_zoom'] = true;
		
		extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/parts/image', 'with-date', $params ); ?>
		<div class="evc-bli-content">
			<?php extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/parts/title', '', $params ); ?>
			<?php extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/parts/excerpt', '', $params ); ?>
		</div>
		<a class="evc-bli-link" href="<?php the_permalink(); ?>"></a>
	</div>
</div>