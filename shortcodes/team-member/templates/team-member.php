<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php do_action( 'extensive_vc_action_before_team_member', $params ); ?>
<div class="evc-team-member evc-shortcode <?php echo esc_attr( $holder_classes ); ?>" <?php extensive_vc_print_inline_style( $holder_styles ); ?>>
	<?php do_action( 'extensive_vc_action_team_member_before_content', $params ); ?>
	<div class="evc-tm-inner">
		<?php if ( ! empty( $image ) ) { ?>
			<div class="evc-tm-image">
				<?php if ( ! empty( $link_attributes ) ) { ?>
					<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?> tabindex="-1" aria-hidden="true">
				<?php } ?>
				<?php echo wp_kses_post( extensive_vc_get_attachment_image( $image, $image_size ) ); ?>
				<?php if ( ! empty( $link_attributes ) ) { ?>
					</a>
				<?php } ?>
			</div>
		<?php } ?>
		<div class="evc-tm-content">
			<?php if ( ! empty( $name ) ) { ?>
				<<?php echo esc_attr( extensive_vc_escape_title_tag( $name_tag, 'h4' ) ); ?> class="evc-tm-name">
					<?php if ( ! empty( $link_attributes ) ) { ?>
						<a <?php extensive_vc_print_link_attributes( $link_attributes ); ?>><?php echo esc_html( $name ); ?></a>
					<?php } else { ?>
						<?php echo esc_html( $name ); ?>
					<?php } ?>
				</<?php echo esc_attr( extensive_vc_escape_title_tag( $name_tag, 'h4' ) ); ?>>
			<?php } ?>
			<?php if ( ! empty( $position ) ) { ?>
				<p class="evc-tm-position"><?php echo esc_html( $position ); ?></p>
			<?php } ?>
			<?php if ( ! empty( $text ) ) { ?>
				<p class="evc-tm-text"><?php echo esc_html( $text ); ?></p>
			<?php } ?>
			<?php if ( ! empty( $social_links ) ) { ?>
				<ul class="evc-tm-social">
					<?php foreach ( $social_links as $social_slug => $social_link ) { ?>
						<li class="evc-tm-social-item">
							<a class="evc-tm-social-link" href="<?php echo esc_url( $social_link['url'], array( 'http', 'https', 'mailto' ) ); ?>" aria-label="<?php echo esc_attr( $social_link['label'] ); ?>" <?php echo $social_link['external'] ? 'target="_blank" rel="noopener noreferrer"' : ''; ?>>
								<?php echo wp_kses( extensive_vc_get_social_icon( $social_slug ), extensive_vc_get_svg_allowed_html() ); ?>
							</a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
	</div>
	<?php do_action( 'extensive_vc_action_team_member_after_content', $params ); ?>
</div>
<?php do_action( 'extensive_vc_action_after_team_member', $params ); ?>
