<?php

$options = array(
    'title'    => 'Top User Panel',
    'type'     => 'tab',
    'priority' => 'high',
    'options'  => array(
        fw()->theme->get_options( 'partial-top-user-panel-visibility' ),
    )
);