<?php
/**
 * @var string $form_type_login
 * @var string $form_type_register
 * @var string $redirect_to
 * @var string $redirect
 * @var string $register_redirect_to
 * @var string $register_redirect
 * @var string $forms
 * @var string $login_shortcode
 * @var string $register_shortcode
 * @var string $login_descr
 * @var string $vcard_title
 * @var string $vcard_subtitle
 * @var string $vcard_profile_btn
 * @var string $enable_captcha
 * @var string $captcha_site_key
 * @var string $register_fields_type
 */
$ext = fw_ext( 'sign-form' );

if ( is_user_logged_in() ) {
	echo $ext->get_view( 'vcard', array(
		'ext'				 => $ext,
		'vcard_title'		 => isset( $vcard_title ) ? $vcard_title : '',
		'vcard_subtitle'	 => isset( $vcard_subtitle ) ? $vcard_subtitle : '',
		'vcard_profile_btn'	 => isset( $vcard_profile_btn ) ? $vcard_profile_btn : '',
	) );
	return;
}

$rand			 = rand( 1000, 9999 );
$can_register	 = get_option( 'users_can_register' );

if ( function_exists( 'bp_current_component' ) ) {
	if ( bp_current_component() === 'register' ) {
		$can_register = 0;
	}
}

$classes	 = array( 'registration-login-form', 'mb-0' );
$classes[]	 = $ext->get_config( 'selectors/formContainer' );
$classes[]	 = "selected-forms-{$forms}";

if ( $forms !== 'both' || !$can_register ) {
	$classes[] = 'selected-forms-single';
}
?>

<div class="<?php echo implode( ' ', $classes ); ?>">
    <!-- Nav tabs -->
	<?php if ( $can_register && $forms === 'both' ) { ?>
		<ul class="nav nav-tabs" role="tablist">
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#login-panel-<?php echo esc_attr( $rand ); ?>"
				   role="tab">
					<i class="olymp-login-icon olympus-icon-Login-Icon" data-toggle="tooltip" data-placement="top" data-original-title="<?php esc_html_e( 'Login', 'crum-ext-sign-form' ); ?>"></i>
					<span class="icon-title"><?php esc_html_e( 'Login', 'crum-ext-sign-form' ); ?></span>
				</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" data-toggle="tab" href="#register-panel-<?php echo esc_attr( $rand ); ?>"
				   role="tab">
					<i class="olymp-register-icon olympus-icon-Register-Icon" data-toggle="tooltip" data-placement="top" data-original-title="<?php esc_html_e( 'Registration', 'crum-ext-sign-form' ); ?>"></i>
					<span class="icon-title"><?php esc_html_e( 'Registration', 'crum-ext-sign-form' ); ?></span>
				</a>
			</li>
		</ul>
	<?php } ?>

    <div class="tab-content">
		<?php
			if ( ($forms === 'login' || $forms === 'both' ) ) {
			if((isset($form_type_login) && $form_type_login == 'native') || !isset($form_type_login)){
		?>
			<div class="tab-pane" id="login-panel-<?php echo esc_attr( $rand ); ?>" role="tabpanel">
				<?php
				echo $ext->get_view( 'form-login', array(
					'rand'			 => $rand,
					'login_descr'	 => $login_descr,
					'redirect_to'	 => $redirect_to,
					'redirect'		 => $redirect,
					'forms'			 => $forms,
					'enable_captcha' => $enable_captcha,
					'captcha_site_key' => $captcha_site_key,
				) );
				?>
			</div>
		<?php
			}elseif($form_type_login != 'native' && isset($form_type_login)){
				?>
				<div class="tab-pane" id="login-panel-<?php echo esc_attr( $rand ); ?>" role="tabpanel">
					<?php
					if($form_type_login == 'youzer'){
						$login_shortcode = '[youzer_login]';
					}

					echo do_shortcode($login_shortcode);
					?>
				</div>
				<?php
			}
			}
		?>

		<?php
			if ( $can_register && ($forms === 'register' || $forms === 'both') ) {
			if((isset($form_type_register) && $form_type_register == 'native') || !isset($form_type_register)){
		?>
			<div class="tab-pane" id="register-panel-<?php echo esc_attr( $rand ); ?>" role="tabpanel">
				<?php
				echo $ext->get_view( 'form-register', array(
					'rand'			 => $rand,
					'login_descr'	 => $login_descr,
					'redirect_to'	 => $register_redirect_to,
					'redirect'		 => $register_redirect,
					'forms'			 => $forms,
					'enable_captcha' => $enable_captcha,
					'captcha_site_key' => $captcha_site_key,
					'register_fields_type' => $register_fields_type
				) );
				?>
			</div>
		<?php
			}elseif($form_type_register != 'native' && isset($form_type_register)){
				?>
				<div class="tab-pane" id="register-panel-<?php echo esc_attr( $rand ); ?>" role="tabpanel">
				<?php
				if($form_type_register == 'youzer'){
					$register_shortcode = '[youzer_register]';
				}

				echo do_shortcode($register_shortcode);
				?>
				</div>
				<?php
			}
			}
		?>
    </div>
</div>

<?php
if($enable_captcha){
    wp_enqueue_script( 'sign-form-google-recaptcha-key-v3', "https://www.google.com/recaptcha/api.js?render={$captcha_site_key}", array( 'jquery' ), false, false );
}
?>