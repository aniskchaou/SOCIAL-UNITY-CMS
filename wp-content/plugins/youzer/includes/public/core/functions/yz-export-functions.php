<?php

/**
 * Register Youzer Data Exporter.
 */
function yz_register_exporter( $exporters ) {

    // User Data Export.
    $exporters['wordpress-user'] = array(
        'exporter_friendly_name' => __( 'User Information', 'youzer' ),
        'callback' => 'yz_wp_user_data_exporter',
    );

    // User Widgets Data Export.
    $exporters['youzer-profile-widgets'] = array(
        'exporter_friendly_name' => __( 'Profile Widgets Data', 'youzer' ),
        'callback' => 'yz_profile_widgets_exporter',
    );

    return $exporters;

}

add_filter( 'wp_privacy_personal_data_exporters', 'yz_register_exporter', 1 );

/**
 * Get Profile Widgets Data.
 */
function yz_profile_widgets_exporter( $email_address, $page = 1 ) {

    $export_items = array();

    // Get User.
    $user = get_user_by( 'email', $email_address );

    // Get User Data.
    $user_data = yz_user_widgets_fields();

    foreach ( $user_data as $widget_name => $widget ) {

        $data = null;

        if ( isset( $widget['fields'] ) ) {

            foreach ( $widget['fields'] as $field_id => $field ) {

                $value = yz_data( $field_id, $user->ID );

                if ( empty( $value ) ) continue;

                $value = apply_filters( 'yz_exported_field_value', $value, $field );

                $data[] = array(
                  'name' => $field['title'],
                  'value' => $value
                );

            }

            if ( empty( $data ) ) {
                continue;
            }
            $export_items[] = array(
                'group_id' => 'yz-' . $widget_name,
                'group_label' => $widget['title'],
                'item_id' => $widget_name,
                'data' => $data
            );

        } else {
            // $data = null;

            $value = yz_data( $widget['id'], $user->ID );

            if ( empty( $value ) && $widget_name != 'social_networks' ) {
                continue;
            }


            switch ( $widget_name ) {

                case 'instagram':

                    $instagram_data = yz_data( 'wg_instagram_account_user_data', $user_id );

                    if ( empty( $instagram_data ) ) {
                        break;
                    }

                    foreach ( $instagram_data as $key => $value ) {
                        if ( ! empty( $value ) && $key != '__PHP_Incomplete_Class_Name' ) {
                            $data[] = array( 'name' => $key, 'value' => $value );
                        }
                    }

                    $export_items[] = array(
                        'group_id' => 'yz-' . $widget_name,
                        'group_label' => $widget['title'],
                        'item_id' => $widget_name,
                        'data' => $data
                    );

                    break;

                case 'flickr':

                    $data = array( array( 'name' => __( 'Account ID', 'youzer' ), 'value' => $value ) );

                    $export_items[] = array(
                        'group_id' => 'yz-' . $widget_name,
                        'group_label' => $widget['title'],
                        'item_id' => $widget_name,
                        'data' => $data
                    );

                    break;

                case 'skills':
                case 'services':

                    $i = 1;

                    foreach ( $value as $key => $wg_data ) {

                        $data = null;

                        foreach ( $wg_data as $key => $val ) {
                            $data[] = array( 'name' => ucfirst( $key ), 'value' => $val );
                        }

                        $export_items[] = array(
                            'group_id' => 'yz-' . $widget_name,
                            'group_label' => $widget['title'],
                            'item_id' => $widget_name . $i,
                            'data' => $data
                        );

                        $i++;
                    }

                    break;

                case 'portfolio':
                case 'slideshow':

                    global $YZ_upload_url;

                    $i = 1;

                    foreach ( $value as $key => $wg_data ) {

                        $data = null;

                        foreach ( $wg_data as $img_key => $img_value) {

                            if ( empty( $img_value ) ) {
                                continue;
                            }

                            $val = ( $img_key == 'original' || $img_key == "thumbnail" ) ? $YZ_upload_url. $img_value: $img_value;

                            $data[] = array( 'name' => ucfirst( $img_key ), 'value' => '<a href=" ' . $val . '" >' . $val . '</a>' );
                        }


                        $export_items[] = array(
                            'group_id' => 'yz-' . $widget_name,
                            'group_label' => $widget['title'],
                            'item_id' => $widget_name . $i,
                            'data' => $data
                        );

                        $i++;
                    }

                    break;

                default:
                    $export_items[] = array(
                        'group_id' => 'yz-' . $widget_name,
                        'group_label' => $widget['title'],
                        'item_id' => $widget_name,
                        'data' => array( array( 'name' => $widget['title'], 'value' => $value ) )
                    );
                    break;
            }

        }

    }

    return array(
        'data' => $export_items,
        'done' => true,
    );
}

