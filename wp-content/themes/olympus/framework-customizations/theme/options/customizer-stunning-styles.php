<?php
if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

if(!function_exists('olympus_get_stunning_styles_option')){
    function olympus_get_stunning_styles_option($prefix = '', $show_all = true){
        $theme_template_directory = get_template_directory_uri();
        $stunning_breadcrumbs_show = "yes";
        $stunning_title_show = "yes";
        $stunning_title_show_title = "";
        $stunning_text = "";
        if (class_exists('Olympus_Options')) {
            $olympus = Olympus_Options::get_instance();
            $stunning_breadcrumbs_show = $olympus->get_option( "{$prefix}header-stunning-content/yes/stunning_breadcrumbs_show", "yes", $olympus::SOURCE_SETTINGS );
            $stunning_title_show = $olympus->get_option( "{$prefix}header-stunning-content/yes/stunning_title_show/show", "yes", $olympus::SOURCE_SETTINGS );
            $stunning_title_show_title = $olympus->get_option( "{$prefix}header-stunning-content/yes/stunning_title_show/yes/title", "", $olympus::SOURCE_SETTINGS );
            $stunning_text = $olympus->get_option( "{$prefix}header-stunning-content/yes/stunning_text", "", $olympus::SOURCE_SETTINGS );
        }

        $options = array(
            'stunning_breadcrumbs_show' => array(
                'label'        => esc_html__( 'Show breadcrumbs', 'olympus' ),
                'type'         => 'switch',
                'value'        => $stunning_breadcrumbs_show,
                'left-choice'  => array(
                    'value' => 'no',
                    'label' => esc_html__( 'No', 'olympus' ),
                ),
                'right-choice' => array(
                    'value' => 'yes',
                    'label' => esc_html__( 'Yes', 'olympus' ),
                ),
            ),
            'stunning_title_show'       => array(
                'type'         => 'multi-picker',
                'label'        => false,
                'desc'         => false,
                'picker'       => array(
                    'show' => array(
                        'label'        => esc_html__( 'Show title', 'olympus' ),
                        'type'         => 'switch',
                        'value'        => $stunning_title_show,
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
                            'value' => $stunning_title_show_title,
                            'label' => esc_html__( 'Custom title text', 'olympus' ),
                            'desc'  => esc_html__( 'Show post title, if that empty', 'olympus' ),
                        ),
                    ),
                ),
                'show_borders' => false,
            ),
            'stunning_text'             => array(
                'type'  => 'textarea',
                'value' => $stunning_text,
                'label' => esc_html__( 'Text', 'olympus' ),
                'desc'  => esc_html__( 'This text will be displayed under the heading', 'olympus' ),
            ),
            'stunning_text_color'        => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Text Color', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
                'value' => ''
            ),
            'stunning_text_align'        => array(
                'type'    => 'radio',
                'value'   => 'stunning-header--content-center',
                'label'   => esc_html__( 'Text align', 'olympus' ),
                'choices' => array(
                    'stunning-header--content-left'   => esc_html__( 'Left', 'olympus' ),
                    'stunning-header--content-center' => esc_html__( 'Center', 'olympus' ),
                    'stunning-header--content-right'  => esc_html__( 'Right', 'olympus' ),
                ),
                'inline'  => true,
            ),
            'stunning_bg_color'          => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Background Color', 'olympus' ),
                'desc'  => esc_html__( 'If you choose no image to display - that color will be set as background', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
                'value' => ''
            ),
            'stunning_bg_image'          => array(
                'type'    => 'background-image',
                'value'   => 'none',
                'label'   => esc_html__( 'Background image', 'olympus' ),
                'desc'    => esc_html__( 'Minimum size for background image is 1920x400px', 'olympus' ),
                'choices' => array(
                    'none' => array(
                        'icon' => $theme_template_directory . '/images/thumb/bg-0.png',
                        'css'  => array(
                            'background-image' => "none",
                        ),
                    ),
                    'header-stunning-1' => array(
                        'icon'  => $theme_template_directory . '/images/thumb/header-stunning-thumb-1.png',
                        'css'  => array(
                            'background-image'  => 'url("' . $theme_template_directory . '/images/header-stunning-1.png")',
                        ),
                    ),
                    'header-stunning-2' => array(
                        'icon'  => $theme_template_directory . '/images/thumb/header-stunning-thumb-2.png',
                        'css'  => array(
                            'background-image'  => 'url("' . $theme_template_directory . '/images/header-stunning-2.png")',
                        ),
                    ),
                    'header-stunning-3' => array(
                        'icon'  => $theme_template_directory . '/images/thumb/header-stunning-thumb-3.png',
                        'css'  => array(
                            'background-image'  => 'url("' . $theme_template_directory . '/images/header-stunning-3.png")',
                        ),
                    ),
                    'header-stunning-4' => array(
                        'icon'  => $theme_template_directory . '/images/thumb/header-stunning-thumb-4.png',
                        'css'  => array(
                            'background-image'  => 'url("' . $theme_template_directory . '/images/header-stunning-4.png")',
                        ),
                    ),
                    'header-stunning-5' => array(
                        'icon'  => $theme_template_directory . '/images/thumb/header-stunning-thumb-5.png',
                        'css'  => array(
                            'background-image'  => 'url("' . $theme_template_directory . '/images/header-stunning-5.png")',
                        ),
                    )
                )
            ),
            'stunning_bg_animate_picker' => array(
                'type'    => 'multi-picker',
                'label'   => false,
                'desc'    => false,
                'picker'  => array(
                    'stunning_bg_animate' => array(
                        'label'        => esc_html__( 'Animate background?', 'olympus' ),
                        'desc'        => esc_html__( 'Background image will be moved while scrolling', 'olympus' ),
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
                'choices' => array(
                    'yes' => array(
                        'stunning_bg_animate_type' => array(
                            'type'    => 'radio',
                            'value'   => 'fixed',
                            'label'   => esc_html__( 'Animate type', 'olympus' ),
                            'choices' => array(
                                'right-to-left' => esc_html__( 'Right to left', 'olympus' ),
                                'left-to-right' => esc_html__( 'Left to right', 'olympus' ),
                                'fixed' => esc_html__( 'Fixed', 'olympus' ),
                            ),
                            'inline'  => true,
                        )
                    ),
                    'no'  => array(
                        'stunning_bg_cover' => array(
                            'type'         => 'switch',
                            'label'        => esc_html__( 'Expand background', 'olympus' ),
                            'desc'         => esc_html__( 'Don\'t repeat image and expand it to full section background', 'olympus' ),
                            'value'        => 'no',
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
                )
            ),
            'stunning_bottom_image'     => array(
                'type'        => 'upload',
                'label'       => esc_html__( 'Bottom image', 'olympus' ),
                'desc'        => esc_html__( 'Select one of images or upload your own pattern', 'olympus' ),
                'images_only' => true,
            ),
            'stunning_padding_top'       => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__( 'Padding from Top', 'olympus' ),
            ),
            'stunning_padding_bottom'    => array(
                'type'  => 'text',
                'value' => '',
                'label' => esc_html__( 'Padding from Bottom', 'olympus' ),
            ),
        );

        if(!$show_all){
            unset($options['stunning_breadcrumbs_show']);
            unset($options['stunning_title_show']);
            unset($options['stunning_text']);
        }

        return $options;
    }
}