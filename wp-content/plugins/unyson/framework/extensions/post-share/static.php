<?php

if ( !defined( 'FW' ) ) {
    return;
}
$ext = fw_ext( 'post-share' );

/**
 * Register post share JS script
 */

wp_enqueue_script( 'sharer', $ext->locate_URI( '/static/js/sharer.min.js' ), array('jquery'), '0.3.8', true );
wp_enqueue_script( 'sharer-main', $ext->locate_URI( '/static/js/post-share.js' ), array('jquery'), '', true );
