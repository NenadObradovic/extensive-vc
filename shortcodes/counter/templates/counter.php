<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wp_enqueue_script( 'jquery-countto' );
?>
<?php do_action( 'extensive_vc_action_before_counter', $params ); ?>
<div class="evc-counter evc-shortcode <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_counter_before_content', $params ); ?>
	<div class="evc-c-inner">
		<?php if ( ! empty( $digit ) ) { ?>
			<span class="evc-c-digit" <?php extensive_vc_print_inline_style( $digit_styles ); ?>><?php echo esc_html( $digit ); ?></span>
		<?php } ?>
		<?php if ( ! empty( $title ) ) { ?>
			<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?> class="evc-c-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
				<?php echo esc_html( $title ); ?>
			</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag ) ); ?>>
		<?php } ?>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-c-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
	<?php do_action( 'extensive_vc_action_counter_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_counter', $params ); ?>
