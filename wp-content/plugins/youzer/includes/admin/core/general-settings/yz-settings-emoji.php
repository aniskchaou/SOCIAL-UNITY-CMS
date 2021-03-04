<?php

/**
 * # Emoji Settings.
 */
function yz_emoji_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Posts Emoji', 'youzer' ),
            'desc'  => __( 'Enable emoji in posts', 'youzer' ),
            'id'    => 'yz_enable_posts_emoji',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Comments Emoji', 'youzer' ),
            'desc'  => __( 'Enable emoji in comments', 'youzer' ),
            'id'    => 'yz_enable_comments_emoji',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Messages Emoji', 'youzer' ),
            'desc'  => __( 'Enable emoji in messages', 'youzer' ),
            'id'    => 'yz_enable_messages_emoji',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}