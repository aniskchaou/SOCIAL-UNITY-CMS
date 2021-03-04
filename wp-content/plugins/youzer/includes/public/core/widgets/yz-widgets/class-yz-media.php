<?php

class YZ_Media_Widget {

    /**
     * # Content.
     */
    function widget() {

        if ( ! bp_is_active( 'activity' ) ) {
            return;
        }
        
        // Init Vars.
        $options = '';
        $user_id = bp_displayed_user_id();
        $filters = yz_option( 'yz_wg_media_filters', 'photos,videos,audios,files' );

        if ( ! empty( $filters ) ) {

            if ( false !== strpos( $filters, 'photos' ) ) {
                $options .= " photos_number='" . yz_option( 'yz_wg_max_media_photos', 9 ) . "'";
            }

            if ( false !== strpos( $filters, 'videos' ) ) {
                $options .= " videos_number='" . yz_option( 'yz_wg_max_media_videos', 9 ) . "'";
            }

            if ( false !== strpos( $filters, 'audios' ) ) {
                $options .= " audios_number='" . yz_option( 'yz_wg_max_media_audios', 6 ) . "'";
            }

            if ( false !== strpos( $filters, 'files' ) ) {
                $options .= " files_number='" . yz_option( 'yz_wg_max_media_files', 6 ) . "'";
            }

        }
        
        echo do_shortcode( "[youzer_media user_id='$user_id' box='small' filters='$filters' $options]" );

    }

}