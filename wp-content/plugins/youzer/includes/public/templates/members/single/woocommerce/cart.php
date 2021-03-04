<?php
/**
 * WC Cart Template
 */
?>
<div class="yzwc-main-content yzwc-cart-content">

	<?php do_action( 'yz_wc_before_cart_content' ); ?>

	<?php echo do_shortcode( '[woocommerce_cart]' ); ?>

	<?php do_action( 'yz_wc_after_cart_content' ); ?>

</div>