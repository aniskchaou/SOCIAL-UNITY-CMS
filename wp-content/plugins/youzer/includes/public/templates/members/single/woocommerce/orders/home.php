<?php
/**
 * WC Orders Home Template
 */
?>
<div class="yzwc-main-content yzwc-orders-content">

	<?php do_action( 'yz_wc_before_orders_content' ); ?>

	<?php echo do_shortcode( '[youzer_woocommerce_orders]' ); ?>

	<?php do_action( 'yz_wc_after_orders_content' ); ?>

</div>