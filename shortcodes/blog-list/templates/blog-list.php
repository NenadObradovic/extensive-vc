<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-blog-list evc-shortcode evc-element-has-columns evc-has-pagination <?php echo esc_attr( $holder_classes ); ?>" data-options="<?php echo esc_attr( $pagination_data ); ?>">
	<div class="evc-bl-wrapper evc-element-wrapper">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/blog-list-item', '', $params ); ?>
	</div>
	<!-- Temporary code -->
	<a href="#" class="evc-load-more-button evc-button evc-btn-normal evc-btn-solid">Load More</a>
</div>