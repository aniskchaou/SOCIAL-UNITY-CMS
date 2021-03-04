<!doctype html>
<html <?php language_attributes(); ?>>

    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?> itemscope="itemscope" itemtype="https://schema.org/WebPage">

	<div id="overflow-x-wrapper">

        <?php
        /**
         * Hook: crumina_body_start.
         *
         * @hooked _action_olympus_tracking_scripts - 10
         */
        do_action( 'crumina_body_start_landing' );
        ?>