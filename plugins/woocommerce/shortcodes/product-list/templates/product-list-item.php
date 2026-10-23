<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pl-item evc-element-item">
	<?php do_action( 'extensive_vc_action_product_list_item_before_content', $params ); ?>
	<div class="evc-pli-inner">
		<?php extensive_vc_template_part( 'woocommerce', 'product-list', 'templates/layouts/' . $layout_collections, '', $params ); ?>
	</div>
	<?php do_action( 'extensive_vc_action_product_list_item_after_content', $params ); ?>
</div>
