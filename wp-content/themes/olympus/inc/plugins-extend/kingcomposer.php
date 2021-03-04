<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

// Verify KingKomposer Extended license.
define( 'KC_LICENSE', 'g62osph1-kqfg-o8qb-y7v2-89gm-5tx7ky6un2sh' );

/*
* KingComposer editor additional hooks and actions.
*/
add_action( 'init', 'olympus_kingkomposer_modifications', 999 );


// Plain HTML field for composer admin panel.
function kc_olympus_html_field() {
	echo '<div id="{{data.name}}" class="kc-param">{{{data.value}}}</div>';
}

// Number field for composer admin panel.
function kc_olympus_number_field() {
	echo '<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="number" min="1" />';
}

// Proper date field for composer admin panel.
function kc_olympus_date_field() { ?>
	<input name="{{data.name}}" class="kc-param" value="{{data.value}}" type="text"/>
	<#
			data.callback = function( wrp, $ ){
			var d = new Pikaday(
			{
			field: wrp.find('.kc-param').get(0),
			firstDay: 1,
			formatStrict:true,
			format: 'L',
			minDate: false,
			maxDate: false,
			yearRange: [2000,2020],
			});
			}
			#>
	<?php
}


/**
 * Theme modifications and new modules.
 */
function olympus_kingkomposer_modifications() {
	global $kc;

	// Add new parameters for composer.
	$kc->add_param_type( 'html-full', 'kc_olympus_html_field' );
	$kc->add_param_type( 'crum-number', 'kc_olympus_number_field' );
	$kc->add_param_type( 'crum_date_picker', 'kc_olympus_date_field' );

	// Add custom icon pack.
	// TODO add icon or delete this.
	//	if ( function_exists( 'kc_add_icon' ) ) {
	//		kc_add_icon( get_template_directory_uri() . '/css/seotheme.css' );
	//	}

	// Remove some default modules.
	if ( function_exists( 'kc_remove_map' ) ) {
		kc_remove_map( 'kc_nested' );
		kc_remove_map( 'kc_box' );
		kc_remove_map( 'kc_coundown_timer' );
		kc_remove_map( 'kc_divider' );
		kc_remove_map( 'kc_pricing' );
		kc_remove_map( 'kc_image_hover_effects' );
		kc_remove_map( 'kc_creative_button' );
		kc_remove_map( 'kc_tooltip' );
		kc_remove_map( 'kc_blog_posts' );
		kc_remove_map( 'kc_post_type_list' );
		kc_remove_map( 'kc_creative_button' );
		kc_remove_map( 'kc_flip_box' );
		kc_remove_map( 'kc_progress_bars' );
		kc_remove_map( 'kc_pie_chart' );
		kc_remove_map( 'kc_button' );
		kc_remove_map( 'kc_title' );
		kc_remove_map( 'kc_accordion' );
		kc_remove_map( 'kc_team' );
		kc_remove_map( 'kc_single_image' );
		kc_remove_map( 'kc_dropcaps' );
		kc_remove_map( 'kc_google_maps' );
		kc_remove_map( 'kc_video_play' );
		kc_remove_map( 'kc_counter_box' );
		kc_remove_map( 'kc_icon' );
		kc_remove_map( 'kc_feature_box' );
		kc_remove_map( 'kc_testimonial' );
		kc_remove_map( 'kc_call_to_action' );
		kc_remove_map( 'kc_carousel_post' );
		kc_remove_map( 'kc_contact_form7' );
		kc_remove_map( 'kc_image_gallery' );
		kc_remove_map( 'kc_image_fadein' );
		kc_remove_map( 'kc_carousel_images' );
	}
}