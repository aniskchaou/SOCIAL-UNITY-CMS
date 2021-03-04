<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$prefix = '';
$theme_template_directory = get_template_directory_uri();
$header_stunning_visibility_general = "yes";
$header_stunning_visibility = "default";
if (class_exists('Olympus_Options')) {
    $olympus = Olympus_Options::get_instance();
    $prefix = $olympus->olympus_stunning_get_option_prefix();
    $header_stunning_visibility_general = $olympus->get_option( "header-stunning-visibility", "yes", $olympus::SOURCE_SETTINGS );
    $header_stunning_visibility = $olympus->get_option( "{$prefix}header-stunning-visibility", "default", $olympus::SOURCE_SETTINGS );
}
fw()->theme->get_options( 'customizer-stunning-styles' );

$options = array(
    'stunning-header' => array(
        'title'   => esc_html__( 'Stunning header', 'olympus' ),
        'options' => array(
            // General
            'general'     => array(
                'title'   => esc_html__( 'General', 'olympus' ),
                'options' => array(
                    'header-stunning-visibility' => array(
                        'type'    => 'radio',
                        'value'   => $header_stunning_visibility_general,
                        'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                            'no'      => esc_html__( 'No', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'header-stunning-customizer-picker' => array(
                        'type'    => 'radio',
                        'value'   => 'yes',
                        'label'   => esc_html__( 'Yes', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'header-stunning-customizer' => array(
                        'type'    => 'multi-picker',
                        'picker'  => 'header-stunning-customizer-picker',
                        'choices' => array(
                            'yes' => array( olympus_get_stunning_styles_option() ),
                        )
                    )
                )
            ),
            // Woocommerce
            'woocommerce' => array(
                'title'   => esc_html__( 'WooCommerce', 'olympus' ),
                'options' => array(
                    'woocommerce_header-stunning-visibility' => array(
                        'type'    => 'radio',
                        'value'   => $header_stunning_visibility,
                        'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                        'choices' => array(
                            'default' => esc_html__( 'Default', 'olympus' ),
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                            'no'      => esc_html__( 'No', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'woocommerce_header-stunning-customizer-picker' => array(
                        'type'    => 'radio',
                        'value'   => 'yes',
                        'label'   => esc_html__( 'Yes', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'woocommerce_header-stunning-customizer' => array(
                        'type'    => 'multi-picker',
                        'picker'  => 'woocommerce_header-stunning-customizer-picker',
                        'choices' => array(
                            'yes' => array( olympus_get_stunning_styles_option('woocommerce_') ),
                        )
                    )
                )
            ),
            // Buddypress
            'buddypress'  => array(
                'title'   => esc_html__( 'BuddyPress', 'olympus' ),
                'options' => array(
                    'buddypress_header-stunning-visibility' => array(
                        'type'    => 'radio',
                        'value'   => $header_stunning_visibility,
                        'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                        'choices' => array(
                            'default' => esc_html__( 'Default', 'olympus' ),
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                            'no'      => esc_html__( 'No', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'buddypress_header-stunning-customizer-picker' => array(
                        'type'    => 'radio',
                        'value'   => 'yes',
                        'label'   => esc_html__( 'Yes', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'buddypress_header-stunning-customizer' => array(
                        'type'    => 'multi-picker',
                        'picker'  => 'buddypress_header-stunning-customizer-picker',
                        'choices' => array(
                            'yes' => array( olympus_get_stunning_styles_option('buddypress_') ),
                        )
                    )
                )
            ),
            // Bbpress
            'bbpress' => array(
                'title' => esc_html__( 'bbPress', 'olympus' ),
                'options' => array(
                    'bbpress_header-stunning-visibility' => array(
                        'type'    => 'radio',
                        'value'   => $header_stunning_visibility,
                        'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                        'choices' => array(
                            'default' => esc_html__( 'Default', 'olympus' ),
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                            'no'      => esc_html__( 'No', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'bbpress_header-stunning-customizer-picker' => array(
                        'type'    => 'radio',
                        'value'   => 'yes',
                        'label'   => esc_html__( 'Yes', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'bbpress_header-stunning-customizer' => array(
                        'type'    => 'multi-picker',
                        'picker'  => 'bbpress_header-stunning-customizer-picker',
                        'choices' => array(
                            'yes' => array( olympus_get_stunning_styles_option('bbpress_') ),
                        )
                    )
                )
            ),
            // Events
            'events' => array(
                'title' => esc_html__( 'Events', 'olympus' ),
                'options' => array(
                    'events_header-stunning-visibility' => array(
                        'type'    => 'radio',
                        'value'   => $header_stunning_visibility,
                        'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                        'choices' => array(
                            'default' => esc_html__( 'Default', 'olympus' ),
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                            'no'      => esc_html__( 'No', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'events_header-stunning-customizer-picker' => array(
                        'type'    => 'radio',
                        'value'   => 'yes',
                        'label'   => esc_html__( 'Yes', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'events_header-stunning-customizer' => array(
                        'type'    => 'multi-picker',
                        'picker'  => 'events_header-stunning-customizer-picker',
                        'choices' => array(
                            'yes' => array( olympus_get_stunning_styles_option('events_') ),
                        )
                    )
                )
            ),
            // Single post
            'single_post' => array(
                'title' => esc_html__( 'Single posts', 'olympus' ),
                'options' => array(
                    'single_post_header-stunning-visibility' => array(
                        'type'    => 'radio',
                        'value'   => $header_stunning_visibility,
                        'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                        'choices' => array(
                            'default' => esc_html__( 'Default', 'olympus' ),
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                            'no'      => esc_html__( 'No', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'single_post_header-stunning-customizer-picker' => array(
                        'type'    => 'radio',
                        'value'   => 'yes',
                        'label'   => esc_html__( 'Yes', 'olympus' ),
                        'choices' => array(
                            'yes'     => esc_html__( 'Yes', 'olympus' ),
                        ),
                        'inline'  => true,
                    ),
                    'single_post_header-stunning-customizer' => array(
                        'type'    => 'multi-picker',
                        'picker'  => 'single_post_header-stunning-customizer-picker',
                        'choices' => array(
                            'yes' => array( olympus_get_stunning_styles_option('single_post_') ),
                        )
                    )
                )
            ),
        ),
    ),
);