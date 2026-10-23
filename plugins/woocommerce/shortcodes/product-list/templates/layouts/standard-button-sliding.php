<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<?php if ( has_post_thumbnail() ) { ?>
	<div class="evc-pli-image-wrapper">
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-image', '', $params ); ?>
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-add-to-cart', '', $params ); ?>
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-link', '', $params ); ?>
	</div>
<?php } ?>
<div class="evc-pli-content">
	<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-title', '', $params ); ?>
	<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-rating', '', $params ); ?>
	<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-price', '', $params ); ?>
	<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-category', '', $params ); ?>
</div>