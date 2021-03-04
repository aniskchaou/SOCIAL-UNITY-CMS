<?php

$ext				 = fw_ext( 'sign-form' );
$builderComponent	 = $ext->get_config( 'builderComponent' );
$actions			 = $ext->get_config( 'actions' );


foreach ( $actions as $key => $action ) {
	add_action( "wp_ajax_{$action}", "FW_Extension_Sign_Form::{$key}" );
	add_action( "wp_ajax_nopriv_{$action}", "FW_Extension_Sign_Form::{$key}" );
}

add_filter( "vc_before_init", 'FW_Extension_Sign_Form::vc_mapping' );

add_filter( 'init', 'FW_Extension_Sign_Form::kc_mapping' );

add_filter( 'registration_errors', '_filter_fw_ext_sign_form_reg_errors', 10, 3 );

function _filter_fw_ext_sign_form_reg_errors( $errors, $sanitized_user_login, $user_email ) {

	$gdpr		 = filter_input( INPUT_POST, 'gdpr' );
	$first_name	 = trim( filter_input( INPUT_POST, 'first_name' ) );
	$last_name	 = trim( filter_input( INPUT_POST, 'last_name' ) );
	$privacy_policy_page_link = get_privacy_policy_url();

	if ( empty( $first_name ) ) {
		$errors->add( 'first_name_error', sprintf( '<strong>%s</strong>: %s', esc_html__( 'ERROR', 'crum-ext-sign-form' ), esc_html__( 'Please enter a first name.', 'crum-ext-sign-form' ) ) );
	}

	if ( empty( $last_name ) ) {
		$errors->add( 'last_name_error', sprintf( '<strong>%s</strong>: %s', esc_html__( 'ERROR', 'crum-ext-sign-form' ), esc_html__( 'Please enter a last name.', 'crum-ext-sign-form' ) ) );
	}

	if ( $gdpr !== 'on' && $privacy_policy_page_link ) {
		$errors->add( 'gdpr_error', sprintf( '<strong>%s</strong>: %s', esc_html__( 'ERROR', 'crum-ext-sign-form' ), esc_html__( 'GDPR is required.', 'crum-ext-sign-form' ) ) );
	}

	return $errors;
}

//Add options to settings page
// add_filter( 'fw_settings_options', '_filter_fw_ext_sign_form_settings', 999, 1 );

function _filter_fw_ext_sign_form_settings( $options ) {
	$ext = fw_ext( 'sign-form' );

	return array_merge( $options, $ext->get_options( 'settings' ) );
}

//Add options to customizer
add_filter( 'fw_customizer_options', '_filter_fw_ext_sign_form_customizer', 999, 1 );

function _filter_fw_ext_sign_form_customizer( $options ) {
	$ext = fw_ext( 'sign-form' );
	
    return array_merge( $options, $ext->get_options( 'customizer' ) );
}

add_action( 'after_setup_theme', '_action_fw_ext_sign_form_wpsignup_redirect', 999 );

function _action_fw_ext_sign_form_wpsignup_redirect() {
	$action	 = isset( $_REQUEST[ 'action' ] ) ? $_REQUEST[ 'action' ] : '';
	$type	 = isset( $_REQUEST[ 'type' ] ) ? $_REQUEST[ 'type' ] : '';

	if ( $action === 'register' && $type === 'internal' ) {
		remove_action( 'bp_init', 'bp_core_wpsignup_redirect' );
	}
}

add_action( 'after_setup_theme', '_action_fw_ext_sign_form_reg_nav_menus', 999 );

function _action_fw_ext_sign_form_reg_nav_menus() {
	$ext = fw_ext( 'sign-form' );

	register_nav_menus( [
		$ext->get_config( 'menuLocation' ) => esc_html__( 'User vCard menu', 'crum-ext-sign-form' ),
	] );
}

add_action( 'register_form', '_action_fw_ext_sign_form_add_type_field', 999 );

function _action_fw_ext_sign_form_add_type_field() {
	?>
	<input name="type" value="internal" type="hidden" />
	<?php
}

//
add_action( 'register_form', '_action_fw_ext_sign_form_add_reg_fields' );

function _action_fw_ext_sign_form_add_reg_fields() {
	$ext		 = fw_ext( 'sign-form' );
	$gdpr		 = filter_input( INPUT_POST, 'gdpr' );
	$first_name	 = filter_input( INPUT_POST, 'first_name' );
	$last_name	 = filter_input( INPUT_POST, 'last_name' );
	?>
	<p>
		<label for="first_name"><?php esc_html_e( 'First Name', 'crum-ext-sign-form' ) ?><br />
			<input type="text" name="first_name" class="input" value="<?php echo esc_attr( $first_name ); ?>" size="25" /></label>
	</p>
	<p>
		<label for="last_name"><?php esc_html_e( 'Last Name', 'crum-ext-sign-form' ) ?><br />
			<input type="text" name="last_name" class="input" value="<?php echo esc_attr( $last_name ); ?>" size="25" /></label>
	</p>
	<?php
	if( method_exists($ext, 'getPrivacyLink' ) ){
		$privacy_policy_html = $ext::getPrivacyLink();
		if($privacy_policy_html != ''){
		?>
			<p>
				<label for="gdpr">
					<input type="checkbox" name="gdpr" <?php echo ($gdpr === 'on') ? 'checked' : ''; ?> />
					<?php echo $ext::getPrivacyLink(); ?>
				</label>
				<br /><br />
			</p>
		<?php
		}
	}
}

