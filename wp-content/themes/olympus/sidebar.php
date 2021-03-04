<?php

if ( function_exists( 'fw_ext_sidebars_get_current_position' ) && function_exists( 'fw_ext_sidebars_show' ) ) {
    $current_position = fw_ext_sidebars_get_current_position();

    switch ( $current_position ) {
        case 'left':
        case 'right':
            echo fw_ext_sidebars_show( 'blue' );
            break;
        default:
            dynamic_sidebar( 'sidebar-main' );
            break;
    }
} else {
    dynamic_sidebar( 'sidebar-main' );
}