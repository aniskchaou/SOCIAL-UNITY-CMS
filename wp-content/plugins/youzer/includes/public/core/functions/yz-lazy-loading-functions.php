<?php

/**
 * Get Image
 */

add_filter( 'yz_get_image_attributes', 'yz_add_image_attributes_lazy_loading', 10, 2 );

function yz_add_image_attributes_lazy_loading( $img, $img_url ) {
    return "class='lazyload' data-src='$img_url'";
}


/**
 * Add Lazy Tags to Avatars
 */

add_filter( 'bp_core_fetch_avatar', 'yz_add_lazyloading_to_avatars' );
add_filter( 'mycred_the_badge', 'yz_add_lazyloading_to_avatars' );

function yz_add_lazyloading_to_avatars( $html) {

	// Replace Src Attribute.
    $html = str_replace( 'src="', 'data-src="', $html );

	// Add Lazy Loading Class Attribute.
    $html = str_replace( 'class="', 'class="lazyload ', $html );

    return $html;
}

/**
 * Iframe.
 */
add_filter( 'yz_profile_video_widget_url', 'yz_add_iframe_lazyloading' );
function yz_add_iframe_lazyloading( $html ) {
    $html = str_replace( 'src="', 'class="lazyload" data-src="', $html );
    return $html;
}