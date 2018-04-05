<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>
<div class="evc-pl-item evc-element-item">
	<div class="evc-pli-inner">
		<?php echo extensive_vc_get_module_template_part( 'woocommerce', 'product-list', 'templates/layouts/' . $layout_collections, '', $params ); ?>
	</div>
</div>
