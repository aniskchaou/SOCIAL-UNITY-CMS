<?php

add_action( 'wp_enqueue_scripts', '_action_olympus_thecalendar_scripts', 9999 );

function _action_olympus_thecalendar_scripts() {
    $theme_version = olympus_get_theme_version();

    wp_enqueue_style( 'the-event-calendar-customization', get_template_directory_uri() . '/css/the-event-calendar-customization.css', false, $theme_version );
}

add_filter( 'tribe_events_view_before_html_data_wrapper', '_filter_olympus_tribe_events_view_before_html_data_wrapper' );

function _filter_olympus_tribe_events_view_before_html_data_wrapper( $html ) {
    return "<div class=\"ui-block\"><div class=\"ui-block-content\">{$html}";
}

add_filter( 'tribe_events_view_after_html_data_wrapper', '_filter_olympus_tribe_events_view_after_html_data_wrapper' );

function _filter_olympus_tribe_events_view_after_html_data_wrapper( $html ) {
    return "{$html}</div></div>";
}

// Make sure that sceleton pack selected.
$stylesheet_option     = Tribe__Settings_Manager::get_option( 'stylesheetOption' );
$tribe_events_template = Tribe__Settings_Manager::get_option( 'tribeEventsTemplate' );

if ( $stylesheet_option !== 'skeleton' ) {
    Tribe__Settings_Manager::set_option( 'stylesheetOption', 'skeleton' );
}

if ( $tribe_events_template !== 'default' ) {
    Tribe__Settings_Manager::set_option( 'tribeEventsTemplate', 'default' );
}

add_filter( 'tribe_get_option', '_filter_olympus_tribe_get_option', 999, 2 );

function _filter_olympus_tribe_get_option( $option_value, $option_name ) {
    if ( $option_name === 'stylesheetOption' ) {
        return 'skeleton';
    }
    if ( $option_name === 'tribeEventsTemplate' ) {
        return 'default';
    }
    if ( $option_name === 'google_maps_js_api_key' ) {
        $olympus = Olympus_Options::get_instance();
        $apikey  = $olympus->get_option( 'gmap-key', '', $olympus::SOURCE_CUSTOMIZER );

        if ( $apikey && !$option_value ) {
            return $apikey;
        }
    }

    return $option_value;
}

// Set global google api key if needed
add_filter( 'tribe_customizer_get_option', '_filter_olympus_tribe_customizer_get_option', 999, 3 );

function _filter_olympus_tribe_customizer_get_option( $option, $search,
                                                      $sections ) {

    if ( !isset( $search[ 1 ] ) ) {
        return $option;
    }
    if ( $search[ 1 ] !== 'map_pin' ) {
        return $option;
    }

    $option = tribe_get_option( 'embedGoogleMapsPin', '' );

    return $option;
}

// Remove the Tribe Customier CSS
add_action( 'wp_footer', '_action_olympus_tribe_remove_customizer_css' );

function _action_olympus_tribe_remove_customizer_css() {
    if ( class_exists( 'Tribe__Customizer' ) ) {
        remove_action( 'wp_print_footer_scripts', array( Tribe__Customizer::instance(), 'print_css_template' ), 15 );
    }
}

// Remove Events calendar section
add_action( 'customize_register', '_action_olympus_tribe_customize_register', 999 );

function _action_olympus_tribe_customize_register( $wp_customize ) {
    $wp_customize->remove_panel( 'tribe_customizer' );
}

// Add style setting fields
add_filter( 'tribe_general_settings_tab_fields', '_filter_olympus_tribe_general_settings_tab_fields', 999 );

function _filter_olympus_tribe_general_settings_tab_fields( $generalTabFields ) {

    $added = olympus_array_insert_after( 'embedGoogleMapsZoom', $generalTabFields, 'embedGoogleMapsPin', array(
        'type'            => 'text',
        'label'           => esc_html__( 'Map Pin', 'olympus' ),
        'validation_type' => 'textarea',
        'class'           => 'field-image-add',
    ) );

    return $added ? $added : $generalTabFields;
}
