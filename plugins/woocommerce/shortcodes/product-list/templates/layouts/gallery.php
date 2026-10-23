<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pli-image-wrapper">
	<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-image', '', $params ); ?>
	<div class="evc-pli-info-on-image">
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-title', '', $params ); ?>
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-rating', '', $params ); ?>
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-price', '', $params ); ?>
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-category', '', $params ); ?>
		<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-add-to-cart', '', $params ); ?>
	</div>
</div>
<?php extensive_vc_template_part( 'woocommerce-part', 'templates', 'woo-link', '', $params ); ?>