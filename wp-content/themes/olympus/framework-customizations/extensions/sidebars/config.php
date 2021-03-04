<?php

$cfg = array();

$cfg[ 'sidebar_positions' ]  = array(
    'full'  => array(
        'icon_url'        => 'full.png',
        'sidebars_number' => 0,
    ),
    'right' => array(
        'icon_url'        => 'right.png',
        'sidebars_number' => 1,
    ),
    'left'  => array(
        'icon_url'        => 'left.png',
        'sidebars_number' => 1,
    ),
);
$cfg[ 'show_in_post_types' ] = true;

$cfg[ 'dynamic_sidebar_args' ] = array(
    'before_widget' => '<div id="%1$s" class="widget ui-block %2$s">',
    'after_widget'  => '</div>',
    'before_title'  => '<div class="ui-block-title"><h6 class="title">',
    'after_title'   => '</h6></div>',
);
