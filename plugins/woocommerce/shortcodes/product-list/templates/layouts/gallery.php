<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pli-image-wrapper">
	<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-image', '', $params ); ?>
	<div class="evc-pli-info-on-image">
		<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-title', '', $params ); ?>
		<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-rating', '', $params ); ?>
		<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-price', '', $params ); ?>
		<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-category', '', $params ); ?>
		<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-add-to-cart', '', $params ); ?>
	</div>
</div>
<?php echo extensive_vc_get_module_template_part( 'woocommerce-part', 'templates', 'woo-link', '', $params ); ?>