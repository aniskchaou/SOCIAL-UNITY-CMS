<?php
/**
 * WC Downloads Template
 */
?>
<div class="yzwc-main-content yzwc-downloads-content">

	<?php do_action( 'yz_wc_before_downloads_content' ); ?>

	<?php echo do_shortcode( '[youzer_woocommerce_downloads]' ); ?>

	<?php do_action( 'yz_wc_after_downloads_content' ); ?>

</div>