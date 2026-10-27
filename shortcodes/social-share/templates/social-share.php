<?php

use ExtensiveVC\Shortcodes\EVCSocialShare\EVCSocialShare;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_social_share', $params ); ?>
<div class="evc-social-share evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_social_share_before_content', $params ); ?>
	<?php if ( ! empty( $title ) ) { ?>
		<span class="evc-ss-title"><?php echo esc_html( $title ); ?></span>
	<?php } ?>
	<?php if ( ! empty( $buttons ) ) { ?>
		<ul class="evc-ss-list">
			<?php foreach ( $buttons as $evc_button_slug => $evc_button ) { ?>
				<?php if ( 'link' === $evc_button['type'] ) { ?>
					<li class="evc-ss-item">
						<a class="evc-ss-button evc-ss-<?php echo esc_attr( $evc_button_slug ); ?>" href="<?php echo esc_url( $evc_button['url'], array( 'http', 'https', 'mailto' ) ); ?>" <?php echo 'email' !== $evc_button_slug ? 'target="_blank" rel="noopener noreferrer nofollow"' : ''; ?> aria-label="<?php echo esc_attr( $evc_button['aria_label'] ); ?>">
							<?php echo wp_kses( EVCSocialShare::getIcon( $evc_button_slug ), extensive_vc_get_svg_allowed_html() ); ?>
							<?php if ( 'icons-labels' === $layout ) { ?>
								<span class="evc-ss-label" aria-hidden="true"><?php echo esc_html( $evc_button['label'] ); ?></span>
							<?php } ?>
						</a>
					</li>
				<?php } elseif ( 'copy' === $evc_button['type'] ) { ?>
					<li class="evc-ss-item">
						<button type="button" class="evc-ss-button evc-ss-copy" data-url="<?php echo esc_url( $share_page_url ); ?>" data-copied-label="<?php echo esc_attr( $copied_label ); ?>" aria-label="<?php echo esc_attr( $copy_label ); ?>">
							<?php echo wp_kses( EVCSocialShare::getIcon( 'copy' ), extensive_vc_get_svg_allowed_html() ); ?>
							<?php if ( 'icons-labels' === $layout ) { ?>
								<span class="evc-ss-label" aria-hidden="true"><?php echo esc_html( $copy_label ); ?></span>
							<?php } ?>
						</button>
					</li>
				<?php } elseif ( 'native' === $evc_button['type'] ) { ?>
					<li class="evc-ss-item evc-ss-item-native" hidden>
						<button type="button" class="evc-ss-button evc-ss-native" data-url="<?php echo esc_url( $share_page_url ); ?>" data-title="<?php echo esc_attr( $share_title ); ?>" aria-label="<?php echo esc_attr( $native_label ); ?>">
							<?php echo wp_kses( EVCSocialShare::getIcon( 'native' ), extensive_vc_get_svg_allowed_html() ); ?>
							<?php if ( 'icons-labels' === $layout ) { ?>
								<span class="evc-ss-label" aria-hidden="true"><?php echo esc_html( $native_label ); ?></span>
							<?php } ?>
						</button>
					</li>
				<?php } ?>
			<?php } ?>
		</ul>
		<?php if ( isset( $buttons['copy'] ) ) { ?>
			<span class="evc-ss-status" role="status" aria-live="polite"></span>
		<?php } ?>
	<?php } ?>
	<?php do_action( 'extensive_vc_action_social_share_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_social_share', $params ); ?>
