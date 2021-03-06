<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$olympus = Olympus_Options::get_instance();
$blog_post_elements_value = $olympus->get_option( 'blog_post_elements', array(), $olympus::SOURCE_SETTINGS );

$options = array(
    'section_general' => array(
        'title' => esc_html__( 'General', 'olympus' ),
        'options' => fw()->theme->get_options( 'partial-blog-general' )
    ),
    'section_sort_panel' => array(
        'title' => esc_html__( 'Sort panel', 'olympus' ),
        'options' => fw()->theme->get_options( 'partial-blog-panel' )
    ),
    'section_post_elements'        => array(
        'title'   => esc_html__( 'Post Elements', 'olympus' ),
        'options' => array(
            'blog_post_elements' => array(
                'type'          => 'multi',
                'value'         => $blog_post_elements_value,
                'attr'          => array(
                    'class' => 'fw-option-type-multi-show-borders',
                ),
                'inner-options' => array(
                    'blog_post_categories' => array(
                        'label'        => esc_html__( 'Categories labels', 'olympus' ),
                        'type'         => 'switch',
                        'left-choice' => array(
                            'value' => 'no',
                            'label' => esc_html__( 'Disable', 'olympus' )
                        ),
                        'right-choice'  => array(
                            'value' => 'yes',
                            'label' => esc_html__( 'Enable', 'olympus' )
                        ),
                    ),
                    'blog_post_meta'       => array(
                        'label'        => esc_html__( 'Meta info', 'olympus' ),
                        'type'         => 'switch',
                        'left-choice' => array(
                            'value' => 'no',
                            'label' => esc_html__( 'Disable', 'olympus' )
                        ),
                        'right-choice'  => array(
                            'value' => 'yes',
                            'label' => esc_html__( 'Enable', 'olympus' )
                        ),
                    ),
                    'blog_post_reactions'  => array(
                        'label'        => esc_html__( 'Reactions', 'olympus' ),
                        'type'         => 'switch',
                        'left-choice' => array(
                            'value' => 'no',
                            'label' => esc_html__( 'Disable', 'olympus' )
                        ),
                        'right-choice'  => array(
                            'value' => 'yes',
                            'label' => esc_html__( 'Enable', 'olympus' )
                        ),
                    ),
                    'blog_post_excerpt'    => array(
                        'type'         => 'multi-picker',
                        'label'        => false,
                        'desc'         => false,
                        'picker'       => array(
                            'value' => array(
                                'label'        => esc_html__( 'Short post excerpt', 'olympus' ),
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
                        'choices'      => array(
                            'yes' => array(
                                'length' => array(
                                    'type'  => 'short-text',
                                    'label' => esc_html__( 'Excerpt length in words', 'olympus' ),
                                    'desc'  => esc_html__( 'Not working for Classic mode', 'olympus' ),
                                    'value' => '20',
                                ),
                            ),
                        ),
                        'show_borders' => false,
                    ),
                )
            )
        ),
    ),
    'section_single_post_elements' => array(
        'title' => esc_html__( 'Single Post Elements', 'olympus' ),
        'options' => array_merge( array( 'single_post_style' => fw()->theme->get_options( 'partial-single-post-style' ) ), fw()->theme->get_options( 'partial-single-post-elements' ) )
    )
);