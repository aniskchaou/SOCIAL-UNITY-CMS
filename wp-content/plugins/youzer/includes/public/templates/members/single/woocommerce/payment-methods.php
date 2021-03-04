<?php
	/**
	 * WC Payment Methods Template
	 */
?>
<div class="yzwc-main-content yzwc-payment-methods-content">

	<?php do_action( 'yz_wc_before_payment_methods_content' ); ?>

	<?php echo do_shortcode( '[youzer_payment_methods]' ); ?>

	<?php do_action( 'yz_wc_after_payment_methods_content' ); ?>

</div>