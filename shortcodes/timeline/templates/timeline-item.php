<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<li class="evc-timeline-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_timeline_item_before_content', $params ); ?>
	<span class="evc-tl-marker" aria-hidden="true"></span>
	<?php if ( ! empty( $date_label ) ) { ?>
		<div class="evc-tl-date"><?php echo esc_html( $date_label ); ?></div>
	<?php } ?>
	<div class="evc-tl-box">
		<?php if ( ! empty( $image ) ) { ?>
			<div class="evc-tl-image">
				<?php echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) ); ?>
			</div>
		<?php } ?>
		<div class="evc-tl-content">
			<?php if ( ! empty( $title ) ) { ?>
				<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h4' ) ); ?> class="evc-tl-title"><?php echo esc_html( $title ); ?></<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h4' ) ); ?>>
			<?php } ?>
			<?php if ( '' !== trim( $content_html ) ) { ?>
				<div class="evc-tl-text">
					<?php echo $content_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Element content is post content entered in page builder. ?>
				</div>
			<?php } ?>
		</div>
	</div>
	<?php do_action( 'extensive_vc_action_timeline_item_after_content', $params ); ?>
</li>