/**
 * Get User Profile Data.
 */
function yz_wp_user_data_exporter( $email_address, $page = 1 ) {

    // Get Fields
    $fields = yz_wp_user_fields();

    // Get User.
    $user = get_user_by( 'email', $email_address );

    foreach ( $fields as $field_id => $field ) {

        $value = yz_data( $field_id, $user->ID );

        if ( empty( $value ) ) continue;

        $value = apply_filters( 'yz_wp_user_data_export_value', $value, $field );

        $data[] = array(
          'name' => $field['title'],
          'value' => $value
        );

    }

    // Get Export Items
    $export_items[] = array(
        'group_id'    => 'user',
        'group_label' => __( 'User', 'youzer' ),
        'item_id'     => "user-{$user->ID}",
        'data' => $data
    );

    return array(
        'data' => $export_items,
        'done' => true,
    );
}


/**
 * Get Wordpress User Fields.
 */
function yz_wp_user_fields() {
    $fields = array(
        'ID' => array(
            'title' => __( 'User ID', 'youzer' ),
        ),
        'user_firstname' => array(
            'title' => __( 'First Name', 'youzer' ),
        ),
        'user_lastname' => array(
            'title' => __( 'Last Name', 'youzer' ),
        ),
        'nickname' => array(
            'title' => __( 'Nickname', 'youzer' ),
        ),
        'user_nicename' => array(
            'title' => __( 'Nice Name', 'youzer' ),
        ),
        'display_name' => array(
            'title' => __( 'Display Name', 'youzer' ),
        ),
        'user_login' => array(
            'title' => __( 'User Login', 'youzer' ),
        ),
        'user_email' => array(
            'title' => __( 'Email', 'youzer' ),
        ),
        'user_url' => array(
            'title' => __( 'Website', 'youzer' ),
        ),
        'user_registered' => array(
            'title' => __( 'User Registration Date', 'youzer' ),
        ),
        'user_description' => array(
            'title' => __( 'Description', 'youzer' ),
        )
    );

    return apply_filters( 'yz_wp_user_fields', $fields );
}

/**
 * Get User Widgets Fields.
 */
