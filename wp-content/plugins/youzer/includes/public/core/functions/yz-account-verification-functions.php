<?php

/**
 * Check if Account Verification Enabled.
 */
function yz_is_account_verification_enabled() {
	return 'on' == yz_option( 'yz_enable_account_verification', 'on' ) ? true : false;
}

/**
 * Check is User Can Verify Account.
 */
function yz_is_user_can_verify_accounts() {

	if ( ! is_user_logged_in() || ! yz_is_account_verification_enabled() ) {
		return false;
	}

	// Get Current User Data.
	$user = wp_get_current_user();

	// Allowed Verifiers Roles
	$allowed_roles = array( 'administrator' );

	// Filter Allowed Roles.
	$allowed_roles = apply_filters( 'yz_allowed_roles_to_verify_accounts', $allowed_roles );

	foreach ( $allowed_roles as $role ) {
		if ( in_array( $role, (array) $user->roles ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Check is User Account Verified.
 */
function yz_is_user_account_verified( $user_id ) {

	// Check if verification is enabled.
	if ( ! yz_is_account_verification_enabled() ) {
		return false;
	}

	$status = false;

	if ( 'on' == get_user_meta( $user_id, 'yz_account_verified', true ) ) {
		$status = true;
	}

	return apply_filters( 'yz_is_user_account_verified', $status, $user_id );
}

/**
 * Get User Tools
 */
function yz_get_user_tools( $user_id = null, $icons = null ) {

	if ( ! $user_id ) {
		return false;
	}

	$icons = ! empty( $icons ) ? $icons : 'only-icons';

	?>

	<div class="yz-tools yz-user-tools yz-tools-<?php echo $icons; ?>" data-nonce="<?php echo wp_create_nonce( 'yz-tools-nonce-' . $user_id ); ?>" data-user-id="<?php echo $user_id; ?>" data-component="profile">
		<?php do_action( 'yz_user_tools', $user_id, $icons ); ?>
	</div>

	<?php
}

/**
 * Get Verification User Tool.
 */
function yz_get_user_verification_tool( $user_id = null, $icons = null ) {

	if ( ! yz_is_user_can_verify_accounts() ) {
		return false;
	}

	// Get User Verification Value.
	$is_account_verified = yz_is_user_account_verified( $user_id );

	// Get Button Title.
	$button_title = $is_account_verified ? __( 'Unverify Account', 'youzer' ) : __( 'Verify Account', 'youzer' );

	?>

	<div class="yz-tool-btn yz-verify-btn" <?php if ( 'only-icons' == $icons ) { ?> data-yztooltip="<?php echo $button_title; ?>"<?php } ?> data-nonce="<?php echo wp_create_nonce( 'yz-account-verification-' . $user_id ); ?>" data-action="<?php echo $is_account_verified ? 'unverify' : 'verify'; ?>" data-user-id="<?php echo $user_id; ?>"><div class="yz-tool-icon"><i class="<?php echo $is_account_verified ? 'fas fa-times' : 'fas fa-check'; ?>"></i></div><?php if ( 'full-btns' == $icons ) : ?><div class="yz-tool-name"><?php echo $button_title; ?></div><?php endif; ?>
	</div>

	<?php
}

add_action( 'yz_user_tools', 'yz_get_user_verification_tool', 10, 2 );

/**
 * Add Profile header Tools
 */
function yz_get_profile_header_tools() {

	// Get Profile Layout.
	$profile_layout = yz_get_profile_layout();

	if ( 'yz-vertical-layout' == $profile_layout ) {
		return false;
	}

	// Get Displayed User ID
	$user_id = bp_displayed_user_id();

	if ( ! $user_id ) {
		return false;
	}

	yz_get_user_tools( $user_id, 'full-btns' );
}

add_action( 'youzer_profile_header', 'yz_get_profile_header_tools' );

/**
 * Get User Verification Icon.
 */
function yz_the_user_verification_icon( $user_id = null, $size = null ) {
	echo yz_get_user_verification_icon( $user_id, $size );
}

/**
 * Get User Verification Icon.
 */
function yz_get_user_verification_icon( $user_id = null, $size = null ) {

	// Check if verification is enabled.
	if ( ! yz_is_account_verification_enabled() ) {
		return false;
	}

	// Get User Verification Status
	$is_account_verified = yz_is_user_account_verified( $user_id );

	if ( ! $is_account_verified ) {
		return false;
	}

	// Get Icon.
	$icon = yz_account_verified_button( $size );

	return $icon;
}

/**
 * Verified Account Button.
 */
function yz_account_verified_button( $size = null ) {

	// Get Icon Size.
	$size = ! empty( $size ) ? $size : 'small';

	// Icon Class.
	$class = array();

	// Add Icon Class.
	$class[] = 'fas fa-check yz-account-verified';

	// Add Icon size.
	$class[] = "yz-$size-verified-icon";

	// Get Full Class
	$classes = yz_generate_class( $class );

	return "<i class='$classes'></i>";
}

/**
 * Add Verification icon to profile username
 */
function yz_add_username_verification_icon( $username ) {

	// Get Displayes User Profile ID.
	$user_id = bp_displayed_user_id();

	// Get User Verification Status
	$is_account_verified = yz_is_user_account_verified( $user_id );

	if ( ! $is_account_verified ) {
		return $username;
	}

	// Verified Icon.
	$icon = yz_account_verified_button( 'big' );

	return $username . $icon;
}

add_filter( 'yz_user_profile_username', 'yz_add_username_verification_icon' );

/**
 * Add verification icon after username link
 */
function yz_add_user_link_verification_icon( $html, $user_id ) {

	// Get User Verification Status
	if ( ! yz_is_user_account_verified( $user_id ) ) {
		return $html;
	}

	// Return;
	return '<a href="' . bp_core_get_user_domain( $user_id ) . '">' . bp_core_get_user_displayname( $user_id ) . yz_account_verified_button() . '</a>' ;
}

add_filter( 'bp_core_get_userlink', 'yz_add_user_link_verification_icon', 10, 2 );

/**
 * Add verification icon after member name
 */
function yz_add_member_name_verification_icon( $member_name ) {

	global $members_template, $activities_template;

	// Get User Id.
	if ( isset( $members_template->member->id ) ) {
		$user_id = $members_template->member->id;
	} elseif ( isset( $activities_template->activity->current_comment->user_id ) ) {
		$user_id = $activities_template->activity->current_comment->user_id;
	} else {
		$user_id = false;
	}

	// Get User Verification Status
	$is_account_verified = yz_is_user_account_verified( $user_id );

	if ( ! $is_account_verified ) {
		return $member_name;
	}

	// Verified Icon.
	$verified_icon = yz_account_verified_button();

	// Return Name With Icon;
	return $member_name . $verified_icon;
}

add_filter( 'bp_member_name', 'yz_add_member_name_verification_icon', 99 );
add_filter( 'bp_activity_comment_name', 'yz_add_member_name_verification_icon', 99 );

/**
 * Get Verified Account.
 */
function yz_get_verified_users( $args = null ) {

	global $wpdb, $Yz_reviews_table;

	// Get Verification Value.
	$verified = isset( $args['verified'] ) && $args['verified'] == true ? 'on' : 'off';

	// Request.
	$request = "SELECT user_id FROM {$wpdb->usermeta} WHERE meta_key = 'yz_account_verified' AND meta_value = '$verified'";

	$order_by = isset( $args['order_by'] ) && $args['order_by'] == 'random' ? 'RAND()' : $args['order_by'];

	if ( isset( $args['order_by'] ) ) {
		$request .= " ORDER BY $order_by";
	}

	if ( isset( $args['limit'] ) ) {
		$request .= " LIMIT {$args['limit']}";
	}

	// Get Result
	$users = $wpdb->get_col( $request );

	return apply_filters( 'yz_get_verified_users', $users );

}

/**
 * Verified Users shortcode
 */
function yz_verified_users_shortcode( $atts = null ) {

	// Get Args.
	$args = shortcode_atts(
		array(
			'limit' => 5,
			'order_by' => 'user_id',
			'verified' => true,
		), $atts, 'yz_verified_users' );

	// Get Users List.
	$verified_users = yz_get_verified_users( $args );

	ob_start();

	// Get List.
	yz_get_users_list( $verified_users, $args );

    return ob_get_clean();
}

add_shortcode( 'youzer_verified_users', 'yz_verified_users_shortcode' );