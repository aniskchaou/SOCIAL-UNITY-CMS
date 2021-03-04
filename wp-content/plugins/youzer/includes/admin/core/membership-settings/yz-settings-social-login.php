<?php

/**
 * # Social Login Settings.
 */
function logy_social_login_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable Social Login', 'youzer' ),
            'desc'  => __( 'Activate social login', 'youzer' ),
            'id'    => 'logy_enable_social_login',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable E-mail Confirmation', 'youzer' ),
            'desc'  => __( 'Enable email confirmation on social registration.', 'youzer' ),
            'id'    => 'logy_enable_social_login_email_confirmation',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Type', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'social_buttons_type' ),
            'desc'  => __( 'Select buttons type', 'youzer' ),
            'id'    => 'logy_social_btns_type',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Icons Position', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'form_icons_position' ),
            'desc'  => __( 'Select buttons icons position', 'youzer' ),
            'id'    => 'logy_social_btns_icons_position',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Buttons Border Style', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'fields_format' ),
            'desc'  => __( 'Select buttons border style', 'youzer' ),
            'id'    => 'logy_social_btns_format',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // Get Providers.
    $providers = logy_get_providers();

    if ( empty( $providers ) ) {
        return false;
    }

    foreach( $providers as $provider ) :

        // Get Provider Data.
        $provider_data = logy_get_provider_data( $provider );

        // Get Provider.
        $provider = strtolower( $provider );

        // Get Key Or ID.
        $key = ( 'key' == $provider_data['app'] ) ? __( 'Key', 'youzer' ) : __( 'ID', 'youzer' );

        // Get Setup Instruction.
        get_provider_settings_note( $provider );

        $Yz_Settings->get_field(
            array(
            'title' => sprintf( __( '%s Settings', 'youzer' ), $provider ),
            'type'  => 'openBox'
            )
        );


        $Yz_Settings->get_field(
            array(
                'title' => __( 'Enable Network', 'youzer' ),
                'desc'  => __( 'Enable application', 'youzer' ),
                'id'    => 'logy_' . $provider . '_app_status',
                'type'  => 'checkbox',
                'std'   => 'on'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => sprintf( __( 'Application %s', 'youzer' ), $key ),
                'desc'  => sprintf( __( 'Enter application %s', 'youzer' ), $key ),
                'id'    => 'logy_' . $provider . '_app_key',
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field(
            array(
                'title' => __( 'Application Secret', 'youzer' ),
                'desc'  => __( 'Enter application secret key', 'youzer' ),
                'id'    => 'logy_' . $provider . '_app_secret',
                'type'  => 'text'
            )
        );

        $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    endforeach;

}


/**
 * Get Setup Instructions.
 */
function get_provider_settings_note( $provider ) {

    global $Yz_Settings;

    $steps = get_provider_instructions( $provider );

    $steps = apply_filters( 'logy_providet_setup_instrcutions', $steps );

    if ( empty( $steps ) ) {
        return false;
    }

    $Yz_Settings->get_field(
        array(
            'msg_type'  => 'info',
            'type'      => 'msgBox',
            'id'        => 'logy_' . $provider . '_setup_steps',
            'title'     => sprintf( __( 'How to get %s keys?', 'youzer' ), $provider ),
            'msg'       => implode( '<br>', $steps )
        )
    );
}

/**
 * Get Provide instructions
 */
function get_provider_instructions( $provider ) {

    switch ( $provider ) {

        case 'facebook':

            // Init Vars.
            $auth_url = home_url( '/yz-auth/social-login/Facebook' );
            $apps_url = 'https://developers.facebook.com/apps';

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzer' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create New App".', 'youzer' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzer' );
            $steps[] = __( '4. Put your website domain in the site URL field.', 'youzer' );
            $steps[] = __( '5. Go to the Status & Review page.', 'youzer' );
            $steps[] = __( '6. Enable <strong>"Do you want to make this app and all its live features available to the general public?"</strong>.', 'youzer' );
            $steps[] = __( '7. Facebook Login > Settings > Valid OAuth redirect URIs:', 'youzer' );
            $steps[] = sprintf( __( '8. OAuth URL : <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
            $steps[] = __( '9. Go to dashboard and get your <strong>App ID</strong> and <strong>App Secret</strong>', 'youzer' );

            return $steps;

        case 'twitter':

            // Init Vars.
            $apps_url = 'https://dev.twitter.com/apps';
            $auth_url = home_url( '/yz-auth/social-login/Twitter' );

            // Get Note
            $steps[] = __( '<strong><a>Note:</a> Twitter do not provide their users email address, to make that happen you have to submit your application for review untill that time we will request the email from users while registration.</strong>', 'youzer' ) . '<br>';

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzer' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create New App".', 'youzer' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzer' );
            $steps[] = __( '4. Put your website domain in the Site URL field.', 'youzer' );
            $steps[] = __( "5. Provide URL's below as the Callback URL's for your application respecting the same order.", 'youzer' );
            $steps[] = sprintf( __( '5.1 First Callback URL: <strong><a>%s</a></strong>', 'youzer' ), home_url() );
            $steps[] = sprintf( __( '5.2 Second Callback URL: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
            $steps[] = __( '6. Register Settings and get Consumer Key and Secret.', 'youzer' );

            return $steps;

        case 'google':

            // Init Vars.
            $apps_url = 'https://code.google.com/apis/console/';
            $auth_url = home_url( '/yz-auth/social-login/Google' );
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzer' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create a new project".', 'youzer' );
            $steps[] = __( '3. Go to API Access under API Project.', 'youzer' );
            $steps[] = __( '4. After that click on Create an OAuth 2.0 client ID to create a new application.', 'youzer' );
            $steps[] = __( '5. A pop-up named "Create Client ID" will appear, fill out any required fields such as the application name and description and Click on Next.', 'youzer' );
            $steps[] = __( '6. On the popup set Application type to Web application and switch to advanced settings by clicking on ( more options ) .', 'youzer' );
            $steps[] = __( '7. Provide URL below as the Callback URL for your application.', 'youzer' );
            $steps[] = sprintf( __( '8. Callback URL: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
            $steps[] = __( '9. Once you have registered, copy the created application credentials (Client ID and Secret ) .', 'youzer' );
            $steps[] = __( "<br><strong style='color: red;'>Notice : </strong> if google did not approved your application because of the sign in button design you can add this code snippet to the file <strong>'bp-custom.php'</strong> in the path <strong>'wp-content/plugins'</strong> : <a href='https://gist.github.com/KaineLabs/cc8d2479f59f09e04450e8b55ca8da51'>New Google Sign in Button</a>.<br><strong>Ps</strong>: if you didn't find the file 'bp-custom.php', just create a new one !", 'youzer' );

            return $steps;

        case 'linkedin':

            // Init Vars.
            $apps_url = 'https://www.linkedin.com/developer/apps';
            $auth_url = home_url( '/yz-auth/social-login/LinkedIn' );

            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzer' ), $apps_url, $apps_url );
            $steps[] = __( '2. Create a new application by clicking "Create Application".', 'youzer' );
            $steps[] = __( '3. Fill out any required fields such as the application name and description.', 'youzer' );
            $steps[] = __( '4. Put the below url in the OAuth 2.0 Authorized Redirect URLs:', 'youzer' );
            $steps[] = sprintf( __( '5. Redirect URL: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
            $steps[] = __( '6. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'youzer' );
            return $steps;

        case 'instagram':

            // Init Vars.
            $apps_url = 'https://kainelabs.ticksy.com/article/15737/';
            $auth_url = home_url( '/yz-auth/social-login/Instagram' );

            // Get Note
            $steps[] = __( '<strong><a>Note:</a> Instagram do not provide their users email address, to make that happen you have to submit your application for review untill that time we will request the email from users while registration.</strong>', 'youzer' ) . '<br>';

            // Get Steps.
            $steps[] = sprintf( __( '1. Check this topic on <a href="%1s">How to Setup Instagram Social Login</a> for a detailed steps.', 'youzer' ), $apps_url );
            $steps[] = __( '2. Put the below url as OAuth redirect_uri Authorized Redirect URLs:', 'youzer' );
            $steps[] = sprintf( __( '3. Redirect URL: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );

            return $steps;

        case 'twitchtv':

            // Init Vars.
            $apps_url = 'https://dev.twitch.tv/console/apps/create';
            $auth_url = home_url( '/yz-auth/social-login/TwitchTV' );
            // Get Steps.
            $steps[] = sprintf( __( '1. Go to <a href="%1s">%2s</a>', 'youzer' ), $apps_url, $apps_url );
            $steps[] = __( '2. Fill out any required fields such as the application name and Category.', 'youzer' );
            $steps[] = __( '3. Put the below url as OAuth redirect_uri  Authorized Redirect URLs:', 'youzer' );
            $steps[] = sprintf( __( 'Redirect URL: <strong><a>%s</a></strong>', 'youzer' ), $auth_url );
            $steps[] = __( '4. Once you have registered, copy the created application credentials ( Client ID and Secret ) .', 'youzer' );

            return $steps;

        default:
            return false;
    }
}