function yz_user_widgets_fields() {

    // if ( ! empty( $))
    $fields = array(
        'instagram' => array(
            'id' => 'wg_instagram_account_token',
            'title' => __( 'User Instagram Widget', 'youzer' )
        ),
        'flickr' => array(
            'id' => 'wg_flickr_account_id',
            'title' => __( 'User Flickr Widget', 'youzer' )
        ),
        'skills' => array(
            'id' => 'youzer_skills',
            'title' => __( 'User Skills Widget', 'youzer' )
        ),
        'services' => array(
            'id' => 'youzer_services',
            'title' => __( 'User Services Widget', 'youzer' )
        ),
        'slideshow' => array(
            'id' => 'youzer_slideshow',
            'title' => __( 'User Slideshow Widget', 'youzer' ),
            'type' => 'images'
        ),
        'portfolio' => array(
            'id' => 'youzer_portfolio',
            'title' => __( 'User Portfolio Widget', 'youzer' ),
            'type' => 'images'
        ),
        'post' => array(
            'title' => __( 'User Post Widget', 'youzer' ),
            'fields' => array(
                'yz_profile_wg_post_id' => array(
                    'title' => __( 'Post ID', 'youzer' ),
                ),
                'wg_post_type' => array(
                    'title' => __( 'Post Type', 'youzer' ),
                )
            )
        ),
        'video' => array(
            'title' => __( 'User Video Widget', 'youzer' ),
            'fields' => array(
                'wg_video_title' => array(
                    'title' => __( 'Title', 'youzer' ),
                ),
                'wg_video_desc' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
                'wg_video_url' => array(
                    'title' => __( 'URL', 'youzer' ),
                )
            )
        ),
        'about_me' => array(
            'title' => __( 'User About me Widget', 'youzer' ),
            'fields' => array(
                'wg_about_me_photo' => array(
                    'title' => __( 'Photo', 'youzer' ),
                    'type' => 'image'
                ),
                'wg_about_me_title' => array(
                    'title' => __( 'Title', 'youzer' ),
                ),
                'wg_about_me_desc' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
                'wg_about_me_bio' => array(
                    'title' => __( 'Biography', 'youzer' ),
                )
            )
        ),
        'quote' => array(
            'title' => __( 'User Quote Widget', 'youzer' ),
            'fields' => array(
                'wg_quote_owner' => array(
                    'title' => __( 'Owner', 'youzer' ),
                ),
                'wg_quote_txt' => array(
                    'title' => __( 'Text', 'youzer' ),
                ),
                'wg_quote_img' => array(
                    'title' => __( 'Cover', 'youzer' ),
                    'type'  => 'image'
                ),
                'wg_quote_use_bg' => array(
                    'title' => __( 'Use Quote Cover?', 'youzer' ),
                )
            )
        ),
        'link' => array(
            'title' => __( 'User Link Widget', 'youzer' ),
            'fields' => array(
                'wg_link_url' => array(
                    'title' => __( 'URL', 'youzer' ),
                ),
                'wg_link_txt' => array(
                    'title' => __( 'Text', 'youzer' ),
                ),
                'wg_link_img' => array(
                    'title' => __( 'Cover', 'youzer' ),
                    'type'  => 'image'
                ),
                'wg_link_use_bg' => array(
                    'title' => __( 'Use Link Cover?', 'youzer' ),
                )
            )
        ),
        'project' => array(
            'title' => __( 'User Project Widget', 'youzer' ),
            'fields' => array(
                'wg_project_title' => array(
                    'title' => __( 'Title', 'youzer' ),
                ),
                'wg_project_desc' => array(
                    'title' => __( 'Description', 'youzer' ),
                ),
                'wg_project_type' => array(
                    'title' => __( 'Type', 'youzer' ),
                ),
                'wg_project_thumbnail' => array(
                    'title' => __( 'Thumbnail', 'youzer' ),
                    'type' => 'image'
                ),
                'wg_project_link' => array(
                    'title' => __( 'Link', 'youzer' ),
                ),
                'wg_project_categories' => array(
                    'title' => __( 'Categories', 'youzer'),
                    'type' => 'options',
                ),
                'wg_project_tags' => array(
                    'title' => __( 'Tags', 'youzer'),
                    'type' => 'options',
                ),
            )
        ),

    );

    // Add Networks Fields
    $networks_fields = yz_get_social_networks_fields();
    if ( ! empty( $networks_fields ) ) {
        $fields['social_networks'] = array( 'title' => __( 'Social Networks', 'youzer' ), 'fields' => $networks_fields );
    }

    return apply_filters( 'yz_export_fields', $fields );
}
/**
 * Get Social Networks Fields.
 */
function yz_get_social_networks_fields() {

    // Init Vars
    $networks_fields = array();

    // Get Social Networks
    $social_networks = yz_option( 'yz_social_networks' );

    if ( empty( $social_networks ) ) {
        return false;
    }

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Check if there's URL related to the icons.
    foreach ( $social_networks as $network => $data ) {
        $networks_fields[ $network ] = array( 'title' => $data['name'] );
    }

    return $networks_fields;
}

/**
 * Filter Exported Fields Values.
 */
function yz_filter_exported_field_value( $value, $field ) {

    if ( ! isset( $field['type'] ) ) {
        return $value;
    }

    switch ( $field['type'] ) {

        case 'options':

            return implode( ', ', $value );

        case 'images':
        case 'image':

            global $YZ_upload_url;

            foreach ( $value as $key => $val ) {

                if ( empty( $val ) ) continue;

                if ( $key == 'original' ) {
                    $key = __( 'Original', 'youzer' );
                } elseif ( $key == 'thumbnail' ) {
                    $key = __( 'Thumbnail', 'youzer' );
                }

                // Get Url.
                $url = $YZ_upload_url . $val;

                // Get Content.
                $content .='<strong>' . $key . '</strong> : <a href="' . $url . '">' . $url . '</a><br>';

            }

            return $content;

        default:
            return $value;
            break;
    }

}

add_filter( 'yz_exported_field_value', 'yz_filter_exported_field_value', 10, 2 );