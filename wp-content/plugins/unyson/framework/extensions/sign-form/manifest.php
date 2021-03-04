<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$manifest = array();

$manifest[ 'name' ]			 = esc_html__( 'Sign in Form', 'crum-ext-sign-form' );
$manifest[ 'description' ]	 = esc_html__( 'Sign in Form.', 'crum-ext-sign-form' );
$manifest[ 'remote' ]		 = 'https://up.crumina.net/extensions/versions/';
$manifest[ 'version' ]		 = '2.6';
$manifest[ 'thumbnail' ]	 = plugins_url( 'unyson/framework/extensions/sign-form/static/img/thumbnail.png' );
$manifest[ 'display' ]		 = true;
$manifest[ 'standalone' ]	 = false;