<?php
/**
 * Lost password form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.2
 */
defined( 'ABSPATH' ) || exit;

wc_print_notices();
?>

<div class="ui-block">
    <div class="ui-block-title">
        <div class="h6 title">
            <?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.', 'olympus' ) ); ?>
        </div>
    </div>
    <div class="ui-block-content">
        <?php do_action( 'woocommerce_before_lost_password_form' ); ?>
        <form method="post" class="woocommerce-ResetPassword lost_reset_password">
            <div class="form-group">
                <label for="user_login"><?php esc_html_e( 'Username or email', 'olympus' ); ?></label>
                <input class="form-control" type="text" name="user_login" id="user_login" autocomplete="username" />
            </div>

            <div class="clear"></div>

            <?php do_action( 'woocommerce_lostpassword_form' ); ?>

            <p class="woocommerce-form-row form-row">
                <input type="hidden" name="wc_reset_password" value="true" />
                <button type="submit" class="btn btn-md btn-primary" value="<?php esc_attr_e( 'Reset password', 'olympus' ); ?>"><?php esc_html_e( 'Reset password', 'olympus' ); ?></button>
            </p>

            <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

        </form>
        <?php do_action( 'woocommerce_after_lost_password_form' ); ?>
    </div>
</div>