add_action( 'user_register', '_action_fw_ext_sign_form_save_reg_fields' );

function _action_fw_ext_sign_form_save_reg_fields( $user_id ) {
	$first_name	 = filter_input( INPUT_POST, 'first_name' );
	$last_name	 = filter_input( INPUT_POST, 'last_name' );
	$gdpr		 = filter_input( INPUT_POST, 'gdpr' );

	if ( !empty( $first_name ) ) {
		update_user_meta( $user_id, 'first_name', $first_name );
	}
	if ( !empty( $last_name ) ) {
		update_user_meta( $user_id, 'last_name', $last_name );
	}
	if ( $gdpr === 'on' ) {
		update_user_meta( $user_id, 'gdpr', $gdpr );
	}
}

add_action( 'bp_after_register_page', '_action_fw_ext_sign_form_prefill_youzer_register_form' );

function _action_fw_ext_sign_form_prefill_youzer_register_form(){
	$ext = fw_ext( 'sign-form' );
	$register_fields_type = fw_get_db_customizer_option('sign-in-register-fields-type', 'simple');
	$bp_fields = array();
	if( method_exists($ext, 'getBPFields' ) ){
		$bp_fields = $ext::getBPFields();
	}

	if(isset($_GET['fw_ext_sign_form_prefill']) && $register_fields_type == 'extensional'){
		$user_login = (isset($_GET['user_login'])) ? $_GET['user_login'] : '';
		$user_email = (isset($_GET['user_email'])) ? $_GET['user_email'] : '';
		?>
		<script>
			jQuery( document ).ready( function($) {
				$('#signup_username').val('<?php echo $user_login; ?>');
				$('#signup_email').val('<?php echo $user_email; ?>');
				<?php
				if ( !empty($bp_fields) ) {
					foreach($bp_fields as $bp_field_key => $bp_field_value){
						$get_field_val = (isset($_GET[$bp_field_key])) ? $_GET[$bp_field_key] : '';
						$input_types = array('textbox', 'textarea', 'number', 'telephone', 'url', 'datebox', 'selectbox');
						if( in_array($bp_field_value['type'], $input_types) ){
						?>
						$('*[name="<?php echo $bp_field_key; ?>"]').val('<?php echo $get_field_val; ?>');
						<?php
						}elseif($bp_field_value['type'] == 'radio'){
						?>
						$('#<?php echo $bp_field_key; ?>').find('input[value="<?php echo $get_field_val; ?>"]').prop('checked', true);
						<?php
						}elseif($bp_field_value['type'] == 'checkbox'){
							$values_arr = explode('|', $get_field_val);
							if(!empty($values_arr)){
							foreach($values_arr as $values_arr_v){
								?>
								$('#<?php echo $bp_field_key; ?>').find('input[value="<?php echo $values_arr_v; ?>"]').prop('checked', true);
								<?php
							}
							}
						}elseif($bp_field_value['type'] == 'multiselectbox'){
							$values_arr = explode('|', $get_field_val);
							if(!empty($values_arr)){
							foreach($values_arr as $values_arr_v){
								?>
								$('select[name="<?php echo $bp_field_key; ?>[]"]').find('option[value="<?php echo $values_arr_v; ?>"]').attr("selected", "selected");
								<?php
							}
							}
						}
					}		
				}
				?>
			});
		</script>
		<?php
	}
}

add_action( 'bp_core_activated_user', '_action_fw_ext_sign_form_activated_user', 10, 3 );

