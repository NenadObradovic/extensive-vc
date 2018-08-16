<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pc-item">
	<div class="evc-pci-inner">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'post-carousel', 'templates/parts/image', 'with-date', $params ); ?>
		<div class="evc-pci-content">
			<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'post-carousel', 'templates/parts/title', '', $params ); ?>
			<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'post-carousel', 'templates/parts/excerpt', '', $params ); ?>
		</div>
		<a class="evc-pci-link" href="<?php the_permalink(); ?>"></a>
	</div>
</div>