<?php

class Youzer_Setup {

    public function __construct() {
	}

	/**
	 * # Install Youzer Options .
	 */
	function install_options() {

		// Add Default Social Netwokrs
		$social_networks = array(
			'yz_sn_1' 	=> array(
				'icon' 	=> 'fab fa-facebook-f',
				'name' 	=> __( 'Facebook', 'youzer' ),
				'color'	=> '#4987bd'
			),
			'yz_sn_2' 	=> array(
				'icon' 	=> 'fab fa-google-plus-g',
				'name'	=> __( 'Google Plus', 'youzer' ),
				'color'	=> '#ed4242'
			),
			'yz_sn_3' 	=> array(
				'icon' 	=> 'fab fa-twitter',
				'name'	=> __( 'Twitter', 'youzer' ),
				'color'	=> '#63CDF1'
				),
			'yz_sn_4' 	=> array(
				'icon' 	=> 'fab fa-instagram',
				'name'	=> __( 'Instagram', 'youzer' ),
				'color'	=> '#ffcd21'
			)
		);

		if ( ! get_option( 'yz_social_networks' ) ) {
			update_option( 'yz_social_networks' , $social_networks );
			update_option( 'yz_next_snetwork_nbr', '5' );
        	update_option( 'hide-loggedout-adminbar', 1 );
		}

		if ( ! get_option( 'yz_next_snetwork_nbr' ) ) {
			update_option( 'yz_next_snetwork_nbr', '5' );
		}

		if ( ! get_option( 'yz_next_widget_nbr' ) ) {
			update_option( 'yz_next_widget_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_field_nbr' ) ) {
			update_option( 'yz_next_field_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_ad_nbr' ) ) {
			update_option( 'yz_next_ad_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_custom_widget_nbr' ) ) {
			update_option( 'yz_next_custom_widget_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_custom_tab_nbr' ) ) {
			update_option( 'yz_next_custom_tab_nbr', '1' );
		}

		if ( ! get_option( 'yz_next_user_tag_nbr' ) ) {
			update_option( 'yz_next_user_tag_nbr', '1' );
		}

		if ( ! get_option( 'yz_profile_default_tab' ) ) {
            update_option( 'yz_profile_default_tab', 'overview' );
		}

		if ( ! get_option( 'installed_youzer_2.3.4' ) ) {
            update_option( 'installed_youzer_2.3.4', 1 );
		}

		// Create Pages
		$this->create_pages();

	}

	/**
	 * Create Pages
	 */
	function create_pages() {

	    // Plugin Pages
	    $pages = array();

	    $pages[] = array(
	        'title' => __( 'Login', 'youzer' ),
	        'slug'  => 'login',
	        'meta'  => '_logy_core',
	        'pages' => 'logy_pages'
	    );

	    $pages[] = array(
	        'title' => __( 'Password Reset', 'youzer' ),
	        'slug'  => 'lost-password',
	        'meta'  => '_logy_core',
	        'pages' => 'logy_pages'
	    );

	    $pages[] = array(
	        'title' => __( 'Complete Registration', 'youzer' ),
	        'slug'  => 'complete-registration',
	        'meta'  => '_logy_core',
	        'pages' => 'logy_pages'
	    );

	    // Get Logy Pages
	    $logy_pages = get_option( 'logy_pages' );

	    foreach ( $pages as $page ) {
	        $slug = $page['slug'];
	        if ( ! isset( $logy_pages[ $slug ] ) ) {
	            $this->add_new_plugin_page( $page );
	        }
	    }

	}

