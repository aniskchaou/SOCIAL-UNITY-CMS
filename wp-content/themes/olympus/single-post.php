<?php

/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package olympus
 */
get_header();

/**
 *  Use post template file depending on options selected
 *
 *  Template files path: 'parts/blog'
 *
 * */

$olympus = Olympus_Options::get_instance();
$post_style = $olympus->get_option_final( 'single_post_style', 'classic', array('final-source' => 'customizer') );

get_template_part( 'templates/post-single/content', $post_style );

get_footer();
