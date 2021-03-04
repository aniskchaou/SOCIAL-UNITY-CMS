<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$options = array(
    'general-body-bg-color' => array(
        'type'  => 'color-picker',
        'label' => esc_html__( 'Background Color', 'olympus' ),
        'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
    ),
    'general-body-bg'       => array(
        'type'    => 'box',
        'title'   => esc_html__( 'Background', 'olympus' ),
        'options' => array(
            'general-body-bg-image'      => array(
                'type'        => 'upload',
                'label'       => esc_html__( 'Background Image', 'olympus' ),
                'images_only' => true,
            ),
            'general-body-bg-position'   => array(
                'type'    => 'select',
                'value'   => 'initial',
                'label'   => esc_html__( 'Background Position', 'olympus' ),
                'choices' => array(
                    'initial'       => esc_html__( 'Initial', 'olympus' ),
                    'inherit'       => esc_html__( 'Inherit', 'olympus' ),
                    'left top'      => esc_html__( 'Left top', 'olympus' ),
                    'left center'   => esc_html__( 'Left center', 'olympus' ),
                    'left bottom'   => esc_html__( 'Left bottom', 'olympus' ),
                    'right top'     => esc_html__( 'Right top', 'olympus' ),
                    'right center'  => esc_html__( 'Right center', 'olympus' ),
                    'right bottom'  => esc_html__( 'Right bottom', 'olympus' ),
                    'center top'    => esc_html__( 'Center top', 'olympus' ),
                    'center center' => esc_html__( 'Center center', 'olympus' ),
                    'center bottom' => esc_html__( 'Center bottom', 'olympus' ),
                ),
            ),
            'general-body-bg-size'       => array(
                'type'    => 'select',
                'value'   => 'initial',
                'label'   => esc_html__( 'Background Size', 'olympus' ),
                'choices' => array(
                    'initial' => esc_html__( 'Initial', 'olympus' ),
                    'auto'    => esc_html__( 'Auto', 'olympus' ),
                    'cover'   => esc_html__( 'Cover', 'olympus' ),
                    'contain' => esc_html__( 'Contain', 'olympus' ),
                    'inherit' => esc_html__( 'Inherit', 'olympus' ),
                ),
            ),
            'general-body-bg-repeat'     => array(
                'type'    => 'select',
                'value'   => 'initial',
                'label'   => esc_html__( 'Background Repeat', 'olympus' ),
                'choices' => array(
                    'initial'   => esc_html__( 'Initial', 'olympus' ),
                    'repeat'    => esc_html__( 'Repeat', 'olympus' ),
                    'repeat-x'  => esc_html__( 'Repeat x', 'olympus' ),
                    'repeat-y'  => esc_html__( 'Repeat y', 'olympus' ),
                    'no-repeat' => esc_html__( 'No repeat', 'olympus' ),
                    'round'     => esc_html__( 'Round', 'olympus' ),
                    'inherit'   => esc_html__( 'Inherit', 'olympus' ),
                ),
            ),
            'general-body-bg-attachment' => array(
                'type'    => 'select',
                'value'   => 'initial',
                'label'   => esc_html__( 'Background Attachment', 'olympus' ),
                'choices' => array(
                    'initial' => esc_html__( 'Initial', 'olympus' ),
                    'scroll'  => esc_html__( 'Scroll', 'olympus' ),
                    'fixed'   => esc_html__( 'Fixed', 'olympus' ),
                    'local'   => esc_html__( 'Local', 'olympus' ),
                    'inherit' => esc_html__( 'Inherit', 'olympus' ),
                ),
            ),
        )
    )
);
