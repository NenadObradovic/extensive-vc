<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_table_of_contents', $params ); ?>
<nav class="evc-table-of-contents evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" aria-labelledby="<?php echo esc_attr( $element_id ); ?>-title" <?php extensive_vc_print_inline_style( $holder_styles ); ?> <?php extensive_vc_print_inline_attrs( $holder_data, true ); ?>>
	<?php do_action( 'extensive_vc_action_table_of_contents_before_content', $params ); ?>
	<div class="evc-toc-header">
		<<?php echo esc_attr( $title_tag ); ?> class="evc-toc-title" id="<?php echo esc_attr( $element_id ); ?>-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( $title_tag ); ?>>
		<?php if ( 'yes' === $collapsible ) { ?>
			<button type="button" class="evc-toc-toggle" aria-expanded="<?php echo $is_collapsed ? 'false' : 'true'; ?>" aria-controls="<?php echo esc_attr( $element_id ); ?>-body">
				<span class="evc-screen-reader-text"><?php esc_html_e( 'Show or hide table of contents', 'extensive-vc-addon' ); ?></span>
				<?php extensive_vc_print_svg_icon( 'chevron-down' ); ?>
			</button>
		<?php } ?>
	</div>
	<div class="evc-toc-body" id="<?php echo esc_attr( $element_id ); ?>-body" <?php echo $is_collapsed ? 'hidden' : ''; ?>>
		<<?php echo esc_attr( $list_tag ); ?> class="evc-toc-list"></<?php echo esc_attr( $list_tag ); ?>>
	</div>
	<?php do_action( 'extensive_vc_action_table_of_contents_after_content', $params ); ?>
</nav>
<?php do_action( 'extensive_vc_action_after_table_of_contents', $params ); ?>
