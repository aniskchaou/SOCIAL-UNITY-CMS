<?php


/**
 * Add Links to the Admin Bar
 */
function yz_admin_bar_pages( $wp_admin_bar ) {
    
    if ( ! is_user_logged_in() ) {
        return false;
    }

    // Add 'Youzer Panel' Bar Link.
    if ( is_super_admin() ) {
        
        $youzer_panel = array(
            'parent' => 'appearance',
            'id'     => 'youzer-panel',
            'title'  =>  __( 'Youzer Panel', 'youzer' ),
            'href'   => admin_url( 'admin.php?page=youzer-panel' ),
        );

        $general_settings = array(
            'parent' => 'youzer-panel',
            'id'     => 'yz-general-settings',
            'title'  =>  __( 'General Settings', 'youzer' ),
            'href'   => admin_url( 'admin.php?page=youzer-panel' ),
        );

        $profile_settings = array(
            'parent' => 'youzer-panel',
            'id'     => 'yz-profile-settings',
            'title'  =>  __( 'Profile Settings', 'youzer' ),
            'href'   => admin_url( 'admin.php?page=yz-profile-settings' ),
        );

        $widgets_settings = array(
            'parent' => 'youzer-panel',
            'id'     => 'yz-widgets-settings',
            'title'  =>  __( 'Widgets Settings', 'youzer' ),
            'href'   => admin_url( 'admin.php?page=yz-widgets-settings' ),
        );

        $membership_settings = array(
            'parent' => 'youzer-panel',
            'id'     => 'yz-membership-settings',
            'title'  =>  __( 'Membership Settings', 'youzer' ),
            'href'   => admin_url( 'admin.php?page=yz-membership-settings' ),
        );

        $wp_admin_bar->add_node( $youzer_panel );
        $wp_admin_bar->add_node( $general_settings );
        $wp_admin_bar->add_node( $profile_settings );
        $wp_admin_bar->add_node( $widgets_settings );
        $wp_admin_bar->add_node( $membership_settings );
    }
}

add_action( 'admin_bar_menu', 'yz_admin_bar_pages', 999 );

/**
 * Delete Temporary Files
 */
function yz_delete_temp_files() {

    // Get Uploads Directory.
    $upload_dir = wp_upload_dir();

    // Get Temporary Folder.
    $temp_folder = $upload_dir['basedir'] . '/youzer/temp/*';

    // Time until file deletion threshold ( in minutes ).
    $time = 5; 

    // Get All directory files
    $temp_files = glob( $temp_folder );

    if ( empty( $temp_files ) ) {
        return false;
    }

    // Remove Old Files.
    foreach ( $temp_files as $filename ) {
        if ( file_exists( $filename ) ) {
            if ( time() - filemtime( $filename ) > $time * 60 ) {
                unlink( $filename );
            }
        }
    }

}

add_action( 'admin_init', 'yz_delete_temp_files' );
