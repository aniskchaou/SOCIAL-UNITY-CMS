<?php

/**
 * Get Send Message Button Url.
 */
function yz_get_send_private_message_url( $user_id = false ) {

    if ( ! is_user_logged_in() ) {
        return false;
    }

    return apply_filters(
        'yz_get_send_private_message_url',
        wp_nonce_url( bp_loggedin_user_domain() . bp_get_messages_slug() . '/compose/?r=' . bp_core_get_username( $user_id ) )
    );
}

/**
 * Get Send Message Button
 */
function yz_get_send_private_message_button( $user_id = false, $title = null ) {

    // Get The User Id To Whom We Are Sending The Message
    $user_id = $user_id ? $user_id : yz_get_context_user_id();

    // Don't show the button if the user id is not present or the user id is same as logged in user id
    if ( ! $user_id || $user_id == bp_loggedin_user_id() ) {
        return;
    }

    $title = ! empty( $title ) ? $title : __( 'Message', 'youzer' );

    $defaults = array(
        'block_self'        => true,
        'must_be_logged_in' => true,
        'link_text'         => $title,
        'component'         => 'messages',
        'wrapper_class'     =>'message-button',
        'link_class'        => 'yz-send-message',
        'id'                => 'private_message-'.$user_id,
        'wrapper_id'        => 'send-private-message-'.$user_id,
        'link_href'         => yz_get_send_private_message_url( $user_id ),
        'link_title'        => __( 'Send a private message to this user.', 'youzer' ),
    );

    // Get Button Html Code.
    return apply_filters( 'yz_get_send_private_message_button', bp_get_button( apply_filters ( 'yz_get_send_message_button', $defaults ) ), $user_id );
}

/**
 * Print Send Message Code.
 */
function yz_send_private_message_button( $user_id = false, $title = null ) {
    if ( ! bp_is_active( 'messages' ) ) {
        return false;
    }
    echo yz_get_send_private_message_button( $user_id, $title );
}

add_action( 'bp_directory_members_actions', 'yz_send_private_message_button', 30 );
add_action( 'bp_group_members_list_item_action', 'yz_send_private_message_button', 30 );

/**
 * Notices Action Activate/Deactivate
 */
function yz_get_message_activate_deactivate_text() {
    global $messages_template;

    if ( 1 === (int) $messages_template->thread->is_active  ) {
        $text = '<span class="dashicons dashicons-hidden deactivate-notice"></span>';
    } else {
        $text = '<span class="dashicons dashicons-visibility activate-notice"></span>';
    }

    return $text;
}


/**
 * Get Message Recipients Avatar.
 */
function yz_get_thread_recipients( $thread_id = 0 ) {

    // Init Vars
    $recipients = BP_Messages_Thread::get_recipients_for_thread( $thread_id );
    $more_recipients = count( $recipients ) - 3;

    foreach ( $recipients as $recipient ) {

        // Get User ID.
        $user_id = $recipient->user_id;

        // Hide Deleted Users.
        if ( ! yz_is_user_exist( $user_id ) ) {
            continue;
        }

        // Get User Avatar.
        $user_avatar =  bp_core_fetch_avatar(
            array( 'item_id' => $user_id, 'type' => 'thumb', 'width' => 35, 'height' => 35 )
        );

        // Get User Profile Url.
        $profile_url = bp_core_get_user_domain( $user_id );

        // Get User Username.
        $username = bp_core_get_user_displayname( $user_id );

        // Print Avatar.
        echo '<a class="tooltip" data-yztooltip="' . $username . '" href="' . $profile_url . '">' . $user_avatar . '</a>';
    }

    if ( $more_recipients > 3 ) {
        // Get Thread Url.
        $thread_url = bp_get_message_thread_view_link( $thread_id, bp_displayed_user_id() );

        // Print View More Button.
        echo '<a href="' . $thread_url . '" class="yz-more-recipients">+' . $more_recipients . '</a>';
    }

}

/**
 * Edit Notifications Delete Button.
 */
function yz_edit_notification_delete_button( $retval, $user_id = 0 ) {
    // New Delete Link.
    return sprintf(
        '<a href="%1$s" class="delete secondary confirm">%2$s</a>',
        esc_url( bp_get_the_notification_delete_url( $user_id ) ),
        '<span class="dashicons dashicons-trash"></span>'
    );
}

add_filter( 'bp_get_the_notification_delete_link' , 'yz_edit_notification_delete_button' );

/**
 * Get Notifications Read Url.
 */
