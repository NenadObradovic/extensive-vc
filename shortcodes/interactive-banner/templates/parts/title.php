<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! empty( $title ) ) { ?>
	<<?php echo esc_attr( $title_tag ); ?> class="evc-ib-title" <?php extensive_vc_print_inline_style( $title_styles ); ?>>
		<?php echo esc_html( $title ); ?>
	</<?php echo esc_attr( $title_tag ); ?>>
<?php } ?>
