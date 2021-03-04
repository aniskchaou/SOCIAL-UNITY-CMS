<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$ext = fw_ext( 'sign-form' );

$options = array(
    'sign-in-form' => array(
        'title'   => esc_html__( 'Sign-in | Register form', 'crum-ext-sign-form' ),
        'options' => array(
            'sign-in-register-fields' => array(
                'title'    => esc_html__( 'Register Form Fields', 'crum-ext-sign-form' ),
                'type'     => 'tab',
                'priority' => 'high',
                'options'  => array(
                    'sign-in-register-fields-type' => array(
                        'type'  => 'radio',
                        'label' => __('Fileds to display in the Olympus register form', 'crum-ext-sign-form'),
                        'value' => 'simple',
                        'desc' => '<b>- Basic</b><br>Form is non-editable and consists of basic WP fields \'First name\', \'Last name\', \'Username\', \'Email\', \'Password\' fields.<br><br><b>- Standard</b><br>Form is editable via Users > Profile fields. Only the required field will be displayed in the form.
                        \'Username\', \'Email\', \'Password\' are non-editable fields.<br><br><b>- Extended</b><br>Form is editable via Users > Profile fields. Only the required field will be displayed in the form.
                        After filling in form fields user will be redirected to the Register page, where can complete other Profile Info Fields
                        \'Username\', \'Email\' are non-editable fields.',
                        'choices' => array(
                            'simple' => __('Basic', 'crum-ext-sign-form'),
                            'buddy_press' => __('Standard', 'crum-ext-sign-form'),
                            'extensional' => __('Extended', 'crum-ext-sign-form'),
                        ),
                        'inline' => false,
                    ),
                    'sign-in-register-activation-email' => array(
                        'type' => 'switch',
                        'label' => __('Activation Email', 'crum-ext-sign-form'),
                        'value' => 'yes',
                        'left-choice' => array(
                            'value' => 'no',
                            'label' => __('Disable', 'crum-ext-sign-form'),
                        ),
                        'right-choice' => array(
                            'value' => 'yes',
                            'label' => __('Enable', 'crum-ext-sign-form'),
                        ),
                    )
                )
            ),
            'sign-form-captcha' => array(
                'title'    => esc_html__( 'Captcha Settings', 'crum-ext-sign-form' ),
                'type'     => 'tab',
                'priority' => 'high',
                'options'  => array(
                    'sign-in-enable-captcha'      => array(
                        'type'  => 'checkbox',
                        'desc'  => sprintf( esc_html__( 'reCAPTCHA protects you against spam and other types of automated abuse. For details, see %sreCAPTCHA (v3)%s', 'crum-ext-sign-form' ), '<a target="_blank" href="https://www.google.com/recaptcha/about/">', '</a>' ),
                        'label' => __( 'Enable Captcha', 'crum-ext-sign-form' ),
                        'value' => false,
                    ),
                    'sign-in-captcha-site-key' => array(
                        'label' => esc_html__( 'Captcha Site Key', 'crum-ext-sign-form' ),
                        'type'  => 'text',
                    ),
                    'sign-in-captcha-secret-key' => array(
                        'label' => esc_html__( 'Captcha Secret Key', 'crum-ext-sign-form' ),
                        'type'  => 'text',
                    )
                )
            ),
            'sign-in-email-options' => array(
                'title'    => esc_html__( 'Email Options', 'crum-ext-sign-form' ),
                'type'     => 'tab',
                'priority' => 'high',
                'desc'     => '<b>You can edit emails text via WP Dashboard > Emails</b>',
                'options'  => array(
                    'sign-in-email-options-name' => array(
                        'label' => esc_html__( 'Mail Sender Name', 'crum-ext-sign-form' ),
                        'type'  => 'text',
                    ),
                    'sign-in-email-options-mail' => array(
                        'label' => esc_html__( 'Mail Sender Email', 'crum-ext-sign-form' ),
                        'type'  => 'text',
                    )
                )
            ),
            'sign-in-header-login-options' => array(
                'title' => esc_html__( 'Sign-in popup', 'crum-ext-sign-form' ),
                'options'  => array(
                    $ext->get_options( 'partials/header-login' )
                )
            )
        ),
    ),
);