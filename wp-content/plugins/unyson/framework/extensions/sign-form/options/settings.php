<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$ext = fw_ext( 'sign-form' );

$options = array(
    'sign-form' => array(
        'title'    => esc_html__( 'Sign in Form', 'crum-ext-sign-form' ),
        'type'     => 'tab',
        'priority' => 'high',
        'options'  => array(
            'sign-form-forms'       => array(
                'type'    => 'select',
                'value'   => 'both',
                'label'   => esc_html__( 'Display', 'crum-ext-sign-form' ),
                'choices' => array(
                    'both'     => esc_html__( 'Both', 'crum-ext-sign-form' ),
                    'login'    => esc_html__( 'Login', 'crum-ext-sign-form' ),
                    'register' => esc_html__( 'Register', 'crum-ext-sign-form' ),
                )
            ),
            'sign-form-redirect'    => array(
                'type'    => 'select',
                'value'   => 'current',
                'label'   => esc_html__( 'Redirect to', 'crum-ext-sign-form' ),
                'choices' => array(
                    'current' => esc_html__( 'Current page', 'crum-ext-sign-form' ),
                    'profile' => esc_html__( 'Profile page', 'crum-ext-sign-form' ),
                    'custom'  => esc_html__( 'Custom page', 'crum-ext-sign-form' ),
                )
            ),
            'sign-form-redirect-to' => array(
                'type'    => 'multi-picker',
                'picker'  => 'sign-form-redirect',
                'choices' => array(
                    'custom' => array(
                        'redirect_to' => array(
                            'label' => esc_html__( 'Redirect URL', 'crum-ext-sign-form' ),
                            'type'  => 'text',
                        )
                    )
                )
            ),
            'sign-form-login-descr'           => array(
                'label' => esc_html__( 'Login form description', 'crum-ext-sign-form' ),
                'type'  => 'textarea',
                'desc' => sprintf( esc_html__( 'You can use [%s text="" url=""] shortcode', 'crum-ext-sign-form' ), $ext->get_config( 'registerLinkSC' ) ),
            )
        )
    )
);
