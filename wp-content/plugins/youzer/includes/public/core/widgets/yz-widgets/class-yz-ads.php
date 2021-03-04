<?php

class YZ_Ads {

    public $ad_name;

    public function __construct( $ad_name ) {
        $this->ad_name = $ad_name;
    }

    /**
     * # Content.
     */
    function widget() {

        // Get ADS.
        $ads = yz_option( 'yz_ads' );

        // Filter Ad Widget
        $ad = apply_filters( 'youzer_edit_ad', $ads[ $this->ad_name ] );

        // Get AD content.
        if ( 'banner' == $ad['type'] ) {
            $ad_content = "<a href='{$ad['url']}' target='_blank'><img loading='lazy' " . yz_get_image_attributes_by_link( $ad['banner'] ) . " alt=''></a>";
        } elseif ( 'adsense' == $ad['type'] ) {
            $ad_content = urldecode( $ad['code'] );
        }

        // Display AD.
        echo "<div class='yz-ad-box'>$ad_content</div>";
    }

}