( function( $ ) {

  jQuery( document).ready(function(){

    // Add Notification To the End of Page Body.
    $( 'body' ).append(
        '<div id="yz-live-notifications" class="yz-notif-icons-colorful yz-notif-icons-radius"></div><div id="yz-notifications-sound"></div>'
    );

    // Init Last Notification Vars.
    var last_notification = Youzer.last_notification;

    /**
     * Set Heartbeat Speed to Fast = 5 Seconds
     */
    wp.heartbeat.interval( Youzer.notifications_interval );

    /**
     * Proccess Received Notifications.
     */
    $( document ).on( 'heartbeat-tick.yz-notification-data', function( event, data ) {

        if ( data.hasOwnProperty( 'yz-notification-data' ) ) {

            // Get Notifications Data.
            var yz_notification_data = data['yz-notification-data'];

            // Update Last Notifications.
            last_notification = yz_notification_data.last_notification;

            var notifications = yz_notification_data.notifications;

            if ( notifications == undefined || notifications.length == 0 ) {
                return ;
            }

            $.each( notifications, function( index, notification ) {

                // Check if The Notification Is Already Added.
                if ( $( '#yz-live-notifications #' + $(  notification ).attr( 'id' ) )[0] ) {
                    return;
                }

                // Play Notification Sound.
                $.yzln_playSound();

                // Append The New Notification.
                $( notification ).appendTo( '#yz-live-notifications' ).delay( Youzer.timeout * 1000 ).queue( function() { $( this ).remove(); } );

            });

        }

    });

    /**
     * Send Last Notification ID.
     */
    $( document ).on( 'heartbeat-send', function( e, data ) {
        data['yz-notification-data'] = { last_notification: last_notification };
    });

    /**
     * Delete Notification.
     */
    $( '#yz-live-notifications' ).on( 'click', '.yz-delete-notification', function( e ) {
        e.preventDefault();
        $( this ).parent().remove();
    });

    /**
     * Notification Sound Function.
     */
    $.yzln_playSound = function() {

        if ( $( '#yz-notifications-sound audio' )[0] ) {
            $( '#yz-notifications-sound audio' ).trigger( 'play' );
            return;
        }
        var mp3Source = '<source src="' + Youzer.sound_file + '.mp3" type="audio/mpeg">';
        console.log( mp3Source );
        var oggSource = '<source src="' + Youzer.sound_file + '.ogg" type="audio/ogg">';
        var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + Youzer.sound_file +'.mp3">';
        $( '#yz-notifications-sound' ).append( '<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>' ).trigger( 'play' );
    }

});

})( jQuery );