<?php
/**
 * Checkout coupon form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.4.4
 */

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
	return;
} ?>

<div class="woocommerce-form-coupon-toggle">
	<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'olympus' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'olympus' ) . '</a>' ), 'notice' ); ?>
</div>


<form class="checkout_coupon row" method="post" style="display:none">
    <div class="form-group label-floating col-md-10">
        <label class="control-label"><?php esc_html_e( 'Coupon code', 'olympus' ); ?></label>
        <input type="text" name="coupon_code" class="input-text form-control"  id="coupon_code" value=""/>
    </div>
    <button type="submit" class="btn btn-primary btn-lg col-md-2"
            name="apply_coupon"><?php esc_html_e( 'Apply coupon', 'olympus' ); ?></button>
    <div class="clear"></div>
</form>