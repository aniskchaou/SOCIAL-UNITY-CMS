<?php

/**
 * # Custom Widgets Settings.
 */
function yz_custom_widget_settings() {

    wp_enqueue_script( 'yz-profile-widgets', YZ_AA . 'js/yz-profile-widgets.min.js', array( 'jquery' ), YZ_Version, true );
    wp_localize_script( 'yz-profile-widgets', 'Yz_Custom_Widgets', array(
        'update_widget'         => __( 'Update Widget', 'youzer' ),
        'no_custom_widgets' => __( 'No custom widgets found!', 'youzer' )
    ) );

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'Info', 'youzer' ),
            'id'       => 'yz_msgbox_custom_widgets_placement',
            'msg'      => __( 'All the custom widgets created will be added automatically to the bottom of the profile sidebar to change their placement or control their visibility go to <strong>Youzer Panel > Profile Settings > Profile Structure</strong>.', 'youzer' )
        )
    );

    $modal_args = array(
        'id'        => 'yz-custom-widgets-form',
        'title'     => __( 'Create New Widget', 'youzer' ),
        'button_id' => 'yz-add-custom-widget'
    );

    // Get New Custom Widgets Form.
    yz_panel_modal_form( $modal_args, 'yz_get_custom_widgetsform' );

    // Get Widgets List.
    yz_get_custom_widgets_list();

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
            'desc'  => __( 'Choose how you want your custom widgets to be loaded?', 'youzer' ),
            'id'    => 'yz_custom_widgets_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * # Create New Custom Widgets Form.
 */
function yz_get_custom_widgetsform() {

    // Get Data.
    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-custom-widgets-form'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'Widget Icon', 'youzer' ),
            'desc'         => __( 'Select widget icon', 'youzer' ),
            'id'           => 'yz_widget_icon',
            'std'          => 'fas fa-globe',
            'type'         => 'icon',
            'icons_type'   => 'web_application',
            'no_options'   => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'Widget Title', 'youzer' ),
            'desc'         => __( 'Add widget title', 'youzer' ),
            'id'           => 'yz_widget_name',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Display Widget Title', 'youzer' ),
            'desc'       => __( 'Show widget title', 'youzer' ),
            'id'         => 'yz_widget_display_title',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Use Widget Padding', 'youzer' ),
            'desc'       => __( 'Display widget padding', 'youzer' ),
            'id'         => 'yz_widget_display_padding',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'      => __( 'Widget Content', 'youzer' ),
            'id'         => 'yz_widget_content',
            'desc'       => __( 'Paste your shortcode or any html code. you can use the following tags inside the content : <br>The tag {displayed_user} will be replaced by the displayed profile user id and the tag {logged_in_user} will be replaced by the logged-in user id.', 'youzer' ),
            'type'       => 'textarea',
            'no_options' => true
        )
    );

    // Add Hidden Input
    $Yz_Settings->get_field(
        array(
            'id'         => 'yz_custom_widgets_form',
            'type'       => 'hidden',
            'class'      => 'yz-keys-name',
            'std'        => 'yz_custom_widgets',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get Widgets List
 */
function yz_get_custom_widgets_list() {

    global $Yz_Settings;

    // Get Custom Widgets
    $yz_custom_widgets = yz_option( 'yz_custom_widgets' );

    // Next ID
    $next_id = yz_option( 'yz_next_custom_widget_nbr' );
    $yz_nextCustomWidget = ! empty( $next_id ) ? $next_id : '1';

    ?>

    <script> var yz_nextCustomWidget = <?php echo $yz_nextCustomWidget; ?>; </script>

    <div class="yz-custom-section">
        <div class="yz-cs-head">
            <div class="yz-cs-buttons">
                <button class="yz-md-trigger yz-custom-widget-button" data-modal="yz-custom-widgets-form">
                    <i class="fas fa-plus"></i>
                    <?php _e( 'Add New Widget', 'youzer' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="yz_custom_widgets" class="yz-cs-content">

    <?php

        // Show No Ads Found .
        if ( empty( $yz_custom_widgets ) ) {
            echo "<p class='yz-no-content yz-no-custom-widgets'>" . __( 'No custom widgets found!', 'youzer' ) . "</p></ul>";
            return false;
        }

        foreach ( $yz_custom_widgets as $widget => $data ) :

            // Get Widget Data.
            $icon = $data['icon'];
            $name = $data['name'];
            $content = $data['content'];
            $display_title = $data['display_title'];
            $display_padding = $data['display_padding'];

            // Get Field Name.
            $input_name = "yz_custom_widgets[$widget]";

            ?>

            <!-- Widget Item -->
            <li class="yz-custom-widget-item" data-widget-name="<?php echo $widget;?>">
                <h2 class="yz-custom-widget-name"><i class="yz-custom-widget-icon <?php echo $icon; ?>"></i><span><?php echo $name; ?></span></h2>
                <input type="hidden" name="<?php echo $input_name; ?>[icon]" value="<?php echo $icon; ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[display_title]" value="<?php echo $display_title; ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[display_padding]" value="<?php echo $display_padding; ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[name]" value="<?php echo $name; ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[content]" value="<?php echo $content; ?>">
                <a class="yz-edit-item yz-edit-custom-widget"></a>
                <a class="yz-delete-item yz-delete-custom-widget"></a>
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}