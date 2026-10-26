<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-accordion-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_accordion_item_before_content', $params ); ?>
	<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h5' ) ); ?> class="evc-acc-title-holder">
		<button type="button" class="evc-acc-title" id="<?php echo esc_attr( $item_id ); ?>-title" aria-expanded="<?php echo $is_open ? 'true' : 'false'; ?>" aria-controls="<?php echo esc_attr( $item_id ); ?>-content">
			<span class="evc-acc-title-text"><?php echo esc_html( $title ); ?></span>
			<span class="evc-acc-icon" aria-hidden="true"><?php extensive_vc_print_svg_icon( 'chevron-down' ); ?></span>
		</button>
	</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h5' ) ); ?>>
	<div class="evc-acc-content" id="<?php echo esc_attr( $item_id ); ?>-content" role="region" aria-labelledby="<?php echo esc_attr( $item_id ); ?>-title" <?php echo $is_open ? '' : 'hidden'; ?>>
		<div class="evc-acc-content-inner">
			<?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Element content is post content entered in page builder. ?>
		</div>
	</div>
	<?php do_action( 'extensive_vc_action_accordion_item_after_content', $params ); ?>
</div>
