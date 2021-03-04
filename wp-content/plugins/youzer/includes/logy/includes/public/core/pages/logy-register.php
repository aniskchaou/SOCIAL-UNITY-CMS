<?php

class Logy_Register {
	
	/**
	 * Init Registration Actions & Filters.
	 */
	public function __construct() {

        if ( apply_filters( 'yz_enable_registration_hooks', true ) ) {

	    	// Init Captcha.
			add_action( 'logy_before_register_form', array( $this, 'init_captcha' ) );

			// Verify Captcha.
			add_action( 'bp_signup_pre_validate', array( $this, 'verify_recaptcha' ) );
		
			// Stop Converting Spaces to '-'.
			remove_action( 'pre_user_login', 'bp_core_strip_username_spaces' );

			// Prevent Username Spaces.
			add_filter( 'validate_username', array( $this, 'restrict_space_in_username' ), 10, 2 );

			// Redirect On Sign Up.
	        add_action( 'bp_core_screen_signup', array( $this, 'redirect_on_signup' ) );
	        
	        // Display Errors.
	        add_action( 'bp_init', array( $this, 'process_signup_errors' ) );

	        // Reference Registration Page
	        add_action( 'logy_after_register_buttons', array( $this, 'registration_page_reference' ) );

        }

	}
 
	/**
	 * Call Captcha Actions
	 */
	function init_captcha() {

		if ( ! $this->is_captcha_active() ) {
			return false;
		}

		// if captcha activated call the captcha javascript file.
		add_action( 'wp_print_footer_scripts', array( $this, 'add_captcha_js' ) );

		// Add Captcha field.
		add_action( 'bp_before_registration_submit_buttons', array( $this, 'add_captcha' ) );

	}

	/**
	 * Check If Captcha Is Active .
	 */
	function is_captcha_active() {

	    if ( 'off' == yz_option( 'logy_enable_recaptcha', 'on' ) )  {
	        return false;
	    }

	    // Check Captcha Options.
	    if ( empty( yz_option( 'logy_recaptcha_site_key' ) ) || empty( yz_option( 'logy_recaptcha_secret_key' ) ) ) {
	        return false;
	    }

	    return true;
	}

	/**
	 * add a filter to invalidate a username with spaces
	 * 
	 */
	function restrict_space_in_username( $valid, $user_name ) {
	  
		// Check if there is an space
		if ( preg_match( '/\s/', $user_name ) ) {
		  //if yes, then we say it is an error
		  return false;
	  	}

		//otherwise return the actual validity
	 	return $valid;

	}

	/**
	 * Checks that the reCAPTCHA parameter sent with the registration request is valid.
	 */
	public function verify_recaptcha() {

		// Get Captcha Response
		if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset ( $_POST['g-recaptcha-response'] ) ) {
			return false;
		}

		// This field is set by the recaptcha widget if check is successful
		$captcha_response = sanitize_text_field( $_POST['g-recaptcha-response'] );

