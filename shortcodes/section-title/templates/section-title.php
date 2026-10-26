<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_section_title', $params ); ?>
<div class="evc-section-title evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_section_title_before_content', $params ); ?>
	<div class="evc-st-inner">
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-st-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
				<?php if ( ! empty( $animated_data ) ) { ?>
					<span class="evc-st-animated-holder">
						<span class="evc-screen-reader-text"><?php echo esc_html( implode( ', ', $animated_data['words'] ) ); ?></span>
						<span class="evc-st-animated evc-st-animated-<?php echo esc_attr( $animated_data['type'] ); ?>" aria-hidden="true" data-words="<?php echo esc_attr( wp_json_encode( $animated_data['words'] ) ); ?>" data-type="<?php echo esc_attr( $animated_data['type'] ); ?>" data-delay="<?php echo esc_attr( $animated_data['delay'] ); ?>" <?php extensive_vc_print_inline_style( $animated_data['styles'] ); ?>><?php echo esc_html( $animated_data['words'][0] ); ?></span>
					</span>
					<?php if ( ! empty( $title_after ) ) { ?>
						<span class="evc-st-title-after"><?php echo esc_html( $title_after ); ?></span>
					<?php } ?>
				<?php } ?>
			</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
		<?php } ?>
		<?php if ( $enable_separator === 'yes' ) { ?>
			<div class="evc-st-separator" <?php extensive_vc_print_inline_style( $separator_styles ); ?>></div>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<<?php echo esc_attr( extensive_vc_escape_title_tag( $text_tag ) ); ?> class="evc-st-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>>
				<?php echo esc_html( $text ); ?>
			</<?php echo esc_attr( extensive_vc_escape_title_tag( $text_tag ) ); ?>>
		<?php } ?>
		<?php if ( ! empty( $button_text ) && ! empty( $button_custom_link ) ) { ?>
			<div class="evc-st-button">
				<?php echo extensive_vc_render_shortcode( 'evc_button', $button_params ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
			</div>
		<?php } ?>
	</div>
	<?php do_action( 'extensive_vc_action_section_title_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_section_title', $params ); ?>
