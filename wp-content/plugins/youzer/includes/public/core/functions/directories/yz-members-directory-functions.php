<?php

/**
 * Get Members Directory Class
 */
function yz_members_directory_class() {

    // New Array
    $directory_class = array( 'yz-directory yz-page yz-members-directory-page' );

    // Add Scheme Class
    $directory_class[] = yz_option( 'yz_profile_scheme', 'yz-blue-scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = yz_option( 'yz_tabs_list_icons_style', 'yz-tabs-list-gradient' );

    return yz_generate_class( $directory_class );
}

/**
 * Get Members Directory User Cover.
 */
function yz_members_directory_user_cover( $user_id ) {

    if ( 'off' == yz_option( 'yz_enable_md_cards_cover', 'on' ) ) {
        return false;
    }

    ?>

    <div class="yz-cover"><?php echo yz_get_user_cover( $user_id ); ?><?php do_action( 'yz_members_directory_cover_content' ); ?></div>

    <?php

}

/**
 * Filters Members Directory Classes.
 */
function yz_edit_members_directory_class( $classes ) {

    // Add OffLine Class.
    if ( ! in_array( 'is-online', $classes ) && 'off' == yz_option( 'yz_show_md_cards_online_only', 'on' ) ) {
        $classes[] = 'is-offline';
    }

    // Remove User Status Class
    if ( 'off' == yz_option( 'yz_enable_md_cards_status', 'on' ) ) {

        // Get Values Keys.
        $is_online = array_search( 'is-online', $classes );
        $is_offline = array_search( 'is-offline', $classes );

        // Remove OnLine Class.
        if ( $is_online !== false ) {
            unset( $classes[ $is_online ] );
        }

        // Remove OffLine Class.
        if ( $is_offline !== false ) {
            unset( $classes[ $is_offline ] );
        }

    }

    if ( 'on' == yz_option( 'yz_enable_md_cards_cover', 'on' ) ) {
        $classes[] = 'yz-show-cover';
    }

    return $classes;
}

add_filter( 'bp_get_member_class', 'yz_edit_members_directory_class' );

/**
 * Members Directory - Max Members Per Page.
 */
function yz_members_directory_members_per_page( $loop ) {
    if ( bp_is_members_directory() ) {
        $loop['per_page'] = yz_option( 'yz_md_users_per_page', 18 );
    }
    return $loop;
}

add_filter( 'bp_after_has_members_parse_args', 'yz_members_directory_members_per_page' );

/**
 * Members Directory - Cards Class.
 */
function yz_members_list_class() {

    // Init Array().
    $classes = array();

    // Main Class.
    $classes[] = 'item-list';

    if ( ! bp_is_directory() ) {
        return yz_generate_class( $classes );
    }

    // Get Avatar Border Visibility.
    $enable_avatar_border = yz_option( 'yz_enable_md_cards_avatar_border', 'off' );

    if ( 'on' == $enable_avatar_border) {

        // Show Avatar Border.
        $classes[] = 'yz-card-show-avatar-border';

    }

    // Get Cards Avatar Style.
    $avatar_border_style = yz_option( 'yz_md_cards_avatar_border_style', 'circle' );

    // Add Avatar Border Style.
    $classes[] = 'yz-card-avatar-border-' . $avatar_border_style;

    // Get Buttons Layout.
    $buttons_layout = yz_option( 'yz_md_cards_buttons_layout', 'block' );

    // Add Buttons Layout.
    $classes[] = 'yz-card-action-buttons-' . $buttons_layout;

    // Get Buttons Border Style.
    $buttons_border_style = yz_option( 'yz_md_cards_buttons_border_style', 'oval' );

    // Add Buttons Border Style.
    $classes[] = 'yz-card-action-buttons-border-' . $buttons_border_style;

    return yz_generate_class( $classes );

}

/**
 * Get Members Directory User settings Button
 */
function yz_get_md_current_user_settings( $user_id = false ) {

    if ( ! is_user_logged_in() || ! bp_is_members_directory() ) {
        return false;
    }

    // Get User Id.
    $user_id = $user_id ? $user_id : yz_get_context_user_id();

    if ( $user_id != bp_loggedin_user_id() ) {
        return false;
    }

    ?>

    <?php if ( bp_is_active( 'xprofile' ) ) : ?>
    <a href="<?php echo yz_get_profile_settings_url( false, $user_id ); ?>" class="yz-profile-settings"><i class="fas fa-user-circle"></i><?php _e( 'Profile Settings', 'youzer' ); ?></a>
    <?php endif; ?>

    <?php if ( bp_is_active( 'friends' ) && bp_is_active( 'messages' ) && 'block' == yz_option( 'yz_md_cards_buttons_layout', 'block' ) ) : ?>

        <?php if ( bp_is_active( 'settings' ) ) : ?>
            <a href="<?php echo bp_core_get_user_domain( $user_id ) . bp_get_settings_slug(); ?>" class="yzmd-second-btn"><i class="fas fa-cogs"></i><?php _e( 'Account Settings', 'youzer' ); ?></a>
        <?php else : ?>
            <a href="<?php echo yz_get_widgets_settings_url( false, $user_id ); ?>" class="yzmd-second-btn"><i class="fas fa-sliders-h"></i><?php _e( 'Widgets Settings', 'youzer' ); ?></a>
        <?php endif; ?>

    <?php endif; ?>

    <?php
}

add_action( 'bp_directory_members_actions', 'yz_get_md_current_user_settings' );

/**
 * Members Directory - Get Member Data Statitics.
 */
function yz_get_member_statistics_data( $user_id ) {

	if ( 'off' == yz_option( 'yz_enable_md_users_statistics', 'on' ) ) {
		return false;
	}

    ?>

    <div class="yzm-user-statistics">

        <?php do_action( 'yz_before_members_directory_card_statistics', $user_id  ); ?>

        <?php if ( 'on' == yz_option( 'yz_enable_md_user_posts_statistics', 'on' ) ) : ?>
            <?php $posts_nbr = yz_get_user_posts_nbr( $user_id ); ?>
        <a <?php if (  $posts_nbr > 0 ) { ?> href="<?php echo yz_get_user_profile_page( 'posts', $user_id ); ?>" <?php } ?> class="yz-data-item yz-data-posts" data-yztooltip="<?php echo sprintf( _n( '%s Post', '%s Posts', $posts_nbr, 'youzer' ), $posts_nbr ); ?>">
            <span class="dashicons dashicons-edit"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == yz_option( 'yz_enable_md_user_comments_statistics', 'on' ) ) : ?>
            <?php $comments_nbr = yz_get_comments_number( $user_id );  ?>
        <a <?php if (  $comments_nbr > 0 ) { ?>  href="<?php echo yz_get_user_profile_page( 'comments', $user_id ); ?>" <?php } ?> class="yz-data-item yz-data-comments" data-yztooltip="<?php echo sprintf( _n( '%s Comment', '%s Comments', $comments_nbr, 'youzer' ), $comments_nbr ); ?>">
            <span class="dashicons dashicons-format-status"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == yz_option( 'yz_enable_md_user_views_statistics', 'on' ) ) : ?>
            <?php $views_nbr = get_post_meta( $user_id, 'profile_views_count', true ); ?>
        <a href="<?php echo bp_member_permalink(); ?>" class="yz-data-item yz-data-vues" data-yztooltip="<?php echo sprintf( _n( '%s View', '%s Views', $views_nbr, 'youzer' ), $views_nbr ); ?>">
            <span class="dashicons dashicons-welcome-view-site"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == yz_option( 'yz_enable_md_user_friends_statistics', 'on' ) && bp_is_active( 'friends' ) ) :  ?>
	       <?php $friends_nbr = friends_get_total_friend_count( $user_id ); ?>
            <a href="<?php echo yz_get_user_profile_page( 'friends', $user_id ); ?>" class="yz-data-item yz-data-friends" data-yztooltip="<?php echo sprintf( _n( '%s Friend', '%s Friends', $friends_nbr, 'youzer' ), $friends_nbr ); ?>">
                <span class="dashicons dashicons-groups"></span>
            </a>
        <?php endif; ?>

        <?php do_action( 'yz_after_members_directory_card_statistics', $user_id  ); ?>

    </div>

    <?php
}

