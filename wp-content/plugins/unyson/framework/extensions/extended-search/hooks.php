<?php

$ext           = fw_ext( 'extended-search' );
$shortcodeName = $ext->get_config( 'shortcodeName' );
$actionName    = $ext->get_config( 'ajaxActionName' );

add_filter( "vc_before_init", 'FW_Extension_Extended_Search::vc_mapping' );

add_filter( 'init', 'FW_Extension_Extended_Search::kc_mapping' );