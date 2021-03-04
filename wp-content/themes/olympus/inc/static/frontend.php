<?php

/**
 * Scripts to include on front pages
 *
 * @package olympus-wp
 */
$theme_version = olympus_get_theme_version();
$enqueue_scripts_array = array();

/* ==============
 * REGISTER
 * 3-rd party plugins
  =============== */

wp_enqueue_script( 'alpine', get_template_directory_uri() . '/js/alpine.min.js', array(), '2.3.3', true );

wp_register_script( 'purecounter', get_template_directory_uri() . '/js/plugins/purecounter.min.js', array(), '1.0.0', true );

wp_enqueue_script( 'magnific-popup-js', get_template_directory_uri() . '/js/plugins/magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );

wp_enqueue_style( 'magnific-popup-css', get_template_directory_uri() . '/css/magnific-popup.css', false, '1.1.0' );
array_push($enqueue_scripts_array, 'magnific-popup-css');

wp_enqueue_script( 'perfect-scrollbar', get_template_directory_uri() . '/js/plugins/perfect-scrollbar.min.js', array( 'jquery' ), '1.5.0', true );

wp_register_script( 'isotope', get_template_directory_uri() . '/js/plugins/isotope.pkgd.js', array( 'imagesloaded' ), '3.0.4', true );

wp_register_script( 'jquery-mousewheel', get_template_directory_uri() . '/js/plugins/jquery-mousewheel.js', array( 'jquery' ), '3.1.13', true );

wp_register_script( 'bootstrap-select', get_template_directory_uri() . '/js/plugins/bootstrap-select.min.js', array( 'jquery' ), '1.13.14', true );

wp_register_script( 'material-forms', get_template_directory_uri() . '/js/plugins/material-forms.js', array( 'jquery' ), '1.0.0', true );

wp_register_script( 'smooth-scroll', get_template_directory_uri() . '/js/plugins/smooth-scroll.js', array( 'jquery' ), '2.1.5', true );


if ( is_page_template( 'blog-template.php' ) ) {
	wp_enqueue_script( 'swiper-js', get_template_directory_uri() . '/js/plugins/swiper.min.js', array( 'jquery', 'olympus-main' ), '5.3.6', true );
	wp_add_inline_script( 'swiper-js', 'jQuery(document).ready(function () {CRUMINA.Swiper.init(jQuery(\'.swiper-container\'));});' );
}



/* ======================
 *  THEME CSS COMPONENTS
  ==================== */
//Unyson
if ( function_exists( 'fw' ) ) {
	fw()->backend->option_type( 'icon-v2' )->packs_loader->enqueue_frontend_css();
	wp_dequeue_style( 'fw-option-type-icon-v2-pack-font-awesome' );
	$all_packs = fw()->backend->option_type( 'icon-v2' )->packs_loader->get_packs();
	$enqueue_packs_array = array();
	if(!empty($all_packs)){
		foreach($all_packs as $pack){
			array_push($enqueue_packs_array, 'fw-option-type-icon-v2-pack-' . $pack['name']);
		}
	}

	// Minify css
	$minify_theme_css = fw_get_db_customizer_option( 'minify-theme-css' );
	if($minify_theme_css == 'enable'){
		olympus_minify_css($enqueue_packs_array, '/minify-icon-packs.css');
		wp_enqueue_style( 'olympus-minify-icon-packs', trailingslashit( wp_upload_dir()['baseurl'] ) . 'olympus-minify/minify-icon-packs.css' );
	}
}

//Font awesome
wp_enqueue_style( 'yz-icons', get_template_directory_uri() . '/css/fontawesome.min.css', array(), '5.12.1' );
array_push($enqueue_scripts_array, 'yz-icons');

// Bootstrap.
wp_register_style( 'bootstrap', get_template_directory_uri() . '/Bootstrap/dist/css/bootstrap.css', array(), '4.4.0' );

// Small JS plugins css
wp_register_style( 'olympus-js-plugins', get_template_directory_uri() . '/css/theme-js-plugins.css', false, $theme_version );

// Add font, used in the main stylesheet.
wp_enqueue_style( 'olympus-theme-font', olympus_font_url(), array(), $theme_version );

wp_register_style( 'olympus-widgets', get_template_directory_uri() . '/css/widgets.css', array(), '5.0.6' );

// Theme.
wp_enqueue_style( 'olympus-main', get_template_directory_uri() . '/css/main.css', array('bootstrap', 'olympus-widgets', 'olympus-js-plugins' ), $theme_version );
array_push($enqueue_scripts_array, 'olympus-main');

// Add icons font, used in the main stylesheet.
wp_enqueue_style( 'olympus-icons-font', get_template_directory_uri() . '/css/olympus-icons-font.css', array(), $theme_version);

if ( is_page_template( 'blog-template.php' ) ) {
	wp_enqueue_style( 'olympus-swiper', get_template_directory_uri() . '/css/swiper.css', array(), $theme_version);
	array_push($enqueue_scripts_array, 'olympus-swiper');
}


/* ======================
 *  Stunning header
  ==================== */
wp_enqueue_style( 'crumina-stunning-header', get_template_directory_uri() . '/css/stunning-header.css', array(), $theme_version);
wp_enqueue_script( 'crumina-stunning-header', get_template_directory_uri() . '/js/stunning-header.js', array( 'jquery' ), $theme_version, true );
array_push($enqueue_scripts_array, 'crumina-stunning-header');

/* ======================
 *  3-D Plugin Styles Integration
  ==================== */

if ( class_exists( 'LearnPress' ) ) {
	wp_enqueue_style( 'olympus-learn-press', get_template_directory_uri() . '/css/learn-press-customization.css', array( 'learn-press' ), $theme_version );
	array_push($enqueue_scripts_array, 'olympus-learn-press');
}
if( class_exists( 'wpForo' ) ) {
	wp_enqueue_style( 'olympus-wp-foro', get_template_directory_uri() . '/css/wp-foro-customization.css', array( 'wpforo-style' ), $theme_version );
	array_push($enqueue_scripts_array, 'olympus-wp-foro');
}
if ( class_exists('Tutor')){
	wp_enqueue_style( 'olympus-tutor-lms', get_template_directory_uri() . '/css/tutor-lms-customization.css', array( 'tutor-frontend' ), $theme_version );
	array_push($enqueue_scripts_array, 'olympus-tutor-lms');
}
if ( class_exists('Adverts')){
	wp_enqueue_style( 'olympus-wpadverts', get_template_directory_uri() . '/css/wpadverts-customization.css', array( 'adverts-frontend' ), $theme_version );
	array_push($enqueue_scripts_array, 'olympus-wpadverts');
}


/* ======================
 *  THEME JS COMPONENTS
  ==================== */
if ( is_singular() ) {
    wp_enqueue_script( 'comment-reply' );
}

wp_register_script( 'olympus-mega-menu', get_template_directory_uri() . '/js/crum-mega-menu.js', array( 'jquery' ), $theme_version, true );

wp_enqueue_script( 'olympus-bootstrap', get_template_directory_uri() . '/Bootstrap/dist/js/bootstrap.bundle.js', array( 'jquery' ), '4.0.0', true );

wp_enqueue_script( 'jquery-scroll-to', get_template_directory_uri() . '/js/plugins/jquery.scrollTo.min.js', array( 'jquery', 'olympus-mega-menu' ), '2.1.2', true );

wp_enqueue_script( 'olympus-main', get_template_directory_uri() . '/js/theme-main.js', array( 'imagesloaded', 'smooth-scroll', 'material-forms', 'olympus-mega-menu' ), $theme_version, true );
wp_localize_script( 'olympus-main', 'themeStrings', array(
    'ajaxUrl' => admin_url( 'admin-ajax.php' ),
    'uploadBtnText' => esc_html__( 'Upload', 'olympus' ),
    'uploadCommentBtnText' => esc_html__( 'Comment', 'olympus' )
) );

$custom_js = ( function_exists( 'fw_get_db_customizer_option' ) ) ? fw_get_db_customizer_option( 'custom-js', '' ) : '';
if ( ! empty( $custom_js ) ) {
	$custom_js = 'jQuery( document ).ready(function($) {  ' . $custom_js . '  });';
	wp_add_inline_script( 'olympus-main', $custom_js );
}

wp_enqueue_script( 'olympus-svg-icons', get_template_directory_uri() . '/js/svg-loader.js', array(), $theme_version,true );
wp_script_add_data( 'olympus-svg-icons', 'async', true );


/* ======================
 *  Plugins customization
  ==================== */

if ( ! class_exists( 'Youzer' ) && function_exists( 'is_bbpress' ) ) {
	if ( is_bbpress() || is_singular() || is_search() ) {
		wp_enqueue_style( 'ol-bbpress', get_theme_file_uri( 'css/bbp-customization.css' ), array(), $theme_version );
		array_push($enqueue_scripts_array, 'ol-bbpress');
	}
}

// Minify css
if(function_exists('fw_get_db_customizer_option')){
	$minify_theme_css = fw_get_db_customizer_option( 'minify-theme-css' );
	if($minify_theme_css == 'enable'){
		olympus_minify_css($enqueue_scripts_array, '/minify.css');
		wp_enqueue_style( 'olympus-minify', trailingslashit( wp_upload_dir()['baseurl'] ) . 'olympus-minify/minify.css' );
	}
}