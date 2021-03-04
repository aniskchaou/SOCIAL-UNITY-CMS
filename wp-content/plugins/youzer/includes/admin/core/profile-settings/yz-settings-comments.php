<?php

/**
 * # Comments Settings.
 */
function yz_comments_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Comments General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Comments Per Page', 'youzer' ),
            'id'    => 'yz_profile_comments_nbr',
            'desc'  => __( 'How many comments per page?', 'youzer' ),
            'type'  => 'number'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Comments Visibility Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Title', 'youzer' ),
            'id'    => 'yz_display_comment_username',
            'desc'  => __( 'Show comments title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Date', 'youzer' ),
            'id'    => 'yz_display_comment_date',
            'desc'  => __( 'Show comments date', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button', 'youzer' ),
            'id'    => 'yz_display_view_comment',
            'desc'  => __( 'Show "View Comment" button', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Comments Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Fullname', 'youzer' ),
            'id'    => 'yz_comment_author_color',
            'desc'  => __( 'Comments author color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Username', 'youzer' ),
            'id'    => 'yz_comment_username_color',
            'desc'  => __( 'Comments username color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Date', 'youzer' ),
            'id'    => 'yz_comment_date_color',
            'desc'  => __( 'Comments date color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Comments Text', 'youzer' ),
            'id'    => 'yz_comment_text_color',
            'desc'  => __( 'Comments text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Background', 'youzer' ),
            'id'    => 'yz_comment_button_bg_color',
            'desc'  => __( '"View Comment" background', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Text', 'youzer' ),
            'id'    => 'yz_comment_button_text_color',
            'desc'  => __( '"View Comment" text color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Button Icon', 'youzer' ),
            'id'    => 'yz_comment_button_icon_color',
            'desc'  => __( '"View Comment" icon color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}