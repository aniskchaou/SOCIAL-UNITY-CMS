<?php

/**
 * # Get Third Party Tabs Settings.
 */
function yz_profile_subtabs_settings() {

    // Get Primary Third Party Tabs.
    $primary_tabs = yz_get_profile_third_party_tabs();

    if ( empty( $primary_tabs ) ) {
        // Get Message.
        $no_subtabs = __( 'Sorry, no subtabs settings exists!' );
        // Print Message.
        echo '<p class="yz-no-content">' . $no_subtabs . '</p>';
        return false;
    }

    // Init Vars.
    $bp = buddypress();

    foreach ( $primary_tabs as $primary_tab ) {

        // Get Tab Slug
        $tab_slug = isset( $primary_tab['slug'] ) ? $primary_tab['slug'] : null;

        // Get Tab Navigation  Menu
        $secondary_tabs = $bp->members->nav->get_secondary( array( 'parent_slug' => $tab_slug ) );

        if ( empty( $secondary_tabs ) ) {
            continue;
        }

        // Get Settings.
        yz_third_party_subtabs_settings( $secondary_tabs, $primary_tab );

    }

}

/**
 * Get Third Party SubTabs Settings
 */
function yz_third_party_subtabs_settings( $tabs, $primary_tab ) {

    global $Yz_Settings;

    // Get Primary Tab Slug
    $primary_slug = isset( $primary_tab['slug'] ) ? $primary_tab['slug'] : null;

    // Get Primary Tab Name
    $primary_name = isset( $primary_tab['name'] ) ? $primary_tab['name'] : $primary_slug;

    $Yz_Settings->get_field(
        array(
            'title' => sprintf( __( '%s Subtabs Settings', 'youzer' ), $primary_name ),
            'type'  => 'openBox'
        )
    );

    foreach ( $tabs as $tab ) {

        // Get Tab Name
        $tab_name = isset( $tab['name'] ) ? $tab['name'] : $tab['slug'];

        // Get Tab ID.
        $tab_id = 'yz_ctabs_' . $primary_slug . '_' . $tab['slug'] . '_icon';

        $Yz_Settings->get_field(
            array(
                'std' => 'fas fa-globe',
                'type'  => 'icon',
                'id'    => $tab_id,
                'title' => sprintf( __( '%s Icon', 'youzer' ), $tab_name ),
                'desc' => sprintf( __( '%s tab icon', 'youzer' ), $tab_name ),
            )
        );

    }

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}