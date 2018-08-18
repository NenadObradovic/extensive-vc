<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-blog-list evc-shortcode evc-element-has-columns <?php echo esc_attr( $holder_classes ); ?>" data-options="<?php echo esc_attr( $pagination_data ); ?>">
	<div class="evc-bl-wrapper evc-element-wrapper">
		<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/blog-list-query', '', $params ); ?>
	</div>
	<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/pagination/pagination', '', $params ); ?>
</div>