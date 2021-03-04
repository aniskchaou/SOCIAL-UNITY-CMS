<?php

/**
 * # Register Public Scripts .
 */
function yz_public_scripts() {

    // Get Data.
    $jquery = array( 'jquery' );

    // Youzer Global Script
    wp_enqueue_script( 'youzer', YZ_PA . 'js/youzer.min.js', array( 'jquery', 'wp-i18n' ), YZ_Version, true );

    wp_enqueue_style( 'yz-opensans', 'https://fonts.googleapis.com/css?family=Open+Sans:400,600', array(), YZ_Version );

    // Youzer Css.
    wp_enqueue_style( 'youzer', YZ_PA . 'css/youzer.min.css', array(), YZ_Version );

    // Get Youzer Script Variables
    wp_localize_script( 'youzer', 'Youzer', youzer_scripts_vars() );

    // Wall Form Uploader CSS.
    wp_register_style( 'yz-bp-uploader', YZ_PA . 'css/yz-bp-uploader.min.css', array(), YZ_Version );

    // Headers Css
    wp_enqueue_style( 'yz-headers', YZ_PA . 'css/yz-headers.min.css', array(), YZ_Version );

    // Get Plugin Scheme.
    $youzer_scheme = yz_option( 'yz_profile_scheme', 'yz-blue-scheme' );

    // Profile Color Schemes Css.
    wp_enqueue_style( 'yz-scheme', YZ_PA . 'css/schemes/' . $youzer_scheme .'.min.css', array(), YZ_Version );

    $is_members_directory = bp_is_members_directory();
    $is_groups_directory = bp_is_groups_directory();

    // Member Pages CSS
    if ( ! $is_members_directory && ! $is_groups_directory  ) {
        wp_enqueue_style( 'yz-social', YZ_PA .'css/yz-social.min.css', array( 'dashicons' ), YZ_Version );
    }

    // Members & Groups Directories CSS
    if ( $is_members_directory || $is_groups_directory ) {

        wp_enqueue_script( 'masonry' );
        wp_enqueue_style( 'yz-directories', YZ_PA . 'css/yz-directories.min.css', array( 'dashicons' ), YZ_Version );
        wp_enqueue_script( 'yz-directories', YZ_PA .'js/yz-directories.min.js', $jquery, YZ_Version, true );

        if ( $is_members_directory ) {
            yz_custom_styling( 'members_directory');
        }

        if ( $is_groups_directory ) {
            yz_custom_styling( 'groups_directory');
        }

    }

    // if ( bp_current_component() ) {
    //     yz_common_scripts();
    // }


    if ( bp_is_messages_conversation() || bp_is_messages_compose_screen() ) {
        wp_enqueue_style( 'yz-messages', YZ_PA .'css/yz-messages.min.css', array(), YZ_Version );
        wp_enqueue_script( 'yz-messages', YZ_PA .'js/yz-messages.min.js', $jquery, YZ_Version, true );
        // wp_enqueue_script( 'yz-attachments', YZ_PA .'js/yz-attachments.min.js', $jquery, YZ_Version, true );
    }

    // Global Youzer JS
    wp_enqueue_style( 'yz-icons' );

    // Global Styling.
    yz_styling()->custom_styling( 'global' );

    // Global Custom Styling.
    yz_custom_styling( 'global' );

}

add_action( 'wp_enqueue_scripts', 'yz_public_scripts' );

/**
 * Common Scripts
 */
// function yz_common_scripts() {

//     // $jquery = ;

//     // Nice Selector
//     // wp_enqueue_script( 'yz-nice-selector', YZ_PA .'js/jquery.nice-select.min.js', array( 'jquery' ), YZ_Version, false );

// }

/**
 * Add Directory Custom CSS.
 */
function yz_custom_styling( $component ) {

    if ( 'off' == yz_option( 'yz_enable_' . $component . '_custom_styling', 'off' ) ) {
        return false;
    }

    // Get CSS Code.
    $custom_css = yz_option( 'yz_' . $component . '_custom_styling' );

    if ( empty( $custom_css ) ) {
        return false;
    }

    // Custom Styling File.
    wp_enqueue_style( 'youzer-customStyle', YZ_AA . 'css/custom-script.css' );

    wp_add_inline_style( 'youzer-customStyle', $custom_css );
}


/**
 * Profile Posts & Comments Pagination
 */
function yz_profile_posts_comments_pagination() {

    // Profile Ajax Pagination Script
    wp_enqueue_script( 'yz-pagination', YZ_PA . 'js/yz-pagination.min.js', array( 'jquery') , YZ_Version, true );

    wp_localize_script( 'yz-pagination', 'ajaxpagination',
        array(
            'ajaxurl'    => admin_url( 'admin-ajax.php' ),
            'query_vars' => json_encode( array( 'yz_user' => bp_displayed_user_id() ) )
        )
    );

}