		// Verify the captcha response from Google
		$response = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret' 	=> yz_option( 'logy_recaptcha_secret_key' ),
					'response' 	=> $captcha_response
				)
			)
		);

		$success = false;

		if ( $response && is_array( $response ) ) {
			$decoded_response = json_decode( $response['body'] );
			$success = $decoded_response->success;
		}

		// Verify Captcha Response.
		if ( ! $success ) {
			$this->redirect( 'wrong_captcha' );
		}

	}

	/**
	 * Add Captcha
	 */
	function add_captcha() { ?>
		
		<div class="logy-recaptcha-container">
			<div class="g-recaptcha"<?php do_action( 'yz_recaptcha_attributes' ); ?> data-sitekey="<?php echo yz_option( 'logy_recaptcha_site_key' ); ?>"></div>
		</div>

		<?php

	}

	/**
	 * Attributes
	 */
	function attributes() {
		return $this->messages_attributes();
	}

	/**
	 * Messages Attributes
	 */
	function messages_attributes() {
		// Retrieve possible errors from request parameters
		$attributes['errors'] = array();
		if ( isset( $_REQUEST['register-errors'] ) ) {
			$error_codes = explode( ',', $_REQUEST['register-errors'] );
			foreach ( $error_codes as $error_code ) {
				$attributes['errors'] []= $this->get_error_message( $error_code );
			}
		}

		// Retrieve recaptcha key
		$attributes['recaptcha_site_key'] = $this->is_captcha_active() ? yz_option( 'logy_recaptcha_site_key' ) : null;

		return $attributes;
	}

    /**
     * If the signup form is being processed, Redirect to the page where the signup form is
     *
     */
    function redirect_on_signup() {
        
        if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) ) {
            return;
        }

        if ( isset( $_POST['yz_registration_page'] ) ) {
        	return;
        }
        
        // Init Buddypress variable.
        $bp = buddypress();
        
        // Only if bp signup object is set
        if ( ! empty( $bp->signup ) ) {
        	// Set Sessions.
            $_SESSION['youzer_signup'] = $bp->signup;
            $_SESSION['youzer_signup_fields'] = $_POST;
        }
        
        // Redirect To Same Page.
        bp_core_redirect( wp_get_referer() );

    }
    
    /**
     * Process Sign Up Errors.
     */
    function process_signup_errors() {
        
        if ( is_user_logged_in() ) {
            return;
        }
        
        // Init Session.
        if ( ! session_id() ) {
            session_start();
        }
        
        // Check if the current request
        if ( ! empty( $_SESSION['youzer_signup'] ) ) {
            
            // Init Variables.
            $bp = buddypress();

            // Restore the old signup object.
            $bp->signup = $_SESSION['youzer_signup'];
            
            // We are sure that it is our redirect from the youzer_redirect_on_signup function, so we can safely replace the $_POST array
            if ( isset( $bp->signup->errors ) && ! empty( $bp->signup->errors ) ) {
                // We need to restore so that the signup form can show the old data.
                $_POST = $_SESSION['youzer_signup_fields'];
            }

            // Init Array.
            $errors = array();
            
            // Get Errors.
            if ( isset( $bp->signup->errors ) ) {
                $errors = (array) $bp->signup->errors;
            }

            // Print Errors.
            foreach ( $errors as $fieldname => $error_message ) {
                
                add_action(
                	'bp_' . $fieldname . '_errors',
                	create_function( '', 'echo apply_filters(\'bp_members_signup_error_message\', "<div class=\"error\">" . stripslashes( \'' . addslashes($error_message) . '\' ) . "</div>" );' )
                );
            }

            // Reset Sessions.
            $_SESSION['youzer_signup']        = null;
            $_SESSION['youzer_signup_fields'] = null;
        }
    }

	/**
	 * An action function used to include the reCAPTCHA JavaScript file at the end of the page.
	 */
	public function add_captcha_js() {

		// Get Captcha Language !
		$language = apply_filters( 'yz_captcha_language' , 'en' );

		echo apply_filters( 'youzer_signup_captcha', "<script src='https://www.google.com/recaptcha/api.js?hl=$language'></script>" );
	}

	/**
	 * Redirect User To Specific Page..
	 */
	public function redirect( $code, $redirect_to = null, $type = null ) {

		// Init Erros.
		$messages = array();

		// Get Redirect Url.	
		$redirect_url = ! empty( $redirect_to ) ? $redirect_to : logy_page_url( 'register' );
				
		// Get Message.
		$messages[] = logy_get_message( __( 'The CAPTCHA check failed. Try again!', 'youzer' ), $type );

		// Get Messages.
		logy_add_message( $messages, $type );
		
		// Redirect User.
		wp_redirect( $redirect_url );

		// Exit.
		exit;

	}

	/**
	 * Custom Field for registration page form
	 */
	function registration_page_reference() {
		
		if ( ! bp_is_register_page() ) {
			return false;	
		}

		?>

		<input type="hidden" name="yz_registration_page" value="true">

		<?php
	}
}