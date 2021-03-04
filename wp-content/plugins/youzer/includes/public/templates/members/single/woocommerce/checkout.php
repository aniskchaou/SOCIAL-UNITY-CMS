<?php
/**
 * WC Checkout Template
 */
?>
<div class="yzwc-main-content yzwc-checkout-content">

	<?php do_action( 'yz_wc_before_checkout_content' ); ?>

	<?php echo do_shortcode( '[woocommerce_checkout]' ); ?>

	<?php do_action( 'yz_wc_after_checkout_content' ); ?>

</div>