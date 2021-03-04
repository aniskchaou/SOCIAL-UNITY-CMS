<?php

/**
 * Check if youzer is active.
 */
function youzer_is_active() {
    return true;
}

/**
 * # Get Youzer Plugin Pages
 */
function youzer_pages( $request_type = null, $id = null ) {

    // Get youzer pages.
    $youzer_pages = yz_option( 'youzer_pages' );

    // Switch Key <=> Values
    if ( 'ids' == $request_type ) {
        $yz_pages_ids = array_flip( $youzer_pages );
        return $yz_pages_ids;
    }

    return $youzer_pages;
}

/**
 * # Get Page URL.
 */
function yz_page_url( $page_name, $user_id = null ) {

	// Get Page Data
    $page_id  = yz_page_id( $page_name );
    $page_url = yz_fix_path( get_permalink( $page_id ) );

	// Get Page with Current User if page = profile or account .
	if ( 'profile' == $page_name && ! empty( $user_id ) ) {
        $page_url = $page_url . get_the_author_meta( 'user_login', $user_id );
    } elseif ( 'profile' == $page_name && empty( $user_id ) ) {
        $page_url = $page_url . esc_html(  yz_data( 'user_login' ) );
    }

	// Return Page Url.
    return $page_url;

}

/**
 * # Get Page ID.
 */
function yz_page_id( $page ) {
    $youzer_pages = yz_option( 'youzer_pages' );
    return $youzer_pages[ $page ];
}

/**
 * # Sort list by numeric order.
 */
function yz_sortByMenuOrder( $a, $b ) {

    if ( ! isset( $a['menu_order'] ) || ! isset( $b['menu_order'] ) ) {
        return false;
    }

    $a = $a['menu_order'];
    $b = $b['menu_order'];

    if ( $a == $b ) {
        return 0;
    }

    return ( $a < $b ) ? -1 : 1;
}

/**
 * Get All Widgets.
 */
function yz_get_profile_hidden_widgets() {
    return apply_filters ( 'yz_get_profile_hidden_widgets', (array) yz_option( 'yz_profile_hidden_widgets' ) );
}

/**
 * # Check widget visibility
 */
function yz_is_widget_visible( $widget_name ) {

    $visibility = false;

    $overview_widgets = yz_options( 'yz_profile_main_widgets' );
    $sidebar_widgets  = yz_options( 'yz_profile_sidebar_widgets' );
    $all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

    foreach ( $all_widgets as $widget_name => $visibility  ) {
        if ( 'visible' == $visibility ) {
            $visibility = true;
        }
    }

    // If its a Custom wiget Return True.
    if ( false !== strpos( $widget_name, 'yz_cwg' ) ) {
        $visibility = true;
    }

    return apply_filters( 'yz_is_widget_visible', $visibility, $widget_name );
}

/**
 * Get Array Key Index.
 */
function yz_get_key_index( $value, $array ) {
    $key = array_search( $value, $array );
    if ( false !== $key ) {
        return $key;
    }
}

/**
 * Fix Url Path.
 */
function yz_scroll_to_top() {
    wp_enqueue_script( 'yz-scrolltotop', YZ_PA .'js/yz-scrolltotop.min.js', array( 'jquery' ), YZ_Version, true );
    echo '<a class="yz-scrolltotop"><i class="fas fa-chevron-up"></i></a>';
}

/**
 * Fix Url Path.
 */
function yz_fix_path( $url ) {
    $url = str_replace( '\\', '/', trim( $url ) );
    return ( substr( $url,-1 ) != '/' ) ? $url .= '/' : $url;
}

/**
 * Detect if Logy Plugin Is installed.
 */
function yz_is_logy_active() {

    if ( yz_is_membership_system_active() ) {
        return true;
    }

    return false;

}

/**
 * Get Login Page Url.
 */
function yz_get_login_page_url() {

    // Init Vars.
    $login_url = wp_login_url();

    // Get Login Type.
    $login_type = yz_option( 'yz_login_page_type', 'url' );

    // Get Login Url.
    if ( 'url' == $login_type ) {
        $url = wp_login_url();
        $login_url = ! empty( $url ) ? $url : $login_url;
    } elseif ( 'page' == $login_type ) {
        $page_id = yz_option( 'yz_login_page' );
        $login_url = ! empty( $page_id ) ? get_the_permalink( $page_id ) : $login_url;
    }

    return apply_filters( 'yz_get_login_page_url', $login_url );

}

