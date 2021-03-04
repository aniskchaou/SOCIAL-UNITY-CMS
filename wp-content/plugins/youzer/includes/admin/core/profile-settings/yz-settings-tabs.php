<?php

/**
 * # Tabs Settings.
 */

function yz_tabs_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tabs General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    // Get Defaut Tab Options.
    $default_tab_options = (array) yz_get_profile_default_nav_options();
    $default_option = array( '' => __( '-- Select Default Tab --', 'youzer' ) );
    $default_tab_options = $default_option + $default_tab_options;

    // $Yz_Settings->get_field(
    //     array(
    //         'id'    => 'yz_display_profile_tabs_count',
    //         'title' => __( 'Display tabs count', 'youzer' ),
    //         'desc'  => __( 'show profile tabs count', 'youzer' ),
    //         'type'  => 'checkbox'
    //     )
    // );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_profile_default_tab',
            'title' => __( 'Default Tab', 'youzer' ),
            'desc'  => __( 'Choose profile default tab', 'youzer' ),
            'opts'  => $default_tab_options,
            'type'  => 'select'
        )
    );

   $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );


    // Get Tabs
    $custom_tabs = yz_get_profile_primary_nav();

    if ( ! empty( $custom_tabs ) ) {
        yz_custom_buddypress_tabs_settings( $custom_tabs );
    }

    // Get Custom Tabs Settings.
    $Yz_Settings->get_field(
        array(
            'title' => __( 'Pagination Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Numbers Color', 'youzer' ),
            'id'    => 'yz_pagination_text_color',
            'desc'  => __( 'Pages numbers color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Numbers Background', 'youzer' ),
            'id'    => 'yz_pagination_bg_color',
            'desc'  => __( 'Pages numbers background', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Active Page Background', 'youzer' ),
            'id'    => 'yz_pagination_current_bg_color',
            'desc'  => __( 'Active page background color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Active Page Number', 'youzer' ),
            'id'    => 'yz_pagination_current_text_color',
            'desc'  => __( 'Active page number color', 'youzer' ),
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * # Get Custom Buddypress Tabs Settings.
 */
function yz_custom_buddypress_tabs_settings( $custom_tabs ) {

    global $Yz_Settings;

    // Default Tab Values.
    $default_tabs = yz_profile_tabs_default_value();
    $tabs_settings = yz_option( 'yz_profile_tabs' );

    foreach ( $custom_tabs as $tab ) {

        // Get Tab Name
        $tab_name = isset( $tab['name'] ) ? $tab['name'] : $tab['slug'];

        // Filter Name.
        $tab_name = _bp_strip_spans_from_title( $tab_name );

        // Get Tab Slug
        $tab_slug = isset( $tab['slug'] ) ? $tab['slug'] : null;

        $Yz_Settings->get_field(
            array(
                'title' => sprintf( __( '%s Tab', 'youzer' ), $tab_name ),
                'class' => 'ukai-box-3cols kl-accordion-box',
                'type'  => 'openBox',
                'hide'  => true,
            )
        );

        $default_visibility = isset( $default_tabs[ $tab_slug ] ) ? $default_tabs[ $tab_slug ]['visibility'] : 'on';

        $Yz_Settings->get_field(
            array(
                'type'  => 'checkbox',
                'std'   => yz_admin_get_tab_option_value( $tab_slug, 'visibility', $default_visibility ),
                'id'    => 'visibility',
                'title' => sprintf( __( 'Display Tab', 'youzer' ), $tab_name ),
                'desc'  => sprintf( __( 'Show %s tab', 'youzer' ), $tab_name ),
            ), false, 'yz_profile_tabs[' . $tab_slug .']'
        );

        $default_icon = isset( $default_tabs[ $tab_slug ] ) ? $default_tabs[ $tab_slug ]['icon'] : 'fas fa-globe-asia';

        $Yz_Settings->get_field(
            array(
                'type'  => 'icon',
                'std'   => yz_admin_get_tab_option_value( $tab_slug, 'icon', $default_icon ),
                'id'    => 'icon',
                'title' => sprintf( __( '%s Icon', 'youzer' ), $tab_name ),
                'desc'  => sprintf( __( '%s tab icon', 'youzer' ), $tab_name ),
            ), false, 'yz_profile_tabs[' . $tab_slug .']'
        );

        $Yz_Settings->get_field(
            array(
                'type'  => 'text',
                'std'   => yz_admin_get_tab_option_value( $tab_slug, 'name', $tab_name ),
                'id'    => 'name',
                'title' => sprintf( __( '%s Title', 'youzer' ), $tab_name ),
                'desc' => sprintf( __( '%s tab title', 'youzer' ), $tab_name ),
            ), false, 'yz_profile_tabs[' . $tab_slug .']'
        );

        $Yz_Settings->get_field(
            array(
                'id'    => 'position',
                'type'  => 'number',
                'std'   => yz_admin_get_tab_option_value( $tab_slug, 'position', $tab['position'] ),
                'title' => sprintf( __( '%s Order', 'youzer' ), $tab_name ),
                'desc'  => sprintf( __( '%s tab order', 'youzer' ), $tab_name ),
            ), false, 'yz_profile_tabs[' . $tab_slug .']'
        );

        if ( in_array( $tab_slug, yz_profile_deletable_tabs() ) ) {

            $Yz_Settings->get_field(
                array(
                    'std'   => yz_admin_get_tab_option_value( $tab_slug, 'deleted', 'off' ),
                    'type'  => 'checkbox',
                    'id'    => 'deleted',
                    'title' => sprintf( __( 'Delete Tab', 'youzer' ), $tab_name ),
                    'desc'  => sprintf( __( 'Delete %s tab', 'youzer' ), $tab_name ),
                ), false, 'yz_profile_tabs[' . $tab_slug .']'
            );
        }

       $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    }

}

/**
 * Get Tab Option
 */
function yz_admin_get_tab_option_value( $slug, $option, $std = null ) {

    $tabs = yz_option( 'yz_profile_tabs' );

    if ( isset( $tabs[ $slug ][ $option ] ) ) {
        return $tabs[ $slug ][ $option ];
    }

    return $std;
}

/**
 * Profile Default Nav Options
 */
function yz_get_profile_default_nav_options() {

    // Get Youzer Custom Tabs
    $primary_tabs = yz_get_profile_primary_nav();

    if ( empty( $primary_tabs ) ) {
        return false;
    }

    // Init
    $tab_options = array();

    foreach ( $primary_tabs as $tab ) {

        // Get Tab Slug.
        $tab_slug = $tab['slug'];

        // Get Tab ID.
        $tab_id = yz_get_custom_tab_id_by_slug( $tab_slug );

        // Get Custom Tab Link.
        if ( yz_is_custom_tab( $tab_id ) ) {

            // Get Tab Type.
            $tab_type = yz_get_custom_tab_data( $tab_id, 'type' );

            if ( 'link' == $tab_type ) {
                continue;
            }
        }

        // Check is Tab Deleted.
        // if ( yz_is_profile_tab_deleted( $tab_slug ) ) {
        //     continue;
        // }

        // Set Option.
        $tab_options[ $tab_slug ] = _bp_strip_spans_from_title( $tab['name'] );

    }

    return $tab_options;
}

/**
 * Get Profile Deletable Tabs.
 */
function yz_profile_deletable_tabs() {

    // Get Default Tabs Slugs.
    $default_tabs = yz_get_youzer_default_tabs();

    // Get Youzer Custom Tabs Slugs.
    $custom_tabs = (array) yz_custom_youzer_tabs_slugs();

    // Merge Tabs Slugs.
    $all_tabs = array_merge( $default_tabs, $custom_tabs );

    return $all_tabs;
}