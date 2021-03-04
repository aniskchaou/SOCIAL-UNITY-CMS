<?php

/**
 * Make RTmedia compatible with Youzer.
 */
function yz_rtmedia_main_template_include( $old_template ) {
    
    if ( yz_is_ajax_call() ) {
        return $old_template;
    }

    $new_template = $old_template;

    if ( bp_is_user() ) {
        $new_template = YZ_TEMPLATE . 'profile-template.php';
    } elseif ( bp_is_group() ) {
        $new_template = YZ_TEMPLATE . 'groups/single/home.php';
    }
        
    return apply_filters( 'yz_rtmedia_media_include', $new_template, $old_template );

}

add_filter( 'rtmedia_media_include', 'yz_rtmedia_main_template_include', 0 );

/**
 * Get Rtmedia Content
 */
function yzc_add_rtmedia_content() {

    global $rtmedia_query;  
    
    if ( $rtmedia_query ) {  
        include_once YZ_TEMPLATE . 'rtmedia/main.php';
    }

}

add_action( 'yz_group_main_column', 'yzc_add_rtmedia_content' );
add_action( 'yz_profile_main_column', 'yzc_add_rtmedia_content' );

// Add Activity Filter.
add_filter( 'yz_activity_template_id', 'yz_buddypress_id' );

// Add Profile Template Filter.
function yz_set_profile_template_id( $id ) {

    if ( bp_is_activity_component() ) {
        $id =  'buddypress';
    }
    return $id;
}

add_action( 'yz_profile_template_id', 'yz_set_profile_template_id' );


// /**
//  * Disable Youzer Template Dor Rtmedia Ajax Call.
//  */
// function yz_rtmedia_disable_youzer_template( $new_template, $old_template ) {
	
// 	if ( yz_is_ajax_call() ) {
// 		return $old_template;
// 	}

// 	return $new_template;
// }

// add_filter( 'youzer_template', 'yz_rtmedia_disable_youzer_template', 10, 2 );