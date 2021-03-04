<?php
/**
 * Login form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/global/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.6.0
 */
if ( !defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( is_user_logged_in() ) {
    return;
}
?>
<form class="woocommerce-form woocommerce-form-login login"
      method="post" <?php olympus_render( ( $hidden ) ? 'style="display:none;"' : ''  ); ?>>

    <?php do_action( 'woocommerce_login_form_start' ); ?>

    <?php echo olympus_render( ( $message ) ? wpautop( wptexturize( $message ) ) : '' ); ?>

    <div class="row">
        <div class="col-md-5">
            <div class="form-group label-floating">
                <label for="username"  class="control-label"><?php esc_html_e( 'Username or email', 'olympus' ); ?></label>
                <input class="form-control" type="text" name="username" id="username" required>
                <div class="invalid-feedback">
                    <div class="error-box">
                        <div class="danger"><?php echo olympus_icon_font( 'olympus-icon-Close-Icon' ) ?></div>
                        <h5 class="title"><?php esc_html_e( 'Error', 'olympus' ) ?></h5>
                        <p><?php esc_html_e( 'Please enter a valid username or E-mail address', 'olympus' ) ?></p>
                    </div>
                </div>
            </div></div>
        <div class="col-md-5">
            <div class="form-group label-floating">
                <label for="password"  class="control-label"><?php esc_html_e( 'Password', 'olympus' ); ?></label>
                <input class="form-control" type="text" name="password" id="password" required>
                <div class="invalid-feedback">
                    <div class="error-box">
                        <div class="danger"><?php echo olympus_icon_font( 'olympus-icon-Close-Icon' ) ?></div>
                        <h5 class="title"><?php esc_html_e( 'Error', 'olympus' ) ?></h5>
                        <p><?php esc_html_e( 'Please enter password for login to your account', 'olympus' ) ?></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-md-2">
            <button class="btn btn-md btn-primary full-width" name="login"
                    type="submit"><?php esc_html_e( 'Login', 'olympus' ); ?></button>
        </div>
    </div>
    <?php do_action( 'woocommerce_login_form' ); ?>

    <p>
    <div class="checkbox">
        <label>
            <input name="rememberme" type="checkbox" id="rememberme" value="forever">
            <?php esc_html_e( 'Remember me', 'olympus' ); ?>
        </label>
    </div>


    <h6 class="lost_password">
        <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'olympus' ); ?></a>
    </h6>
</p>
<div class="clear"></div>

<?php do_action( 'woocommerce_login_form_end' ); ?>

<input type="hidden" name="redirect" value="<?php echo esc_url( $redirect ) ?>"/>
<?php wp_nonce_field( 'woocommerce-login' ); ?>

</form>