/**
 * Get Card Custom Meta.
 */
function yz_get_md_user_meta( $user_id = null ) {

    // Get Custom Card Meta Availability
    $custom_meta = yz_option( 'yz_enable_md_custom_card_meta', 'off' );

    if ( 'off' == $custom_meta || ! bp_is_members_directory() ) {

        // Get Default Meta.
        $default_meta = '@' . bp_core_get_username( $user_id );

        return $default_meta;

    }

    // Get Custom Meta Data
    $meta_icon  = yz_option( 'yz_md_card_meta_icon', 'at' );
    $field_id   = yz_option( 'yz_md_card_meta_field', 'user_login' );
    $meta_value = yz_get_user_field_data( $field_id, $user_id );

    if ( empty( $meta_value ) ) {
        // Set Default Meta.
        $meta_html = '<i class="fas fa-at"></i>' . bp_core_get_username( $user_id );
    } else {
        // Create Custom Meta HTML Code.
        $meta_html = '<i class="' . $meta_icon .'"></i>' . $meta_value;
    }

    // Filter
    $meta_html = apply_filters( 'yz_get_md_user_meta', $meta_html, $meta_icon, $field_id, $meta_value );

    return $meta_html;
}

/**
 * Get Card User Meta Value.
 */
function yz_get_user_field_data( $field_id = null, $user_id = null ) {

    // Get Hidden Fields.
    if ( bp_is_active( 'xprofile' ) ) {

        $hidden_fields = bp_xprofile_get_hidden_fields_for_user();

        if ( in_array( $field_id, $hidden_fields ) )  {
            return;
        }

    }

    if ( bp_is_active( 'xprofile' ) && is_numeric( $field_id ) ) {
        // Get Field Data.
        $meta_value = xprofile_get_field_data( $field_id, $user_id, 'comma' );
    } elseif ( $field_id == 'full_location' ) {
        $meta_value = yz_users()->location( true, $user_id );
    } elseif ( $field_id == 'user_url' ) {
        $meta_value = yz_get_xprofile_field_value( 'user_url', $user_id );
    } else {
        // Get Field Data.
        $meta_value = get_the_author_meta( $field_id, $user_id );
    }

    // Filter.
    $meta_value = apply_filters( 'yz_get_user_field_data', $meta_value, $field_id, $user_id );

    // Return Data.
    return $meta_value;
}

