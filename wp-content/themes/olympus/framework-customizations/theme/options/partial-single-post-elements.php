<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$single_related_show_default = array(
    'show' => 'yes',
    'yes'  => array(
        'meta'    => 'yes',
        'excerpt' => 'yes'
    )
);

$olympus = Olympus_Options::get_instance();
$single_featured_show_value = $olympus->get_option( 'single_featured_show', 'yes', $olympus::SOURCE_SETTINGS );
$single_meta_show_value = $olympus->get_option( 'single_meta_show', 'yes', $olympus::SOURCE_SETTINGS );
$single_share_show_value = $olympus->get_option( 'single_share_show', 'yes', $olympus::SOURCE_SETTINGS );
$single_related_show_value = $olympus->get_option( 'single_related_show', $single_related_show_default, $olympus::SOURCE_SETTINGS );

$options = apply_filters( 'crumina_section_single_post_elements', array(
    'single_featured_show' => array(
        'label'        => esc_html__( 'Featured media', 'olympus' ),
        'desc'         => esc_html__( 'Featured image or other media on top of post', 'olympus' ),
        'type'         => 'switch',
        'left-choice' => array(
	        'value' => 'no',
	        'label' => esc_html__( 'Disable', 'olympus' )
        ),
        'right-choice'  => array(
            'value' => 'yes',
            'label' => esc_html__( 'Enable', 'olympus' )
        ),
        'value'        => $single_featured_show_value,
    ),
    'single_meta_show'     => array(
        'label'        => esc_html__( 'Post meta', 'olympus' ),
        'desc'         => esc_html__( 'Post time, post author, etc', 'olympus' ),
        'type'         => 'switch',
        'left-choice' => array(
	        'value' => 'no',
	        'label' => esc_html__( 'Disable', 'olympus' )
        ),
        'right-choice'  => array(
	        'value' => 'yes',
	        'label' => esc_html__( 'Enable', 'olympus' )
        ),
        'value'        => $single_meta_show_value,
    ),
    'single_share_show'    => array(
        'label'        => esc_html__( 'Share post buttons?', 'olympus' ),
        'desc'         => esc_html__( 'Show icons that share post on social networks', 'olympus' ),
        'type'         => 'switch',
        'left-choice' => array(
	        'value' => 'no',
	        'label' => esc_html__( 'Disable', 'olympus' )
        ),
        'right-choice'  => array(
	        'value' => 'yes',
	        'label' => esc_html__( 'Enable', 'olympus' )
        ),
        'value'        => $single_share_show_value,
    ),
    'single_related_show'  => array(
        'type'    => 'multi-picker',
        'label'   => false,
        'desc'    => false,
        'value'   => $single_related_show_value,
        'picker'  => array(
            'show' => array(
                'label'        => esc_html__( 'Related posts section', 'olympus' ),
                'type'         => 'switch',
                'left-choice' => array(
	                'value' => 'no',
	                'label' => esc_html__( 'Disable', 'olympus' )
                ),
                'right-choice'  => array(
	                'value' => 'yes',
	                'label' => esc_html__( 'Enable', 'olympus' )
                ),
                'value'        => 'yes',
            ),
        ),
        'choices' => array(
            'yes' => array(
                'meta'    => array(
                    'label'        => esc_html__( 'Post meta (In related item)', 'olympus' ),
                    'desc'         => esc_html__( 'Post time, post author, etc', 'olympus' ),
                    'type'         => 'switch',
                    'left-choice' => array(
	                    'value' => 'no',
	                    'label' => esc_html__( 'Disable', 'olympus' )
                    ),
                    'right-choice'  => array(
	                    'value' => 'yes',
	                    'label' => esc_html__( 'Enable', 'olympus' )
                    ),
                    'value'        => 'yes',
                ),
                'excerpt' => array(
                    'label'        => esc_html__( 'Post excerpt (In related item)', 'olympus' ),
                    'desc'         => esc_html__( 'Short post description', 'olympus' ),
                    'type'         => 'switch',
                    'left-choice' => array(
	                    'value' => 'no',
	                    'label' => esc_html__( 'Disable', 'olympus' )
                    ),
                    'right-choice'  => array(
	                    'value' => 'yes',
	                    'label' => esc_html__( 'Enable', 'olympus' )
                    ),
                    'value'        => 'yes',
                ),
            ),
        ),
    ),
) );
