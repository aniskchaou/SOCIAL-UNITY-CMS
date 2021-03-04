<?php
	/**
	 * WC Edit Address Template
	 */
?>
<div class="yzwc-main-content yzwc-edit-address-content">

	<?php do_action( 'yz_wc_before_edit_address_content' ); ?>

	<?php echo do_shortcode( '[youzer_woocommerce_addresses]' ); ?>

	<?php do_action( 'yz_wc_after_edit_address_content' ); ?>

</div>