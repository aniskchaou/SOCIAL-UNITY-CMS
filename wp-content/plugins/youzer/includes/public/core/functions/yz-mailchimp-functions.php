<?php

/**
 * Check Is Mail Chimp Enabled.
 */
function yz_is_mailchimp_active() {

    // Check if MailChimp Sync is Enabled.
    if ( yz_option( 'yz_enable_mailchimp', 'off' ) == 'off' ) {
        return false;
    }

    // Get Mailchimp API Key.
    if ( empty( yz_option( 'yz_mailchimp_api_key' ) ) ) {
        return false;
    }

    // Check Mailchimp List ID.
    if ( empty( yz_option( 'yz_mailchimp_list_id' ) ) ) {
        return false;
    }

    return true;
    
}

/**
 * Subscribe Registered User to MailChimp.
 */
function yz_subscribe_user_to_mailchimp( $user_id, $key, $user ) {
    
    // Check if Mail Chimp is active.
    if ( ! yz_is_mailchimp_active() ) {
        return false;
    }

    // Get User Infos.
    $user_info = get_userdata( $user_id );

    if ( ! is_object( $user_info ) ) {
        return false;
    }

    // Get User Data
    $user_data = array(
        'status'    => 'subscribed',
        'email'     => $user_info->user_email,
        'firstname' => $user_info->first_name,
        'lastname'  => $user_info->last_name
    );

    // Get List ID.
    $list_id = yz_option( 'yz_mailchimp_list_id' );

    // Add User To Mailchimp List.
    yz_syncMailchimp( $list_id, $user_data );

};

add_action( 'bp_core_activated_user', 'yz_subscribe_user_to_mailchimp', 10, 3 );

/**
 * Add User To Mailchimp List
 */
function yz_syncMailchimp( $list_id, $data ) {

    // Get API Key.
    $apiKey = yz_option( 'yz_mailchimp_api_key' );

    // Get Member ID.
    $memberId = md5( strtolower( $data['email'] ) );

    // Get Data Center
    $dataCenter = substr( $apiKey, strpos( $apiKey, '-' ) + 1 );

    // Get Api URL.
    $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $memberId;

    // Status Types : "subscribed","unsubscribed","cleaned","pending".

    // Get Json Code.
    $json = json_encode(
    	array(
	        'email_address' => $data['email'],
	        'status'        => $data['status'],
	        'merge_fields'  => array(
	            'FNAME'     => $data['firstname'],
	            'LNAME'     => $data['lastname']
        )
    ));

    $ch = curl_init( $url );

    // Set Options.
    curl_setopt( $ch, CURLOPT_USERPWD, 'user:' . $apiKey );
    curl_setopt( $ch, CURLOPT_HTTPHEADER, array( 'Content-Type: application/json' ) );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 10 );
    curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'PUT' );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $json );                                                                                                                 
    // Get Result.
    $result = curl_exec( $ch );
    
    // Get Response Code.
    $httpCode = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

    // End Request.
    curl_close( $ch );

    return $httpCode;

}
