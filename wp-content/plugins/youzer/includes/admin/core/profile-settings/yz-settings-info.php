<?php

/**
 * Infos settings
 */
function yz_profile_info_tab_settings() {

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Info Styling Settings', 'youzer' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Info Title', 'youzer' ),
            'desc'  => __( 'Info titles color', 'youzer' ),
            'id'    => 'yz_infos_wg_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Info Value', 'youzer' ),
            'desc'  => __( 'Info values color', 'youzer' ),
            'id'    => 'yz_infos_wg_value_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}