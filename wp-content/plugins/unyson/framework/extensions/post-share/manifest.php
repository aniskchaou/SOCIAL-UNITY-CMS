<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$manifest = array();

$manifest[ 'name' ]         = esc_html__( 'Post Share', 'crum-ext-post-share' );
$manifest[ 'description' ]  = esc_html__( 'Post Share', 'crum-ext-post-share' );
$manifest[ 'remote' ]       = 'https://up.crumina.net/extensions/versions/';
$manifest[ 'version' ]      = '2.6';
$manifest[ 'thumbnail' ]    = plugins_url( 'unyson/framework/extensions/post-share/static/img/thumbnail.png' );
$manifest[ 'display' ]      = true;
$manifest[ 'standalone' ]   = true;