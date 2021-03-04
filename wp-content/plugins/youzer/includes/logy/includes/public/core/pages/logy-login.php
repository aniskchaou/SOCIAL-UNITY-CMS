<?php

class Logy_Login {

	protected $logy;

	/**
	 * Init Shortcode & Actions & Filters
	 */
	public function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		// Add "[logy_login]" Shortcode.
		add_shortcode( 'logy_login_page', array( $this, 'get_login_form' ) );

        if ( apply_filters( 'yz_enable_login_hooks', true ) ) {

			// Ajax Login.
			add_action( 'wp_ajax_nopriv_yz_ajax_login', array( $this, 'ajax_login' ) );
			add_action( 'logy_after_login_buttons', array( $this, 'ajax_login_nonce' ) );

			// Redirects.
			add_filter( 'authenticate', array( $this, 'maybe_redirect_at_authenticate' ), 101, 3 );
			add_filter( 'login_redirect', array( $this, 'redirect_after_login' ), 10, 3 );
			add_action( 'wp_login_failed', array( $this, 'logy_login_fail' ), 9999 );

		}
	}

	/**
	 * Add Ajax Login Nonce
	 */
	function ajax_login_nonce() {

		if ( ! logy_is_ajax_login_active() ) {
			return false;
		}

		// Get Ajax Nonce.
		$ajax_nonce = wp_create_nonce( 'yz-ajax-login-nonce' );

		?>

		<input type="hidden" name="yz_ajax_login_nonce" value="<?php echo $ajax_nonce; ?>">

		<?php
	}

	/**
	 * Ajaxed Login
	 */
	function ajax_login() {

		if ( is_user_logged_in() || ! logy_is_ajax_login_active() ) {
		    return false;
		}

	    // First check the nonce, if it fails the function will break.
	    check_ajax_referer( 'yz-ajax-login-nonce', 'security' );

	    // Init Credentials.
	    $creds = array();

	    // Get Credentials.
	    $creds['remember'] = $_POST['remember'];
	    $creds['user_login'] = $_POST['username'];
	    $creds['user_password'] = $_POST['password'];

	    // Nonce is checked, get the POST data and sign user on
	    $user = wp_signon( $creds );

	    // Get Response.
	    if ( is_wp_error( $user ) ) {
	        echo json_encode(
	            array(
	                'loggedin' => false,
	                'error_code' => $user->get_error_code(),
	                'message'  => $user->get_error_message()
	            )
	        );
	    } else {
	    	$redirect_url = $this->redirect_after_login( null, null, $user );
	        echo json_encode(
	            array(
	                'loggedin' => true,
	                'redirect_url' => apply_filters( 'yz_ajax_login_redirect_url', $redirect_url, $user, $_POST ),
	                'message'  =>__( 'Logged in successfully, redirecting...', 'youzer' )
	            )
	        );
	    }

	    die();

	}

	/**
	* Redirect on Login failed.
	*/
	function logy_login_fail( $username ) {

		if ( function_exists( 'wp_doing_ajax' ) && wp_doing_ajax() ) {
			return false;
		}

		// Get Login Page Url.
		$login_url = logy_page_url( 'login' );

		// Get Redirect Url.
		if ( isset( $_REQUEST['redirect_to'] ) ) {
		    $login_url = add_query_arg( 'redirect_to', $_REQUEST['redirect_to'], $login_url );
		}

		// Redirect User.
		wp_redirect( $login_url );
		exit;
	}

	/**
	 * Returns the URL to which the user should be redirected after the successful login.
	 */
	public function redirect_after_login( $redirect_to, $requested_redirect_to , $user ) {


		if ( $requested_redirect_to && $redirect_to == home_url() ) {
			$requested_redirect_to = false;
		}

		$requested_redirect_to = apply_filters( 'yz_requested_redirect_to', $requested_redirect_to );

		// Use the redirect_to parameter if one is set, otherwise redirect to custom page.
		if ( ! $requested_redirect_to && isset( $user->ID ) ) {
			if ( user_can( $user, 'manage_options' ) ) {
				// Get Admin Redirect Page
				$admin_redirect_page = yz_option( 'logy_admin_after_login_redirect', 'dashboard' );
				$redirect_to = $this->get_redirect_page( $admin_redirect_page, $user->ID );
			} else {
				// Get User Redirect Page
				$user_redirect_page  = yz_option( 'logy_user_after_login_redirect', 'home' );
				$redirect_to = $this->get_redirect_page( $user_redirect_page, $user->ID );
			}
		}

		return $redirect_to;

	}

	/**
	 * Redirect the user after authentication if there were any errors.
	 */
	public function maybe_redirect_at_authenticate( $user, $username, $password ) {

		// Filters whether the given user can be authenticated with the provided $password.
		$user = apply_filters( 'wp_authenticate_user', $user, $password );

		if ( is_wp_error( $user ) ) {
			// Get Errors
			$errors = logy_get_error_messages( $user->get_error_messages() );
			// Add Errors.
			logy_add_message( $errors );
		}

		return $user;
	}

	/**
	 * A shortcode for rendering the login form.
	 */
	public function get_login_form( $attributes = null ) {

		if ( is_user_logged_in() ) {
			return false;
		}

		// Render the login form.
		return $this->logy->form->get_page( 'login', $attributes );

	}

	/**
	 * Attributes
	 */
	function attributes() {

		$attrs = $this->messages_attributes();

		// Add Form Type & Action to generate form class later.
		$attrs['form_type']   = 'login';
		$attrs['form_action'] = 'login';

		// Get Login Box Classes.
		$attrs['action_class'] = $this->get_actions_class();
		$attrs['form_class']   = $this->logy->form->get_form_class( $attrs );

		// Form Elements Visibilty Settings.
		$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
		$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

		// Form Actions Elements Visibilty Settings.
		$attrs['actions_lostpswd'] = ( false !== strpos( $attrs['action_class'], 'logy-lost-pswd' ) ) ? true : false;
		$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

		return $attrs;
	}

	/**
	 * Redirects the user to the correct page depending on whether he / she is an admin or not.
	 */
	private function redirect_logged_in_user( $redirect_to = null ) {
		$user = wp_get_current_user();
		if ( user_can( $user, 'manage_options' ) ) {
			if ( $redirect_to ) {
				wp_safe_redirect( $redirect_to );
			} else {
				wp_redirect( admin_url() );
			}
		} else {
			wp_redirect( home_url() );
		}
	}

	/**
	 * Get Redirect Page Url
	 */
	function get_redirect_page( $page, $user_id = null ) {

		// If Page ID is numeric Return Page Url.
		if ( is_numeric( $page ) ) {
			// Get Page Url.
			$page_link = get_the_permalink( $page );
			// Return Page Link.
			if ( ! empty( $page_link ) ) {
				return $page_link;
			}
		}

		switch( $page ) {
			case 'home':
				$page_url = home_url( '/' );
	        	break;
			case 'dashboard':
				$page_url = admin_url();
	        	break;
			case 'profile':
				$page_url = bp_core_get_user_domain( $user_id );
	        	break;
	        default:
				$page_url = home_url( '/' );
	        break;
        }

        return esc_url( $page_url );
	}

}