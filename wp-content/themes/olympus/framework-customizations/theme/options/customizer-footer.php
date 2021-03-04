<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$grid_link = '<a href="http://getbootstrap.com/css/#grid" target="_blank">Bootstrap Grid</a>';
$theme_template_directory = get_template_directory_uri();

$olympus = Olympus_Options::get_instance();
$footer_copyright = $olympus->get_option( "footer_copyright", 'Site is built on <a href="https://wordpress.org">WordPress</a> by Crumina <a href="https://crumina.net">Theme Development</a>', $olympus::SOURCE_SETTINGS );
$scroll_top_icon = $olympus->get_option( "scroll_top_icon/value", 'show', $olympus::SOURCE_SETTINGS );
$footer_sibebar_columns = $olympus->get_option( 'footer-widget-block-columns', 3, $olympus::SOURCE_SETTINGS );
$site_description_show = $olympus->get_option( 'site_description/show', 'no', $olympus::SOURCE_SETTINGS );
$description_columns = $olympus->get_option( 'site_description/yes/text_columns', 4, $olympus::SOURCE_SETTINGS );
$show_footer_logo = $olympus->get_option( 'site_description/yes/footer-logo/show', 'no', $olympus::SOURCE_SETTINGS );
$description_text = $olympus->get_option( 'site_description/yes/text', '', $olympus::SOURCE_SETTINGS );
$description_text_align = $olympus->get_option( 'site_description/yes/text-alignment', 'flex-start', $olympus::SOURCE_SETTINGS );
$description_socials = $olympus->get_option( 'site_description/yes/social_networks', array(), $olympus::SOURCE_SETTINGS );

