<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$manifest = array();

$manifest[ 'name' ]			 = esc_html__( 'Extended Search', 'crum-ext-extended-search' );
$manifest[ 'description' ]	 = esc_html__( 'Extended Search', 'crum-ext-extended-search' );
$manifest[ 'remote' ]		 = 'https://up.crumina.net/extensions/versions/';
$manifest[ 'version' ]		 = '2.7';
$manifest[ 'thumbnail' ]	 = plugins_url( 'unyson/framework/extensions/extended-search/static/img/thumbnail.png' );
$manifest[ 'display' ]		 = true;
$manifest[ 'standalone' ]	 = true;

