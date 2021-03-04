<?php

$extension = fw()->extensions->get( 'post-reaction' );
$img_path  = $extension->locate_URI( '/static/img' );

$options = array(
    'general' => array(
        'title'   => __( 'General', 'fw' ),
        'type'    => 'box',
        'options' => array(
            'show-reactions'      => array(
                'type'  => 'checkbox',
                'label' => __( 'Show Reactions', 'fw' ),
                'value' => true,
            ),
            'available-reactions' => array(
                'type'            => 'addable-box',
                'label'           => __( 'Available reactions', 'fw' ),
                'value'           => array(
                    array(
                        'title' => __( 'Amazed', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-amazed',
                    ),
                    array(
                        'title' => __( 'Anger', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-anger',
                    ),
                    array(
                        'title' => __( 'Bad', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-bad',
                    ),
                    array(
                        'title' => __( 'Cool', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-cool',
                    ),
                    array(
                        'title' => __( 'Joy', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-joy',
                    ),
                    array(
                        'title' => __( 'Like', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-like',
                    ),
                    array(
                        'title' => __( 'Lol', 'crum-ext-post-reaction' ),
                        'ico'   => 'crumina-reaction-lol',
                    ),
                ),
                'box-options'     => array(
                    'title' => array( 'type' => 'text' ),
                    'ico'   => array(
                        'type'    => 'image-picker',
                        'blank'   => true,
                        'choices' => array(
                            'crumina-reaction-amazed' => "{$img_path}/crumina-reaction-amazed.png",
                            'crumina-reaction-anger'  => "{$img_path}/crumina-reaction-anger.png",
                            'crumina-reaction-bad'    => "{$img_path}/crumina-reaction-bad.png",
                            'crumina-reaction-cool'   => "{$img_path}/crumina-reaction-cool.png",
                            'crumina-reaction-joy'    => "{$img_path}/crumina-reaction-joy.png",
                            'crumina-reaction-like'   => "{$img_path}/crumina-reaction-like.png",
                            'crumina-reaction-lol'    => "{$img_path}/crumina-reaction-lol.png",
                        )
                    ),
                ),
                'template'        => '{{- title }}',
                'limit'           => 0,
                'add-button-text' => __( 'Add', 'fw' ),
                'sortable'        => true,
            )
        ),
    ),
);