$options = array(
    'footer-options' => array(
        'title'      => esc_html__( 'Widgets section', 'olympus' ),
        'options' => array(
            'footer-widget-block-columns'    => array(
                'type'       => 'slider',
                'value'      => $footer_sibebar_columns,
                'properties' => array(
                    'min'        => 2,
                    'max'        => 12,
                    'step'       => 1,
                    'grid_snap'  => true,
                ),
                'label'      => esc_html__( 'Widget block width', 'olympus' ),
                'desc'       => esc_html__( 'Select width in 12 column grid', 'olympus' ),
                'help'       => esc_html__( 'More about grid and columns you can read here', 'olympus' ) . ' - ' . $grid_link,
            ),
            'site_description'               => array(
                'type'       => 'multi-picker',
                'label'      => false,
                'desc'       => false,
                'picker'     => array(
                    'show' => array(
                        'label'          => esc_html__( 'Show text block', 'olympus' ),
                        'type'           => 'switch',
                        'left-choice'    => array(
                            'value'  => 'no',
                            'label'  => esc_html__( 'Disable', 'olympus' )
                        ),
                        'right-choice'   => array(
                            'value'  => 'yes',
                            'label'  => esc_html__( 'Enable', 'olympus' )
                        ),
                        'value' => $site_description_show,
                        'desc' => esc_html__( 'Text block with description in footer', 'olympus' ),
                    ),
                ),
                'choices'    => array(
                    'yes' => array(
                        'text_columns'       => array(
                            'type'       => 'slider',
                            'value'      => $description_columns,
                            'properties' => array(
                                'min'        => 2,
                                'max'        => 12,
                                'step'       => 1,
                                'grid_snap'  => true,
                            ),
                            'label'      => esc_html__( 'Text block width', 'olympus' ),
                            'desc'       => esc_html__( 'Select width in 12 column grid', 'olympus' ),
                            'help'       => esc_html__( 'More about grid and columns you can read here', 'olympus' ) . ' - ' . $grid_link,
                        ),
                        'footer-logo'        => array(
                            'type'       => 'multi-picker',
                            'label'      => false,
                            'desc'       => false,
                            'picker'     => array(
                                'show' => array(
                                    'label'          => esc_html__( 'Show logotype block?', 'olympus' ),
                                    'type'           => 'switch',
                                    'right-choice'   => array(
                                        'value'  => 'yes',
                                        'label'  => esc_html__( 'Show', 'olympus' ),
                                    ),
                                    'left-choice'    => array(
                                        'value'  => 'no',
                                        'label'  => esc_html__( 'Hide', 'olympus' ),
                                    ),
                                    'value' => $show_footer_logo,
                                ),
                            ),
                            'choices'    => array(
                                'yes' => array(
                                    'logo-options' => fw()->theme->get_options( 'settings-logo' ),
                                ),
                            ),
                        ),
                        'text'               => array(
                            'type'           => 'wp-editor',
                            'label'          => esc_html__( 'Text block Content', 'olympus' ),
                            'desc'           => esc_html__( 'Text in left footer column', 'olympus' ),
                            'value'          => $description_text,
                            'tinymce'        => true,
                            'media_buttons'  => true,
                            'wpautop'        => true,
                            'size'           => 'small',
                            'editor_type'    => 'tinymce',
                            'editor_height'  => 200,
                        ),
                        'text-alignment'     => array(
                            'label'      => esc_html__( 'Text block alignment', 'olympus' ),
                            'desc'       => esc_html__( 'Choose the block alignment', 'olympus' ),
                            'type'       => 'image-picker',
                            'value'      => $description_text_align,
                            'choices'    => array(
                                'flex-start' => array(
                                    'small' => array(
                                        'height' => 50,
                                        'src'    => $theme_template_directory . '/images/admin/left-position.jpg',
                                        'title'  => esc_html__( 'Left', 'olympus' )
                                    ),
                                ),
                                'center'     => array(
                                    'small' => array(
                                        'height' => 50,
                                        'src'    => $theme_template_directory . '/images/admin/center-position.jpg',
                                        'title'  => esc_html__( 'Center', 'olympus' )
                                    ),
                                ),
                                'flex-end'   => array(
                                    'small' => array(
                                        'height' => 50,
                                        'src'    => $theme_template_directory . '/images/admin/right-position.jpg',
                                        'title'  => esc_html__( 'Right', 'olympus' )
                                    ),
                                ),
                            ),
                        ),
                        'social_networks'    => array(
                            'type'               => 'addable-box',
                            'label'              => esc_html__( 'Social networks', 'olympus' ),
                            'box-options'        => array(
                                'link'   => array(
                                    'label'  => esc_html__( 'Link to social network page', 'olympus' ),
                                    'type'   => 'text',
                                ),
                                'icon'   => array(
                                    'label'      => esc_html__( 'Icon', 'olympus' ),
                                    'type'       => 'select',
                                    'choices'    => olympus_social_network_icons( true ),
                                ),
                            ),
                            'template'           => 'Icon - {{- icon }}',
                            'limit'              => 0,
                            'add-button-text'    => esc_html__( 'Add icon', 'olympus' ),
                            'desc'               => esc_html__( 'Icons of social networks with links to profile', 'olympus' ),
                            'sortable'           => true,
                            'value'              => $description_socials
                        ),
                    ),
                ),
            ),
        ),
    ),
    'section_footer_design' => array(
        'title'   => esc_html__( 'Footer Design', 'olympus' ),
        'options' => array(
            'footer_wide_content' => array(
                'type'         => 'switch',
                'value'        => 'container',
                'label'        => esc_html__( 'Wide content?', 'olympus' ),
                'left-choice'  => array(
                    'value' => 'container',
                    'label' => esc_html__( 'No', 'olympus' ),
                ),
                'right-choice' => array(
                    'value' => 'container-fluid',
                    'label' => esc_html__( 'Yes', 'olympus' ),
                ),
            ),
            
            'footer_text_color'   => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Text Color', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
            ),
            'footer_title_color'  => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Widget Titles Color', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
            ),
            'footer_link_color'   => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Widget Links Color', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
            ),
            'footer_bg_color'     => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Background Color', 'olympus' ),
                'desc'  => esc_html__( 'If you choose no image to display - that color will be set as background', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
            ),
            'footer_bg_image'     => array(
                'type'    => 'background-image',
                'label'   => esc_html__( 'Background image', 'olympus' ),
                'desc'    => esc_html__( 'Select one of images or upload your own pattern', 'olympus' ),
                'choices' => false,
            ),
            'footer_bg_cover'     => array(
                'type'  => 'switch',
                'label' => esc_html__( 'Expand background', 'olympus' ),
                'desc'  => esc_html__( 'Don\'t repeat image and expand it to full section background', 'olympus' ),
            ),
        ),
    ),
    'section_scroll_top'    => array(
        'title'   => esc_html__( 'Scroll Top Button', 'olympus' ),
        'options' => array(
            'scroll_top_icon' => array(
                'type'   => 'multi-picker',
                'label'  => false,
                'desc'   => false,
                'picker' => array(
                    'value' => array(
                        'label'          => esc_html__( 'Scroll to top button', 'olympus' ),
                        'type'           => 'switch',
                        'right-choice'   => array(
                            'value'  => 'show',
                            'label'  => esc_html__( 'Show', 'olympus' ),
                        ),
                        'left-choice'    => array(
                            'value'  => 'hide',
                            'label'  => esc_html__( 'Hide', 'olympus' ),
                        ),
                        'value'          => $scroll_top_icon,
                        'desc'           => esc_html__( 'Show or hide button that scroll page to top on click.', 'olympus' ),
                    ),
                )
            ),
            'totop_bg_color'   => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Background Color', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
            ),
            'totop_icon_color' => array(
                'type'  => 'color-picker',
                'label' => esc_html__( 'Icon Color', 'olympus' ),
                'help'  => esc_html__( 'Click on field to choose color or clear field for default value', 'olympus' ),
            ),
        ),
    ),
    'footer-copyright-tab'   => array(
        'title'      => esc_html__( 'Copyright section', 'olympus' ),
        'options'    => array(
            'footer_copyright' => array(
                'type'   => 'textarea',
                'label'  => esc_html__( 'Copyright text', 'olympus' ),
                'value'  => $footer_copyright,
            ),
            
        ),
    ),
);


