<?php

/**
 * Customize preview
 *
 * @param WP_Customize_Manager $wp_customize customize manager.
 */
function olympus_action_customize_preview( $wp_customize ) {
    $my_theme = wp_get_theme();
    wp_enqueue_script( 'olympus-customizer', get_template_directory_uri() . '/js/customizer.js', array( 'jquery', 'customize-preview' ), $my_theme->version, true );
    wp_localize_script( 'olympus-customizer', 'theme_vars', array(
        'templateUrl' => get_template_directory_uri()
    ) );
}

add_action( 'customize_preview_init', 'olympus_action_customize_preview' );

/**
 * Customize register
 *
 * @param WP_Customize_Manager $wp_customize customize manager.
 */
function olympus_action_customize_register( $wp_customize ) {

    //Base options
    $wp_customize->add_setting( 'custom-logo-height', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'custom-logo-height', array(
        'label'    => esc_html__( 'Logo height', 'olympus' ),
        'section'  => 'title_tagline',
        'settings' => 'custom-logo-height',
        'description' => esc_html__('Limit image height', 'olympus'),
        'type' => 'number',
        'priority' => 8.1
    ) );

    $wp_customize->add_setting( 'custom-logo-text', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'custom-logo-text', array(
        'label'    => esc_html__( 'Logo Text', 'olympus' ),
        'section'  => 'title_tagline',
        'settings' => 'custom-logo-text',
        'priority' => 8.2
    ) );

    $wp_customize->add_setting( 'custom-logo-description', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'sanitize_text_field',
    ) );

    $wp_customize->add_control( 'custom-logo-description', array(
        'label'    => esc_html__( 'Logo Description', 'olympus' ),
        'section'  => 'title_tagline',
        'settings' => 'custom-logo-description',
        'priority' => 8.3
    ) );

    $wp_customize->add_setting( 'custom-logo-uppercase', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'olympus_sanitize_checkbox',
    ) );

    $wp_customize->add_control( 'custom-logo-uppercase', array(
        'type' => 'checkbox',
        'label'    => esc_html__( 'Enable Logo Uppercase', 'olympus' ),
        'section'  => 'title_tagline',
        'settings' => 'custom-logo-uppercase',
        'default' => '1',
        'priority' => 8.4
    ) );

    //Color options
    $wp_customize->add_setting( 'primary-accent-color', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( 'primary-accent-color', array(
        'label'    => esc_html__( 'Primary Accent Color', 'olympus' ),
        'section'  => 'section_colors',
        'type'     => 'color',
        'settings' => 'primary-accent-color',
    ) );

    $wp_customize->add_setting( 'secondary-accent-color', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( 'secondary-accent-color', array(
        'label'    => esc_html__( 'Secondary Accent Color', 'olympus' ),
        'section'  => 'section_colors',
        'type'     => 'color',
        'settings' => 'secondary-accent-color',
    ) );

	$wp_customize->add_setting( 'third-accent-color', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( 'third-accent-color', array(
		'label'    => esc_html__( 'Third Accent Color', 'olympus' ),
		'section'  => 'section_colors',
		'type'     => 'color',
		'settings' => 'third-accent-color',
	) );

    $wp_customize->add_setting( 'post-thumb-bg-color', array(
        'type'              => 'option',
        'capability'        => 'manage_options',
        'sanitize_callback' => 'sanitize_hex_color',
    ) );

    $wp_customize->add_control( 'post-thumb-bg-color', array(
        'label'    => esc_html__( 'Post Thumbnail Background Color', 'olympus' ),
        'section'  => 'section_colors',
        'type'     => 'color',
        'settings' => 'post-thumb-bg-color',
    ) );

	$wp_customize->add_setting( 'icons-color', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( 'icons-color', array(
		'label'    => esc_html__( 'Icons Color', 'olympus' ),
		'section'  => 'section_colors',
		'type'     => 'color',
		'settings' => 'icons-color',
    ) );

    $wp_customize->get_control('background_color')->section = 'section_colors';
    $wp_customize->get_control('background_image')->section = 'section_background_image';
    $wp_customize->get_control('background_preset')->section = 'section_background_image';
    $wp_customize->get_control('background_position')->section = 'section_background_image';
    $wp_customize->get_control('background_size')->section = 'section_background_image';
    $wp_customize->get_control('background_repeat')->section = 'section_background_image';
    $wp_customize->get_control('background_attachment')->section = 'section_background_image';

    $wp_customize->remove_section( 'colors' );
    $wp_customize->remove_section( 'background_image' );
    $wp_customize->remove_control('page_for_posts');

	$wp_customize->get_setting( 'primary-accent-color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'secondary-accent-color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'third-accent-color' )->transport = 'postMessage';
	$wp_customize->get_setting( 'icons-color' )->transport = 'postMessage';



	/* Left Panel Color Customization */
	$wp_customize->add_setting( 'side-panel-bg-color', array(
		'type'              => 'option',
		'capability'        => 'manage_options',
		'sanitize_callback' => 'sanitize_hex_color',
	) );

	$wp_customize->add_control( 'side-panel-bg-color', array(
		'label'    => esc_html__( 'Background Color', 'olympus' ),
		'section'  => 'panel_aside_menu',
		'type'     => 'color',
		'settings' => 'side-panel-bg-color',
	) );
	$wp_customize->get_setting( 'side-panel-bg-color' )->transport = 'postMessage';

    function olympus_sanitize_checkbox( $checked ) {
        // Boolean check.
        return ( ( isset( $checked ) && true == $checked ) ? '1' : '0' );
    }

    // FW Options
    if ( defined( 'FW' ) ) {
    	// Colors Customize

	    if ( $wp_customize->get_setting( 'fw_options[primary_font_color]' ) ) {
		    $wp_customize->get_setting( 'fw_options[primary_font_color]' )->transport = 'postMessage';
	    }
	    if ( $wp_customize->get_setting( 'fw_options[accent_font_color]' ) ) {
		    $wp_customize->get_setting( 'fw_options[accent_font_color]' )->transport = 'postMessage';
	    }


        // Header social styles
        if ( $wp_customize->get_setting( 'fw_options[header_social_bg_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_social_bg_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[header_social_form_bg_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_social_form_bg_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[header_social_form_text_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_social_form_text_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[header_social_title_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_social_title_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[sign-form-popup]' ) ) {
            $wp_customize->get_setting( 'fw_options[sign-form-popup]' )->transport = 'postMessage';
        }

	    // Youzer styles options
	    if ( $wp_customize->get_setting( 'fw_options[enable_youzer_styles]' ) ) {
		    $wp_customize->get_setting( 'fw_options[enable_youzer_styles]' )->transport = 'postMessage';
	    }

	    if ( $wp_customize->get_setting( 'fw_options[enable_youzer_icons]' ) ) {
		    $wp_customize->get_setting( 'fw_options[enable_youzer_icons]' )->transport = 'postMessage';
	    }

        // Header general styles
        if ( $wp_customize->get_setting( 'fw_options[header_general_bg_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_general_bg_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[header_general_logo_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_general_logo_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[header_general_cart_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[header_general_cart_color]' )->transport = 'postMessage';
        }

        //Footer styles
        if ( $wp_customize->get_setting( 'fw_options[footer_wide_content]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_wide_content]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[footer_text_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_text_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[footer_title_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_title_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[footer_link_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_link_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[footer_bg_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_bg_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[footer_bg_image]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_bg_image]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[footer_bg_cover]' ) ) {
            $wp_customize->get_setting( 'fw_options[footer_bg_cover]' )->transport = 'postMessage';
        }

        //Back to top btn
        if ( $wp_customize->get_setting( 'fw_options[totop_bg_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[totop_bg_color]' )->transport = 'postMessage';
        }

        if ( $wp_customize->get_setting( 'fw_options[totop_icon_color]' ) ) {
            $wp_customize->get_setting( 'fw_options[totop_icon_color]' )->transport = 'postMessage';
        }
    }
}

add_action( 'customize_register', 'olympus_action_customize_register', 999 );
