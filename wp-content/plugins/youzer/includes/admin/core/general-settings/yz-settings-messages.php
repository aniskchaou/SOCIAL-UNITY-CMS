<?php

/**
 * # Message Settings.
 */
function yz_messages_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Attachments Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'yz_messages_attachments',
            'title' => __( 'Messages Attachments', 'youzer' ),
            'desc'  => __( 'Enable attachments', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'yz_messages_attachments_extensions',
            'title' => __( 'Allowed Extensions', 'youzer' ),
            'desc'  => __( 'Allowed extensions list', 'youzer' ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'yz_messages_attachments_max_size',
            'title' => __( 'Max File Size', 'youzer' ),
            'desc'  => __( 'Attachment max size by megabytes', 'youzer' ),
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}