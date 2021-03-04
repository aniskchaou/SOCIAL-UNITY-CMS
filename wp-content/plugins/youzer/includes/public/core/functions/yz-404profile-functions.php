<?php


/**
 * Display Spammer Profile as 404 Profile Page
 */
function yz_show_spammer_404() {

    if ( bp_displayed_user_id() && bp_is_user_spammer( bp_displayed_user_id() ) && ! bp_current_user_can( 'bp_moderate' ) ) {
        return true;
    }

    return false;
}

/**
 * # Get 404 Profile Template
 */
function yz_get_404_profile_template( $template ) {

    if ( yz_is_404_profile() ) {

        if ( ! yz_show_spammer_404() ) {

            global $wp_query;

            status_header( 200 );
            
            // Mark Page As 404.
            $wp_query->is_404 = false;

        }

        // 404 Profile Styling.
        yz_styling()->custom_styling( '404_profile' );

        require_once YZ_PUBLIC_CORE . 'class-yz-header.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-widgets.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-user.php';
        
        // Add 404 Profile Content.
        add_filter( 'yz_user_profile_username', 'yz_add_404_profile_page_username' );
        add_action( 'yz_after_profile_header_user_meta', 'yz_add_404_profile_header_meta' );
        add_action( 'yz_profile_main_content', function() { ?>
            
        <div class="yz-box-404">
            <h2>404</h2>
            <p><?php echo sanitize_textarea_field( yz_options( 'yz_profile_404_desc' ) ); ?></p>
            <a class="yz-box-button" href="<?php echo home_url(); ?>">
                <?php echo sanitize_text_field( yz_options( 'yz_profile_404_button' ) ); ?>
            </a>
        </div>

        <?php } );

        add_filter( 'yz_user_profile_avatar_img', 'yz_404_user_profile_avatar' );
        add_filter( 'yz_user_profile_cover', 'yz_404_user_profile_cover' );
        
        return yz_404_profile_template();

    }

    return $template;
}

add_filter( 'youzer_template', 'yz_get_404_profile_template' );

/**
 * 404 Porfile Template
 */
function yz_404_profile_template() {

    // Get Header
    get_header();

    // Get Profile Template.
    include YZ_TEMPLATE . 'profile-template.php';

    // Get Footer
    get_footer();
    
}


/**
 * 404 Profile Username
 */
function yz_add_404_profile_page_username() {
    return __( '404 Profile', 'youzer' );
}

/**
 * 404 Profile Meta.
 */
function yz_add_404_profile_header_meta() {

    $meta = '<li><i class="fas fa-map-marker-alt"></i><span>' . __( '404 city', 'youzer' ) . '</span></li>';
    $meta .= '<li><i class="fas fa-link"></i><span>' . __( 'www.page.404', 'youzer' ) . '</span></li>';

    echo '<div class="yz-usermeta"><ul>' . apply_filters( 'yz_add_404_profile_header_meta', $meta ) . '</ul></div>';

}

/**
 * Change Cover.
 */
function yz_404_user_profile_cover( $default_cover ) {

    // Get Cover Path.
    $cover_path = yz_option( 'yz_profile_404_cover' );
    
    if ( ! empty( $cover_path ) ) {
        return "style='background-image:url( $cover_path ); background-size: cover;'";
    }

    return $default_cover;
}

/**
 * Change Avatar.
 */
function yz_404_user_profile_avatar( $default_avatar ) {

    // Get 404 Profile Picture
    $avatar_404 = yz_option( 'yz_profile_404_photo' );

    if ( ! empty( $avatar_404 ) ) {
        return yz_get_avatar_img_by_url( $avatar_404 );
    }

    return $default_avatar;
}


/**
 * # 404 Profile Scripts.
 */
function yz_404_profile_scripts() {

    if ( yz_is_404_profile() ) {
        wp_enqueue_style( 'yz-profile', YZ_PA . 'css/yz-profile-style.min.css', array(), YZ_Version );;
        wp_enqueue_style( 'yz-schemes' );
    }

}

add_action( 'wp_enqueue_scripts', 'yz_404_profile_scripts' );

/**
 * Check is Page: Profile 404
 */
function yz_is_404_profile() {

    if ( yz_show_spammer_404() ) {
        return true;
    }

    global $wp;

    // Get Members Slug
    $members_slug = bp_get_members_slug();

    // Get Page Path.
    $page_path = isset( $wp->request ) ? $wp->request : null;

    if ( ! $page_path ) {
        return false;
    }

    // Get Sub Pages
    $sub_pages = explode( '/', $page_path );

    // Get Current Component.
    $component = isset( $sub_pages[0] ) ? $sub_pages[0] : null;

    if ( $component == $page_path ) {
        return;
    }

    // Get Buddypresss Values
    $bp = buddypress();

    // Get User ID.
    $user_id = ! empty( $bp->displayed_user->id ) ? $bp->displayed_user->id : 0;

    // Check if it's a 404 profile
    if ( strcasecmp( $members_slug, $component ) == 0 && 0 == $user_id ) {
        return true;
    }

    return false;
}


/**
 * 404 Statistics.
 */
function yz_set_404_statistics( $value, $user_id, $type, $order ) {

	switch ( $order) {

		case 'first':
			$value = 4;
			break;
		
		case 'second':
			$value = 0;
			break;
		
		case 'third':
			$value = 4;
			break;
		
		default:
			break;
	}

	return $value;

}

add_action( 'yz_get_user_statistic_number', 'yz_set_404_statistics', 10, 4 );


/**
 * Set Profile Class
 */
function yz_set_404_profile_class( $classes ) {
	return $classes . ' yz-404-profile';
}

add_filter( 'yz_profile_class',  'yz_set_404_profile_class' );

/**
 * Ratings
 */
function yz_set_404_reviews_count() {
	return '404';

}
add_filter( 'yz_user_reviews_count', 'yz_set_404_reviews_count' );

/**
 * Hide Navbar
 */
add_filter( 'yz_display_profile_navbar', '__return_false' );
