<?php
/**
 * WC Edit Account Template
 */
?>
<div class="yzwc-main-content yzwc-edit-account-content">

	<?php do_action( 'yz_wc_before_edit_account_content' ); ?>

	<?php echo do_shortcode( '[youzer_woocommerce_edit_account]' ); ?>

	<?php do_action( 'yz_wc_after_edit_account_content' ); ?>

</div>