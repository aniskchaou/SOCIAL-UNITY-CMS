<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Extension_Sign_Form extends FW_Extension {

	protected function _init() {
		add_shortcode( $this->get_config( 'builderComponent' ), array( $this, 'builderComponent' ) );
		add_shortcode( $this->get_config( 'registerLinkSC' ), array( $this, 'registerLinkSC' ) );
		add_shortcode( $this->get_config( 'currentUserSC' ), array( $this, 'currentUserSC' ) );
	}

	public function builderComponent( $atts ) {
		global $wp;

		$builder_type = isset( $atts[ 'builder_type' ] ) ? $atts[ 'builder_type' ] : '';

		if ( $builder_type !== 'kc' && function_exists( 'vc_map_get_attributes' ) ) {
			$atts = vc_map_get_attributes( $this->get_config( 'builderComponent' ), $atts );
		}

		$atts = self::prepareAtts( shortcode_atts( array(
				'form_type_login'		=> 'native',
				'form_type_register'	=> 'native',
				'forms'					=> 'both',
				'login_shortcode'		=> '',
				'register_shortcode'	=> '',
				'redirect'				=> 'current',
				'redirect_to'			=> '',
				'register_redirect'		=> 'current',
				'register_redirect_to'	=> '',
				'login_descr'			=> '',
				'vcard_title'			=> '',
				'vcard_subtitle'		=> '',
				'vcard_profile_btn'		=> '',
								), $atts ) );

		$redirect_to = filter_var( $atts[ 'redirect_to' ], FILTER_VALIDATE_URL );

		if ( $redirect_to && $atts[ 'redirect' ] === 'custom' ) {
			$atts[ 'redirect_to' ] = $redirect_to;
		} else {
			$atts[ 'redirect_to' ] = home_url( $wp->request );
		}

		$register_redirect_to = filter_var( $atts[ 'register_redirect_to' ], FILTER_VALIDATE_URL );

		if ( $register_redirect_to && $atts[ 'register_redirect' ] === 'custom' ) {
			$atts[ 'register_redirect_to' ] = $register_redirect_to;
		} else {
			$atts[ 'register_redirect_to' ] = home_url( $wp->request );
		}

		// Captcha
		$enable_captcha = fw_get_db_customizer_option('sign-in-enable-captcha', false);
		$captcha_site_key = fw_get_db_customizer_option('sign-in-captcha-site-key');
	
		$atts['enable_captcha'] = $enable_captcha;
		$atts['captcha_site_key'] = $captcha_site_key;
		
		// Captcha

		$register_fields_type = fw_get_db_customizer_option('sign-in-register-fields-type', 'simple');
		$atts['register_fields_type'] = $register_fields_type;

		wp_localize_script( 'sign-form', 'signFormParams', array(
			'nonce'	 => wp_create_nonce( 'sign-form-nonce' ),
			'ext'	 => $this,
			'atts'	 => $atts,
		) );

		return $this->render_view( 'form', $atts );
	}

	public function registerLinkSC( $atts ) {
		$atts = shortcode_atts( array(
			'url'	 => '',
			'text'	 => '',
				), $atts );

		$atts[ 'url' ] = filter_var( $atts[ 'url' ], FILTER_VALIDATE_URL );

		$url	 = $atts[ 'url' ] ? $atts[ 'url' ] : esc_url( wp_registration_url() );
		$text	 = $atts[ 'text' ] ? $atts[ 'text' ] : esc_html__( 'Register Now!', 'crum-ext-sign-form' );

		return "<a href='{$url}'>{$text}</a>";
	}

	public function currentUserSC() {
		$user_ID = get_current_user_id();

		if ( !$user_ID ) {
			return;
		}

		if ( self::useBuddyPress() ) {
			$author_url	 = bp_core_get_user_domain( $user_ID );
			$author_name = bp_activity_get_user_mentionname( $user_ID );
		} else {
			$author_url	 = get_author_posts_url( $user_ID );
			$author_name = wp_get_current_user()->display_name;
		}

		return '<a href="' . esc_url( $author_url ) . '" class="author-name">' . $author_name . '</a>';
	}

	/**
	 * @param string $name View file name (without .php) from <extension>/views directory
	 * @param  array $view_variables Keys will be variables names within view
	 * @param   bool $return In some cases, for memory saving reasons, you can disable the use of output buffering
	 * @return string HTML
	 */
	final public function get_view( $name, $view_variables = array(), $return = true ) {
		$full_path = $this->locate_path( '/views/' . $name . '.php' );

		if ( !$full_path ) {
			trigger_error( 'Extension view not found: ' . $name, E_USER_WARNING );
			return;
		}

		return fw_render_view( $full_path, $view_variables, $return );
	}

	public static function useBuddyPress() {
		if ( function_exists( 'bp_core_get_user_domain' ) && function_exists( 'bp_activity_get_user_mentionname' ) && function_exists( 'bp_attachments_get_attachment' ) && function_exists( 'bp_loggedin_user_domain' ) && function_exists( 'bp_is_active' ) && function_exists( 'bp_get_activity_slug' ) && function_exists( 'bp_is_active' ) && function_exists( 'bp_get_notifications_unread_permalink' ) && function_exists( 'bp_loggedin_user_domain' ) && function_exists( 'bp_get_settings_slug' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * @param string $token Recaptcha token
	 * @param string $sicret_key Recaptcha secret key
	 * @return array
	 */
	public static function returnReCaptcha( $token, $captcha_secret_key ){
		$recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
		$recaptcha = file_get_contents($recaptcha_url . '?secret=' . $captcha_secret_key . '&response=' . $token);
		$recaptcha = json_decode($recaptcha, true);
		return $recaptcha;
	}

	/**
	 * This functions prepares attributes to use in template
	 * Converts back escaped characters
	 *
	 * @param $atts
	 *
	 * @return array
	 */
	public static function prepareAtts( $atts ) {
		$returnAttributes = array();
		if ( is_array( $atts ) ) {
			foreach ( $atts as $key => $val ) {
				$returnAttributes[ $key ] = str_replace( array(
					'`{`',
					'`}`',
					'``',
						), array(
					'[',
					']',
					'"',
						), $val );
			}
		}

		return $returnAttributes;
	}

	public static function signIn() {
//        check_ajax_referer( 'crumina-sign-form' );

		$errors = array();

		$log		 = filter_input( INPUT_POST, 'log' );
		$pwd		 = filter_input( INPUT_POST, 'pwd' );
		$rememberme	 = filter_input( INPUT_POST, 'rememberme' );
		$redirect	 = filter_input( INPUT_POST, 'redirect' );
		$redirect_to = filter_input( INPUT_POST, 'redirect_to', FILTER_VALIDATE_URL );
		// $token 		 = filter_input( INPUT_POST, 'token' );
		$token		= (isset($_POST['token'])) ? $_POST['token'] : '';

		if ( !$log ) {
			$errors[ 'log' ] = esc_html__( 'Login is required', 'crum-ext-sign-form' );
		}

		if ( !$pwd ) {
			$errors[ 'pwd' ] = esc_html__( 'Password is required', 'crum-ext-sign-form' );
		}

		if($token != ''){
			$captcha_secret_key = fw_get_db_customizer_option('sign-in-captcha-secret-key');
			$captcha_response = self::returnReCaptcha($token, $captcha_secret_key);

			if ($captcha_response['success'] != 1){
				$errors[ 'captcha' ] = esc_html__( 'Whrong captcha', 'crum-ext-sign-form' );
			}
		}

		if ( !empty( $errors ) ) {
			wp_send_json_error( array(
				'errors' => $errors,
			) );
		}

		$user = wp_signon( array(
			'user_login'	 => $log,
			'user_password'	 => $pwd,
			'remember'		 => $rememberme,
				) );

		if ( is_wp_error( $user ) ) {
			wp_send_json_error( array(
				'message' => $user->get_error_message(),
			) );
		}

		if(self::useBuddyPress()){
		if ( $redirect === 'profile' && function_exists( 'bp_core_get_user_domain' ) ) {
			$redirect_to = bp_core_get_user_domain( $user->ID );
		}

		if ( $redirect === 'activity' ) {
			$redirect_to = get_bloginfo('url') . '/members/'. $user->user_login . '/activity/';
		}
		}
		
		wp_send_json_success( array(
			'redirect_to' => $redirect_to ? $redirect_to : ''
		) );
	}

	public static function getBPFields(){
		$fields_arr = array();
		if ( self::useBuddyPress() ) {
			if ( bp_is_active( 'xprofile' ) ) : if ( bp_has_profile( array( 'profile_group_id' => 1, 'fetch_field_data' => false ) ) ) : while ( bp_profile_groups() ) : bp_the_profile_group();
				while ( bp_profile_fields() ) : bp_the_profile_field();
					if(bp_get_the_profile_field_required_label() != ''){
						if(bp_get_the_profile_field_type() != 'datebox'){
							$fields_arr['field_' . bp_get_the_profile_field_id()] = array(
								'label' => bp_get_the_profile_field_name(),
								'type' => bp_get_the_profile_field_type(),
								'id' => bp_get_the_profile_field_id()
							);
						}else{
							$fields_arr['field_' . bp_get_the_profile_field_id() . '_day'] = array(
								'label' => bp_get_the_profile_field_name(),
								'type' => bp_get_the_profile_field_type(),
								'id' => bp_get_the_profile_field_id()
							);
							$fields_arr['field_' . bp_get_the_profile_field_id() . '_month'] = array(
								'label' => bp_get_the_profile_field_name(),
								'type' => bp_get_the_profile_field_type(),
								'id' => bp_get_the_profile_field_id()
							);
							$fields_arr['field_' . bp_get_the_profile_field_id() . '_year'] = array(
								'label' => bp_get_the_profile_field_name(),
								'type' => bp_get_the_profile_field_type(),
								'id' => bp_get_the_profile_field_id()
							);
						}
					}
				endwhile;
				endwhile;
			endif;
			endif;
		}

		return $fields_arr;
	}

	public static function signUp() {
//        check_ajax_referer( 'crumina-sign-form' );

		$errors = array();

		$user_login	 = filter_input( INPUT_POST, 'user_login' );
		$user_email	 = filter_input( INPUT_POST, 'user_email', FILTER_VALIDATE_EMAIL );
		$first_name	 = filter_input( INPUT_POST, 'first_name' );
		$last_name	 = filter_input( INPUT_POST, 'last_name' );
		$redirect_to = filter_input( INPUT_POST, 'redirect_to', FILTER_VALIDATE_URL );
		$redirect	 = filter_input( INPUT_POST, 'redirect' );
		$gdpr		 = filter_input( INPUT_POST, 'gdpr' );
		$token		= (isset($_POST['token'])) ? $_POST['token'] : '';

		$privacy_policy_page_link = get_privacy_policy_url();

		$user_password			 = filter_input( INPUT_POST, 'user_password' );
		$user_password_confirm	 = filter_input( INPUT_POST, 'user_password_confirm' );

		if(isset($user_password)){
			$user_password = trim($user_password);
		}

		$register_fields_type = fw_get_db_customizer_option('sign-in-register-fields-type', 'simple');
		$bp_fields = self::getBPFields();
		if($register_fields_type != 'simple'){
			if(!empty($bp_fields)){
				foreach($bp_fields as $bp_field_key => $bp_field_value){
					$post_val = (isset($_POST[$bp_field_key])) ? $_POST[$bp_field_key] : '';
					if(trim($post_val) == ''){
						$errors[ $bp_field_key ] = esc_html__( $bp_field_value['label'] . ' is required', 'crum-ext-sign-form' );
					}
				}
			}
		}

		if($register_fields_type == 'simple'){
			if ( !trim($first_name) && isset($first_name) ) {
				$errors[ 'first_name' ] = esc_html__( 'First name is required', 'crum-ext-sign-form' );
			}
			if ( !trim($last_name) && isset($last_name) ) {
				$errors[ 'last_name' ] = esc_html__( 'Last name is required', 'crum-ext-sign-form' );
			}
		}

		if ( !trim($user_login) ) {
			$errors[ 'user_login' ] = esc_html__( 'Login is required', 'crum-ext-sign-form' );
		}
		if ( !trim($user_email) ) {
			$errors[ 'user_email' ] = esc_html__( 'Email is required', 'crum-ext-sign-form' );
		}

		if ( strlen( $user_password ) < 6 && isset($user_password) ) {
			$errors[ 'user_password' ] = esc_html__( 'Minimum password length is 6 characters', 'crum-ext-sign-form' );
		} else if ( $user_password !== $user_password_confirm && isset($user_password_confirm) && isset($user_password) ) {
			$errors[ 'user_password_confirm' ] = esc_html__( 'Password and confirm password does not match', 'crum-ext-sign-form' );
		}

		if ( !$gdpr && $privacy_policy_page_link ) {
			$errors[ 'gdpr' ] = esc_html__( 'Please, accept privacy policy', 'crum-ext-sign-form' );
		}

		if($token != ''){
			$captcha_secret_key = fw_get_db_customizer_option('sign-in-captcha-secret-key');
			$captcha_response = self::returnReCaptcha($token, $captcha_secret_key);

			if ($captcha_response['success'] != 1){
				$errors[ 'captcha' ] = esc_html__( 'Whrong captcha', 'crum-ext-sign-form' );
			}
		}

		if ( !empty( $errors ) ) {
			wp_send_json_error( array(
				'errors' => $errors,
			) );
		}

		$send_validation_email = fw_get_db_customizer_option('sign-in-register-activation-email', 'yes');
		$bp_pages_option = get_option( 'bp-pages' );
		$register_page_id = $bp_pages_option['register'];
		$register_page_status = get_post_status($register_page_id);
		$register_page_url = get_permalink($register_page_id);
		$yz_membership_system = get_option( 'yz_activate_membership_system' );
		if($yz_membership_system == 'off' || $register_page_status != 'publish' || $register_fields_type != 'extensional'){
			if(!self::useBuddyPress()){
				$user_id = self::register_new_user( $user_login, $user_email, $user_password );
				// Authorize user
				wp_set_auth_cookie( $user_id, true );
				if ( is_wp_error( $user_id ) ) {
					wp_send_json_error( array(
						'message' => $user_id->get_error_message(),
					) );
				}

				wp_send_json_success( array(
					'redirect_to' => $redirect_to ? $redirect_to : ''
				) );
			}else{
				$user_meta_arr = array('last_name' => $last_name, 'first_name' => $first_name, 'gdpr' => $gdpr);
				if(!empty($bp_fields)){
					$date_val = array();
					foreach($bp_fields as $bp_field_key => $bp_field_value){
						$post_val = (isset($_POST[$bp_field_key])) ? $_POST[$bp_field_key] : '';
						if($bp_field_value['type'] != 'datebox'){
							$user_meta_arr['fw_ext_sign_form_' . $bp_field_key] = $post_val;
						}else{
							if(!isset($date_text)){
								$date_text = '';
							}
							$date_text .= $post_val . '-';
							array_push($date_val, 1);
							if(count($date_val) == 3){
								$date_text = substr($date_text, 0, -1);
								$user_meta_arr['fw_ext_sign_form_' . $bp_field_value['id']] = $date_text;
								$date_text = '';
								$date_val = array();
							}
						}
					}
				}

				$user_id = bp_core_signup_user( $user_login, $user_password, $user_email, $user_meta_arr );
				if ( is_wp_error( $user_id ) ) {
					wp_send_json_error( array(
						'message' => $user_id->get_error_message(),
					) );
				}

				if($send_validation_email == 'no'){
					if ( $redirect === 'profile' && function_exists( 'bp_core_get_user_domain' ) ) {
						$redirect_to = bp_core_get_user_domain( $user_id );
					}

					$user_data = get_userdata( $user_id );

					if ( $redirect === 'activity' ) {
						$redirect_to = get_bloginfo('url') . '/members/'. $user_data->user_login . '/activity/';
					}
		
					wp_send_json_success( array(
						'redirect_to' => $redirect_to ? $redirect_to : ''
					) );
				}else{
					wp_send_json_success( array(
						'email_sent' => 1
					) );
				}
			}
		}elseif($yz_membership_system == '' && $register_page_status == 'publish' && $register_fields_type == 'extensional'){
			$register_page_url = wp_registration_url();
			$add_params = '?fw_ext_sign_form_prefill=1&user_login=' . $user_login . '&user_email=' . $user_email . '&first_name=' . $first_name . '&last_name=' . $last_name;

			if(!empty($bp_fields)){
				foreach($bp_fields as $bp_field_key => $bp_field_value){
					$post_val_el = '';
					$post_val = (isset($_POST[$bp_field_key])) ? $_POST[$bp_field_key] : '';
					if(is_array($post_val)){
						foreach($post_val as $post_val_v){
							$post_val_el .= wp_unslash($post_val_v) . '|';
						}
						$post_val_el = substr($post_val_el, 0, -1);
					}else{
						$post_val_el = wp_unslash($post_val);
					}
					
					$add_params .= '&' . $bp_field_key . '=' . $post_val_el;
				}
			}

			$register_page_url .= $add_params;
			wp_send_json_success( array(
				'redirect_to' => $register_page_url
			) );
		}
	}

	/**
	 * Handles registering a new user.
	 *
	 * @param string $user_login User's username for logging in
	 * @param string $user_email User's email address to send password and add
	 * @param string $user_pass User's email address to send password and add
	 * @return int|WP_Error Either user's ID or error on failure.
	 */
	public static function register_new_user( $user_login, $user_email, $user_pass ) {
		$errors = new WP_Error();

		$sanitized_user_login	 = sanitize_user( $user_login );
		/**
		 * Filters the email address of a user being registered.
		 *
		 * @since 2.1.0
		 *
		 * @param string $user_email The email address of the new user.
		 */
		$user_email				 = apply_filters( 'user_registration_email', $user_email );

		// Check the username
		if ( $sanitized_user_login === '' ) {
			$errors->add( 'empty_username', __( '<strong>ERROR</strong>: Please enter a username.' ) );
		} elseif ( !validate_username( $user_login ) ) {
			$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: This username is invalid because it uses illegal characters. Please enter a valid username.' ) );
			$sanitized_user_login = '';
		} elseif ( username_exists( $sanitized_user_login ) ) {
			$errors->add( 'username_exists', __( '<strong>ERROR</strong>: This username is already registered. Please choose another one.' ) );
		} else {
			/** This filter is documented in wp-includes/user.php */
			$illegal_user_logins = array_map( 'strtolower', (array) apply_filters( 'illegal_user_logins', array() ) );
			if ( in_array( strtolower( $sanitized_user_login ), $illegal_user_logins ) ) {
				$errors->add( 'invalid_username', __( '<strong>ERROR</strong>: Sorry, that username is not allowed.' ) );
			}
		}

		// Check the email address
		if ( $user_email === '' ) {
			$errors->add( 'empty_email', __( '<strong>ERROR</strong>: Please type your email address.' ) );
		} elseif ( !is_email( $user_email ) ) {
			$errors->add( 'invalid_email', __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.' ) );
			$user_email = '';
		} elseif ( email_exists( $user_email ) ) {
			$errors->add( 'email_exists', __( '<strong>ERROR</strong>: This email is already registered, please choose another one.' ) );
		}

		// Check the password
		if ( $user_pass === '' ) {
			$errors->add( 'empty_pass', __( '<strong>ERROR</strong>: Please type your password.' ) );
		} elseif ( strlen( $user_pass ) < 6 ) {
			$errors->add( 'invalid_pass', __( '<strong>ERROR</strong>: The minimum password length is 6 characters.' ) );
		}

		/**
		 * Fires when submitting registration form data, before the user is created.
		 *
		 * @since 2.1.0
		 *
		 * @param string   $sanitized_user_login The submitted username after being sanitized.
		 * @param string   $user_email           The submitted email.
		 * @param WP_Error $errors               Contains any errors with submitted username and email,
		 *                                       e.g., an empty field, an invalid username or email,
		 *                                       or an existing username or email.
		 */
		do_action( 'register_post', $sanitized_user_login, $user_email, $errors );

		/**
		 * Filters the errors encountered when a new user is being registered.
		 *
		 * The filtered WP_Error object may, for example, contain errors for an invalid
		 * or existing username or email address. A WP_Error object should always returned,
		 * but may or may not contain errors.
		 *
		 * If any errors are present in $errors, this will abort the user's registration.
		 *
		 * @since 2.1.0
		 *
		 * @param WP_Error $errors               A WP_Error object containing any errors encountered
		 *                                       during registration.
		 * @param string   $sanitized_user_login User's username after it has been sanitized.
		 * @param string   $user_email           User's email.
		 */
		$errors = apply_filters( 'registration_errors', $errors, $sanitized_user_login, $user_email );

		if ( $errors->has_errors() ) {
			return $errors;
		}

		$user_id = wp_create_user( $sanitized_user_login, $user_pass, $user_email );
		if ( !$user_id || is_wp_error( $user_id ) ) {
			$errors->add( 'registerfail', sprintf( __( '<strong>ERROR</strong>: Couldn&#8217;t register you&hellip; please contact the <a href="mailto:%s">webmaster</a> !' ), get_option( 'admin_email' ) ) );
			return $errors;
		}

		update_user_option( $user_id, 'default_password_nag', true, true ); //Set up the Password change nag.

		/**
		 * Fires after a new user registration has been recorded.
		 *
		 * @since 4.4.0
		 *
		 * @param int $user_id ID of the newly registered user.
		 */
		do_action( 'register_new_user', $user_id );

		return $user_id;
	}
	
	public static function getPrivacyLinkHTML() {

		$privacy_policy_page_link = get_privacy_policy_url();

		if ( $privacy_policy_page_link ) {

			$privacy_policy_html = '<div class="gdpr form-group"><div class="checkbox"><label><input name="gdpr" type="checkbox" class="simple-input">';
			$privacy_policy_html .= sprintf( '%s <a href="%s" target="_blank">%s</a>', esc_html__( 'Accept', 'crum-ext-sign-form' ), esc_url( $privacy_policy_page_link ), esc_html__( 'Privacy Policy', 'crum-ext-sign-form' ) );
			$privacy_policy_html .= '</label></div>';
			$privacy_policy_html .= '<p>'.esc_html__( 'I allow this website to collect and store submitted data.', 'crum-ext-sign-form' ).'</p>';
			$privacy_policy_html .= '</div>';

			return $privacy_policy_html;
		}

		return '';
	}

	public static function getPrivacyLink() {
		$privacy_policy_page_link = get_privacy_policy_url();
		if ( $privacy_policy_page_link ) {
			$privacy_policy = sprintf( '%s <a href="%s" target="_blank">%s</a>', esc_html__( 'Accept', 'crum-ext-sign-form' ), esc_url( $privacy_policy_page_link ), esc_html__( 'Privacy Policy', 'crum-ext-sign-form' ) );
			return $privacy_policy;
		}
		return '';
	}

	public static function kc_mapping() {
		$builderComponent = fw_ext( 'sign-form' )->get_config( 'builderComponent' );

		if ( function_exists( 'kc_add_map' ) ) {
			kc_add_map( array(
				$builderComponent => array(
					'name'		 => esc_html__( 'Sign in Form', 'crum-ext-sign-form' ),
					'category'	 => esc_html__( 'Crumina', 'crum-ext-sign-form' ),
					'icon'		 => 'kc-sign-form-icon',
					'params'	 => array(
						array(
							'type'	 => 'hidden',
							'name'	 => 'builder_type',
							'value'	 => 'kc'
						)
					)
				)
			) );
		}
	}

	public static function vc_mapping() {
		$ext				 = fw_ext( 'sign-form' );
		$builderComponent	 = $ext->get_config( 'builderComponent' );

		if ( function_exists( 'vc_map' ) ) {
			vc_map( array(
				'base'		 => $builderComponent,
				'name'		 => esc_html__( 'Sign in Form', 'sign-form' ),
				'category'	 => esc_html__( 'Crumina', 'sign-form' ),
				'icon'		 => $ext->locate_URI( '/static/img/builder-ico.svg' ),
				'params'	 => array(
					array(
						'heading'	 => esc_html__( 'Display', 'crum-ext-sign-form' ),
						'param_name' => 'forms',
						'type'		 => 'dropdown',
						'value'		 => array(
							esc_html__( 'Both', 'crum-ext-sign-form' )		 => 'both',
							esc_html__( 'Login', 'crum-ext-sign-form' )		 => 'login',
							esc_html__( 'Register', 'crum-ext-sign-form' )	 => 'register',
						),
						'std'		 => 'both',
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Form login', 'crum-ext-sign-form' ),
						'param_name' => 'form_type_login',
						'type'		 => 'dropdown',
						'value'		 => array(
							esc_html__( 'Olympus login form', 'crum-ext-sign-form' )		 => 'native',
							esc_html__( 'Youzer plugin form', 'crum-ext-sign-form' )		 => 'youzer',
							esc_html__( 'Custom shortcode', 'crum-ext-sign-form' )		 => 'custom',
						),
						'std'		 => 'native',
						'dependency'	 => array(
							'element'	 => 'forms',
							'value'		 => array('login','both')
						),
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'		 => esc_html__( 'Login form shortcode', 'crum-ext-sign-form' ),
						'param_name'	 => 'login_shortcode',
						'type'			 => 'textarea',
						'dependency'	 => array(
							'element'	 => 'form_type_login',
							'value'		 => 'custom',
						),
						'group'			 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Form register', 'crum-ext-sign-form' ),
						'param_name' => 'form_type_register',
						'type'		 => 'dropdown',
						'value'		 => array(
							esc_html__( 'Olympus login form', 'crum-ext-sign-form' )		 => 'native',
							esc_html__( 'Youzer plugin form', 'crum-ext-sign-form' )		 => 'youzer',
							esc_html__( 'Custom shortcode', 'crum-ext-sign-form' )		 => 'custom',
						),
						'std'		 => 'native',
						'dependency'	 => array(
							'element'	 => 'forms',
							'value'		 => array('register','both')
						),
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'		 => esc_html__( 'Register form shortcode', 'crum-ext-sign-form' ),
						'param_name'	 => 'register_shortcode',
						'type'			 => 'textarea',
						'dependency'	 => array(
							'element'	 => 'form_type_register',
							'value'		 => 'custom',
							
						),
						'group'			 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Login redirect to', 'crum-ext-sign-form' ),
						'param_name' => 'redirect',
						'type'		 => 'dropdown',
						'value'		 => array(
							esc_html__( 'Current page', 'crum-ext-sign-form' )	 => 'current',
							esc_html__( 'Profile page', 'crum-ext-sign-form' )	 => 'profile',
							esc_html__( 'Activity page', 'crum-ext-sign-form' )	 => 'activity',
							esc_html__( 'Custom page', 'crum-ext-sign-form' )	 => 'custom',
						),
						'dependency' => array(
							'element'	 => 'form_type_login',
							'value'		 => 'native',
						),
						'std'		 => 'current',
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Login redirect URL', 'crum-ext-sign-form' ),
						'param_name' => 'redirect_to',
						'type'		 => 'textfield',
						'dependency' => array(
							'element'	 => 'redirect',
							'value'		 => 'custom',
						),
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Register redirect to', 'crum-ext-sign-form' ),
						'param_name' => 'register_redirect',
						'type'		 => 'dropdown',
						'value'		 => array(
							esc_html__( 'Current page', 'crum-ext-sign-form' )	 => 'current',
							esc_html__( 'Profile page', 'crum-ext-sign-form' )	 => 'profile',
							esc_html__( 'Activity page', 'crum-ext-sign-form' )	 => 'activity',
							esc_html__( 'Custom page', 'crum-ext-sign-form' )	 => 'custom',
						),
						'dependency' => array(
							'element'	 => 'form_type_register',
							'value'		 => 'native',
						),
						'std'		 => 'current',
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Register redirect URL', 'crum-ext-sign-form' ),
						'param_name' => 'register_redirect_to',
						'type'		 => 'textfield',
						'dependency' => array(
							'element'	 => 'register_redirect',
							'value'		 => 'custom',
						),
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					),
					array(
						'heading'		 => esc_html__( 'Welcome Back title', 'crum-ext-sign-form' ),
						'param_name'	 => 'vcard_title',
						'type'			 => 'textfield',
						'description'	 => sprintf( esc_html__( 'You can use [%s] shortcode', 'crum-ext-sign-form' ), $ext->get_config( 'currentUserSC' ) ),
						'group'			 => esc_html__( 'Strings Options', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Welcome Back subtitle', 'crum-ext-sign-form' ),
						'param_name' => 'vcard_subtitle',
						'type'		 => 'textfield',
						'group'		 => esc_html__( 'Strings Options', 'crum-ext-sign-form' ),
					),
					array(
						'heading'	 => esc_html__( 'Welcome Back profile button', 'crum-ext-sign-form' ),
						'param_name' => 'vcard_profile_btn',
						'type'		 => 'textfield',
						'group'		 => esc_html__( 'Strings Options', 'crum-ext-sign-form' ),
					),
					array(
						'heading'		 => esc_html__( 'Login form description', 'crum-ext-sign-form' ),
						'param_name'	 => 'login_descr',
						'type'			 => 'textarea',
						'description'	 => sprintf( esc_html__( 'You can use [%s text="" url=""] shortcode', 'crum-ext-sign-form' ), $ext->get_config( 'registerLinkSC' ) ),
						'dependency'	 => array(
							'element'	 => 'forms',
							'value'		 => array( 'both', 'login' ),
						),
						'group'			 => esc_html__( 'Strings Options', 'crum-ext-sign-form' ),
					),
					array(
						'type'		 => 'hidden',
						'param_name' => 'builder_type',
						'value'		 => 'vc',
						'group'		 => esc_html__( 'General', 'crum-ext-sign-form' ),
					)
				)
			) );
		}
	}

	public static function prepareMmIconPrm( $meta = '' ) {
		$parsed = (array) json_decode( urldecode( $meta ) );

		if ( $meta && !$parsed ) {
			$parsed = array(
				'type'		 => 'icon-font',
				'icon-class' => $meta
			);
		}

		return array_merge( array(
			'type'			 => '',
			'icon-class'	 => '',
			'attachment-id'	 => '',
			'url'			 => ''
				), $parsed );
	}

	public static function embedCustomSvg( $svg_url, $extra_class = '', $atts = '' ) {

		$svg_url = str_replace( 'https://', 'http://', $svg_url );


		$svg_file = wp_remote_get( esc_url_raw( $svg_url ) );
		if ( is_wp_error( $svg_file ) ) {
			$svg_file = '';
		} else {
			$response_code = wp_remote_retrieve_response_code( $svg_file );
			if ( 200 === $response_code ) {
				$svg_file = wp_remote_retrieve_body( $svg_file );
			}
		}

		if ( !is_string( $svg_file ) ) {
			$svg_file = '';
		}

		$svg_file_new	 = '';
		$find_string	 = '<svg';

// Remove dimensions
		$svg_file = preg_replace( "/(width|height)=\".*?\"/", "", $svg_file );

// Add class if needed
		$svg_file = str_replace( $find_string, $find_string . ' class="' . esc_attr( $extra_class ) . '" ', $svg_file );

// Add atts if needed
		$svg_file = str_replace( $find_string, $find_string . ' ' . $atts, $svg_file );

		$position		 = strpos( $svg_file, $find_string );
		$svg_file_new	 = substr( $svg_file, $position );

		return $svg_file_new;
	}
}