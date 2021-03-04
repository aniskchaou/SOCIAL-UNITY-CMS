<?php

/* -------------------------------------------------------
 Enqueue CSS from child theme style.css
-------------------------------------------------------- */


function crum_child_css() {
	wp_enqueue_style( 'child-style', get_stylesheet_uri() );
}

add_action( 'wp_enqueue_scripts', 'crum_child_css', 99 );


/* -------------------------------------------------------
 You can add your custom functions below
-------------------------------------------------------- */
