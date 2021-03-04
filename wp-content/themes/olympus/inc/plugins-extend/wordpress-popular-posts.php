<?php
/*
  Add filters
 *  */

add_action( 'wp_enqueue_scripts', '_action_olympus_wordpress_popular_posts_customization_scripts', 999 );

function _action_olympus_wordpress_popular_posts_customization_scripts() {
    $theme_version = olympus_get_theme_version();

    wp_enqueue_style( 'wordpress-popular-posts-customization', get_template_directory_uri() . '/css/wordpress-popular-posts-customization.css', false, $theme_version );
}
