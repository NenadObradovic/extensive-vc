<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_flip_image', $params ); ?>
<div class="evc-flip-image evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_flip_image_before_content', $params ); ?>
	<div class="evc-fi-image">
		<?php if ( 'content' === $front_type ) { ?>
			<div class="evc-fi-front-content" <?php extensive_vc_print_inline_style( $front_styles ); ?>>
				<div class="evc-fi-front-content-inner">
					<?php if ( ! empty( $front_title ) ) { ?>
						<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-fi-front-title"><?php echo esc_html( $front_title ); ?></<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
					<?php } ?>
					<?php if ( ! empty( $front_text ) ) { ?>
						<p class="evc-fi-front-text"><?php echo esc_html( $front_text ); ?></p>
					<?php } ?>
				</div>
			</div>
		<?php } else {
			echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) );
		} ?>
	</div>
	<div class="evc-fi-content-wrapper" <?php extensive_vc_print_inline_style( $content_styles ); ?>>
		<div class="evc-fi-content-inner">
			<div class="evc-fi-content">
				<?php if ( ! empty( $title ) ) { ?>
					<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-fi-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
					<?php echo esc_html( $title ); ?>
				</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
				<?php } ?>
				<?php if ( ! empty( $text ) ) { ?>
					<p class="evc-fi-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
				<?php } ?>
				<?php if ( ! empty( $button_params ) ) { ?>
					<div class="evc-fi-button">
						<?php echo extensive_vc_render_shortcode( 'evc_button', $button_params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>
	<?php if ( ! empty( $link_attributes ) ) { ?>
		<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>></a>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_flip_image_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_flip_image', $params ); ?>
