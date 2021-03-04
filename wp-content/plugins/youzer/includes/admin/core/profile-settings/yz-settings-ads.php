<?php

/**
 * # Ads Settings.
 */
function yz_get_ads_settings() {

    wp_enqueue_script( 'yz-ads', YZ_AA . 'js/yz-ads.min.js', array( 'jquery' ), YZ_Version, true );
    wp_localize_script( 'yz-ads', 'Yz_Ads', array(
        'empty_ad' => __( 'Ad name is empty or already exists!', 'youzer' ),
        'empty_banner' => __( 'Banner field is empty !', 'youzer' ),
        'code_empty'   => __( 'Ad code is empty!', 'youzer' ),
        'update_ad'    => __( 'Update Ad', 'youzer' ),
        'no_ads'       => __( 'No ads found!', 'youzer' )
    ));

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'info', 'youzer' ),
            'id'       => 'yz_msgbox_ads_placement',
            'msg'      => __( 'All the ads created will be added automatically to the bottom of the profile sidebar to change their placement or control their visibility go to <strong>Youzer Panel > Profile Settings > Profile Structure</strong>.', 'youzer' )
        )
    );

    $modal_args = array(
        'id'        => 'yz-ads-form',
        'title'     => __( 'Create New Ad', 'youzer' ),
        'button_id' => 'yz-add-ad'
    );

    // Get 'Create new ad' Form.
    yz_panel_modal_form( $modal_args, 'yz_create_new_AD_form' );

    // Get Exists Ads.
    yz_get_ads();

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'Choose how you want your ad to be loaded?', 'youzer' ),
            'id'    => 'yz_ads_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * # Create New AD Form.
 */
function yz_create_new_AD_form() {

    // Get Data.
    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-ads-form'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Is Sponsored?', 'youzer' ),
            'desc'       => __( 'Display "Sponsored" title above the ad', 'youzer' ),
            'id'         => 'yz_ad_is_sponsored',
            'type'       => 'checkbox',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Ad Name', 'youzer' ),
            'id'         => 'yz_ad_title',
            'desc'       => __( "You'll use it in the profile structure", 'youzer' ),
            'type'       => 'text',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Ad type', 'youzer' ),
            'id'         => 'yz_ad_type',
            'desc'       => __( 'Choose the ad type', 'youzer' ),
            'std'        => 'banner',
            'no_options' => true,
            'type'       => 'radio',
            'opts'       => array(
                'banner'  => __( 'Banner', 'youzer' ),
                'adsense' => __( 'Adsense Code', 'youzer' )
            ),
        )
    );

    //Banner Options
    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-adbanner-items'
        )
    );

        $Yz_Settings->get_field(
            array(
                'title'      => __( 'Ad URL', 'youzer' ),
                'id'         => 'yz_ad_url',
                'desc'       => __( 'Ad banner link URL', 'youzer' ),
                'type'       => 'text',
                'no_options' => true
            )
        );

         $Yz_Settings->get_field(
            array(
                'title'      => __( 'Ad Banner', 'youzer' ),
                'id'         => 'yz_ad_banner',
                'desc'       => __( 'Uplaod ad banner image', 'youzer' ),
                'type'       => 'upload',
                'no_options' => true
            )
        );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

    // Ad Code Options
    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-adcode-item'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Ad Code', 'youzer' ),
            'id'         => 'yz_ad_code',
            'desc'       => __( 'Put your adsense code here', 'youzer' ),
            'type'       => 'textarea',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

    // Add Hidden Input
    $Yz_Settings->get_field(
        array(
            'id'         => 'yz_ads_form',
            'type'       => 'hidden',
            'class'      => 'yz-keys-name',
            'std'        => 'yz_ads',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get Ads List
 */
function yz_get_ads() {

    global $Yz_Settings;

    // Get Ads Items
    $yz_ads = yz_option( 'yz_ads' );

    // Next Ad ID
    $yz_nextAD = yz_option( 'yz_next_ad_nbr' );
    $yz_nextAD = ! empty( $yz_nextAD ) ? $yz_nextAD : 1;

    ?>

    <script> var yz_nextAD = <?php echo $yz_nextAD; ?>; </script>

    <div class="yz-custom-section">
        <div class="yz-cs-head">
            <div class="yz-cs-buttons">
                <button class="yz-md-trigger yz-ads-button" data-modal="yz-ads-form">
                    <i class="fas fa-plus-circle"></i>
                    <?php _e( 'Add New Ad', 'youzer' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="yz_ads" class="yz-cs-content">

    <?php

        // Show No Ads Found .
        if ( empty( $yz_ads ) ) {
            echo "<p class='yz-no-content yz-no-ads'>" . __( 'No ads found!', 'youzer' ) . "</p></ul>";
            return false;
        }

        foreach ( $yz_ads as $ad => $data ) :

            // Get Widget Data.
            $url            = $data['url'];
            $code           = $data['code'];
            $type           = $data['type'];
            $title          = $data['title'];
            $banner         = $data['banner'];
            $is_sponsored   = $data['is_sponsored'];

            // Ad photo background.
            $banner_img = ( $type == 'banner' ) ? "style='background-image:url($banner);'" : null;
            $code_icon  = ( $type == 'adsense' ) ? 'yz_show_icon' : 'yz_hide_icon';

            // Get Field Name.
            $name = "yz_ads[$ad]";

            ?>

            <!-- AD Item -->
            <li class="yz-ad-item" data-ad-name="<?php echo $ad; ?>">
                <div class="yz-ad-img <?php echo $code_icon; ?>" <?php echo $banner_img; ?>>
                    <i class="fas fa-code"></i>
                </div>
                <div class="yz-ad-data">
                    <h2 class="yz-ad-title"><?php echo $title; ?></h2>
                    <div class="yz-ad-actions">
                        <a class="yz-edit-item yz-edit-ad"></a>
                        <a class="yz-delete-item yz-delete-ad"></a>
                    </div>
                </div>
                <!-- Data Inputs -->
                <input type="hidden" name="<?php echo $name; ?>[url]" value="<?php echo $url; ?>">
                <input type="hidden" name="<?php echo $name; ?>[code]" value="<?php echo $code; ?>">
                <input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $type; ?>">
                <input type="hidden" name="<?php echo $name; ?>[title]" value="<?php echo $title; ?>">
                <input type="hidden" name="<?php echo $name; ?>[banner]" value="<?php echo $banner; ?>">
                <input type="hidden" name="<?php echo $name; ?>[is_sponsored]" value="<?php echo $is_sponsored; ?>">
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}