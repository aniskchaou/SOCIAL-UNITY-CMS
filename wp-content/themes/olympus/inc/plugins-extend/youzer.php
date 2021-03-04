<?php
$options                 = get_theme_mod( 'fw_options' );
$youzer_styles_customize = olympus_akg( 'enable_youzer_styles', $options, 'yes' );
$youzer_icons_customize  = olympus_akg( 'enable_youzer_icons', $options, 'yes' );

if ( 'yes' === $youzer_styles_customize ) {
	add_action( 'wp_enqueue_scripts', '_action_olympus_yz_styles', 999 );
}
if ( 'yes' === $youzer_icons_customize ) {
	add_action( 'wp_enqueue_scripts', '_action_olympus_yz_icons', 999 );
}

//Register Public Scripts .

function _action_olympus_yz_styles() {

	global $Youzer;

	if ( ! $Youzer ) {
		return;
	}

	$theme_version   = olympus_get_theme_version();
	$enqueued_styles = olympus_enqueued_styles_handle();
	$post_content    = get_post_field( 'post_content', get_the_ID() );

	$youzer_activity_shortcode = has_shortcode( $post_content, 'youzer_activity' ) ? true : false;

	/* ---------------------------------- */
	$enqueue_scripts_array = array('olympus-youzer', 'youzer-customization');

	wp_enqueue_style( 'olympus-youzer', get_theme_file_uri( 'css/youzer/youzer.css' ), 'youzer', $theme_version );
	wp_enqueue_style( 'youzer-customization', get_template_directory_uri() . '/css/youzer-customization.css', false, $theme_version );

	if ( has_shortcode( $post_content, 'youzer_members' ) ) {
		wp_enqueue_style( 'olympus-yz-directories', get_theme_file_uri( 'css/youzer/yz-directories.css' ), 'yz-directories', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-directories');
	}

	if ( in_array( 'yz-wall', $enqueued_styles ) || true === $youzer_activity_shortcode ) {
		wp_enqueue_style( 'olympus-yz-wall', get_theme_file_uri( 'css/youzer/yz-wall.css' ), 'yz-wall', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-wall');
	}

	if ( in_array( 'yz-profile', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-profile', get_theme_file_uri( 'css/youzer/yz-profile-style.css' ), 'yz-profile', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-profile');
	}

	if ( in_array( 'yz-iconpicker', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-iconpicker', get_theme_file_uri( 'css/youzer/yz-iconpicker.css' ), 'yz-iconpicker', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-iconpicker');
	}

	if ( in_array( 'yz-headers', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-headers', get_theme_file_uri( 'css/youzer/yz-headers.css' ), 'yz-headers', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-headers');
	}

	if ( in_array( 'yz-woocommerce', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-woocommerce', get_theme_file_uri( 'css/youzer/yz-woocommerce.css' ), 'yz-woocommerce', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-woocommerce');
	}

	if ( in_array( 'yz-social', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-social', get_theme_file_uri( 'css/youzer/yz-social.css' ), 'yz-social', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-social');
	}

	if ( in_array( 'yz-reviews', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-reviews', get_theme_file_uri( 'css/youzer/yz-reviews.css' ), 'yz-reviews', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-reviews');
	}

	if ( in_array( 'yz-mycred', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-mycred', get_theme_file_uri( 'css/youzer/yz-mycred.css' ), 'yz-mycred', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-mycred');
	}

	if ( in_array( 'yz-mycred', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-mycred', get_theme_file_uri( 'css/youzer/yz-mycred.css' ), 'yz-mycred', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-mycred');
	}

	if ( in_array( 'yz-bbpress', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-bbpress', get_theme_file_uri( 'css/youzer/yz-bbpress.css' ), 'yz-bbpress', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-bbpress');
	}

	if ( in_array( 'yz-groups', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-groups', get_theme_file_uri( 'css/youzer/yz-groups.css' ), 'yz-groups', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-groups');
	}

	if ( in_array( 'yz-directories', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-directories', get_theme_file_uri( 'css/youzer/yz-directories.css' ), 'yz-directories', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-directories');
	}

	if ( in_array( 'yz-account', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-yz-account', get_theme_file_uri( 'css/youzer/yz-account-style.css' ), 'yz-account-css', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-yz-account');
	}

	if ( in_array( 'logy-style', $enqueued_styles ) ) {
		wp_enqueue_style( 'olympus-logy-style', get_theme_file_uri( 'css/youzer/logy.css' ), 'logy-style', $theme_version );
		array_push($enqueue_scripts_array, 'olympus-logy-style');
	}

	/* Disable unused font from Youzer */
	wp_dequeue_style( 'yz-opensans' );
	wp_dequeue_style( 'yz-roboto' );
	wp_dequeue_style( 'yz-lato' );

	/* Minify css from Youzer */
	if(function_exists('fw_get_db_customizer_option')){
		$minify_theme_css = fw_get_db_customizer_option( 'minify-theme-css' );
		if($minify_theme_css == 'enable'){
			olympus_minify_css($enqueue_scripts_array, '/minify-youzer.css');
			wp_enqueue_style( 'olympus-youzer-minify', trailingslashit( wp_upload_dir()['baseurl'] ) . 'olympus-minify/minify-youzer.css' );
		}
	}
}

function _action_olympus_yz_icons() {
	$theme_version = olympus_get_theme_version();

	wp_enqueue_style( 'olympus-youzer-icons-customization', get_theme_file_uri( 'css/youzer/yz-icons-customization.css' ), array(
		'youzer',
		'yz-icons'
	), $theme_version );
}



/**
 * User Balance WP Widget
 */

function crum_mycred_user_balance_wp_widget() {
	$regWdgt = 'register_' . 'widget';
	$regWdgt( 'YZ_Mycred_Balance_Widget' );
}

add_action( 'widgets_init', 'crum_mycred_user_balance_wp_widget' );


/** *
 * Change Some default options value
 */

/*add_filter( 'youzer_edit_options', '_filter_olympus_yz_edit_options', 10, 2 );*/

function _filter_olympus_yz_edit_options( $option_value, $option_id ) {

	switch ( $option_id ) {
		case 'yz_enable_settings_copyright':
			$option_value = 'off';
			break;
	}

	return $option_value;
}


function _filter_olympus_yz_default_options( $options ) {
	return olympus_array_merge( $options, array(
		'yz_enable_settings_copyright'    => 'off',
		'yz_display_scrolltotop'          => 'off',
		'yz_plugin_content_width'         => '1300',
		'yz_plugin_background'            => array( 'color' => '#edf2f6' ),
		'yz_profile_scheme'               => 'yz-darkorange-scheme',
		'yz_panel_scheme'                 => 'uk-orange-scheme',
		'yz_enable_profile_custom_scheme' => 'on',
		'yz_buttons_border_style'         => 'radius',
		'yz_tabs_list_icons_style'        => 'yz-tabs-list-gray',
		'yz_profile_custom_scheme_color'  => array( 'color' => '#ff5e3a' ),
	) );
}

/*add_filter( 'yz_default_options', '_filter_olympus_yz_default_options' );*/