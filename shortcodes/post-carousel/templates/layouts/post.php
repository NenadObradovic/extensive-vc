<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pc-item">
	<div class="evc-pci-inner">
		<?php extensive_vc_template_part( 'shortcodes', 'post-carousel', 'templates/parts/image', '', $params ); ?>
		<div class="evc-pci-content">
			<?php extensive_vc_template_part( 'shortcodes', 'post-carousel', 'templates/parts/title', '', $params ); ?>
			<?php extensive_vc_template_part( 'shortcodes', 'post-carousel', 'templates/parts/excerpt', '', $params ); ?>
		</div>
		<a class="evc-pci-link" href="<?php the_permalink(); ?>"></a>
	</div>
</div>