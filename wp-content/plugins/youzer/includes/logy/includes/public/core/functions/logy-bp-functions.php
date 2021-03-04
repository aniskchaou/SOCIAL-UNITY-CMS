<?php

/**
 * Check Is Buddypress Registration Completed.
 */
function logy_is_bp_registration_completed() {
	if ( logy_is_page( 'register' ) && 'completed-confirmation' == bp_get_current_signup_step() ) {
		return true;
	}

	return false;
}

/**
 * # Register Buddypress Custom Template.
 */
function logy_register_bp_template() {

    if ( function_exists( 'bp_register_template_stack'  ) ) {
        bp_register_template_stack( 'logy_register_bp_templates_location', 0 );
    }

}

add_action( 'init', 'logy_register_bp_template', 9999 );

/**
 * # Register Buddypress Custom Template Location .
 */
function logy_register_bp_templates_location() {
    return LOGY_TEMPLATE . '/';
}

function logy_is_bp_reset_password_active() {

    // Check if term Already Exist.
    $term = term_exists( 'request_reset_password', bp_get_email_tax_type() );

    if ( ! $term ) {
        return false;
    }

    return true;

}

function logy_bp_retrieve_password() {

    $errors = new WP_Error();

    if ( empty( $_POST['user_login'] ) ) {
        $errors->add('empty_username', __('<strong>Error</strong>: Enter a username or email address.'));
    } elseif ( strpos( $_POST['user_login'], '@' ) ) {
        $user_data = get_user_by( 'email', trim( wp_unslash( $_POST['user_login'] ) ) );
        if ( empty( $user_data ) )
            $errors->add('invalid_email', __('<strong>Error</strong>: There is no user registered with that email address.'));
    } else {
        $login = trim($_POST['user_login']);
        $user_data = get_user_by('login', $login);
    }

    /**
     * Fires before errors are returned from a password reset request.
     *
     * @since 2.1.0
     * @since 4.4.0 Added the `$errors` parameter.
     *
     * @param WP_Error $errors A WP_Error object containing any errors generated
     *                         by using invalid credentials.
     */
    do_action( 'lostpassword_post', $errors );

    if ( $errors->get_error_code() )
        return $errors;

    if ( !$user_data ) {
        $errors->add('invalidcombo', __('<strong>Error</strong>: Invalid username or email.'));
        return $errors;
    }

    // Redefining user_login ensures we return the right case in the email.
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key = get_password_reset_key( $user_data );

    if ( is_wp_error( $key ) ) {
        return $key;
    }

    if ( is_multisite() ) {
        $blogname = get_network()->site_name;
    } else {
        $blogname = wp_specialchars_decode( get_option( 'blogname' ), ENT_QUOTES );
    }

    $args = array(
        'tokens' => array(
            'site.name' => $blogname,
            'password.reset.url' => network_site_url( "wp-login.php?action=rp&key=$key&login=" . rawurlencode( $user_login ), 'login' ),
        ),
    );

    bp_send_email( 'request_reset_password', (int) $user_data->ID, $args );

    return true;
}

/**
 * Add Terms description to the register  form
 */
function yz_buddypress_register_form_terms() {

    // Display terms and conditions & privacy policy.
    if ( 'off' == yz_option( 'logy_show_terms_privacy_note', 'on' ) ) {
        return false;
    }

    // Get Data
    $terms_url = yz_option( 'logy_terms_url' );
    $privacy_url = yz_option( 'logy_privacy_url' );

    ?>

    <div class="logy-form-note logy-terms-note">
        <?php echo sprintf( __( 'By creating an account you agree to our <a href="%1s" target="_blank">Terms and Conditions</a> and our <a href="%2s" target="_blank">Privacy Policy</a>.', 'youzer' ), $terms_url, $privacy_url ); ?>
    </div>

    <?php

}

add_action( 'yz_register_form_before_actions', 'yz_buddypress_register_form_terms' );