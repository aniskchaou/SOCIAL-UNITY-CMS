<?php

/**
 * Get Groups Directory Class
 */
function yz_groups_directory_class() {

    // New Array
    $directory_class = array( 'yz-directory yz-page yz-groups-directory-page' );

    // Add Scheme Class
    $directory_class[] = yz_option( 'yz_profile_scheme', 'yz-blue-scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = yz_option( 'yz_tabs_list_icons_style', 'yz-tabs-list-gradient' );

    return yz_generate_class( $directory_class );
}

/**
 * Get Groups Directory Group Cover.
 */
function yz_groups_directory_group_cover( $group_id ) {

    if ( 'off' == yz_option( 'yz_enable_gd_cards_cover', 'on' ) ) {
        return false;
    }

    echo '<div class="yz-cover">' . yz_get_group_cover( $group_id ) . '</div>';

}

/**
 * Groups Directory - Edit Groups Class.
 */
function yz_edit_group_directory_class( $classes ) {

    if ( bp_is_groups_directory() && 'on' == yz_option( 'yz_enable_gd_cards_cover', 'on' ) ) {
        $classes[] = 'yz-show-cover';
    }

    return $classes;
}

add_filter( 'bp_get_group_class', 'yz_edit_group_directory_class' );

/**
 * Groups Directory - Get Member Data Statitics.
 */
function yz_get_group_statistics_data( $group_id ) {

    if ( 'off' == yz_option( 'yz_enable_gd_groups_statistics', 'on' ) ) {
        return false;
    }

    // Get Data

    ?>

    <div class="yzg-user-statistics">

        <?php if ( 'on' == yz_option( 'yz_enable_gd_group_posts_statistics', 'on' ) ) : ?>
            <?php $posts_nbr = yz_get_group_total_posts_count( $group_id ); ?>
        <div class="yz-data-item yz-data-posts" data-yztooltip="<?php echo sprintf( _n( '%s Post', '%s Posts', $posts_nbr, 'youzer' ), $posts_nbr ); ?>">
            <span class="dashicons dashicons-edit"></span>
        </div>
        <?php endif; ?>

        <?php if ( 'on' == yz_option( 'yz_enable_gd_group_activity_statistics', 'on' ) ) : ?>
        <div class="yz-data-item yz-data-activity" data-yztooltip="<?php printf( __( 'Active %s', 'youzer' ), bp_get_group_last_active() ); ?>">
            <span class="dashicons dashicons-clock"></span>
        </div>
        <?php endif; ?>

        <?php if ( 'on' == yz_option( 'yz_enable_gd_group_members_statistics', 'on' ) ) : ?>
        <?php $members_count = groups_get_total_member_count( $group_id ); ?>
        <div class="yz-data-item yz-data-members" data-yztooltip="<?php echo sprintf( _n( '%s Member', '%s Members', $members_count, 'youzer' ), bp_core_number_format( $members_count ) ); ?>">
            <span class="dashicons dashicons-groups"></span>
        </div>
        <?php endif; ?>


    </div>

    <?php
}

/**
 * Groups Directory - Get Group Buttons.
 */
function yz_get_gd_manage_group_buttons() {

    if ( ! is_user_logged_in() || ! bp_is_groups_directory() ) {
        return false;
    }

    // Check if Current User is admin.
    if ( false == groups_is_user_admin( get_current_user_id(), bp_get_group_id() ) ) {
        return false;
    }

    ?>

    <a href="<?php echo bp_get_group_admin_permalink(); ?>" class="yz-manage-group"><i class="fas fa-cogs"></i><?php _e( 'Manage Group', 'youzer' ); ?></a>

    <?php

}

add_action( 'bp_directory_groups_actions', 'yz_get_gd_manage_group_buttons', 999 );

/**
 * Groups Directory - Max Groups Number per Page.
 */
function yz_groups_directory_groups_per_page( $loop ) {
    if ( bp_is_groups_directory() ) {
        $loop['per_page'] = yz_option( 'yz_gd_groups_per_page', 18 );
    }
    return $loop;
}

add_filter( 'bp_after_has_groups_parse_args', 'yz_groups_directory_groups_per_page' );

/**
 * Groups Directory - Cards Class.
 */
function yz_groups_list_class() {

    // Init Array().
    $classes = array();

    // Main Class.
    $classes[] = 'item-list';

    if ( ! bp_is_groups_directory() ) {
        return yz_generate_class( $classes );
    }

    // Get Avatar Border Visibility.
    $enable_avatar_border = yz_option( 'yz_enable_gd_cards_avatar_border', 'on' );

    if ( 'on' == $enable_avatar_border) {

        // Show Avatar Border.
        $classes[] = 'yz-card-show-avatar-border';

    }

    // Get Cards Avatar Style.
    $avatar_border_style = yz_option( 'yz_gd_cards_avatar_border_style', 'circle' );

    // Add Avatar Border Style.
    $classes[] = 'yz-card-avatar-border-' . $avatar_border_style;

    // Get Buttons Layout.
    $buttons_layout = yz_option( 'yz_gd_cards_buttons_layout', 'block' );

    // Add Buttons Layout.
    $classes[] = 'yz-card-action-buttons-' . $buttons_layout;

    // Get Buttons Border Style.
    $buttons_border_style = yz_option( 'yz_gd_cards_buttons_border_style', 'oval' );

    // Add Buttons Border Style.
    $classes[] = 'yz-card-action-buttons-border-' . $buttons_border_style;

    return yz_generate_class( $classes );

}


/**
 * Add Groups Directory Shortcode.
 **/
function youzer_groups_shortcode( $atts ) {

    add_filter( 'bp_is_current_component', 'yz_enable_groups_directory_shortcode', 10, 2 );

    // Scripts
    wp_enqueue_style( 'yz-directories', YZ_PA . 'css/yz-directories.min.css', array( 'dashicons' ), YZ_Version );
    wp_enqueue_script( 'yz-directories', YZ_PA .'js/yz-directories.min.js', array( 'jquery' ), YZ_Version, true );

    global $yz_gd_shortcode_atts;

    // Get Args.
    $yz_gd_shortcode_atts = wp_parse_args( $atts, array( 'per_page' => 12, 'show_filter' => false ) );

    // Add Filter.
    add_filter( 'bp_after_has_groups_parse_args', 'yz_set_groups_directory_shortcode_atts' );

    if ( $yz_gd_shortcode_atts['show_filter'] == false ) {
        add_filter( 'yz_display_groups_directory_filter', '__return_false' );
    }

    ob_start();

    echo "<div class='yz-groups-directory-shortcode'>";
    include YZ_TEMPLATE . 'groups/index.php';
    echo "</div>";

    // Remove Filter.
    remove_filter( 'bp_after_has_groups_parse_args', 'yz_set_groups_directory_shortcode_atts' );

    if ( $yz_gd_shortcode_atts['show_filter'] == false ) {
        remove_filter( 'yz_display_groups_directory_filter', '__return_false' );
    }

    // Unset Global Value.
    unset( $yz_gd_shortcode_atts );

    remove_filter( 'bp_is_current_component', 'yz_enable_groups_directory_shortcode', 10, 2 );

    return ob_get_clean();
}

add_shortcode( 'youzer_groups', 'youzer_groups_shortcode' );

/**
 * Groups Directory - Shortcode Attributes.
 */
function yz_set_groups_directory_shortcode_atts( $loop ) {

    global $yz_gd_shortcode_atts;

    $loop = shortcode_atts( $loop, $yz_gd_shortcode_atts, 'youzer_groups_atts' );

    return $loop;

}

/**
 * Enable Groups Directory Component For Shortcode.
 */
function yz_enable_groups_directory_shortcode( $active, $component ) {

    if ( $component == 'groups' ) {
        return true;
    }

    return $active;

}