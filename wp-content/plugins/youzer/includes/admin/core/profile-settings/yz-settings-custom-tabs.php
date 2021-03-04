<?php

/**
 * # Custom Tabs Settings.
 */
function yz_profile_custom_tabs_settings() {

    wp_enqueue_script( 'yz-custom-tabs', YZ_AA . 'js/yz-custom-tabs.min.js', array( 'jquery' ), YZ_Version, true );
    wp_localize_script( 'yz-custom-tabs', 'Yz_Custom_Tabs', array(
        'tab_url_empty'   => __( 'Tab link URL is empty!', 'youzer' ),
        'no_custom_tabs'  => __( 'No custom tabs found!', 'youzer' ),
        'tab_code_empty'  => __( 'Tab content is empty!', 'youzer' ),
        'tab_title_empty' => __( 'Tab title is empty!', 'youzer' ),
        'update_tab'      => __( 'Update Tab', 'youzer' )
    ) );

    // Get New Custom Tabs Form.
    yz_panel_modal_form( array(
        'id'        => 'yz-custom-tabs-form',
        'title'     => __( 'Create New Tab', 'youzer' ),
        'button_id' => 'yz-add-custom-tab'
    ), 'yz_profile_custom_tabs_form' );

    // Get Custom Tabs List.
    yz_profile_custom_tabs_list();

}

/**
 * # Create New Custom Widgets Form.
 */
function yz_profile_custom_tabs_form() {

    // Get Data.
    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-custom-tabs-form'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Show for Non Logged-in', 'youzer' ),
            'desc'       => __( 'Display tab for non logged-in users', 'youzer' ),
            'id'         => 'yz_tab_display_nonloggedin',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'Tab Title', 'youzer' ),
            'desc'         => __( 'Add tab title', 'youzer' ),
            'id'           => 'yz_tab_title',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'Tab Slug', 'youzer' ),
            'desc'         => __( 'Should be in english lowercase letters and without spaces you can use only underscores to link words example: new_company', 'youzer' ),
            'id'           => 'yz_tab_slug',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Tab Type', 'youzer' ),
            'id'         => 'yz_tab_type',
            'desc'       => __( 'Choose the tab type', 'youzer' ),
            'std'        => 'link',
            'no_options' => true,
            'type'       => 'radio',
            'opts'       => array(
                'link'    => __( 'Link', 'youzer' ),
                'shortcode' => __( 'Shortcode', 'youzer' )
            ),
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Tab Link', 'youzer' ),
            'id'         => 'yz_tab_link',
            'desc'       => __( 'You can use the tag {username} in the link and it will be replaced by the displayed profile user id.', 'youzer' ),
            'class'		 => 'yz-custom-tabs-link-item',
            'type'       => 'text',
            'no_options' => true
        )
    );


    // Tabs ShortCode Options
    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-custom-tabs-shortcode-items'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Display page sidebar', 'youzer' ),
            'desc'       => __( 'Show page sidebar works only on horizontal layout', 'youzer' ),
            'id'         => 'yz_tab_display_sidebar',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Tab Content', 'youzer' ),
            'id'         => 'yz_tab_content',
            'desc'       => __( 'Paste your shortcode or any html code. you can use the following tags inside the content :
the tag {displayed_user} will be replaced by the displayed profile user id and the tag {logged_in_user} will be replaced by the logged-in user id.', 'youzer' ),
            'type'       => 'textarea',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

    // Add Hidden Input
    $Yz_Settings->get_field(
        array(
            'id'         => 'yz_custom_tabs_form',
            'type'       => 'hidden',
            'class'      => 'yz-keys-name',
            'std'        => 'yz_custom_tabs',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get Custom Tabs List
 */
function yz_profile_custom_tabs_list() {

    global $Yz_Settings;

    // Get Custom Tabs Items
    $yz_custom_tabs = yz_option( 'yz_custom_tabs' );

    // Next Custom Tab ID
    $next_id = yz_option( 'yz_next_custom_tab_nbr' );
    $yz_nextTab = ! empty( $next_id ) ? $next_id : '1';
    ?>

    <script> var yz_nextTab = <?php echo $yz_nextTab; ?>; </script>

    <div class="yz-custom-section">
        <div class="yz-cs-head">
            <div class="yz-cs-buttons">
                <button class="yz-md-trigger yz-custom-tabs-button" data-modal="yz-custom-tabs-form">
                    <i class="fas fa-plus-circle"></i>
                    <?php _e( 'Add New Tab', 'youzer' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="yz_custom_tabs" class="yz-cs-content">

    <?php

        // Show No Tabs Found .
        if ( empty( $yz_custom_tabs ) ) {
            echo "<p class='yz-no-content yz-no-custom-tabs'>" . __( 'No custom tabs found!', 'youzer' ) .  "</p></ul>";
            return false;
        }

        foreach ( $yz_custom_tabs as $tab => $data ) :

            // Get Field Name.
            $name = "yz_custom_tabs[$tab]";

            ?>

            <!-- Tab Item -->
            <li class="yz-custom-tab-item" data-tab-name="<?php echo $tab; ?>">
                <h2 class="yz-custom-tab-name"><i class="yz-custom-tab-icon fas fa-angle-right"></i><span><?php echo $data['title']; ?></span></h2>
                <input type="hidden" name="<?php echo $name; ?>[slug]" value="<?php echo $data['slug']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[link]" value="<?php echo $data['link']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $data['type']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[title]" value="<?php echo $data['title']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[content]" value="<?php echo $data['content']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[display_sidebar]" value="<?php echo $data['display_sidebar']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[display_nonloggedin]" value="<?php echo $data['display_nonloggedin']; ?>">
                <a class="yz-edit-item yz-edit-custom-tab"></a>
                <a class="yz-delete-item yz-delete-custom-tab"></a>
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}
