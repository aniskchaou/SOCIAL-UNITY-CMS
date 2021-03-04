<?php

/**
 * Get Heartbear Procees.
 */
add_filter( 'heartbeat_received', 'yz_process_notification_request', 10, 3 );

function yz_process_notification_request( $response, $data, $screen_id ) {


    if ( isset( $data['yz-notification-data'] ) ) {

        $notifications    = array();
        $notification_ids = array();

        $request = $data['yz-notification-data'];

        $last_notification_id = absint( $request['last_notification'] );

        if ( ! empty( $request ) ) {

            $new_notifications = yz_get_new_notifications( get_current_user_id(), $last_notification_id );

            $notification_ids = wp_list_pluck( $new_notifications, 'id' );

            foreach ( $new_notifications as $new_notification ) {
                $notifications[] = yz_get_the_notification_description( $new_notification );
            }

        }

        // Add Last Notification ID.
        $notification_ids[] = $last_notification_id;

        $response['yz-notification-data'] = array( 'notifications' => $notifications, 'last_notification' => max( $notification_ids ) );

    }

    return $response;

}

/**
 * Get New Notification.
 */
function yz_get_new_notifications( $user_id, $last_notification_id ) {

    global $wpdb, $bp;

    $query = $wpdb->prepare( "SELECT * FROM {$bp->notifications->table_name} WHERE user_id = %d AND id > %d AND is_new = %d", $user_id, $last_notification_id, 1 );

    return $wpdb->get_results( $query );
}

/**
 * Get The Last Notification ID of The User.
 */
function yz_get_latest_notification_id( $user_id = false ) {

    // Get User ID.
    $user_id = ! empty( $user_id ) ? $user_id : get_current_user_id();

    global $wpdb, $bp;

    $query = $wpdb->prepare( "SELECT MAX(id) FROM {$bp->notifications->table_name} WHERE user_id = %d AND is_new = %d ", $user_id, 1 );

    return (int) $wpdb->get_var( $query );

}

/**
 * Get Notification Description.
 */
function yz_get_the_notification_description( $notification ) {

    $bp = buddypress();

    // Callback function exists
    if ( isset( $bp->{$notification->component_name}->notification_callback ) && is_callable( $bp->{$notification->component_name}->notification_callback ) ) {
        $description = call_user_func( $bp->{$notification->component_name}->notification_callback, $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 1 );
    } else {

        $description = apply_filters_ref_array( 'bp_notifications_get_notifications_for_user', array( $notification->component_action, $notification->item_id, $notification->secondary_item_id, 1, 1 ) );
    }

    $notification->href = $description['link'];
    $notification->content = yz_format_notification( $description['text'], $notification );

    ob_start();

    ?>

    <a id="yz-notif-<?php echo$notification->id;?>" href="<?php echo $notification->href; ?>" class="yz-notif-item yz-notif-<?php echo $notification->component_action; ?>">
            <div class="yz-notif-icon"><?php echo bp_core_fetch_avatar( array( 'item_id' => $notification->secondary_item_id, 'type' => 'thumb', 'width' => 50, 'height' => 50 ) ) . yz_get_notification_icon( $notification ); ?></div>
            <div class="yz-notif-content">
                <div class="yz-notif-desc"><?php echo $notification->content; ?></div>
                <span class="yz-notif-time"><i class="far fa-clock"></i><?php echo bp_core_time_since( $notification->date_notified ); ?></span>
            </div>
            <i class="fas fa-times yz-delete-notification"></i>
    </a>

    <?php

    $content = ob_get_contents();

    ob_end_clean();

    return apply_filters( 'yz_get_the_live_notification_content', $content );
}

/**
 * Format Notification Action
 */

add_filter( 'bp_get_the_notification_description', 'yz_format_notification', 10, 2 );

function yz_format_notification( $description, $notification ) {

    $display_name = bp_core_get_user_displayname( $notification->secondary_item_id );

    // Add Name Tags.
    $description = str_replace( $display_name, '<span class="display-name">' . $display_name . '</span>', $description );

    return $description;
}

/**
 * Scripts & Styles.
 */

add_filter( 'youzer_scripts_vars', 'yz_notification_localization' );

function yz_notification_localization( $data ) {

    // Set Options.
    $data['live_notifications'] = 'on';
    $data['notifications_interval'] = yz_option( 'yz_live_notifications_interval', 30 );
    $data['timeout'] = 10;
    $data['last_notification'] = yz_get_latest_notification_id();
    $data['sound_file'] = YZ_PA . 'notification_sound';

    return $data;
}


/**
 * Call Heartbeat
 */
function yz_call_heartbeat() {

    // Call Heartbeat Script.
    wp_enqueue_script( 'heartbeat' );

}

add_action( 'wp_enqueue_scripts', 'yz_call_heartbeat' );