<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$manifest = array();

$manifest[ 'name' ]         = __( 'Contact form', 'crumina' );
$manifest[ 'description' ]  = __( 'Contact form.', 'crumina' );
$manifest[ 'remote' ]       = 'https://up.crumina.net/extensions/versions/';
$manifest[ 'version' ]      = '1.0.21';
$manifest[ 'thumbnail' ]    = plugins_url( 'unyson/framework/extensions/contact-form/static/img/thumbnail.png' );
$manifest[ 'display' ]      = true;
$manifest[ 'standalone' ]   = false;
$manifest[ 'requirements' ] = array(
    'extensions' => array(
        'shortcodes' => array(),
        'builder' => array(),
        'forms' => array(),
    )
);
