<?php

/**
 * Check is Youzer Account Page.
 */
function yz_is_account_page() {

    if ( bp_is_current_component( 'profile' ) || bp_is_current_component( 'settings' ) || bp_is_current_component( 'widgets' ) ) {
        return true;
    }

    return false;
}

/**
 * Get All Fields.
 */
function yz_get_all_profile_fields() {

    // Merge All Fields
    $all_fields = yz_array_merge( yz_get_bp_profile_fields(), yz_get_youzer_profile_fields() );

    return apply_filters( 'yz_get_all_profile_fields', $all_fields );

}

/**
 * Get Youzer Fields
 */
function yz_get_youzer_profile_fields() {

    // Init Data
    $fields = array(
        array(
            'id'   => 'full_location',
            'name' => __( 'Country, City', 'youzer' ),
        )
    );

    // Filter
    return apply_filters( 'yz_get_youzer_profile_fields', $fields );
}

/**
 * Get Youzer Xprofile Fields
 */
function yz_get_youzer_xprofile_fields() {

    // Get Profile Fields.
    $profile_fields = yz_option( 'yz_xprofile_contact_info_group_ids' );
    $contact_fields = yz_option( 'yz_xprofile_profile_info_group_ids' );

    $all_fields = (array) $contact_fields + (array) $profile_fields;

    if ( isset( $all_fields['group_id'] ) ) {
        unset( $all_fields['group_id'] );
    }

    return apply_filters( 'yz_get_youzer_xprofile_fields', $all_fields );
}

/**
 * Get Youzer Xprofile Field Value
 */
function yz_get_xprofile_field_value( $field_name, $user_id = null ) {

    // Field Value
    $field_value = null;

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get Field ID.
    $field_id = yz_get_xprofile_field_id( $field_name );

    if ( ! empty( $field_id ) ) {
        $field_value = yz_get_user_field_data( $field_id, $user_id );
    }

    return apply_filters( 'yz_get_xprofile_field_value' , $field_value, $field_id, $user_id );

}


/**
 * Get Youzer Xprofile Field
 */
function yz_get_xprofile_field_id( $field_name ) {

    // Get Field ID.
    $field_id = null;

    // Get Profile Fields.
    $fields = yz_get_youzer_xprofile_fields();

    if ( isset( $fields[ $field_name ] ) ) {
        $field_id = $fields[ $field_name ];
    }

    return apply_filters( 'yz_get_xprofile_field_id' , $field_id, $field_name );

}

/**
 * Get User Statistics Details.
 */
function yz_get_user_statistics_details() {

    $statistics = array(
        'posts'     => __( 'Posts', 'youzer' ),
        'comments'  => __( 'Comments', 'youzer' ),
        'views'     => __( 'Views', 'youzer' ),
        'ratings'   => __( 'Ratings', 'youzer' ),
        'followers' => __( 'Followers', 'youzer' ),
        'following' => __( 'Following', 'youzer' ),
        'points'    => __( 'Points', 'youzer' )
    );

    return apply_filters( 'yz_get_user_statistics_details', $statistics );

}

/**
 * Sync WP & BP Fields.
 */
function yz_sync_bp_and_wp_fields( $field_id, $value ) {

    // Get User ID
    $user_id = bp_displayed_user_id();

    // Sync Fields
    $fields = yz_get_youzer_xprofile_fields();

    // Get Field Key.
    $field_key = array_search( $field_id, $fields, true );

    if ( ! empty( $field_key ) ) {
        wp_update_user( array( 'ID' => $user_id, $field_key => $value ) );
    }

}

add_action( 'xprofile_profile_field_data_updated', 'yz_sync_bp_and_wp_fields', 10, 2 );


/**
 * Get Settings Url.
 */
// function yz_get_settings_url( $slug = false, $user_id = null ) {

//     if ( ! bp_is_active( 'settings' ) ) {
//         return false;
//     }

//     // Get User ID.
//     $user_id =! empty( $user_id ) ? $user_id :  bp_displayed_user_id();

//     // Get User Settings Page Url.
//     $url = bp_core_get_user_domain( $user_id ) . bp_get_settings_slug() . '/';

//     if ( $slug ) {
//         $url = $url . $slug;
//     }

//     return $url;
// }

/**
 * Get Profile Url.
 */
function yz_get_profile_settings_url( $slug = false, $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Profile Settings Page Url.
    $url = bp_core_get_user_domain( $user_id ) . bp_get_profile_slug() . '/';

    if ( ! empty( $slug ) ) {
        $url = $url . $slug;
    } else {
        $url .= apply_filters( 'yz_profile_settings_default_tab', 'edit/group/1' );
    }

    return $url;
}

/**
 * Get Widgets Settings Url.
 */
function yz_get_widgets_settings_url( $slug = false, $user_id = null ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();

    // Get User Widgets Settings Page Url.
    $url = bp_core_get_user_domain( $user_id ) . 'widgets/';

    if ( $slug ) {
        $url = $url . $slug;
    }

    return $url;
}