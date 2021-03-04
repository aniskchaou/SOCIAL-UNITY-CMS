<?php

/**
 * Sync WP & BP Fields.
 */
function yz_sync_wp_and_bp_fields( $user_id ) {
	
	if ( ! bp_is_active( 'xprofile' ) ) {
		return;
	}

    // Get All fields.
    $fields = yz_get_youzer_xprofile_fields();

    // Sync Fields
    foreach ( $fields as $meta_key => $field_id ) {

        // Change User Website Meta Key.
        if ( $meta_key == 'user_url' ) {
            $meta_key = 'url';
        }

        if ( isset( $_POST[ $meta_key ] ) ) {
            xprofile_set_field_data( $field_id, $user_id, $_POST[ $meta_key ] );
        }

    }

}

add_action( 'personal_options_update', 'yz_sync_wp_and_bp_fields' );
add_action( 'edit_user_profile_update', 'yz_sync_wp_and_bp_fields' );