<?php

class Logy_Form {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

    	// Add "[logy_register]" Shortcode.
		add_shortcode( 'logy_complete_registration_page', array( $this, 'get_complete_registration_form' ) );

	}

	/**
	 * Get Complete Registration form.
	 */
	public function get_complete_registration_form() {
		// Render the form.
		return $this->get_page( 'complete_registration' );
	}
	/**
	 * Form Fields
	 */
	function get_page( $form, $attributes = null ) {

		do_action( 'logy_before_' . $form . '_form' );

		echo '<div id="logy" class="logy logy-page-box yz-page">';
		$this->get_form( $form, $attributes );
		echo '</div>';

		do_action( 'logy_after_' . $form . '_form' );

	}

	/**
	 * Form Fields
	 */
	function get_form( $form, $shortcode_attrs = null ) {

		// Get Form Attributes
		$attributes = $this->get_attributes( $form );
		$elements 	= $this->get_form_elements( $form );

		// Get Action Link
		if ( 'login' == $form ) {
			$action = apply_filters( 'yz_login_url' , wp_login_url() );
		} elseif ( 'register' == $form ) {
			$action = wp_registration_url();
		} elseif ( 'lost_password' == $form && isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {
			$action = site_url( 'wp-login.php?action=resetpass' );
		} elseif ( 'complete_registration' == $form ) {
			$action = logy_page_url( 'complete-registration' );
		} elseif ( 'lost_password' == $form ) {
			$action = wp_lostpassword_url();
		} else {
			$action = null;
		}

		$action = apply_filters( 'yz_membership_form_action', $action, $form );

		?>

		<div class="<?php echo $attributes['form_class']; ?>">

			<?php $this->get_form_header( $form ); ?>
			<?php $this->get_form_messages( $attributes ); ?>

			<form id="logy-form" class="logy-<?php echo $form; ?>-form" method="post" action="<?php echo $action; ?>">

				<!-- After Form Buttons -->
				<?php do_action( 'logy_before_' . $form . '_fields', $shortcode_attrs ); ?>

				<?php $this->generate_form_fields( $elements['fields'], $attributes ); ?>
				<?php $this->generate_form_actions( $elements['actions'], $attributes ); ?>

				<!-- After Form Buttons -->
				<?php do_action( 'logy_after_' . $form . '_buttons', $shortcode_attrs ); ?>

				<input type="hidden" name="logy-form" value="1">

			</form>

		</div>

		<?php
	}

	/**
	 * Form Attributes.
	 */
	function get_attributes( $form ) {

		$attrs = array();

		switch ( $form ) {

			case 'login':

				// Add Form Type & Action to generate form class later.
				$attrs['form_type']   = 'login';
				$attrs['form_action'] = 'login';

				// Get Login Box Classes.
				$attrs['action_class'] = $this->get_actions_class( 'login' );
				$attrs['form_class']   = $this->get_form_class( $attrs );

				// Form Elements Visibilty Settings.
				$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
				$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

				// Form Actions Elements Visibilty Settings.
				$attrs['actions_lostpswd'] = ( false !== strpos( $attrs['action_class'], 'logy-lost-pswd' ) ) ? true : false;
				$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

				break;

			case 'register':
			case 'complete_registration':

				// Add Form Type & Action to generate form class later.
				$attrs['form_type']   = 'signup';
				$attrs['form_action'] = 'signup';

				// Get Login Box Classes.
				$attrs['action_class'] = $this->get_actions_class( 'register' );
				$attrs['form_class'] = $this->get_form_class( $attrs );

				// Form Elements Visibilty Settings.
				$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
				$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

				// Form Actions Elements Visibilty Settings.
				$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

				break;

			case 'lost_password':

				// Add Form Type & Action to generate form class later.
				$attrs['form_type']   = 'login';
				$attrs['form_action'] = 'lost-password';

				// Get Login Box Classes.
				$attrs['action_class'] = $this->get_actions_class( 'login' );
				$attrs['form_class']   = $this->get_form_class( $attrs );

				// Form Elements Visibilty Settings.
				$attrs['use_labels'] = ( false !== strpos( $attrs['form_class'], 'logy-with-labels' ) ) ? true : false;
				$attrs['use_icons']	 = ( false !== strpos( $attrs['form_class'], 'logy-fields-icon' ) ) ? true : false;

				// Form Actions Elements Visibilty Settings.
				$attrs['actions_icons']	= ( false !== strpos( $attrs['action_class'], 'logy-buttons-icons' ) ) ? true : false;

				break;

				// break;

			default:
				break;
		}

		return $attrs;

	}

	/**
	 * Signup
	 */
	function get_actions_class( $type ) {

		// Array.
		$actions_class = array( 'logy-form-actions' );

		switch ( $type ) {

			case 'login':

				// Get Actions Layout
				$actions_layout = yz_option( 'logy_login_actions_layout', 'logy-actions-v1' );

				// Get Form Options Data

				$one_button = array(
					'logy-actions-v3', 'logy-actions-v6'
				);

				$forgot_password = array(
					'logy-actions-v2', 'logy-actions-v5', 'logy-actions-v9', 'logy-actions-v10'
				);

				$use_icons	= array(
					'logy-actions-v4', 'logy-actions-v5', 'logy-actions-v6', 'logy-actions-v7',
					'logy-actions-v10'
				);

				$full_witdh	= array(
					'logy-actions-v1', 'logy-actions-v3', 'logy-actions-v4', 'logy-actions-v6',
					'logy-actions-v9', 'logy-actions-v10'
				);

				$half_witdh	= array(
					'logy-actions-v2', 'logy-actions-v5', 'logy-actions-v7', 'logy-actions-v8'
				);

				// Get One Button Class.
				$actions_class[] = in_array( $actions_layout, $one_button ) ? 'logy-one-button' : null;

				// Get Buttons icons Class.
				$actions_class[] = in_array( $actions_layout, $use_icons ) ? 'logy-buttons-icons' : null;

				// Get full Width Class.
				$actions_class[] = in_array( $actions_layout, $full_witdh ) ? 'logy-fullwidth-button' : null;

				// Get Half Width Class.
				$actions_class[] = in_array( $actions_layout, $half_witdh ) ? 'logy-halfwidth-button' : null;

				// Get "Forgot Password" Class.
				$actions_class[] = in_array( $actions_layout, $forgot_password ) ? 'logy-lost-pswd' : null;

				// Get Button Border Style.
				$actions_class[] = yz_option( 'logy_login_btn_format', 'logy-border-radius' );

				// Get Button Icons Position.
				if ( in_array( $actions_layout, $use_icons ) ) {
					$actions_class[] = yz_option( 'logy_login_btn_icons_position', 'logy-icons-left' );
				}

				break;

			case 'register':

				// Get Actions Layout
				$actions_layout = yz_option( 'logy_signup_actions_layout', 'logy-regactions-v1' );

				// Get One Button Class.
				if ( in_array( $actions_layout, array( 'logy-regactions-v5', 'logy-regactions-v6' ) ) ) {
					$actions_class[] = 'logy-one-button';
				}

				// Get Buttons icons Class.
				if ( in_array( $actions_layout, array( 'logy-regactions-v3', 'logy-regactions-v4', 'logy-regactions-v6' ) ) ) {
					$actions_class[] = 'logy-buttons-icons';
					$actions_class[] = yz_option( 'logy_signup_btn_icons_position', 'logy-icons-left' );
				}

				// Get full Width Class.
				if ( in_array( $actions_layout, array( 'logy-regactions-v1', 'logy-regactions-v3', 'logy-regactions-v5', 'logy-regactions-v6' ) ) ) {
					$actions_class[] = 'logy-fullwidth-button';
				}

				// Get Half Width Class.
				if ( in_array( $actions_layout, array( 'logy-regactions-v2', 'logy-regactions-v4' ) ) ) {
					$actions_class[] = 'logy-halfwidth-button';
				}

				// Get Button Border Style.
				$actions_class[] = yz_option( 'logy_signup_btn_format', 'logy-border-radius' );

				// If Buddypress
				if ( logy_is_page( 'register' ) && logy_is_bp_registration_completed() ){
					$actions_class[] = 'logy-bp-registration-completed';
				}

				break;

			default:
				break;
		}

		// Return Action Area Classes
		return logy_generate_class( $actions_class );
	}

	/**
	 * Form Header
	 */
	function get_form_header( $form ) {

		// Get Form Title.
		if ( 'activate' == $form ) {
			$form_title = __( 'Activate Account', 'youzer' );
			$form_subtitle = __( 'Activate your account', 'youzer' );
		} elseif ( 'lost_password' == $form ) {
			$form_title = yz_option( 'logy_lostpswd_form_title', __( 'Forgot your password?', 'youzer' ) );
			$form_subtitle = yz_option( 'logy_lostpswd_form_subtitle', __( 'Reset your account password', 'youzer' ) );
		} elseif ( 'register' == $form ) {
			$form_title = yz_option( 'logy_signup_form_title', __( 'Sign Up', 'youzer' ) );
			$form_subtitle = yz_option( 'logy_signup_form_subtitle', __( 'Create new account', 'youzer' ) );
		} elseif ( 'complete_registration' == $form ) {
			$form_title = __( 'Complete Registration', 'youzer' );
			$form_subtitle = __( 'Complete registration steps', 'youzer' );
		} else {
			$form_title = yz_option( 'logy_login_form_title', __( 'Login', 'youzer' ) );
			$form_subtitle 	= yz_option( 'logy_login_form_subtitle', __( 'Sign in to your account', 'youzer' ) );
		}

		// Sanitize Form Title & Subtitle
		$form_title = sanitize_text_field( $form_title );
		$form_subtitle = sanitize_text_field( $form_subtitle );

		// Get Form Options
		if ( 'activate' == $form ) {
			$form = 'login';
		} elseif ( 'lost_password' == $form ) {
			$form = 'lostpswd';
		} elseif ( 'register' == $form || 'complete_registration' == $form ) {
			$form = 'signup';
		}

		// Get Cover Data
		$form_cover = esc_url( yz_option( 'logy_' . $form . '_cover' ) );
		$cover_class = ! empty( $form_cover ) ? 'logy-custom-cover' : 'logy-default-cover';

		// If cover photo not exist use pattern.
		if ( ! $form_cover ) {
			$form_cover = LOGY_PA . 'images/geopattern.png';
		}

		?>

    	<header class="logy-form-header">
	    	<?php if ( 'on' == yz_option( 'logy_' . $form . '_form_enable_header', 'on' ) ) : ?>
	    		<div class="logy-form-cover <?php echo $cover_class; ?>" style="background-image: url( <?php echo apply_filters( 'yz_' . $form . '_form_cover', $form_cover ); ?> )">
			        <h2 class="form-cover-title"><?php echo $form_title; ?></h2>
	    		</div>
	    	<?php else : ?>
	    		<div class="form-title">
		    		<h2><?php echo $form_title; ?></h2>
		    		<?php if ( ! empty( $form_subtitle ) ) : ?>
		    			<span class="logy-form-desc"><?php echo $form_subtitle; ?></span>
    				<?php endif; ?>
	    		</div>
    		<?php endif; ?>
    	</header>

	    <?php
	}

	/**
	 * Form Elements
	 */
	function get_form_elements( $form = null ) {

		// New Array's
		$fields = array();
		$actions = array();

		switch ( $form ) :

		case 'login':

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-user',
				'label'	=> __( 'Username or Email', 'youzer' ),
				'id'	=> 'user_login',
				'name'	=> 'log',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-lock',
				'label'	=> __( 'Password', 'youzer' ),
				'id'	=> 'user_pass',
				'name'	=> 'pwd',
				'type'	=> 'password'
			);

			$fields[] = array(
				'item' 		=> 'remember-me',
				'label'		=> __( 'Remember Me', 'youzer' )
			);

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'fas fa-sign-in-alt',
				'name' => 'signin_submit',
				'title' => yz_option( 'logy_login_signin_btn_title', __( 'Log In', 'youzer' ) )
			);

			if ( get_option( 'users_can_register' ) ) :

				// Get Custom Registration Link.
				$custom_registration = yz_option( 'logy_login_custom_register_link' );

				// Get Registration Link.
				$register_page_link = ! empty( $custom_registration ) ? $custom_registration : logy_page_url( 'register' );

				$actions[] = array(
					'item' 	=> 'link',
					'icon'	=> 'fas fa-pencil-alt',
					'url'	=> $register_page_link,
					'title' => yz_option( 'logy_login_register_btn_title', __( 'Create New Account', 'youzer' ) )
				);
			endif;

			$actions[] = array( 'item' 	=> 'lost_pswd' );

			$actions[] = array( 'item' 	=> 'redirect' );

		break;

		case 'register':

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-user',
				'label'	=> __( 'Username', 'youzer' ),
				'id'	=> 'user_login',
				'name'	=> 'username',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'far fa-envelope',
				'label'	=> __( 'Email', 'youzer' ),
				'id'	=> 'email',
				'name'	=> 'email',
				'type'	=> 'email'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'address-card-o',
				'label'	=> __( 'First Name', 'youzer' ),
				'id'	=> 'first_name',
				'name'	=> 'first_name',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'id-card',
				'label'	=> __( 'Last Name', 'youzer' ),
				'id'	=> 'last_name',
				'name'	=> 'last_name',
				'type'	=> 'text'
			);

			$fields[] = array(
				'item' 	=> 'note',
				'note'	=> __( '<strong>Note:</strong> We will email you a link you can follow to set your account password.', 'youzer' )
			);

			$fields[] = array( 'item' => 'captcha' );

			// Display terms and conditions & privacy policy.
			if ( 'on' == yz_option( 'logy_show_terms_privacy_note', 'on' ) ) {

				$terms_url = yz_option( 'logy_terms_url' );
				$privacy_url = yz_option( 'logy_privacy_url' );

				$fields[] = array(
					'item'  => 'note',
					'class' => 'logy-terms-note',
					'note'  => sprintf( __( 'By creating an account you agree to our <a href="%1s" target="_blank">Terms and Conditions</a> and our <a href="%2s" target="_blank">Privacy Policy</a>.', 'youzer' ), $terms_url, $privacy_url )
				);
			}

			if ( ! logy_is_bp_registration_completed() ) {
				$actions[] = array(
					'item' 	=> 'submit',
					'icon'	=> 'fas fa-pencil-alt',
					'name'  => 'signup_submit',
					'title' => yz_option( 'logy_signup_register_btn_title', __( 'Sign Up', 'youzer' ) )
				);
			}

			$actions[] = array(
				'item' 	=> 'link',
				'icon'	=> 'fas fa-sign-in-alt',
				'url'	=> yz_get_login_page_url(),
				'title' => yz_option( 'logy_signup_signin_btn_title', __( 'Log In', 'youzer' ) )
			);

			break;

		case 'activate':

			$fields[] = array(
				'item' 	=> 'input',
				'icon'	=> 'fas fa-key',
				'id'	=> 'key',
				'name'	=> 'key',
				'type'	=> 'text',
				'label'	=> __( 'Activation Key', 'youzer' ),
				'value'	=> esc_attr( bp_get_current_activation_key() )
			);

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'fas fa-check',
				'title' => __( 'Activate', 'youzer' )
			);

			break;

		case 'complete_registration':

			// Init Vars
			$errors = array();

			// Get Required Fields.
			$required_fields = json_decode( logy_user_session_data( 'get' ), true );

			if ( isset( $required_fields['email'] ) ) {
				$errors[] = sprintf( __( "- %s didn't provide us with your email.", 'youzer' ), $required_fields['provider'] );
			}

			if ( isset( $required_fields['user_login'] ) ) {
				$errors[] = __( "- We couldn't get your username or its already exist.", 'youzer' );
			}

			$erros_msg =  implode( '<br>', $errors ) ;

			if ( ! isset( $_GET['register-errors'] ) ) {
				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> sprintf( __( "<strong>Note:</strong> We couldn't get the information below : <br> %s", 'youzer' ), $erros_msg )
				);
			}

			// Get Username Field
			if ( isset( $required_fields['user_login'] ) ) {
				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'fas fa-user',
					'label'	=> __( 'Username', 'youzer' ),
					'id'	=> 'user_login',
					'name'	=> 'signup_username',
					'type'	=> 'text'
				);
			}

			if ( isset( $required_fields['email'] ) ) {
				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'far fa-envelope',
					'label'	=> __( 'Email', 'youzer' ),
					'name'	=> 'signup_email',
					'id'	=> 'email',
					'type'	=> 'email'
				);
			}

			$actions[] = array(
				'item' 	=> 'submit',
				'icon'	=> 'fas fa-pencil',
				'title' => __( 'Complete Registration', 'youzer' )
			);

			$fields[] = array(
				'item' 	=> 'hidden',
				'name'	=> 'complete-registration',
				'value'	=> 'true',
			);

			break;

		case 'lost_password':

			if ( isset( $_GET['action'] ) && 'rp' == $_GET['action'] ) {

				$fields[] = array(
					'item' 	=> 'hidden',
					'name'	=> 'rp_login',
					'key'	=> 'login',
					'value'	=> isset( $_GET['login'] ) ? $_GET['login'] : ''
				);

				$fields[] = array(
					'item' 	=> 'hidden',
					'name'	=> 'rp_key',
					'key'	=> 'key',
					'value'	=> isset( $_GET['key'] ) ? $_GET['key'] : ''
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'fas fa-lock',
					'label'	=> __( 'New Password', 'youzer' ),
					'id'	=> 'pass1',
					'name'	=> 'pass1',
					'type'	=> 'password'
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'fas fa-lock',
					'label'	=> __( 'Repeat New Password', 'youzer' ),
					'id'	=> 'pass2',
					'name'	=> 'pass2',
					'type'	=> 'password'
				);

				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> wp_get_password_hint()
				);

				$actions[] = array(
					'icon'	=> 'fas fa-undo',
					'item' 	=> 'submit',
					'title'	=> yz_option( 'logy_lostpswd_submit_btn_title', __( 'Reset Password', 'youzer' ) )
				);

			} else {

				$fields[] = array(
					'item' 	=> 'note',
					'note'	=> __( "Enter your email address and we'll send you a link you can use to pick a new password.", 'youzer' )
				);

				$fields[] = array(
					'item' 	=> 'input',
					'icon'	=> 'far fa-envelope',
					'label'	=> __( 'Email', 'youzer' ),
					'id'	=> 'email',
					'name'	=> 'user_login',
					'type'	=> 'email'
				);

				$actions[] = array(
					'item' 	=> 'submit',
					'icon'	=> 'fas fa-undo',
					'title' => yz_option( 'logy_lostpswd_submit_btn_title', __( 'Reset Password', 'youzer' ) )
				);

				$actions[] = array(
					'item' 	=> 'link',
					'icon'	=> 'fas fa-sign-in-alt',
					'url'	=> logy_page_url( 'login' ),
					'title' => yz_option( 'logy_signup_signin_btn_title', __( 'Log In', 'youzer' ) )
				);
			}
			break;

		endswitch;

		return apply_filters( 'yz_login_form_elements', array( 'fields' => $fields, 'actions' => $actions ), $form );
	}

	/**
	 * Form Class
	 */
	function get_form_class( $attributes = null ) {

		// Create New Array();
		$form_class = array();

		// Get Form Type.
		$form_type = $attributes['form_type'];

		// Get Form Options Data

		$silver_icons = array(
			'logy-field-v2', 'logy-field-v5', 'logy-field-v10'
		);

		$silver_inputs = array(
			'logy-field-v4', 'logy-field-v6', 'logy-field-v9'
		);

		$use_labels = array(
			'logy-field-v1','logy-field-v2', 'logy-field-v4', 'logy-field-v6', 'logy-field-v11'
		);

		$use_icons = array(
			'logy-field-v2','logy-field-v5', 'logy-field-v6', 'logy-field-v7',
			'logy-field-v8', 'logy-field-v9', 'logy-field-v10', 'logy-field-v11'
		);

		$full_border = array(
			'logy-field-v1','logy-field-v2', 'logy-field-v4', 'logy-field-v5','logy-field-v6',
			'logy-field-v8', 'logy-field-v9', 'logy-field-v11', 'logy-field-v12'
		);

		// Get Form Layout
		$form_layout = yz_option( 'logy_' . $form_type . '_form_layout', 'logy-field-v1' );

		// Check if header is Enable Or Disabled.
		if ( 'lost-password' == $attributes['form_action'] ) {
			$use_header = yz_option( 'logy_lostpswd_form_enable_header', 'on' );
		} else {
			$use_header = yz_option( 'logy_' . $form_type . '_form_enable_header', 'on' );
		}

		// Main Form Class
		$form_class[] = 'logy-form';

		// Add Registration	Incomplete class
		if ( is_registration_incomplete() ) {
			$form_class[] = 'logy-complete-registration-page';
		}

		// Add Registration	Incomplete class
		if ( logy_is_bp_registration_completed() ) {
			$form_class[] = 'logy-complete-registration-page';
		}

		// Get Page Class Name
		$form_class[] = "logy-$form_type-page";
		if ( 'lost-password' == $attributes['form_action'] ) {
			$form_class[] = 'logy-lost-password-page';
		}

		// Get Header Type.
		$form_class[] = ( $use_header == 'on' ) ? 'logy-with-header' : 'logy-no-header';

		// Get Labels Type
		$form_class[] = in_array( $form_layout, $use_labels ) ? 'logy-with-labels' : 'logy-no-labels';

		// Get Labels Type
		$form_class[] = in_array( $form_layout, $silver_inputs ) ? 'logy-silver-inputs' : null;

		// Get Icons Type
		$form_class[] = in_array( $form_layout, $use_icons ) ? 'logy-fields-icon' : 'logy-no-icons';

		// Get Border Type
		$form_class[] = in_array( $form_layout, $full_border ) ? 'logy-full-border' : 'logy-bottom-border';

		// Get Border Format.
		$form_class[] = yz_option( 'logy_' . $form_type . '_fields_format', 'logy-border-flat' );

		// Icons Options
		if ( in_array( $form_layout, $use_icons ) ) {
			// Get icons position.
			$form_class[] = yz_option( 'logy_' . $form_type . '_icons_position', 'logy-icons-left' );
			// Get icons background.
			$form_class[] = in_array( $form_layout, $silver_icons ) ? 'logy-silver-icons' : 'logy-nobg-icons';
		}

		// Add Error Messages Class
		if ( 'login' == $attributes['form_action'] ) {
			$form_class[] = (
				isset( $attributes['errors'] ) ||
				isset( $attributes['logged_out'] ) ||
				isset( $attributes['registered'] )
			) ? 'logy-form-msgs' : null;
		} else {
			if ( isset( $attributes['errors'] ) ) {
				$form_class[] = count( $attributes['errors'] ) > 0 ? 'logy-form-msgs' : null;
			}
		}

		// Return Form Classes.
		return logy_generate_class( $form_class );
	}

	/**
	 * Form Messages
	 */
	function get_form_messages( $attrs ) {

		?>

		<?php do_action( 'logy_form_notices' ); ?>
<!--
		< ?php if ( count( $attrs['errors'] ) > 0 )  : ?>
			<div class="logy-form-message logy-error-msg">
				< ?php foreach ( $attrs['errors'] as $error_msg ) : ?>
					<p><strong>< ?php _e( 'ERROR', 'youzer' ); ?> !</strong>< ?php echo $error_msg; ?></p>
				< ?php endforeach; ?>
			</div>
		< ?php endif; ?>
 -->
		<?php if ( isset( $attrs['registered'] ) && $attrs['registered'] ) : ?>
			<div class="logy-form-message logy-success-msg">
				<p>
					<strong><?php _e( 'Done!' , 'youzer' ); ?></strong>
					<?php _e( 'You have successfully registered. We have emailed your password to the email address you entered.', 'youzer' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( isset( $attrs['logged_out'] ) && $attrs['logged_out'] ) : ?>
			<div class="logy-form-message logy-info-msg">
				<p>
					<?php _e( '<strong>You have signed out!</strong> Would you like to sign in again?', 'youzer' ); ?>
				</p>
			</div>
		<?php endif; ?>

		<?php if ( ( isset( $attrs['password_updated'] ) && $attrs['password_updated'] ) || ( isset( $_GET['password'] ) && $_GET['password'] == 'changed' ) ) : ?>
			<div class="logy-form-message logy-success-msg">
				<p>
				<strong><?php _e( 'Done!' , 'youzer' ); ?></strong>
					<?php _e( 'Your password has been changed. You can sign into your account now.', 'youzer' ); ?>
				</p>
			</div>
		<?php endif; ?>

	<?php
	}

	/**
	 * Form Fields
	 */
	function get_form_fields( $field, $attrs ) {

		// Get Fields By Type.
		switch ( $field['item'] ) {

			case 'input': ?>
				<div class="logy-form-item">
		    		<div class="logy-item-content">
			           	<?php if ( $attrs['use_labels'] ) : ?>
			           		<label for="<?php echo $field['id']; ?>"><?php echo sanitize_text_field( $field['label'] ); ?></label>
			        	<?php endif; ?>
			           <div class="logy-field-content">
		           			<?php if ( $attrs['use_icons'] ) : ?>
					           <div class="logy-field-icon">
		           					<i class="<?php echo $field['icon']; ?>"></i>
		           				</div>
		        			<?php endif; ?>
				    		<input type="<?php echo $field['type'];?>" name="<?php echo $field['name']; ?>" autocomplete="false" placeholder="<?php if ( ! $attrs['use_labels'] ) { echo sanitize_text_field( $field['label'] ); } ?>" value="<?php if ( isset( $field['value'] ) ) { echo $field['value']; } ?>" required>
			            </div>
		        	</div>
		       	</div>
			<?php	break;

			case 'remember-me': ?>
		    	<div class="logy-form-item logy-remember-me">
		    		<div class="logy-item-content">
			        	<label class="logy_checkbox_field" ><input name="rememberme" type="checkbox" value="forever"><div class="logy_field_indication"></div><?php echo $field['label']; ?></label>

		        	</div>
					<?php
						if ( ! $attrs['actions_lostpswd'] ) {
							$this->lost_password_field();
						}
					?>
		        </div>
			<?php break;

			case 'checkbox': ?>
		    	<div class="logy-form-item logy-checkbox-item <?php echo $field['class']; ?>">
		    		<div class="logy-item-content">
			        	<label class="logy_checkbox_field" ><input name="<?php echo $field['name']; ?>" type="checkbox" value="<?php echo $field['value'];  ?>"><div class="logy_field_indication"></div><?php echo $field['label']; ?></label>

		        	</div>
		        </div>
			<?php break;

			case 'submit': ?>
				<div class="logy-action-item logy-submit-item">
					<div class="logy-item-inner">
	           			<button type="submit" value="submit" <?php if ( isset( $field['name'] ) ) : ?> name="<?php echo $field['name']; ?>" <?php endif; ?> >
	            			<?php if ( $attrs['actions_icons'] ) : ?>
		           				<div class="logy-button-icon">
		           					<i class="<?php echo $field['icon']; ?>"></i>
		           				</div>
		           			<?php endif; ?>
	           				<span class="logy-button-title"><?php echo sanitize_text_field( $field['title'] ); ?></span>
	           			</button>
	            	</div>
	            </div>
			<?php break;

			case 'link': ?>
				<div class="logy-action-item logy-link-item">
					<div class="logy-item-inner">
	            		<a href="<?php echo esc_url( $field['url'] ); ?>" class="logy-link-button" >
	            			<?php if ( $attrs['actions_icons'] ) : ?>
    							<div class="logy-button-icon">
		           					<i class="<?php echo $field['icon']; ?>"></i>
		           				</div>
		           			<?php endif; ?>
	           				<?php echo sanitize_text_field( $field['title'] ); ?>
	            		</a>
	            	</div>
	            </div>
			<?php break;

			case 'lost_pswd':
					if ( $attrs['actions_lostpswd'] ) {
						$this->lost_password_field();
					}
				break;

			case 'redirect': ?>
				<?php if ( isset( $_GET['redirect_to'] ) ) : ?>
					<input type="hidden" name="redirect_to" value="<?php echo esc_url( $_GET['redirect_to'] ); ?>">
				<?php endif; ?>
			<?php break;

			case 'note':

				// Init Vars
				$note_class = array();
				$note_class[] = 'logy-form-note';
				$note_class[] = isset( $field['class'] ) ? $field['class'] : null;

				?>

				<div class="<?php echo logy_generate_class( $note_class ); ?>">
					<?php echo $field['note']; ?>
				</div>

			<?php break;

			case 'captcha': ?>
				<?php if ( $attrs['recaptcha_site_key'] ) : ?>
					<div class="logy-recaptcha-container">
						<div class="g-recaptcha" data-sitekey="<?php echo $attrs['recaptcha_site_key']; ?>"></div>
					</div>
				<?php endif; ?>
			<?php break;

			case 'hidden':

				$value = isset( $field['value'] ) ? $field['value'] : $attrs[ $field['key'] ];

			?>
				<input type="hidden" name="<?php echo $field['name']; ?>" value="<?php echo esc_attr( $value ); ?>" autocomplete="off">
			<?php break;

		}
	}

	/**
	 * Generate Form Fields
	 */
	function generate_form_fields( $fields, $attributes ) {
		// Print Fields
		foreach ( $fields as $field ) {
			$this->get_form_fields( $field, $attributes );
		}
	}

	/**
	 * Generate Form Actions
	 */
	function generate_form_actions( $actions, $attributes ) {
		// Print Fields
		echo '<div class="' . $attributes['action_class'] . '">';
		foreach ( $actions as $action ) {
			$this->get_form_fields( $action, $attributes );
		}
		echo '</div>';
	}

	/**
	 * Lost Password Link
	 */
	function lost_password_field() {
		$field_title = sanitize_text_field( yz_option( 'logy_login_lostpswd_title', __( 'Lost password?', 'youzer'  ) ) );
		$lostpswd = apply_filters( 'yz_lostpassword_url', wp_lostpassword_url() );
		echo '<a class="logy-forgot-password" href="' . $lostpswd . '">' . $field_title . '</a>';
	}

	/**
	 * Finds and returns a matching error message for the given error code.
	 */
	public function get_error_message( $error_code ) {
		switch ( $error_code ) {

			case 'empty_fields':
				return __( 'Required form field is missing.', 'youzer' );

			case 'username_invalid':
				return __( 'Invalid username!', 'youzer' );

			case 'username_exists':
				return __( 'That username already exists!', 'youzer' );

			case 'username_length':
				return __( 'Username too short. At least 4 characters is required!', 'youzer' );

			case 'email':
				return __( 'The email address you entered is not valid.', 'youzer' );

			case 'email_exists':
				return __( 'An account exists with this email address.', 'youzer' );

			case 'first_name':
				return __( 'First name should be alphabetic!', 'youzer' );

			case 'last_name':
				return __( 'Last name should be alphabetic!', 'youzer' );

			case 'registration_closed':
				return __( 'Registering new users is currently not allowed.', 'youzer' );

			// case 'wrong_captcha':
				// return __( 'The CAPTCHA check failed. Try again!', 'youzer' );

			case 'empty_username':
				return __( 'You do have an email address, right?', 'youzer' );

			case 'invalid_url':
				return __( 'The requested URL is invalid', 'youzer' );

			case 'empty_password':
				return __( 'You need to enter a password to login.', 'youzer' );

			case 'file_not_found':
				return __( 'Provider functions file not found.', 'youzer' );

			case 'invalid_username':
				return __(
					"We don't have any users with that email address. Maybe you used a different one when signing up?", 'youzer' );

			case 'incorrect_password':
				return __( "The password you entered wasn't quite right.", 'youzer' );

			case 'expiredkey':
			case 'invalidkey':
				return __( 'The password reset link you used is not working.', 'youzer' );

			case 'registration_disabled':
				return __( 'Registering new users is currently not allowed.', 'youzer' );

			case 'network_unavailable':
				return __( 'The chosen network is not available.', 'youzer' );

			case 'social_auth_unavailable':
				return __( 'The social authentication is not available.', 'youzer' );

			case 'cant_connect':
				return __( "We couldn't connect to your account. Please try again!", 'youzer' );

			case 'lost_password_sent':
				return __( 'Check your email for a link to reset your password.', 'youzer' );

			case 'too_many_retries':

			global $Logy;

			return $Logy->limit->get_lockout_msg();

			case 'registration_needs_activation':

			return __( 'You have successfully created your account! To begin using this site you will need to activate your account via the email we have just sent to your address.', 'youzer' );

			default:
				break;
		}
		return __( 'An unknown error occurred. Please try again later.', 'youzer' );
	}
}