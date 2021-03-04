<?php

$options = array(
    'title'    => 'Top Menu Panel',
    'type'     => 'tab',
    'priority' => 'high',
    'options'  => array(
        fw()->theme->get_options( 'partial-top-menu-panel-visibility' ),
    )
);