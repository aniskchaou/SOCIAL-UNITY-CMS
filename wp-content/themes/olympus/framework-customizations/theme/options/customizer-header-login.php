<?php

if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
$olympus    = Olympus_Options::get_instance();

$form_selection_options = array(
	'native' => esc_html__( 'Olympus login form', 'olympus' ),
);
if ( class_exists( 'Youzer' ) ) {
	$form_selection_options['youzer'] = esc_html__( 'Youzer plugin form', 'olympus' );
}
if ( function_exists( 'digits_version' ) ) {
	$form_selection_options['digits'] = esc_html__( 'Digits plugin form', 'olympus' );
}
$form_selection_options['custom'] = esc_html__( 'Custom shortcode', 'olympus' );

$sign_forms_forms_val = fw_get_db_settings_option( 'sign-form-forms', 'both' );
$sign_form_redirect_val = fw_get_db_settings_option( 'sign-form-redirect', 'current' );
$sign_form_redirect_to_val = filter_var( fw_get_db_settings_option( 'sign-form-redirect-to/custom/redirect_to', '' ), FILTER_VALIDATE_URL );
$sign_form_login_descr_val = fw_get_db_settings_option( 'sign-form-login-descr', '' );

$options = array(
	'login-panel-options-icon' => array(
		'label' => esc_html__('Button icon', 'olympus'),
		'desc'  => esc_html__('Icon for menu open button', 'olympus'),
		'type'  => 'icon-v2',
		'preview_size' => 'small',
		'modal_size' => 'medium',
	),

	'sign-form-popup' => array(
		'type'    => 'multi-picker',
		'label'   => false,
		'desc'    => false,
		'picker'  => array(
			'sign-form-picker' => array(
				'label'   => esc_html__( 'Form popup', 'olympus' ),
				'desc'    => esc_html__( 'What login form will appear when the button is clicked.', 'olympus' ),
				'type'    => 'radio', // or 'short-select'
				'value'   => 'native',
				'choices' => $form_selection_options,
			),
		),
		'choices' => array(
			'custom' => array(
				'popup-content' => array(
					'label' => esc_html__( 'Popup content', 'olympus' ),
					'desc'  => esc_html__( 'You can use own custom HTML or shortcodes that will appear in popup box', 'olympus' ),
					'type'  => 'textarea',
				),
			),
			'native' => array(
				'sign-form-forms' => array(
					'type' => 'select',
					'value' => $sign_forms_forms_val,
					'label' => esc_html__( 'Display', 'olympus' ),
					'choices' => array(
						'both' => esc_html__( 'Both', 'olympus' ),
						'login' => esc_html__( 'Login', 'olympus' ),
						'register' => esc_html__( 'Register', 'olympus' ),
					)
				),
				'sign-form-redirect-to' => array(
					'type' => 'multi-picker',
					'picker'  => array(
						'sign-form-redirect' => array(
							'type'    => 'select',
							'value'   => $sign_form_redirect_val,
							'label'   => esc_html__( 'Redirect to', 'olympus' ),
							'choices' => array(
								'current' => esc_html__( 'Current page', 'olympus' ),
								'profile' => esc_html__( 'Profile page', 'olympus' ),
								'custom'  => esc_html__( 'Custom page', 'olympus' ),
							)
						),
					),
					'choices' => array(
						'custom' => array(
							'redirect_to' => array(
								'label' => esc_html__( 'Redirect URL', 'olympus' ),
								'type'  => 'text',
								'value' => $sign_form_redirect_to_val
							)
						)
					)
				),
				'sign-form-login-descr' => array(
					'label' => esc_html__( 'Login form description', 'olympus' ),
					'type'  => 'textarea',
					'value' => $sign_form_login_descr_val,
					'desc' => sprintf( esc_html__( 'You can use [%s text="" url=""] shortcode', 'olympus' ), 'register-link' ),
				)
			)
		),
	),

);