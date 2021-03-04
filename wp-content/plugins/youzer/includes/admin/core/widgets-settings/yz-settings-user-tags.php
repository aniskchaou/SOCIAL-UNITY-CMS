<?php

/**
 * User Tags Settings.
 */
function yz_user_tags_widget_settings() {

    // Load User Tags Script.
    wp_enqueue_script( 'yz-user-tags', YZ_AA . 'js/yz-user-tags.min.js', array( 'jquery' ), YZ_Version, true );
    wp_localize_script( 'yz-user-tags', 'Yz_User_Tags', array(
        'utag_name_empty' => __( 'User tag name is empty!', 'youzer' ),
        'no_user_tags'    => __( 'No user tags found!', 'youzer' ),
        'update_user_tag' => __( 'Update user tags type', 'youzer' )
    ) );

    global $Yz_Settings;

    if ( bp_is_active( 'xprofile' ) ) {

        // Get Modal Args
        $modal_args = array(
            'button_id' => 'yz-add-user-tag',
            'id'        => 'yz-user-tags-form',
            'title'     => __( 'Create New Tag', 'youzer' )
        );

        // Get New User Tags Form.
        yz_panel_modal_form( $modal_args, 'yz_get_user_tags_form' );

        // Get User Tags List.
        yz_get_user_tags_list();

    }

    $Yz_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzer' ),
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Display Title', 'youzer' ),
            'id'    => 'yz_wg_user_tags_display_title',
            'desc'  => __( 'Show slideshow title', 'youzer' ),
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzer' ),
            'id'    => 'yz_wg_user_tags_title',
            'desc'  => __( 'User tags widget title', 'youzer' ),
            'type'  => 'text'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzer' ),
            'opts'  => $Yz_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the user tags to be loaded?', 'youzer' ),
            'id'    => 'yz_user_tags_load_effect',
            'type'  => 'select'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Tags Icon', 'youzer' ),
            'desc'  => __( 'Display user tags icon', 'youzer' ),
            'id'    => 'yz_enable_user_tags_icon',
            'type'  => 'checkbox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Enable User Tags Description', 'youzer' ),
            'desc'  => __( 'Display user tags description', 'youzer' ),
            'id'    => 'yz_enable_user_tags_description',
            'type'  => 'checkbox'
        )
    );

$Yz_Settings->get_field(
    array(
        'type'  => 'select',
        'id'    => 'yz_wg_user_tags_border_style',
        'title' => __( 'Tags Border Style', 'youzer' ),
        'desc'  => __( 'Select tags border style', 'youzer' ),
        'opts'  => $Yz_Settings->get_field_options( 'buttons_border_styles' )
    )
);

$Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'User Tags Styling Settings', 'youzer' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Title', 'youzer' ),
            'desc'  => __( 'Tags type title color', 'youzer' ),
            'id'    => 'yz_wg_user_tags_title_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Icon', 'youzer' ),
            'desc'  => __( 'Tags type icon color', 'youzer' ),
            'id'    => 'yz_wg_user_tags_icon_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Description', 'youzer' ),
            'desc'  => __( 'Tags type description color', 'youzer' ),
            'id'    => 'yz_wg_user_tags_desc_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Background', 'youzer' ),
            'desc'  => __( 'Tags background color', 'youzer' ),
            'id'    => 'yz_wg_user_tags_background',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Text', 'youzer' ),
            'desc'  => __( 'Tags text color', 'youzer' ),
            'id'    => 'yz_wg_user_tags_color',
            'type'  => 'color'
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * # Create New User Tags Form.
 */