/**
 * Get Arguments consedering default values.
 */
function yz_get_args( $pairs, $atts, $prefix = null ) {

    // Set Up Arrays
    $out  = array();
    $atts = (array) $atts;

    // Get Prefix Value.
    $prefix = $prefix ? $prefix . '_' : null;

    // Get Values.
    foreach ( $pairs as $name => $default ) {
        if ( array_key_exists(  $prefix . $name, $atts ) ) {
            $out[ $name ] = $atts[ $prefix . $name ];
        } else {
            $out[ $name ] = $default;
        }
    }

    return $out;
}

/**
 * Add Groups & Wall Sidebar Widgets
 */
function yz_add_sidebar_widgets( $sidebar_id, $widgets_list ) {

    // Get Sidebar Widgets
    $sidebars_widgets = yz_option( 'sidebars_widgets' );

    // Check if Sidebar is empty.
    if ( ! empty( $sidebars_widgets[ $sidebar_id ] ) ) {
        return false;
    }

    // Add Widgets To sidebar.
    foreach ( $widgets_list as $widget ) {

        // Get Widgets Data.
        $widget_data = yz_option( 'widget_' . $widget );

        // Get Last Widget Id
        $last_id = (int) ! empty( $widget_data ) ? max( array_keys( $widget_data ) ) : 0;

        // Get Next ID.
        $counter = $last_id + 1;

        // Add Widget Default Settings.
        $widget_data[] = yz_get_widget_defaults_settings( $widget );

        // Get Widgets Data.
        update_option( 'widget_' . $widget, $widget_data );

        // Add Widget To sidebar
        $sidebars_widgets[ $sidebar_id ][] = strtolower( $widget ) . '-' . $counter;
    }

    // Update Sidebar
    update_option( 'sidebars_widgets', $sidebars_widgets );

}

/**
 * Create New Plugin Page.
 */
function yz_add_new_plugin_page( $args ) {

    // Get Page Slug
    $slug = $args['slug'];

    // Check that the page doesn't exist already
    $is_page_exists = yz_get_post_id( 'page', $args['meta'], $slug );

    if ( $is_page_exists ) {

        if ( ! isset( $pages[ $slug ] ) ) {

            // init Array.
            $pages = get_option( $args['pages'] );

            // Get Page ID
            $page_id = yz_get_post_id( 'page', $args['meta'], $slug );

            // Add New Page Data.
            $pages[ $slug ] = $page_id;

            update_option( $args['pages'], $pages );
        }

        return false;
    }

    $user_page = array(
        'post_title'     => $args['title'],
        'post_name'      => $slug,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'post_author'    =>  1,
        'comment_status' => 'closed'
    );

    $post_id = wp_insert_post( $user_page );

    wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

    update_post_meta( $post_id, $args['meta'], $slug );

    // init Array.
    $pages = get_option( $args['pages'] );

    // Add New Page Data.
    $pages[ $slug ] = $post_id;

    if ( isset( $pages ) ) {
        update_option( $args['pages'], $pages );
    }

}

/**
 * Display Notice Function
 */
function yz_display_admin_notice() {

    // Remove Default Function.
    global $BP_Legacy;
    remove_action( 'wp_footer', array( $BP_Legacy, 'sitewide_notices' ), 1 );

}

add_action( 'wp_head', 'yz_display_admin_notice' );

/**
 * Check is user exist by id
 */
function yz_is_user_exist( $user_id = null ) {

    if ( $user_id instanceof WP_User ) {
        $user_id = $user_id->ID;
    }
    return (bool) get_user_by( 'id', $user_id );
}

/**
 * Template Messages
 */
function yz_template_messages() {

    ?>

    <div id="template-notices" role="alert" aria-atomic="true">
        <?php

        /**
         * Fires towards the top of template pages for notice display.
         *
         * @since 1.0.0
         */
        do_action( 'template_notices' ); ?>

    </div>

    <?php
}

add_action( 'yz_group_main_content', 'yz_template_messages' );
add_action( 'yz_profile_main_content', 'yz_template_messages' );

/**
 * Get Attachments Allowed Extentions
 */
