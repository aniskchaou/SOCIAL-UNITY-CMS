<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(
	'main' => array(
		'type'    => 'box',
		'title'   => '',
		'options' => array(

			'id'       => array(
				'type' => 'unique',
			),
			'builder'  => array(
				'type'    => 'tab',
				'title'   => __( 'Form Fields', 'utouch' ),
				'options' => array(
					'form' => array(
						'label'        => false,
						'type'         => 'form-builder',
						'value'        => array(
							'json' => apply_filters( 'fw:ext:forms:builder:load-item:form-header-title', true )
								? json_encode( array(
									array(
										'type'      => 'form-header-title',
										'shortcode' => 'form_header_title',
										'width'     => '',
										'options'   => array(
											'title'    => '',
											'subtitle' => '',
										)
									)
								) )
								: '[]'
						),
						'fixed_header' => true,
					),
				),
			),
			'settings' => array(
				'type'    => 'tab',
				'title'   => __( 'Settings', 'utouch' ),
				'options' => array(
					'settings-options' => array(
						'title'   => __( 'Options', 'utouch' ),
						'type'    => 'tab',
						'options' => array(
							'form_text_settings'  => array(
								'type'    => 'group',
								'options' => array(
									'subject-group'       => array(
										'type'    => 'group',
										'options' => array(
											'subject_message' => array(
												'type'  => 'text',
												'label' => __( 'Subject Message', 'utouch' ),
												'desc'  => __( 'This text will be used as subject message for the email', 'utouch' ),
												'value' => __( 'New message', 'utouch' ),
											),
										)
									),
									'submit-button-group' => array(
										'type'    => 'group',
										'options' => array(
											'submit_button_text' => array(
												'type'  => 'text',
												'label' => __( 'Submit Button', 'utouch' ),
												'desc'  => __( 'This text will appear in submit button', 'utouch' ),
												'value' => __( 'Send', 'utouch' ),
											),
										)
									),
									'success-group'       => array(
										'type'    => 'group',
										'options' => array(
											'success_message' => array(
												'type'  => 'text',
												'label' => __( 'Success Message', 'utouch' ),
												'desc'  => __( 'This text will be displayed when the form will successfully send', 'utouch' ),
												'value' => __( 'Message sent!', 'utouch' ),
											),
										)
									),
									'failure_message'     => array(
										'type'  => 'text',
										'label' => __( 'Failure Message', 'utouch' ),
										'desc'  => __( 'This text will be displayed when the form will fail to be sent', 'utouch' ),
										'value' => __( 'Oops something went wrong.', 'utouch' ),
									),
								),
							),
							'form_email_settings' => array(
								'type'    => 'group',
								'options' => array(
									'email_to' => array(
										'type'  => 'text',
										'label' => __( 'Email To', 'utouch' ),
										'help'  => __( 'We recommend you to use an email that you verify often', 'utouch' ),
										'desc'  => __( 'The form will be sent to this email address.', 'utouch' ),
									),
								),
							),


						)
					),
					'mailer-options'   => array(
						'title'   => __( 'Mailer', 'utouch' ),
						'type'    => 'tab',
						'options' => array(
							'mailer' => array(
								'label' => false,
								'type'  => 'mailer'
							)
						)
					)
				),
			)
		)
	)
);