<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$theme_template_directory = get_template_directory_uri();
fw()->theme->get_options( 'customizer-stunning-styles' );

$options = array(
    'stunning-header' => array(
        'title'    => esc_html__( 'Stunning header', 'olympus' ),
        'type'     => 'box',
        'priority' => 'high',
        'options'  => array(
            'header-stunning-visibility' => array(
                'type'    => 'radio',
                'label'   => esc_html__( 'Show stunning header', 'olympus' ),
                'choices' => array(
                    'default' => esc_html__( 'Default', 'olympus' ),
                    'yes'     => esc_html__( 'Yes', 'olympus' ),
                    'no'      => esc_html__( 'No', 'olympus' ),
                ),
                'inline'  => true,
            ), // header-stunning-visibility
            'header-stunning-customize'          => array(
                'type'    => 'multi-picker',
                'picker'  => 'header-stunning-visibility',
                'choices' => array(
                    'yes' => array(
                        'header-stunning-customize-content' => array(
                            'type'    => 'multi-picker',
                            'label'   => false,
                            'desc'    => false,
                            'picker'  => array(
                                'customize' => array(
                                    'label'        => esc_html__( 'Customize content', 'olympus' ),
                                    'type'         => 'switch',
                                    'value'        => 'no',
                                    'left-choice'  => array(
                                        'value' => 'no',
                                        'label' => esc_html__( 'No', 'olympus' ),
                                    ),
                                    'right-choice' => array(
                                        'value' => 'yes',
                                        'label' => esc_html__( 'Yes', 'olympus' ),
                                    ),
                                )
                            ),
                            'choices' => array(
                                'yes' => array(
                                    'header-stunning-content-popup' => array(
                                        'type'          => 'popup',
                                        'label'         => esc_html__( 'Custom Content', 'olympus' ),
                                        'desc'          => esc_html__( 'Change custom content for this page', 'olympus' ),
                                        'button'        => esc_html__( 'Change Content', 'olympus' ),
                                        'size'          => 'medium',
                                        'popup-options' => array(
                                            'stunning_title_show'       => array(
                                                'type'         => 'multi-picker',
                                                'label'        => false,
                                                'desc'         => false,
                                                'picker'       => array(
                                                    'show' => array(
                                                        'label'        => esc_html__( 'Show title', 'olympus' ),
                                                        'type'         => 'switch',
                                                        'value'        => 'yes',
                                                        'left-choice'  => array(
                                                            'value' => 'no',
                                                            'label' => esc_html__( 'No', 'olympus' ),
                                                        ),
                                                        'right-choice' => array(
                                                            'value' => 'yes',
                                                            'label' => esc_html__( 'Yes', 'olympus' ),
                                                        ),
                                                    ),
                                                ),
                                                'choices'      => array(
                                                    'yes' => array(
                                                        'title' => array(
                                                            'type'  => 'text',
                                                            'value' => '',
                                                            'label' => esc_html__( 'Custom title text', 'olympus' ),
                                                            'desc'  => esc_html__( 'Show post title, if that empty', 'olympus' ),
                                                        ),
                                                    ),
                                                ),
                                                'show_borders' => false,
                                            ),
                                            'stunning_breadcrumbs_show' => array(
                                                'label'        => esc_html__( 'Show breadcrumbs', 'olympus' ),
                                                'type'         => 'switch',
                                                'value'        => 'yes',
                                                'left-choice'  => array(
                                                    'value' => 'no',
                                                    'label' => esc_html__( 'No', 'olympus' ),
                                                ),
                                                'right-choice' => array(
                                                    'value' => 'yes',
                                                    'label' => esc_html__( 'Yes', 'olympus' ),
                                                ),
                                            ),
                                            'stunning_text'             => array(
                                                'type'  => 'textarea',
                                                'label' => esc_html__( 'Text', 'olympus' ),
                                                'desc'  => esc_html__( 'This text will be displayed under the heading', 'olympus' ),
                                            ),
                                        )
                                    ),
                                ),
                            ),
                        ),
                        'header-stunning-customize-styles'  => array(
                            'type'    => 'multi-picker',
                            'label'   => false,
                            'desc'    => false,
                            'picker'  => array(
                                'customize' => array(
                                    'label'        => esc_html__( 'Customize styles', 'olympus' ),
                                    'type'         => 'switch',
                                    'value'        => 'no',
                                    'left-choice'  => array(
                                        'value' => 'no',
                                        'label' => esc_html__( 'No', 'olympus' ),
                                    ),
                                    'right-choice' => array(
                                        'value' => 'yes',
                                        'label' => esc_html__( 'Yes', 'olympus' ),
                                    ),
                                )
                            ),
                            'choices' => array(
                                'yes' => array(
                                    'header-stunning-styles-popup' => array(
                                        'type'          => 'popup',
                                        'label'         => esc_html__( 'Custom Styles', 'olympus' ),
                                        'desc'          => esc_html__( 'Change custom styles for this page', 'olympus' ),
                                        'button'        => esc_html__( 'Change Styles', 'olympus' ),
                                        'size'          => 'medium',
                                        'popup-options' => array( olympus_get_stunning_styles_option('', false) )
                                    ),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
);