function yz_get_allowed_extensions( $type = null, $format = null ) {

    // Extentions
    $extensions = null;

    switch ( $type ) {

        case 'image':
            // Get Images Extensions.
            $extensions = yz_option( 'yz_atts_allowed_images_exts', array( 'png', 'jpg', 'jpeg', 'gif' ) );
            break;

        case 'video':
            // Get Videos Extensions.
            $extensions = yz_option( 'yz_atts_allowed_videos_exts', array( 'mp4', 'ogg', 'ogv', 'webm' ) );
            break;

        case 'audio':
            // Get Audios Extensions.
            $extensions = yz_option( 'yz_atts_allowed_audios_exts', array( 'mp3', 'ogg', 'wav' ) );
            break;

        case 'file':
            // Get Files Extensions.
            $extensions = yz_option( 'yz_atts_allowed_files_exts', array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'ogg', 'pfi' ) );
            break;

        default:
            // Get Default Extensions.
            $extensions = array(
                'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar',
                'zip', 'mp4', 'mp3', 'ogg', 'pfi'
            );
            break;
    }

    // Convert Extentions To Lower Case.
    $extensions = array_map( 'strtolower', $extensions );

    // Return Extentions as Text Format
    $extensions = ( $format == 'text' ) ? implode( ', ', $extensions ) : $extensions;

    return $extensions;
}

/**
 * Insert After Array.
 */
function yz_array_insert_after( array $array, $key, array $new ) {
    $keys = array_keys( $array );
    $index = array_search( $key, $keys );
    $pos = false === $index ? count( $array ) : $index + 1;
    return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

/*
 * Set Body Scheme Class
 */
function yz_body_add_youzer_scheme( $classes ) {

    // Get Profile Scheme
    $classes[] = yz_option( 'yz_profile_scheme', 'yz-blue-scheme' );
    $classes[] = is_user_logged_in() ? 'logged-in' : 'not-logged-in';

    return $classes;

}

add_filter( 'body_class', 'yz_body_add_youzer_scheme' );

/**
 *  Font-edn Modal
 */
function yz_modal( $args, $modal_function, $options = null ) {

    $title        = $args['title'];
    $button_id    = $args['button_id'];
    $title_icon = isset( $args['title_icon'] ) ? $args['title_icon'] : '';
    $default_submit_icon = isset( $args['operation'] ) && $args['operation'] == 'add' ? 'fas fa-edit' : 'fas fa-sync-alt';
    $submit_btn_icon = isset( $args['submit_button_icon'] ) ? $args['submit_button_icon'] : $default_submit_icon;
    $button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'Save', 'youzer' );
    $show_close = isset( $args['show_close'] ) ? $args['show_close'] : true;
    $show_delete_btn = isset( $args['show_delete_button'] ) ? $args['show_delete_button'] : false;
    $delete_btn_title = isset( $args['delete_button_title'] ) ? $args['delete_button_title'] : __( 'Delete', 'youzer' );
    $delete_btn_id = isset( $args['delete_button_id'] ) ? $args['delete_button_id'] : null;
    $delete_btn_item_id = isset( $args['delete_button_item_id'] ) ? $args['delete_button_item_id'] : null;

    $clases = array( 'yz-modal' );

    if ( ! empty( $title_icon ) ) {
        $clases[] = 'yz-big-close-icon';
    }

    $clases = implode( ' ', $clases );

    ?>

    <div id="yz-modal">

    <?php if ( isset( $args['modal_type'] ) && $args['modal_type'] == 'div' ) : ?>
        <div class="<?php echo $clases; ?>" id="<?php echo $args['id'] ;?>">
    <?php else : ?>
        <form class="<?php echo $clases; ?>" id="<?php echo $args['id'] ;?>" method="post" >
    <?php endif; ?>
        <div class="yz-modal-title" data-title="<?php echo $title; ?>">
            <?php if ( ! empty( $title_icon ) ) : ?><i class="<?php echo $title_icon; ?>"></i><?php endif;?>
            <spa class="yz-modal-title-text"><?php echo $title; ?></span>
            <i class="fas fa-times yz-modal-close-icon"></i>
        </div>

        <div class="yz-modal-content">
            <?php
                if ( is_array( $modal_function ) ) {
                    call_user_func(array( $modal_function[0], $modal_function[1] ), $options );
                } else {
                    $modal_function( $options );
                }
            ?>
        </div>

        <?php if ( ! isset( $args['hide-action'] ) ) : ?>
        <div class="yz-modal-actions">

            <?php if ( isset( $args['operation'] ) ) : ?>
            <button id="<?php echo $button_id; ?>" data-action="<?php echo $args['operation']; ?>" class="yz-modal-button yz-modal-save">
                <i class="<?php echo $submit_btn_icon; ?>"></i><?php echo $button_title ?>
            </button>
            <?php endif; ?>

            <?php if ( $show_delete_btn ) : ?>
            <button id="<?php echo $delete_btn_id; ?>" class="yz-md-button yz-modal-delete" data-item-id="<?php echo $delete_btn_item_id ?>">
                <i class="far fa-trash-alt"></i><?php echo $delete_btn_title; ?>
            </button>
            <?php endif; ?>

            <?php if ( $show_close ) : ?>
            <button class="yz-modal-button yz-modal-close">
                <i class="fas fa-times"></i><?php _e( 'Close', 'youzer' ); ?>
            </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php if ( isset( $args['modal_type'] ) && $args['modal_type'] == 'div' ) : ?>
        </div>
    <?php else : ?>
        </form>
    <?php endif; ?>
    </div>
    <?php
}

