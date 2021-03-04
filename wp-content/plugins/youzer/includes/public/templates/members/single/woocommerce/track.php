<?php
/**
 * WC Track Template
 */
?>
<div class="yzwc-main-content yzwc-track-content">

	<?php do_action( 'yz_wc_before_track_content' ); ?>

	<div class="yzwc-box-title">
		<?php echo yz_wc_get_user_address_type_icon( 'tracking' ); ?>	
    	<h3><?php _e( 'Track your order', 'youzer' ); ?></h3>
    </div>
    
	<?php echo do_shortcode( '[woocommerce_order_tracking]' ); ?>

	<?php do_action( 'yz_wc_after_track_content' ); ?>

</div>