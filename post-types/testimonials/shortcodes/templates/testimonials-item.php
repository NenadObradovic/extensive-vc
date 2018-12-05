<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-t-item">
	<?php if ( has_post_thumbnail() ) { ?>
		<div class="evc-t-image">
			<?php echo get_the_post_thumbnail( $current_id, $image_size ); ?>
		</div>
	<?php } ?>
	<div class="evc-t-content">
		<?php if ( ! empty( $text ) ) { ?>
			<p class="evc-t-text"><?php echo esc_html( $text ); ?></p>
		<?php } ?>
		<?php if ( ! empty( $author ) ) { ?>
			<span class="evc-t-author">
				<span class="evc-t-author-label"><?php echo esc_html( $author ); ?></span>
				<?php if ( ! empty( $position ) ) { ?>
					<span class="evc-t-author-position"><?php echo esc_html( $position ); ?></span>
				<?php } ?>
			</span>
		<?php } ?>
	</div>
</div>