function yz_fix_networks_icons_css( $icon ) {
    if ( strpos( $icon, ' ' ) === false) {
        $icon = 'fab fa-' . $icon;
    }

    return $icon;

}

add_filter( 'yz_panel_networks_icon', 'yz_fix_networks_icons_css' );
add_filter( 'yz_user_social_networks_icon', 'yz_fix_networks_icons_css' );

/**
 * Youzer Scrips Vars.
 */
function youzer_scripts_vars() {

    $vars = array(
        'unknown_error' => __( 'An unknown error occurred. Please try again later.', 'youzer' ),
        'slideshow_auto' => apply_filters( 'yz_profile_slideshow_auto_loop' , true ),
        'slides_height_type' => yz_option( 'yz_slideshow_height_type', 'fixed' ),
        'activity_autoloader' => yz_enable_wall_activity_loader(),
        'authenticating' => __( 'Authenticating ...', 'youzer' ),
        'security_nonce' => wp_create_nonce( 'youzer-nonce' ),
        'displayed_user_id' => bp_displayed_user_id(),
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'save_changes' => __( 'Save Changes', 'youzer' ),
        'thanks'   => __( 'OK! Thanks', 'youzer' ),
        'confirm' => __( 'Confirm', 'youzer' ),
        'cancel' => __( 'Cancel', 'youzer' ),
        'menu_title' => __( 'Menu', 'youzer' ),
        'gotit' => __( 'Got it!', 'youzer' ),
        'done' => __( 'Done !', 'youzer' ),
        'ops' => __( 'Oops !', 'youzer' ),
        'slideshow_speed' => 5,
        'assets' => YZ_PA,
        'youzer_url' => YZ_URL,
    );

    return apply_filters( 'youzer_scripts_vars', $vars );
}

/**
 * Enable Activity Loader
 */
function yz_enable_wall_activity_loader() {

    $can = yz_option( 'yz_enable_wall_activity_loader', 'on' );

    if ( wp_is_mobile() ) {
        $can = 'off';
    }

    return apply_filters( 'yz_enable_wall_activity_loader', $can );

}

/**
 * Get Suggestions List.
 */
function yz_get_users_list( $users, $args = null ) {

    if ( empty( $users ) ) {
        return;
    }

    // Get Widget Class.
    $main_class = isset( $args['main_class'] ) ? $args['main_class'] : null;

    ?>

    <div class="yz-items-list-widget yz-list-avatar-circle <?php echo yz_generate_class( $main_class ); ?>">

        <?php foreach ( $users as $user_id ) : ?>

        <?php $profile_url = bp_core_get_user_domain( $user_id ); ?>

        <div class="yz-list-item">
            <a href="<?php echo $profile_url; ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb' ) ); ?></a>
            <div class="yz-item-data">
                <a href="<?php echo $profile_url; ?>" class="yz-item-name"><?php echo bp_core_get_user_displayname( $user_id ); ?><?php yz_the_user_verification_icon( $user_id ); ?></a>
                <div class="yz-item-meta">
                    <div class="yz-meta-item">@<?php echo bp_core_get_username( $user_id ); ?></div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

    <?php

}

/**
 * Die Message
 */
function yz_die( $message ) {
    $response['error'] = $message;
    die( json_encode( $response ) );
}

/**
 * Get User ID By Email.
 */
