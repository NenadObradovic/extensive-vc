<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! empty( $title ) ) { ?>
	<<?php echo esc_attr( $title_tag ); ?> class="evc-iwt-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
		<?php if ( ! empty( $link_attributes ) ) { ?>
			<a <?php echo implode( ' ', $link_attributes ); ?>>
		<?php } ?>
			<?php echo esc_html( $title ); ?>
		<?php if ( ! empty( $link_attributes ) ) { ?>
			</a>
		<?php } ?>
	</<?php echo esc_attr( $title_tag ); ?>>
<?php } ?>
<?php if ( ! empty( $text ) ) { ?>
	<p class="evc-iwt-text" <?php extensive_vc_print_inline_style( $text_styles ); ?>><?php echo esc_html( $text ); ?></p>
<?php } ?>
