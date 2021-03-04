<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$cfg = array();

$cfg[ 'menuLocation' ] = 'menu-vcard';

$cfg[ 'builderComponent' ] = 'sign-form';
$cfg[ 'registerLinkSC' ]   = 'register-link';
$cfg[ 'currentUserSC' ]    = 'current-user';

$cfg[ 'actions' ] = array(
    'signIn' => 'crumina-signin-form',
    'signUp' => 'crumina-signup-form'
);

$cfg[ 'selectors' ] = array(
    //It will be classes
    'form'          => 'crumina-sign-form',
    'formContainer' => 'crumina-sign-form-container',
    'formRegister'  => 'crumina-sign-form-register',
    'formLogin'     => 'crumina-sign-form-login',
);