function yz_get_user_id_by_email( $email_address = null ) {

    // Get User Data.
    $user = get_user_by( 'email', $email_address );

    return $user->ID;
}

/**
 * Get Image Tag By Url
 */
function yz_get_avatar_img_by_url( $url ) {
    return '<img src="' . $url . '" alt="' . __( 'User Avatar', 'youzer' ) . '">';
}

/**
 * Convert Tags
 */
function yz_convert_content_tags( $content ) {

    if ( empty( $content ) ) {
        return $content;
    }

    if ( strpos( $content, '{displayed_user}' ) !== false ) {

        // Get Displayed profile username.
        $displayed_username = bp_core_get_username( bp_displayed_user_id() );

        // Replace Tags.
        $content = str_replace( '{displayed_user}', $displayed_username, $content );

    }

    if ( strpos( $content, '{logged_in_user}' ) !== false ) {

        // Get Displayed profile username.
        $logged_in_username = bp_core_get_username( bp_loggedin_user_id() );

        // Replace Tags.
        $content = str_replace( '{logged_in_user}', $logged_in_username, $content );

    }

    return apply_filters( 'yz_convert_content_tags', $content );

}

/**
 * # Pagination.
 */
function yz_pagination( $data_args ) {

    // Get Base.
    $base = isset( $_POST['base'] ) ? $_POST['base'] : get_pagenum_link( 1 );

    // Get Items Per Page Number
    $per_page = $data_args['limit'] ? $data_args['limit'] : 1;

    // Get total Pages Number
    $max_page = ceil( $data_args['total'] / $per_page );

    // Get Current Page Number
    $cpage = ! empty( $_POST['page'] ) ?  $_POST['page'] : 1;

    // Get Offset
    $offset = ( ( $per_page * ( $cpage - 1 ) ) );

    if ( $cpage != 1 ) {
        $offset = $offset + $per_page;
    }

    // Get Next and Previous Pages Number
    if ( ! empty( $cpage ) ) {
        $next_page = $cpage + 1;
        $prev_page = $cpage - 1;
    }

    // Pagination Settings
    $pagination_args = array(
        'base'        => $base . '%_%',
        'format'      => 'page/%#%',
        'total'       => $max_page,
        'current'     => $cpage,
        'show_all'    => false,
        'end_size'    => 1,
        'mid_size'    => 2,
        'prev_next'   => True,
        'prev_text'   => '<div class="yz-page-symbole">&laquo;</div><span class="yz-next-nbr">'. $prev_page .'</span>',
        'next_text'   => '<div class="yz-page-symbole">&raquo;</div><span class="yz-next-nbr">'. $next_page .'</span>',
        'type'         => 'plain',
        'add_args'     => false,
        'add_fragment' => '',
        'before_page_number' => '<span class="yz-page-nbr">',
        'after_page_number'  => '</span>',
    );

    // Call Pagination Function
    $paginate_comments = paginate_links( $pagination_args );

    // Get Data Args.
    $pargs = '';

    if ( ! empty( $data_args ) ) {
        foreach ( $data_args as $key => $value ) {
            $pargs .=' data-' . $key .'="' . $value . '"';
        }
    }

    // Print Pagination
    if ( $paginate_comments ) {
        echo sprintf( "<nav class='yz-pagination' data-base='%1s' data-page='%3d' $pargs>" , $base, $offset, $cpage );
        echo '<span class="yz-pagination-pages">';
        printf( __( 'Page %1$d of %2$d' , 'youzer' ), $cpage, $max_page );
        echo "</span><div class='comments-nav-links yz-nav-links'>$paginate_comments</div></nav>";
    }
}

/**
 * Get File Contents.
 */
function yz_file_get_contents( $durl ) {
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $durl );
    curl_setopt( $ch, CURLOPT_TIMEOUT, 5 );
    curl_setopt( $ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6' );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    $r = curl_exec( $ch );
    curl_close( $ch );
    return $r;
}

/**
 * Redirect Buddypress No access page to login page.
 */
function yz_redirect_bp_no_access_to_login_page( $data ) {
    if ( $data['mode'] == 2 ) {
        $data['mode'] = 1;
        $data['root'] = yz_get_login_page_url();
    }
    return $data;
}

add_filter( 'bp_core_no_access', 'yz_redirect_bp_no_access_to_login_page' );

/**
 * Get File Type.
 */
