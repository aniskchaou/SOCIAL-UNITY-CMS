<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$manifest = array();

$manifest[ 'name' ]        = esc_html__( 'Post reaction', 'crumina' );
$manifest[ 'description' ] = esc_html__( 'Post reaction.', 'crumina' );
$manifest[ 'version' ]     = '2.5';
$manifest[ 'display' ]     = true;
$manifest[ 'standalone' ]  = true;
$manifest[ 'remote' ]       = 'https://up.crumina.net/extensions/versions/';
$manifest[ 'thumbnail' ]   = plugins_url( 'unyson/framework/extensions/post-reaction/static/img/thumbnail.png' );
