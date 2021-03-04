<?php

class Logy_Complete_Registration {

	protected $logy;
	
	/**
	 * Init Actions & Filters.
	 */
	public function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		// Complete Registration.
		$this->register_user();

    }
	
	/**
	 * Get Registration form.
	 */
	public function get_form() {
		// Render the form.
		return $this->logy->form->get_page( 'complete_registration' );
	}

	/**
	 * Redirect User To Specific Page..
	 */
	public function redirect( $code, $redirect_to = null, $type = null ) {
		
		// Init Array.
		$messages = array();

		// Get Redirect Url.	
		$redirect_url = ! empty( $redirect_to ) ? $redirect_to : logy_page_url( 'complete-registration' );
			
		// Get Message.
		$messages[] = logy_get_message( $this->logy->form->get_error_message( $code ), $type );

		// Get Messages.
		logy_add_message( $messages, $type );
		
		// Redirect User.
		wp_redirect( $redirect_url );

		// Exit.
		exit;

	}

}

$complete_registration  = new Logy_Complete_Registration();