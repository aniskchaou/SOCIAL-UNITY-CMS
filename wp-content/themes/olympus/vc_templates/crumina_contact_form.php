<?php
$wrapper_attributes = array();

$form = $class = $current_form_id = $form_html = $submit_text = $submit_color = '';

// Params extraction
extract( shortcode_atts( array(
	'form'   => '',
	'submit_text'   => '',
	'submit_color '   => '',
	'id'    => '',
	'class' => ''
), $atts ) );

$form_id		 = $current_form_id ? $current_form_id : (int) $form;
$form_options	 = get_post_meta( $form_id, 'fw_options', true );

if ( !empty( $form_id ) && !empty( $form_options ) && isset( $form_options[ 'form' ] ) ) {
	$styles		 = '';
	$submit_text = $submit_text ? $submit_text : __( 'Submit', 'olympus' );

	if ( $submit_color ) {
		$styles	 .= "background-color: {$submit_color} !important;";
		$styles	 .= "border-color: {$submit_color} !important;";
	}

	$submit_html = '<button type="submit" class="btn btn-md btn-primary" style="' . $styles . '">' . $submit_text . '</button>';

	ob_start();
	FW_Flash_Messages::_print_frontend();
	$messages = ob_get_clean();

	$form_html = '<div class="crumina-ext-contact-form standart-form-flex ' . $class . '">'
		. $messages
		. fw()->extensions->get( 'forms' )->render_form( $form_id, $form_options[ 'form' ], 'contact-forms', $submit_html )
		. '</div>';
}

return $form_html;