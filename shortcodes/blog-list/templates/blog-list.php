<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_blog_list', $params ); ?>
<div class="evc-blog-list evc-shortcode evc-element-has-columns <?php echo esc_attr( $holder_classes ); ?>" data-options="<?php echo esc_attr( $pagination_data ); ?>">
	<?php do_action( 'extensive_vc_action_blog_list_before_content', $params ); ?>
	<div class="evc-bl-wrapper evc-element-wrapper">
		<?php extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/blog-list-query', '', $params ); ?>
	</div>
	<?php extensive_vc_template_part( 'shortcodes', 'blog-list', 'templates/pagination/pagination', '', $params ); ?>
	<?php do_action( 'extensive_vc_action_blog_list_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_blog_list', $params ); ?>