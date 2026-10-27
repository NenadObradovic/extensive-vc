<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<li class="evc-pm-item <?php echo esc_attr( $holder_classes ); ?>">
	<?php do_action( 'extensive_vc_action_price_menu_item_before_content', $params ); ?>
	<?php if ( ! empty( $image ) ) { ?>
		<div class="evc-pm-image">
			<?php echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) ); ?>
		</div>
	<?php } ?>
	<div class="evc-pm-content">
		<div class="evc-pm-heading">
			<<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h5' ) ); ?> class="evc-pm-title">
				<?php if ( ! empty( $link_attributes ) ) { ?>
					<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>><?php echo esc_html( $title ); ?></a>
				<?php } else { ?>
					<?php echo esc_html( $title ); ?>
				<?php } ?>
				<?php if ( ! empty( $badge ) ) { ?>
					<span class="evc-pm-badge"><?php echo esc_html( $badge ); ?></span>
				<?php } ?>
			</<?php echo esc_attr( extensive_vc_escape_title_tag( $title_tag, 'h5' ) ); ?>>
			<span class="evc-pm-line" aria-hidden="true"></span>
			<?php if ( '' !== $price || '' !== $old_price ) { ?>
				<span class="evc-pm-prices">
					<?php if ( '' !== $old_price ) { ?>
						<del class="evc-pm-old-price"><?php echo esc_html( $old_price ); ?></del>
					<?php } ?>
					<?php if ( '' !== $price ) { ?>
						<span class="evc-pm-price"><?php echo esc_html( $price ); ?></span>
					<?php } ?>
				</span>
			<?php } ?>
		</div>
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-pm-text"><?php echo esc_html( $text ); ?></p>
		<?php } ?>
	</div>
	<?php do_action( 'extensive_vc_action_price_menu_item_after_content', $params ); ?>
</li>