function yz_edit_notification_read_button( $retval, $user_id = 0 ) {
    // New Read Link.
    return sprintf(
        '<a href="%1$s" data-yztooltip="%2$s" class="mark-read primary">%3$s</a>',
        esc_url( bp_get_the_notification_mark_read_url( $user_id ) ),
        __( 'Mark as Read', 'youzer' ),
        '<span class="dashicons dashicons-visibility"></span>'
    );
}

add_filter( 'bp_get_the_notification_mark_read_link' , 'yz_edit_notification_read_button' );

/**
 * Get Notifications UnRead Url.
 */
function yz_edit_notification_unread_button( $retval, $user_id = 0 ) {
    // Get Unread Link.
    return sprintf(
        '<a href="%1$s" data-yztooltip="%2$s" class="mark-unread primary">%3$s</a>',
        esc_url( bp_get_the_notification_mark_unread_url( $user_id ) ),
        __( 'Mark as Unread', 'youzer' ),
        '<span class="dashicons dashicons-hidden"></span>'
    );
}

add_filter( 'bp_get_the_notification_mark_unread_link' , 'yz_edit_notification_unread_button' );

/**
 * Get the User Id in the current context
 */
function yz_get_context_user_id( $user_id = false ) {

    if ( ! is_user_logged_in() ) {
        return false;
    }

    if ( ! $user_id ) {

        // For members loop.
        $user_id = bp_get_member_user_id();

        // For user profile.
        if ( bp_is_user() ) {
            $user_id = bp_displayed_user_id();
        }

    }

    return apply_filters( 'yz_get_context_user_id', $user_id );

}

/**
 * Get Activity Attachments.
 */
function yz_get_message_attachments( $message_id = null, $field = 'media_id' ) {

    if ( empty( $message_id ) ) {
        return;
    }

    global $wpdb, $Yz_media_table;

    // Prepare Sql
    $sql = $wpdb->prepare( "SELECT $field FROM $Yz_media_table WHERE item_id = %d AND component = 'message'", $message_id );

    $result = $wpdb->get_row( $sql , ARRAY_A );

    if ( ! empty( $result ) ) {
        $result =  maybe_unserialize( $result[ $field ] );
    }

    return $result;

}

/**
 * Allow Empty Messages That contains Attachments.
 */
function yz_allow_messages_without_content( $content ) {
    return str_replace( '{{{yz_message_attachment}}}', '', $content );
}

add_filter( 'messages_message_content_before_save', 'yz_allow_messages_without_content' );

/**
 * Get Message Attachment.
 */
function yz_add_message_attachments( $content ) {

    $message_id = bp_get_the_thread_message_id();

    $attachments = bp_messages_get_meta( $message_id, 'yz_attachments' );

    if ( empty( $attachments ) ) {
        return $content;
    }
    foreach ( $attachments as $media_id => $data ) {

    $attachment_url = wp_get_attachment_url( $media_id );

    // Get File Type.
    switch ( yz_get_file_type( $attachment_url ) ) {

        case 'image':
            $attachment = '<a href="' .  $attachment_url .'" rel="nofollow" data-lightbox="yz-post-'. $message_id . '"><img loading="lazy" ' . yz_get_image_attributes( $media_id, 'youzify-message', 'message' ) . ' alt=""></a>';
            break;

        case 'audio':
            $attachment = '<audio controls><source src="' . $attachment_url . '" type="audio/mpeg">' . __( 'Your browser does not support the audio element.', 'youzer' ) . '</audio>';
            break;

        case 'video':
            $attachment = '<video width="100%" controls preload="metadata"><source src="' . $attachment_url . '" type="video/mp4">' . __( 'Your browser does not support the video tag.', 'youzer' ) . '</video>';
            break;

        case 'file':

            $attachment = '<a class="yz-message-file" rel="nofollow" href="' . $attachment_url .'"><span class="yz-file-icon"><i class="fas fa-download yz-attachment-file-icon"></i></span><span class="yzw-file-details"><span class="yzw-file-title" title="'. $data['real_name']. '">' . yz_get_filename_excerpt( $data['real_name'], 45 ) . '</span><span class="yzw-file-size">' . yz_file_format_size( $data['file_size'] ) . '</span></span></a>';
            break;

        default:
            $attachment = '';
            break;
    }

    }

    return $content . '<div class="yz-message-attachment">' . $attachment . '</div>';
}

add_filter( 'bp_get_the_thread_message_content', 'yz_add_message_attachments' );