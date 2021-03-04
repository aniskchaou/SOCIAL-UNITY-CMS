<?php
if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}
$options = array(
	'custom_js'        => array(
		'title'   => esc_html__( 'Additional JS', 'olympus' ),
		'options' => array(
			'custom-js' => array(
				'type'  => 'textarea',
				'value' => '',
				'label' => esc_html__( 'JS code field', 'olympus' ),
				'desc'  => wp_kses( esc_html__( 'without &lt;script&gt; tags', 'olympus' ), array(
					'&lt;' => array(),
					'&gt;' => array(),
				) ),
				'attr'  => array(
					'class'       => 'large-textarea',
					'placeholder' => 'jQuery( document ).ready(function() {  SOME CODE  });',
					'rows'        => 20,
				),
			),
		),
	),
);