	/**
	 * Create New Plugin Page.
	 */
	function add_new_plugin_page( $args ) {

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
     * Install New Version Options
     */
    function install_new_version_options() {

        // if ( get_option( 'install_youzer_2.1.5_options' ) ) {
        //     return false;
        // }

        // $main_widgets = get_option( 'yz_profile_main_widgets' );

        // if ( ! empty( $main_widgets ) ) {
        // 	$main_widgets[] = array( 'reviews' => 'visible' );
        // 	update_option( 'yz_profile_main_widgets', $main_widgets );
        // }

        // // Mark New Options As Installed
        // update_option( 'install_youzer_2.1.5_options', 1 );

    }

	/**
	 * Build DataBase Tables.
	 */
	public function build_database_tables() {

        global $wpdb, $bp;

        // Set Variables
        $sql = array();
        $hashtags_table = $wpdb->prefix . 'yz_hashtags';
        $hashtags_items_table = $wpdb->prefix . 'yz_hashtags_items';
        $users_table = $wpdb->prefix . 'logy_users';
        $bookmark_table = $wpdb->prefix . 'yz_bookmark';
        $reviews_table = $wpdb->prefix . 'yz_reviews';
        $albums_table = $wpdb->prefix . 'yz_albums';
        $albums_items_table = $wpdb->prefix . 'yz_albums_items';
        $media_table = $wpdb->prefix . 'yz_media';
        $charset_collate = $wpdb->get_charset_collate();

        // Users Table SQL.
		$sql[] = "CREATE TABLE $users_table (
			id int(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			provider varchar(200) NOT NULL,
			identifier varchar(200) NOT NULL,
			websiteurl varchar(255) NOT NULL,
			profileurl varchar(255) NOT NULL,
			photourl varchar(255) NOT NULL,
			displayname varchar(150) NOT NULL,
			description varchar(200) NOT NULL,
			firstname varchar(150) NOT NULL,
			lastname varchar(150) NOT NULL,
			gender varchar(10) NOT NULL,
			language varchar(20) NOT NULL,
			age varchar(10) NOT NULL,
			birthday int(11) NOT NULL,
			birthmonth int(11) NOT NULL,
			birthyear int(11) NOT NULL,
			email varchar(255) NOT NULL,
			emailverified varchar(200) NOT NULL,
			phone varchar(75) NOT NULL,
			address varchar(255) NOT NULL,
			country varchar(75) NOT NULL,
			region varchar(50) NOT NULL,
			city varchar(50) NOT NULL,
			zip varchar(25) NOT NULL,
			profile_hash varchar(200) NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $bookmark_table (
			id BIGINT(11) NOT NULL AUTO_INCREMENT,
			user_id int(11) NOT NULL,
			collection_id int(11) NOT NULL,
			item_id int(11) NOT NULL,
			item_type varchar(200) NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $hashtags_items_table (
			id BIGINT(11) NOT NULL AUTO_INCREMENT,
			hashtag varchar(20) NOT NULL,
			object varchar(3) NOT NULL,
			user_id int(11) NOT NULL,
			item_id int(11) NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $hashtags_table (
			id BIGINT(11) NOT NULL AUTO_INCREMENT,
			hashtag varchar(20) NOT NULL,
			object varchar(3) NOT NULL,
			count BIGINT(8) NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $reviews_table (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			reviewer int(11) NOT NULL,
			reviewed int(11) NOT NULL,
			rating DECIMAL(2,1) NOT NULL,
			review LONGTEXT NOT NULL,
			options LONGTEXT NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		$sql[] = "CREATE TABLE $media_table (
			id BIGINT(20) NOT NULL AUTO_INCREMENT,
			media_id BIGINT(20) NULL,
			user_id int(11) DEFAULT '0' NOT NULL,
			src LONGTEXT NULL,
			data LONGTEXT NULL,
			item_id int(11) DEFAULT '0' NULL,
			privacy varchar(10) NOT NULL,
			source varchar(50) NULL,
			type varchar(10) DEFAULT 'none' NOT NULL,
			component varchar(10) DEFAULT 'activity' NOT NULL,
		 	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			PRIMARY KEY (id)
		) $charset_collate;";

		// $sql[] = "CREATE TABLE $albums_table (
		// 	id BIGINT(20) NOT NULL AUTO_INCREMENT,
		// 	title varchar(200) NOT NULL,
		// 	description LONGTEXT NULL,
		// 	cover varchar(100)  NULL,
		// 	privacy varchar(10) NOT NULL,
		// 	item_id int(11) NOT NULL,
		// 	component varchar(10) NOT NULL,
		//  	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		// 	PRIMARY KEY (id)
		// ) $charset_collate;";

		// $sql[] = "CREATE TABLE $albums_items_table (
		// 	id BIGINT(20) NOT NULL AUTO_INCREMENT,
		// 	album_id int(11) NOT NULL,
		// 	media_id BIGINT(20) NOT NULL,
		//  	time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		// 	PRIMARY KEY (id)
		// ) $charset_collate;";

        $row = $wpdb->get_results(  "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '{$bp->activity->table_name}' AND column_name = 'privacy'"  );

        if ( empty( $row ) ) {
           $wpdb->query("ALTER TABLE {$bp->activity->table_name} ADD privacy varchar(10) NULL DEFAULT 'public'");
        }

		// Include Files.
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		// Build Tables.
		dbDelta( $sql );

	}

	/**
	 * # Install Pages .
	 */
	function install_pages() {

		if ( current_user_can( 'manage_options' ) && ! get_option( 'logy_is_installed' ) ) {


			// Plugin Pages
			$pages = array(
				'login' 		 => array( 'title' => __( 'Login', 'youzer' ) ),
			 	'lost-password'  => array( 'title' => __( 'Password Reset', 'youzer' ) ),
			 	'complete-registration'  => array( 'title' => __( 'Complete Registration', 'youzer' ) )
			);

			// Install Core Pages
			foreach ( $pages as $slug => $page ) {

				// Check that the page doesn't exist already
				$is_page_exists = $this->get_post_id( 'page', '_logy_core', $slug );

				if ( ! $is_page_exists ) {

					$user_page = array(
						'post_title'	 => $page['title'],
						'post_name'		 => $slug,
						'post_type' 	 => 'post',
						'post_status'	 => 'publish',
						'post_author'    =>  1,
						'comment_status' => 'closed'
					);

					$post_id = wp_insert_post( $user_page );

					wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

					update_post_meta( $post_id, '_logy_core', $slug );

					$logy_pages[ $slug ] = $post_id;
				}
			}

			if ( isset( $logy_pages ) ) {
				update_option( 'logy_pages', $logy_pages );
			}

			update_option( 'logy_is_installed', 1 );
		}
	}

	/**
	 * # Get Post ID .
	 */
	function get_post_id( $post_type, $key_meta , $meta_value ) {

		// Get Posts
		$posts = get_posts(
		    array(
		        'post_type'  => $post_type,
		        'meta_key'   => $key_meta,
		        'meta_value' => $meta_value
		    )
		);

		if ( isset( $posts[0] ) && ! empty( $posts ) ) {
		    return $posts[0]->ID;
		}

		return false;
	}

	/**
	 * Register Reset Password Email.
	 */
	function register_bp_reset_password_email() {

	    // Do not create if it already exists and is not in the trash
	    $post_exists = post_exists( '[{{{site.name}}}] Reset Your Account Password' );

	    if ( $post_exists != 0 && get_post_status( $post_exists ) == 'publish' ) {
	       return;
	    }

	    // Check if term Already Exist.
		$term = term_exists( 'request_reset_password', bp_get_email_tax_type() );

		if ( $term ) {
			return;
		}

	    // Create post object
	    $my_post = array(
	      'post_title'    => __( '[{{{site.name}}}] Reset Your Account Password', 'youzer' ),
	      'post_content'  => __( "We got a request to reset your account password. If you made this request, visit the following link to reset your password: <a href=\"{{password.reset.url}}\">{{password.reset.url}}</a>\n\n If this was a mistake, just ignore this email and nothing will happen.", "youzer" ),  // HTML email content.
	      'post_excerpt'  => __( "We got a request to reset your account password. If you made this request, visit the following link to reset your password: {{password.reset.url}}\n\n If this was a mistake, just ignore this email and nothing will happen.", 'youzer' ),  // Plain text email content.
	      'post_status'   => 'publish',
	      'post_type' => bp_get_email_post_type() // this is the post type for emails
	    );

	    // Insert the email post into the database
	    $post_id = wp_insert_post( $my_post );

	    if ( $post_id ) {
	    // add our email to the taxonomy term 'post_received_comment'
	        // Email is a custom post type, therefore use wp_set_object_terms

	        $tt_ids = wp_set_object_terms( $post_id, 'request_reset_password', bp_get_email_tax_type() );
	        foreach ( $tt_ids as $tt_id ) {
	            $term = get_term_by( 'term_taxonomy_id', (int) $tt_id, bp_get_email_tax_type() );
	            wp_update_term( (int) $term->term_id, bp_get_email_tax_type(), array(
	                'description' => 'A member request a password reset.',
	            ) );
	        }
	    }
	}

	/**
	 * Create Youzer Xprofile fields !
	 */
	function create_xprofile_groups() {

	    if ( ! bp_is_active( 'xprofile' ) ) {
	        return;
	    }

	    $xprofile_option_id = 'yz_install_xprofile_groups';

	    // Check if the plugin is already installed.
	    $already_installed = yz_option( $xprofile_option_id );

	    if ( $already_installed ) {
	    	return;
	    }

	    // Init Array.
	    $profile_info = array();
	    $contact_info = array();

	    /**
	     * Add Profile Info Fields.
	     */
	    $profile_info['group_id'] = xprofile_insert_field_group(
            array(
                'field_group_id'        => 1,
                'name'        => __( 'Profile Info', 'youzer' ),
            )
        );

	    // Set Icon.
	    update_option( 'yz_xprofile_group_icon_1', 'fas fa-info' );

	    $profile_info['first_name'] = xprofile_insert_field( array(
	        'field_group_id' => $profile_info['group_id'],
	        'name'           => __( 'First Name', 'youzer' ),
	        'description'    => __( 'Enter your first name', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 2,
	        'is_required'    => 1,
	    ) );

	    $profile_info['last_name'] = xprofile_insert_field( array(
	        'field_group_id' => $profile_info['group_id'],
	        'name'           => __( 'Last Name', 'youzer' ),
	        'description'    => __( 'Enter your last name', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 3,
	        'is_required'    => 1,
	    ) );

	    $profile_info['user_country'] = xprofile_insert_field( array(
	        'field_group_id' => $profile_info['group_id'],
	        'name'           => __( 'Country', 'youzer' ),
	        'description'    => __( 'Enter your country', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 4,
	        'is_required'    => 1,
	    ) );

	    $profile_info['user_city'] = xprofile_insert_field( array(
	        'field_group_id' => $profile_info['group_id'],
	        'name'           => __( 'City', 'youzer' ),
	        'description'    => __( 'Enter your city', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 5,
	        'is_required'    => 1,
	    ) );

	    $profile_info['description'] = xprofile_insert_field( array(
	        'field_group_id' => $profile_info['group_id'],
	        'name'           => __( 'Biographical Info', 'youzer' ),
	        'description'    => __( 'Add your biography', 'youzer' ),
	        'type'           => 'textarea',
	        'field_order'    => 6,
	        'is_required'    => 1,
	    ) );

	    /**
	     * Add Contact Info Fields.
	     */

	    $contact_info['group_id'] = xprofile_insert_field_group(
	        array(
	            'name'        => __( 'Contact Info', 'youzer' ),
	            'description' => 'yz_contant_info', //WE USE THE DESCRIPTION FIELD AS KEY,FOR UNIQUE CODE
	        )
	    );

	    // Set Icon
	    update_option( 'yz_xprofile_group_icon_' . $contact_info['group_id'], 'far fa-envelope' );

	    $contact_info['email_address'] = xprofile_insert_field( array(
	        'field_group_id' => $contact_info['group_id'],
	        'name'           => __( 'E-mail', 'youzer' ),
	        'description'    => __( 'Add your email address', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 1,
	        'is_required'    => 1,
	    ) );

	    $contact_info['phone_nbr'] = xprofile_insert_field( array(
	        'field_group_id' => $contact_info['group_id'],
	        'name'           => __( 'Phone', 'youzer' ),
	        'description'    => __( 'Add your phone number', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 2,
	        'is_required'    => 1,
	    ) );

	    $contact_info['user_address'] = xprofile_insert_field( array(
	        'field_group_id' => $contact_info['group_id'],
	        'name'           => __( 'Address', 'youzer' ),
	        'description'    => __( 'Add your address', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 3,
	        'is_required'    => 1,
	    ) );

	    $contact_info['user_url'] = xprofile_insert_field( array(
	        'field_group_id' => $contact_info['group_id'],
	        'name'           => __( 'Website', 'youzer' ),
	        'description'    => __( 'Add your website url', 'youzer' ),
	        'type'           => 'textbox',
	        'field_order'    => 4,
	        'is_required'    => 1,
	    ) );

	    if ( is_multisite() ) {
	        // Set the plugin to be installed.
	        update_blog_option( BP_ROOT_BLOG, $xprofile_option_id, true );
	        // Save Fields.
	        update_blog_option( BP_ROOT_BLOG, 'yz_xprofile_contact_info_group_ids', $contact_info );
	        update_blog_option( BP_ROOT_BLOG, 'yz_xprofile_profile_info_group_ids', $profile_info );
	    } else {
	        // Set the plugin to be installed
	        update_option( $xprofile_option_id, true );
	        // save the contact info data.
	        update_option( 'yz_xprofile_contact_info_group_ids', $contact_info, 'no' );
	        update_option( 'yz_xprofile_profile_info_group_ids', $profile_info, 'no' );
	    }

	}
}