function form() {

    // Get Data.
    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-user-tags-form'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'Tags Type Icon', 'youzer' ),
            'desc'         => __( 'Select tag type icon', 'youzer' ),
            'id'           => 'yz_user_tag_icon',
            'std'          => 'fas fa-globe',
            'type'         => 'icon',
            'no_options'   => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Field', 'youzer' ),
            'desc'  => __( 'Select the tags source field name', 'youzer' ),
            'opts'  => yz_get_user_tags_xprofile_fields(),
            'id'    => 'yz_user_tag_field',
            'type'  => 'select',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Name', 'youzer' ),
            'desc'  => __( 'Type tag name by default is the field title', 'youzer' ),
            'id'    => 'yz_user_tag_name',
            'type'  => 'text',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Description', 'youzer' ),
            'desc'  => __( 'Type tag description', 'youzer' ),
            'id'    => 'yz_user_tag_description',
            'type'  => 'text',
            'no_options' => true
        )
    );

    // Add Hidden Input
    $Yz_Settings->get_field(
        array(
            'type'       => 'hidden',
            'class'      => 'yz-keys-name',
            'std'        => 'yz_user_tags',
            'id'         => 'yz_user_tags_form',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get User Tags List
 */
function yz_get_user_tags_list() {

    // Get User Tag Items
    $yz_user_tags = yz_option( 'yz_user_tags' );

    // Next User Tag ID
    $next_id = yz_option( 'yz_next_user_tag_nbr' );
    $yz_nextUTag = ! empty( $next_id ) ? $next_id : '1';

    ?>

    <script> var yz_nextUTag = <?php echo $yz_nextUTag; ?>; </script>

    <div class="yz-custom-section">
        <div class="yz-cs-head">
            <div class="yz-cs-buttons">
                <button class="yz-md-trigger yz-user-tags-button" data-modal="yz-user-tags-form">
                    <i class="fas fa-user-plus"></i>
                    <?php _e( 'Add New User Tag', 'youzer' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="yz_user_tags" class="yz-cs-content">

    <?php

        // Show No Tags Found .
        if ( empty( $yz_user_tags ) ) {
            echo "<p class='yz-no-content yz-no-user-tags'>" . __( 'No user tags found!', 'youzer' ) . "</p></ul>";
            return false;
        }

        foreach ( $yz_user_tags as $tag => $data ) :

            // Get Widget Data.
            $icon  = $data['icon'];
            $title = $data['name'];
            $field = $data['field'];
            $desc  = $data['description'];

            // Get Field Name.
            $name = "yz_user_tags[$tag]";

            ?>

            <!-- Tag Item -->
            <li class="yz-user-tag-item" data-user-tag-name="<?php echo $tag; ?>">
                <h2 class="yz-user-tag-name">
                    <i class="yz-user-tag-icon <?php echo apply_filters( 'yz_user_tags_builder_icon', $icon ); ?>"></i>
                    <span><?php echo $title; ?></span>
                </h2>
                <input type="hidden" name="<?php echo $name; ?>[icon]" value="<?php echo $icon; ?>">
                <input type="hidden" name="<?php echo $name; ?>[name]" value="<?php echo $title; ?>">
                <input type="hidden" name="<?php echo $name; ?>[field]" value="<?php echo $field; ?>">
                <input type="hidden" name="<?php echo $name; ?>[description]" value="<?php echo $desc; ?>">
                <a class="yz-edit-item yz-edit-user-tag"></a>
                <a class="yz-delete-item yz-delete-user-tag"></a>
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}

/**
 * # Create New User Tags Form.
 */
function yz_get_user_tags_form() {

    // Get Data.
    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'yz-user-tags-form'
        )
    );

    $Yz_Settings->get_field(
        array(
            'title'        => __( 'Tags Type Icon', 'youzer' ),
            'desc'         => __( 'Select tag type icon', 'youzer' ),
            'id'           => 'yz_user_tag_icon',
            'std'          => 'fas fa-globe',
            'type'         => 'icon',
            'no_options'   => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Field', 'youzer' ),
            'desc'  => __( 'Select the tags source field name', 'youzer' ),
            'opts'  => yz_get_user_tags_xprofile_fields(),
            'id'    => 'yz_user_tag_field',
            'type'  => 'select',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Name', 'youzer' ),
            'desc'  => __( 'Type tag name by default is the field title', 'youzer' ),
            'id'    => 'yz_user_tag_name',
            'type'  => 'text',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Tags Type Description', 'youzer' ),
            'desc'  => __( 'Type tag description', 'youzer' ),
            'id'    => 'yz_user_tag_description',
            'type'  => 'text',
            'no_options' => true
        )
    );

    // Add Hidden Input
    $Yz_Settings->get_field(
        array(
            'type'       => 'hidden',
            'class'      => 'yz-keys-name',
            'std'        => 'yz_user_tags',
            'id'         => 'yz_user_tags_form',
            'no_options' => true
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeDiv' ) );

}