function _action_fw_ext_sign_form_activated_user( $user_id, $key, $user ) {
	$ext = fw_ext( 'sign-form' );
	$register_fields_type = fw_get_db_customizer_option('sign-in-register-fields-type', 'simple');
	if($register_fields_type != 'extensional'){
		$bp_fields = array();
		if( method_exists($ext, 'getBPFields' ) ){
			$bp_fields = $ext::getBPFields();
		}
		if ( !empty($bp_fields) ) {
			foreach($bp_fields as $bp_field_key => $bp_field_value){
				if($bp_field_value['type'] != 'datebox'){
					$meta = (isset($user['meta']['fw_ext_sign_form_' . $bp_field_key])) ? $user['meta']['fw_ext_sign_form_' . $bp_field_key] : '';
					if(!is_array($meta)){
						$meta = wp_unslash($meta);
					}
					xprofile_set_field_data( $bp_field_value['id'], $user_id, $meta );
				}else{
					$meta = (isset($user['meta']['fw_ext_sign_form_' . $bp_field_value['id']])) ? date("Y-m-d 00:00:00", strtotime($user['meta']['fw_ext_sign_form_' . $bp_field_value['id']])) : '';
					xprofile_set_field_data( $bp_field_value['id'], $user_id, $meta );
				}
			}
		}

		$last_name = (isset($user['meta']['last_name'])) ? $user['meta']['last_name'] : '';
		$first_name = (isset($user['meta']['first_name'])) ? $user['meta']['first_name'] : '';
		$gdpr = (isset($user['meta']['gdpr'])) ? $user['meta']['gdpr'] : '';

		update_user_meta( $user_id, 'last_name', $last_name );
		update_user_meta( $user_id, 'first_name', $first_name );
		update_user_meta( $user_id, 'gdpr', $gdpr );
	}
}

// add_action('bp_xprofile_field_edit_html_elements','_action_fw_ext_sign_form_add_bp_form_classes');

function _action_fw_ext_sign_form_add_bp_form_classes($elements){
	if(isset($elements['class'])){
		$elements['class'] .= ' form-control fw-ext-sign-form-bp-field';
	}else{
		$elements['class'] = ' form-control fw-ext-sign-form-bp-field';
	}
    return $elements;
}

add_filter( 'wp_mail_from', '_action_fw_ext_sign_form_sender_email' );

function _action_fw_ext_sign_form_sender_email( $original_email_address ) {
	$mail = fw_get_db_customizer_option('sign-in-email-options-mail');
	$mail = filter_input( INPUT_POST, $mail, FILTER_VALIDATE_EMAIL );
	if($mail != ''){
		$original_email_address = $mail;
	}
    return $original_email_address;
}
 
add_filter( 'wp_mail_from_name', '_action_fw_ext_sign_form_sender_name' );

function _action_fw_ext_sign_form_sender_name( $original_email_from ) {
	$name = fw_get_db_customizer_option('sign-in-email-options-name');
	if($name != ''){
		$original_email_from = wp_unslash($name);
	}
    return $original_email_from;
}

add_action( 'bp_init', '_action_fw_ext_sign_form_activation_email' );
function _action_fw_ext_sign_form_activation_email(){
	$send_validation_email = fw_get_db_customizer_option('sign-in-register-activation-email', 'yes');
	if($send_validation_email == 'no'){
		add_filter( 'bp_core_signup_send_activation_key', '_action_fw_ext_sign_form_disable_activation_email' );
		add_filter( 'bp_registration_needs_activation', '_action_fw_ext_sign_form_fix_signup_form_validation_text' );

		if( isset($_GET['page']) && 'bp-disable-activation-reloaded' == $_GET['page'] && !empty( $_GET['fix_pending_users'] ) ) {
			global $wpdb;

			$users = $wpdb->get_results( "SELECT activation_key, user_login FROM {$wpdb->prefix}signups WHERE active = '0' ");

			foreach ($users as $user) {
				bp_core_activate_signup($user->activation_key);
				BP_Signup::validate($user->activation_key);

				//fix roles
				$user_id = $wpdb->get_var( "SELECT ID FROM $wpdb->users WHERE user_login = '$user->user_login'");

				$u = new WP_User( $user_id );
				$u->add_role( 'subscriber' );
			}
		}
	}
}

function _action_fw_ext_sign_form_disable_activation_email(){
	return false;
}
function _action_fw_ext_sign_form_fix_signup_form_validation_text(){
	return false;
}

add_action( 'bp_core_signup_user' ,'_action_fw_ext_sign_form_disable_validation' );
function _action_fw_ext_sign_form_disable_validation($user_id){
	$send_validation_email = fw_get_db_customizer_option('sign-in-register-activation-email', 'yes');
	if($send_validation_email == 'no'){
		global $wpdb;
		$activation_key = get_user_meta($user_id, 'activation_key', true);
		$activate = apply_filters('bp_core_activate_account', bp_core_activate_signup($activation_key));
		BP_Signup::validate($activation_key);
		$wpdb->query( $wpdb->prepare( "UPDATE $wpdb->users SET user_status = 0 WHERE ID = %d", $user_id ) );
			
		//Send email to admin
		wp_new_user_notification( $user_id );
		// Remove the activation key meta
		delete_user_meta( $user_id, 'activation_key' );
		// Delete the total member cache
		wp_cache_delete( 'bp_total_member_count', 'bp' );
		//Automatically log the user in	.
		$user_info = get_userdata($user_id);
		wp_set_auth_cookie($user_id);
		do_action('wp_signon', $user_info->user_login);
	}
}