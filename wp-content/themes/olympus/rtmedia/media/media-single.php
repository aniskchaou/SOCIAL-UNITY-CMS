<?php

global $rt_ajax_request, $rtmedia;

if ( $rt_ajax_request ) {
    get_template_part( 'rtmedia/media/media-single-ajax' );
} else {
    get_template_part( 'rtmedia/media/media-single-base' );
}
