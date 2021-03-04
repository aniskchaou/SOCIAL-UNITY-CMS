<?php

class Logy_Social {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

        add_action( 'parse_request', array( $this, 'process_authentication' ) );

		// Complete Registration.
		$this->complete_social_registration();

	}

	function process_authentication( $query ) {

		if ( is_user_logged_in() || ! isset( $query->query_vars['yz-authentication'] ) ) {
			return;
		}

	  	if ( $query->query_vars['yz-authentication'] != 'social-login' ) {
	  		return;
	  	}

	    // Check if Social Login is Enabled.
	    if ( ! logy_is_social_login_enabled() ) {
	    	// Redirect User.
	    	$this->redirect( 'social_auth_unavailable' );
	    }

	  	// Get Provider.
	    $provider = $query->query_vars['yz-provider'];

	    // Check if The Provider Allowed.
	    if ( empty( $provider ) || ! in_array( $provider, logy_get_providers() ) ) {
	    	// Redirect User.
	    	$this->redirect( 'network_unavailable' );
	    }

	    // Inculue Files.
		if ( ! class_exists( 'Hybridauth', false ) ) {
			require_once YZ_PUBLIC_CORE . 'hybridauth/autoload.php';
			require_once YZ_PUBLIC_CORE . 'hybridauth/Hybridauth.php';
		}

	    try {

			// Create an Instance with The Config Data.
			$class = "Hybridauth\\Provider\\$provider";
	        $hybridauth = new $class( $this->get_config( $provider ) );

	        // Start the Authentication Process.
	        $hybridauth->authenticate();


    	} catch( Exception $e ) {

    		$this->redirect( $e );

    	}

        if ( 'login' == $this->get_mode() ) {

        	// Get User Data.
			$user_data = $this->get_user_data( $hybridauth, $provider );

	        if ( ! empty( $user_data['user_id'] ) ) {
	        	// Process Login ...
	        	$this->authenticate_user( $user_data['user_id'] );

	        } else {

				// Check if Registration is Enabled.
				if ( ! get_option( 'users_can_register' ) ) {
	        		$this->redirect( 'registration_disabled' );
				}

				// Get User Profile Data.
				$user_profile = $user_data['user_profile'];

	        	if ( is_multisite() ) {
	        		if ( $this->wpmu_is_user_need_confirmation( $user_profile->email ) ) {
	        			$this->display_inactive_account_msg();
	        		}
	        	}

				// Create User.
	        	$user_id = $this->create_new_user( $user_profile, $provider );

	        	if ( $user_id > 0 ) {
	        		// Store All User Social Account Data In Database.
	        		$this->store_user_data( $user_id, $provider, $user_profile );
	        	}

				if ( yz_social_registration_needs_activation() ) {
					if ( bp_registration_needs_activation() ) {
						$this->redirect( 'registration_needs_activation', false, 'success' );
					}
				}

	        	// Process Login ...
	        	$this->authenticate_user( $user_id );

	        }

        }

	}
	/**
	 * Authenticate User.
	 */
	// public function process_authentication() {



 //    }

	/**
	 * Get User By Provider And ID.
	 */
	public function wpmu_is_user_need_confirmation( $email ) {

		global $wpdb;

		// Get Result
		$result = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM " . $wpdb->prefix . "logy_users WHERE email = %s AND user_id = 0", $email ) );

		if ( $result > 0 ) {
			return true;
		}

		// Return Result.
		return false;
	}

	/**
	 * Create New User.
	 */
	public function create_new_user( $user, $provider ) {

		// init Vars
		$user_id = false;

		// Get Display Name.
		$display_name = $this->get_display_name( $user->displayName, $user->firstName, $user->lastName );

		// Get Unique Username.
		$user_login = $this->get_unique_username( $display_name );

		// Get Unique Email.
		$user_email = $this->get_unique_email( $user->email );

		// Init Vars.
		$required_fields = array();

		/**
		 * In case we coudn't get the user login or email we will ask the user to
		 * provide them manually.
		 */

		// Ask User to enter username.
		if ( empty( $user_login ) || username_exists( $user_login ) || ! validate_username( $user_login ) ) {
			$required_fields['user_login'] = true;
		}

		// Ask User to enter email.
		if ( empty( $user_email ) ) {
			$required_fields['email'] = true;
		}

		// Redirect User to Registration Page to Complete Required Fields.
		if ( ! empty( $required_fields ) ) {
			// Get Provider.
			$required_fields['provider'] = $provider;
			// Store User Data.
			logy_user_session_data( 'set', $required_fields );
			// Redirect to complete Registration Page.
			wp_safe_redirect( logy_page_url( 'complete-registration' ) );
			exit;
		}

		// Multisite installs have their own install procedure.
		if ( is_multisite() ) {
			// $user_id = wpmu_create_user( $user_login, wp_generate_password(), $user_email );
			wpmu_signup_user( $user_login, $user_email );
		} else {
			// Update User Data.
		 	$user->email = ! empty( $user_email ) ? $user_email : $user->emailVerified;
		 	$user->emailVerified = ! empty( $user_email ) ? $user_email : $user->emailVerified;
		 	$user->displayName = ! empty( $user_login ) ? $user_login : $user->displayName;

			// Register User.
			$this->bp_register_user( $user, $provider );

			die();
		}

		return $user_id;
	}

	/**
	 * Get User Data.
	 */
	public function get_user_data( $adapter, $provider ) {

    	// Get User Profile Data.
		$user_profile = $this->getUserProfile( $adapter );

		// Create Logy Session.
		$user_profile_data = logy_user_profile_data( 'get' );

		if ( empty( $user_profile_data ) && ! empty( $user_profile ) ) {
			// Set Provider.
			// $user_profile->provider = $provider;
			logy_user_profile_data( 'set', $user_profile );
		}

		/**
		 * Check if User Exist in Logy Database
		 */

        // Get User Identifier.
        $uid = $user_profile->identifier;
        $user_email = $user_profile->email;

        // Get User ID.
        $user_id = $this->get_user_by_provider( $provider, $uid, $user_email );

		/**
		 * Check if User Already Registered
		 */
		if ( $user_id == null && ! empty( $user_email ) ) {

			// Get User ID
			$user_id = email_exists( $user_email );

			// Get User ID By Verified Email.
			if ( ! $user_id ) {
				$user_id = $this->get_user_verified_email( $user_email );
			}
		}

        // Get User Data.
        $user_data = array( 'user_id' => $user_id, 'user_profile' => $user_profile );

        // Return Data.
		return $user_data;
	}

	/**
	 * Get User By Provider And ID.
	 */
	public function get_user_by_provider( $provider, $uid, $email = null ) {

		global $wpdb;

		// Get SQL.
		$sql = "SELECT user_id FROM " . $wpdb->prefix . "logy_users WHERE provider = %s AND identifier = %s";

		// if ( ! empty( $email ) && $provider == 'Facebook' ) {
		// 	$sql = "SELECT user_id FROM $this->users_table WHERE provider = %s AND email = %s";
		// 	$uid = $email;
		// }

		// Get Result
		$result = $wpdb->get_var( $wpdb->prepare( $sql, $provider, $uid ) );

		// Return Result.
		return $result;
	}


	/**
	 * Get User ID by Verified Email.
	 */
	function get_user_verified_email( $email ) {

		global $wpdb;
		// Get SQL.
		$sql = "SELECT user_id FROM " . $wpdb->prefix . "logy_users WHERE emailverified = %s";
		// Get Result
		$result = $wpdb->get_var( $wpdb->prepare( $sql, $email ) );
		// Return Result.
		return $result;
	}

	/**
	 * Complete Registration.
	 */
	public function bp_register_user( $user, $provider ) {

		// Init Vars
		$errors = array();

		// Generate A new Password.
		$user_password = wp_generate_password();

	 	// Get User Name.
		$user_display_name = str_replace( array( '-', '_' ), ' ', $user->displayName );

	 	// Add New User.
		$user_id = bp_core_signup_user( $user->displayName, $user_password, $user->email, array( 'social_login' => true, 'field_1' => $user_display_name ) );

		if ( is_wp_error( $user_id ) ) {

			// Add ERROR
			$errors[] = logy_get_message( $user_id->get_error_message() );

			// Show Error Message.
			logy_add_message( $errors );

			// Redirect.
			bp_core_redirect( wp_get_referer() );

		}

		// Get Required Fields.
		$get_session = json_decode( logy_user_session_data( 'get' ) );

		// Get Provider
		$provider = ! empty( $get_session ) ? $get_session->provider : $provider;

		// Store All User Social Account Data In Database.
		$this->store_user_data( $user_id, $provider, $user );

		if ( yz_social_registration_needs_activation() ) {
			if ( bp_registration_needs_activation() ) {
				$register_page = logy_page_url( 'register' );
				$this->redirect( 'registration_needs_activation', $register_page, 'success' );
			}
		}

		do_action( 'yz_after_social_signup_user', $user_id );

		// Process Login ...
		$this->authenticate_user( $user_id );

	}

	/**
	 * Complete Registration.
	 */
	public function complete_registration( $username, $email, $provider ) {

		// Init Vars.
		$errors = new WP_Error();
		$error_msg = $this->logy->register;

	 	if ( ! empty( $email ) ) {

			// Check if email field is empty.
			if ( empty( $email ) ) {
				$errors->add( 'empty_fields', $error_msg->get_error_message( 'empty_fields' ) );
				return $errors;
			}

			// Check If Email is Valid
			if ( ! is_email( $email ) ) {
				$errors->add( 'email', $error_msg->get_error_message( 'email' ) );
				return $errors;
			}

			// Check if email Already Exist.
			if ( email_exists( $email ) ) {
				$errors->add( 'email_exists', $error_msg->get_error_message( 'email_exists' ) );
				return $errors;
			}

	 	}

	 	if ( ! empty( $username ) ) {

			// Check if username field is empty.
			if ( empty( $username ) ) {
				$errors->add( 'empty_fields', $error_msg->get_error_message( 'empty_fields' ) );
				return $errors;
			}

			// Check if the username already exists.
			if ( username_exists( $username ) ) {
				$errors->add( 'username_exists', $error_msg->get_error_message( 'username_exists' ) );
				return $errors;
			}

			// Check if the username is too short ( less than 4 characters ).
			if ( 4 > strlen( $username ) ) {
				$errors->add( 'username_length', $error_msg->get_error_message( 'username_length' ) );
				return $errors;
			}

			// Check if the username is valid.
			if ( ! validate_username( $username ) ) {
				$errors->add( 'username_invalid', $error_msg->get_error_message( 'username_invalid' ) );
				return $errors;
			}

	 	}

	 	// Get User Profile Data
		$user_profile_data = logy_user_profile_data( 'get' );

	 	$user = ! empty( $user_profile_data ) ? json_decode( $user_profile_data ) : null;

	 	if ( empty( $user ) ) {
	        // Display Error.
	        $this->redirect( 'cant_connect' );
	 	}

	 	// Get User Email & Username.
	 	$user->email = ! empty( $email ) ? $email : $user->emailVerified;
	 	$user->emailVerified = ! empty( $email ) ? $email : $user->emailVerified;
	 	$user->displayName = ! empty( $username ) ? $username : $user->displayName;

		// Prepare User Data
		$user_data = array(
			'user_email'    => $user->email,
			'user_login'    => $user->displayName,
			'display_name'  => $user->displayName,
			'first_name'    => $user->firstName,
			'last_name'     => $user->lastName,
			'description'   => $user->description,
			'user_pass'     => wp_generate_password(),
			'user_url'      => $this->get_user_url( $user->webSiteURL, $user->profileURL ),
		);

		// Insert User and Get User ID.
		$user_id = $this->bp_register_user( $user_data, $provider );

		if ( is_wp_error( $user_id ) ) {
			// Parse errors into a string and append as parameter to redirect
			$errors = $user_id->get_error_messages();
			$this->redirect( $errors, logy_page_url( 'complete-registration' ) );
		} else {

			// Get Required Fields.
			$get_session = json_decode( logy_user_session_data( 'get' ) );

			// Store All User Social Account Data In Database.
			$this->store_user_data( $user_id, $get_session->provider, $user );

			if ( yz_social_registration_needs_activation() ) {
				if ( bp_registration_needs_activation() ) {
					$this->redirect( 'registration_needs_activation', false, 'success' );
				}
		    }
	    	// Process Login ...
	    	$this->authenticate_user( $user_id );

		}

		return $user_id;
	}

	/**
	 * Get User Website Url.
	 */
	public function get_user_url( $website = null, $profile = null ) {

		// Get Website Url.
		if ( ! empty( $website ) ) {
			return $website;
		}

		// Get User Profile Url.
		if ( ! empty( $profile ) ) {
			return $profile;
		}

		return  null;
	}

	/**
	 * Get Unique Username.
	 */
	public function get_unique_username( $display_name, $user_login = null ) {

		if ( ! empty( $user_login ) ) {
			return $user_login;
		}

		// Sanitize Username.
		$username = sanitize_user( $display_name, true );

		if ( empty( $username ) ) {
			return false;
		}

		$separator = apply_filters( 'yz_social_login_registration_username_separator',  '-' );

		// Prepare Username By Removing Dots, Spaces.
		$username = trim( str_replace( array( ' ', '_' ), $separator, $username ) );

		if ( ! username_exists( $username ) ) {
			return $username;
		}

		return false;
	}

	/**
	 * Process Login ..
	 */
	function display_inactive_account_msg( $user_login = null ) {

		// Init Array.
		$messages = array();

		// Get Inactive Account Message.
		$message = $this->get_inactive_account_message( $user_login );

		// Get Login Page Url.
		$login_page = logy_page_url( 'login' );

		// Get Message.
		$messages[] = logy_get_message( $message, 'error' );

		// Get Messages.
		logy_add_message( $messages, 'info' );

		// Redirect User.
		wp_redirect( $login_page );
		exit;
	}

	/**
	 * Process Login ..
	 */
	public function authenticate_user( $user_id, $hybridauth = null ) {

		// Clear Sessions
		$this->clear_session();

		// Get All User Data.
		$user = get_user_by( 'id', $user_id );

		// Get User Status.
		$user_status = BP_Signup::check_user_status( $user_id );

		if ( 2 === $user_status ) {

			// Init Array.
			$messages = array();

			// Get Inactive Account Message.
			$message = $this->get_inactive_account_message( $user->user_login );

			// Get Login Page Url.
			$login_page = logy_page_url( 'login' );

			// Get Message.
			$messages[] = logy_get_message( $message, 'error' );

			// Get Messages.
			logy_add_message( $messages, 'info' );

			// Redirect User.
			wp_redirect( $login_page );
			exit;

		}

		// Set WP auth cookie
		wp_set_auth_cookie( $user_id, true );

		// Add WP Login Filter.
		do_action( 'wp_login', $user->user_login, $user );

		// Get Redirection Url.
		$redirection_url = $this->logy->login->redirect_after_login( null, null, $user );

		// Redirect User.
		wp_safe_redirect( $redirection_url );

		// Die.
		die();

	}

	/**
	 * Get Provide Adapter.
	 */
	function getUserProfile( $adapter ) {

        if ( $adapter->isConnected() ) {
        	return $adapter->getUserProfile();
        }

        // Display Can't Connect to provider Message
        $this->redirect( 'cant_connect' );

	}

	/**
	 * Get Unique Email.
	 */
	public function get_unique_email( $email ) {

		if ( ! empty( $email ) && is_email( $email ) ) {
			return $email;
		}

		return false;
	}

	/**
	 * Clear Logy Session's.
	 */
	public function clear_session() {

		// Clear Sessions.
		if ( isset( $_SESSION['HYBRIDAUTH::STORAGE'] ) ) {
			unset( $_SESSION['HYBRIDAUTH::STORAGE'] );
		}

		if ( isset( $_SESSION['HA::CONFIG'] ) ) {
			unset( $_SESSION['HA::CONFIG'] );
		}

		if ( isset( $_SESSION['logy::profile'] ) ) {
			unset( $_SESSION['logy::profile'] );
		}

		logy_user_session_data( 'delete' );
		logy_user_profile_data( 'delete' );

	}

	/**
	 * Get User Display Name.
	 */
	function get_display_name( $display_name, $first_name, $last_name ) {

		if ( ! empty( $display_name ) ) {
			return $display_name;
		}

		// Init Display Name.
		$display_name = null;

		if ( ! empty( $first_name ) ) {
			$display_name[] = $first_name;
		}

		if ( ! empty( $last_name ) ) {
			$display_name[] = $last_name;
		}

    	return implode( ' ' , array_filter( $display_name ) );
	}

	/**
	 * Get Config Data.
	 */
	public function get_config( $provider ) {

		// Get Config Data.
		$config = array(
	        'callback'   => home_url( 'yz-auth/social-login/' . $provider ),
	        "debug_mode" => apply_filters( 'youzer_social_login_debug_mode', true ),
	        "debug_file" => 'debug.log'
	    );

		// Get Provider Data.
        $provider_data = logy_get_provider_data( $provider );

        // Check if network use key or id.
        $key_or_id = $provider_data['app'];

        // Transform Provider name to lowercase
        $network = strtolower( $provider );

		// Get Network status
		$network_status = yz_option( 'logy_' . $network . '_app_status' );

		// Check if network is enabled.
		$is_enabled = ( 'off' == $network_status ) ? false: true;

		// Get Networks Params.
		$config['keys'] = array(
			'secret' 	=> yz_option( 'logy_' . $network . '_app_secret' ),
		);

		if ( $network == 'twitter' ) {
			$config['keys']['key'] = yz_option( 'logy_' . $network . '_app_key' );
		} else {
			$config['keys']['id'] = yz_option( 'logy_' . $network . '_app_key' );
		}

		if ( 'Google' == $provider ) {
			$network_params['redirect_uri'] = home_url( '/?hauth.done=Google' );
			$network_params['scope'] = "https://www.googleapis.com/auth/userinfo.profile " .
		    "https://www.googleapis.com/auth/userinfo.email";
		}

		// Get Providers Data.
		// $config = $;
		// http://beta.kainelabs.com/?hauth.done=LinkedIn
		// $config['providers'] = $network_params array( $provider =>  );

		// Return Data
		return $config;
	}

    /**
     * Check is User Account Activated
     */
    function get_inactive_account_message( $user_login = null ) {

    	// Get Inactive Account Message.
		$inactive_account_msg = apply_filters( 'yz_get_inactive_account_message', __( 'Your account has not been activated. Check your email for the activation link.', 'youzer' ) );

    	if ( empty( $user_login ) ) {
    		return $inactive_account_msg;
    	}

		// Look for the unactivated signup corresponding to the login name.
		$signup = BP_Signup::get( array( 'user_login' => sanitize_user( $user_login ) ) );

		// No signup or more than one, something is wrong. Let's bail.
		if ( empty( $signup['signups'][0] ) || $signup['total'] > 1 ) {
			return $inactive_account_msg;
		}

		// Unactivated user account found!
		// Set up the feedback message.
		$signup_id = $signup['signups'][0]->signup_id;

		$resend_url_params = array(
			'action' => 'bp-resend-activation',
			'id'     => $signup_id,
		);

		$resend_url = wp_nonce_url(
			add_query_arg( $resend_url_params, wp_login_url() ),
			'bp-resend-activation'
		);

		$resend_string = apply_filters( 'yz_get_inactive_account_message_resend_msg', '<br />' . sprintf( __( 'If you have not received an email yet, <a href="%s">click here to resend it</a>.', 'youzer' ), esc_url( $resend_url ) ) );

		return $inactive_account_msg . $resend_string;

    }


	/**
	 * Store User Data Into Database.
	 */
	public function store_user_data( $user_id = null, $provider, $profile ) {

		// Update Buddypress User Meta.
		yz_update_user_profile_meta( $user_id, $profile );

		// Update User Avatar.
		if ( isset( $profile->photoURL ) && ! empty( $profile->photoURL ) ) {

			// Upload Image Localy.
			$profile_photo = yz_upload_image_by_url( $profile->photoURL );

			if ( ! empty( $profile_photo ) ) {
				$profile->photoURL = $profile_photo;
			}

			if ( is_multisite() ) {
				update_user_option( $user_id, 'logy_avatar', $profile->photoURL );
			} else {
				update_user_meta( $user_id, 'logy_avatar', $profile->photoURL );
			}

		}

		if ( isset( $profile->firstname ) ) {
			update_user_meta( $user_id, 'first_name', $profile->firstname );
		}

		if ( isset( $profile->lastname ) ) {
			update_user_meta( $user_id, 'last_name', $profile->firstname );
		}

		// Get Profile Hash
		$new_hash = sha1( serialize( $profile ) );

		// Get User Old Profile Data.
		$old_profile = $this->get_user_profile( $user_id, $provider, $profile->identifier );

		// Check if user data changed since last login.
		if ( ! empty( $old_profile ) && $old_profile[0]->profile_hash == $new_hash ) {
			return false;
		}

		// Get Table ID.
		$table_id = ! empty( $old_profile ) ? $old_profile[0]->id : null;

		// Get Table Data.
		$table_data = array(
			'id' => $table_id,
			'user_id' => $user_id,
			'provider' => $provider,
			'profile_hash' => $new_hash
		);

		// Get Table Fields.
		$fields = array(
			'identifier',
			'profileurl',
			'websiteurl',
			'photourl',
			'displayname',
			'description',
			'firstname',
			'lastname',
			'gender',
			'language',
			'age',
			'birthday',
			'birthmonth',
			'birthyear',
			'email',
			'emailverified',
			'phone',
			'address',
			'country',
			'region',
			'city',
			'zip'
		);

		foreach( $profile as $key => $value ) {
			// Transform Key To LowerCase.
			$key = strtolower( $key );
			// Get Table Data.
			if ( in_array( $key, $fields ) ) {
				$table_data[ $key ] = (string) $value;
			}
		}

		global $wpdb;

		// Replace Data.
		$wpdb->replace( $wpdb->prefix . 'logy_users', $table_data );

		return false;
	}

	/**
	 * Save "Complete Registartion" Form Data.
	 */
	public function complete_social_registration() {

		if ( is_user_logged_in() ) {
			return false;
		}

	    // Get User Session Data.
	    $user_session_data = json_decode( logy_user_session_data( 'get' ) );

		if ( ! isset( $_POST['complete-registration'] ) || empty( $user_session_data ) ) {
			return false;
		}

		// Check if Registration is Enabled.
		if ( ! get_option( 'users_can_register' ) ) {
			$this->redirect( 'registration_disabled' );
		}

		$bp = buddypress();

		// Init Vars.
		$errors = array();

	 	// Get User Profile Data
		$user_profile_data = logy_user_profile_data( 'get' );

	 	$user = ! empty( $user_profile_data ) ? json_decode( $user_profile_data ) : null;

	 	if ( empty( $user ) ) {
	        // Display Error.
			$errors[] = logy_get_message( $this->logy->form->get_error_message( 'cant_connect' ) );
	 	}

	 	// Update User Data.
	 	$user->email = isset( $_POST['signup_email'] ) ? sanitize_email( $_POST['signup_email'] ) : $user->email;
	 	$user->emailVerified = isset( $_POST['signup_email'] ) ? sanitize_email( $_POST['signup_email'] ): $user->emailVerified;
	 	$user->displayName = isset( $_POST['signup_username'] ) ? sanitize_user( $_POST['signup_username'] ) : $this->get_unique_username( $user->displayName );

		/**
		 * Fires before the validation of a new signup.
		 */
		do_action( 'bp_signup_pre_validate' );

		// Check the base account details for problems.
		$account_details = bp_core_validate_user_signup( $user->displayName, $user->email );

		// If there are errors with account details, set them for display.
		if ( ! empty( $account_details['errors']->errors['user_name'] ) ) {
			$errors[] = logy_get_message( $account_details['errors']->errors['user_name'][0] );
		}

		if ( ! empty( $account_details['errors']->errors['user_email'] ) ) {
			$errors[] = logy_get_message( $account_details['errors']->errors['user_email'][0] );
		}

		// Display Errors
		if ( ! empty( $errors ) ) {
			logy_add_message( $errors );
		} else {

			// Prepare User Data
			$user_data = array(
				'user_email'    => $user->email,
				'user_login'    => $user->displayName,
				'display_name'  => $user->displayName,
				'first_name'    => $user->firstName,
				'last_name'     => $user->lastName,
				'description'   => $user->description,
				'user_pass'     => wp_generate_password(),
				'user_url'      => $this->get_user_url( $user->webSiteURL, $user->profileURL ),
			);

		 	// Register User.
		 	$this->bp_register_user( $user, $user_session_data['provider'] );

			/**
			 * Fires after the completion of a new signup.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_complete_signup' );

			// Redirect.
			bp_core_redirect( logy_page_url( 'register' ));

		}
	}

	/**
	 * Get User Profile Data.
	 */
	public function get_user_profile( $user_id, $provider, $uid ) {

		global $wpdb;

		// Get SQL Request.
		$sql = "SELECT * FROM " . $wpdb->prefix ."logy_users WHERE user_id = %d AND provider = %s AND identifier = %s";

		// Get Result.
		$result = $wpdb->get_results( $wpdb->prepare( $sql, $user_id, $provider, $uid ) );

		return $result;
	}

	/**
	 * Get Provider.
	 */
	public function get_provider() {
	    $provider = isset( $_REQUEST['provider'] ) ? sanitize_text_field( $_REQUEST['provider'] ) : null;
		return $provider;
	}

	/**
	 * Get Mode.
	 */
	public function get_mode() {
	    $mode = isset( $_REQUEST['mode'] ) ? sanitize_text_field( $_REQUEST['mode'] ) : 'login';
		return $mode;
	}

	/**
	 * Get Action.
	 */
	public function get_action() {
	    $action = isset( $_REQUEST['action'] ) ? $_REQUEST['action'] : null;
		return $action;
	}

	/**
	 * Get Redirect Url.
	 */
	public function get_redirect_url() {
		$redirect_to = isset( $_REQUEST[ 'redirect_to' ] ) ? $_REQUEST[ 'redirect_to' ] : home_url();
		return $redirect_to;
	}

	/**
	 * Redirect User.
	 */
	public function redirect( $code, $redirect_to = null, $type = null ) {

		// Init Array.
		$messages = array();

		// Get Redirect Url.
		$redirect_url = ! empty( $redirect_to ) ? $redirect_to : logy_page_url( 'login' );

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