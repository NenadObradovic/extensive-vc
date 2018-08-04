<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$image_class = isset( $without_zoom ) ? '' : 'evc-ib-zoom';

if ( has_post_thumbnail() ) { ?>
	<div class="evc-bli-image <?php echo esc_attr( $image_class ); ?>">
		<a href="<?php the_permalink(); ?>">
			<?php the_post_thumbnail( $image_proportions ); ?>
			<?php echo extensive_vc_get_module_template_part( 'shortcodes', 'blog-list', 'templates/parts/date', 'simple', $params ); ?>
		</a>
	</div>
<?php }