function yz_get_file_type( $path ) {

    // Get File Extension.
    $ext = pathinfo( $path, PATHINFO_EXTENSION );

    if ( in_array( $ext, array( 'png', 'jpg', 'jpeg', 'gif' ) ) ) {
        return 'image';
    } else if ( in_array( $ext, array( 'mp4', 'ogg', 'ogv', 'webm', 'flv', 'wmv', 'avi', 'mov' ) ) ) {
        return 'video';
    } else if ( in_array( $ext, array( 'mp3', 'ogg', 'wav' ) ) ) {
        return 'audio';
    } else {
        return 'file';
    }

}

/**
 * Get Bookmarked Post.
 */
function yz_get_bookmark_id( $user_id, $item_id, $item_type ) {

    global $wpdb, $Yz_bookmark_table;

    // Prepare Sql
    $sql = $wpdb->prepare(
        "SELECT id FROM $Yz_bookmark_table WHERE user_id = %d AND item_id = %d AND item_type = %s",
        $user_id, $item_id, $item_type
    );

    // Get Result
    $result = $wpdb->get_var( $sql );

    return $result;

}

/**
 * Get Attributes
 */
function yz_get_item_attributes( $attributes = null ) {

    if ( ! empty( $attributes ) ) {
        foreach ( $attributes as $attribute => $value ) {
            echo 'data-' . $attribute . '="' . $value . '"';
        }
    }

}

/**
 * Is Activity Component
 */
function yz_is_activity_component() {
    $active = bp_is_activity_component() ? true : false;
    return apply_filters( 'yz_is_activity_component', $active );
}

/**
 * Authenticate User.
 */

add_action( 'parse_request', 'yz_instagram_widget_process_authentication' );

function yz_instagram_widget_process_authentication( $query ) {

    if ( ! is_user_logged_in() || ! isset( $query->query_vars['yz-authentication'] ) ) {
        return;
    }

    if ( $query->query_vars['yz-authentication'] != 'feed' ) {
        return;
    }

    // Get Provider.
    $provider = $query->query_vars['yz-provider'];

    if ( empty( $provider ) || $provider != 'Instagram' ) {
        return;
    }

    // Inculue Files.
    if ( ! class_exists( 'Hybridauth', false ) ) {
        require_once YZ_PUBLIC_CORE . 'hybridauth/autoload.php';
        require_once YZ_PUBLIC_CORE . 'hybridauth/Hybridauth.php';
    }

    try {

        // Config Data.
        $config = array(
            'callback'   => home_url( '/yz-auth/feed/Instagram' ),
            "debug_file" => 'debug-instagram.txt',
            "debug_mode" => false,
            'keys' => array(
                'id'  => yz_option( 'yz_wg_instagram_app_id' ),
                'secret' => yz_option( 'yz_wg_instagram_app_secret' ),
            )
        );

        // Create an Instance with The Config Data.
        $hybridauth = new Hybridauth\Provider\Instagram( $config );

        // Get User ID.
        $user_id = get_current_user_id();

        // Start the Authentication Process.
        $adapter = $hybridauth->authenticate();

        // Get Access Token.
        $accessToken = $hybridauth->getAccessToken();

        if ( isset( $accessToken['access_token'] ) && ! empty( $accessToken['access_token'] ) ) {

            $response = wp_remote_get( 'https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=' . $config['keys']['secret'] . '&access_token=' . $accessToken['access_token'] , array( 'timeout' => 60, 'sslverify' => false ) );

            if ( ! is_wp_error( $response ) ) {

                // certain ways of representing the html for double quotes causes errors so replaced here.
                $response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );

                // Get Current Time.
                $date = new DateTime();

                // Set Expiration Date After 30 Days.
                $date->modify( '+30 days' );

                update_user_meta( $user_id, 'wg_instagram_account_token', array( 'token' => $response['access_token'], 'expires' => $date->format( 'Y/m/d' ) ) );

            }

        }

        // Get User Data.
        $user_data = $hybridauth->getUserProfile();

        if ( ! empty( $user_data ) ) {
            update_user_meta( $user_id, 'wg_instagram_account_user_data', $user_data );
            do_action( 'yz_after_linking_instagram_account', $user_id, $response['access_token']  );
        }

    } catch( Exception $e ) {
        yz_auth_redirect( $e );
    }

    wp_redirect( yz_get_widgets_settings_url( 'instagram', $user_id ) );
    exit;

}