/**
 * Display Members Directory
 */
function yz_display_md_filter_bar() {
    return apply_filters( 'yz_display_members_directory_filter', true );
}

/**
 * Add Member Directory Shortcode.
 **/
function youzer_members_shortcode( $atts ) {

    add_filter( 'bp_is_current_component', 'yz_enable_shortcode_md', 10, 2 );
    add_filter( 'bp_is_directory', '__return_true' );

    // Scripts
    wp_enqueue_script( 'masonry' );
    wp_enqueue_style( 'yz-directories', YZ_PA . 'css/yz-directories.min.css', array( 'dashicons' ), YZ_Version );
    wp_enqueue_script( 'yz-directories', YZ_PA .'js/yz-directories.min.js', array( 'jquery' ), YZ_Version, true );

    global $yz_md_shortcode_atts;

    // Get Args.
    $yz_md_shortcode_atts = wp_parse_args( $atts, array( 'per_page' => 12, 'member_type' => false, 'show_filter' => 'false' ) );

    // Add Filter.
    add_filter( 'bp_after_has_members_parse_args', 'yz_set_members_directory_shortcode_atts' );

    if ( $yz_md_shortcode_atts['show_filter'] == false ) {
        add_filter( 'yz_display_members_directory_filter', '__return_false' );
    }

    ob_start();

    echo "<div class='yz-members-directory-shortcode'>";
    include YZ_TEMPLATE . 'members/index.php';
    echo "</div>";

    // Remove Filter.
    remove_filter( 'bp_after_has_members_parse_args', 'yz_set_members_directory_shortcode_atts' );


    if ( $yz_md_shortcode_atts['show_filter'] == false ) {
        remove_filter( 'yz_display_members_directory_filter', '__return_false' );
    }

    // Unset Global Value.
    unset( $yz_md_shortcode_atts );

    remove_filter( 'bp_is_directory', '__return_true' );
    remove_filter( 'bp_is_current_component', 'yz_enable_shortcode_md', 10, 2 );

    return ob_get_clean();
}

add_shortcode( 'youzer_members', 'youzer_members_shortcode' );

/**
 * Members Directory - Shortcode Attributes.
 */
function yz_set_members_directory_shortcode_atts( $loop ) {

    global $yz_md_shortcode_atts;

    $loop = shortcode_atts( $loop, $yz_md_shortcode_atts, 'youzer_members_atts' );

    return $loop;

}

/**
 * Members Directory - Hide Filter.
 */
// function yz_disable_md_shortcode_filter() {
//     return false;
// }

/**
 * Enable Members Directory Component For Shortcode.
 */
function yz_enable_shortcode_md( $active, $component ) {

    if ( $component == 'members' ) {
        return true;
    }

    return $active;

}