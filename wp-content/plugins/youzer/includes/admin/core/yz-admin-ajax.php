<?php

class Youzer_Admin_Ajax {

	function __construct() {

		// Save Settings
		add_action( 'wp_ajax_youzer_admin_data_save',  array( $this, 'save_settings' ) );

		// Reset Settings
		add_action( 'wp_ajax_youzer_reset_settings',  array( $this, 'reset_settings' ) );

	}

	/**
	 * # Save Settings With Ajax.
	 */
	function save_settings() {

		check_ajax_referer( 'youzer-settings-data', 'security' );

		do_action( 'yz_before_panel_save_settings', $_POST );

		$data = $_POST;

		unset( $data['security'], $data['action'] );

		// Include Styles.
        require_once YZ_PUBLIC_CORE . 'class-yz-styling.php';

	    // Youzer Panel options
	    $options = isset( $data['youzer_options'] ) ? $data['youzer_options'] : null;

	    // Save Options
	    if ( $options ) {

	    	// Get Default Options.
	    	$default_options = yz_default_options();

	    	// Get Active Styles.
	    	$active_styles = yz_option( 'yz_active_styles', array() );

	    	if ( empty( $active_styles ) ) {
	    		$active_styles = array();
	    	}

	    	// Get All Youzer Styles
	    	$all_styles = yz_styling()->get_all_styles( 'ids' );

		    foreach ( $options as $option => $value ) {

		    	// Get Option Value
		        if ( ! is_array( $value ) ) {
		        		$the_value = stripslashes( $value );
		        } else {

		        	$the_value = $value;

		        	if ( isset( $value['color'] ) && empty( $value['color'] ) ) {
		        		$the_value = '';
		        	}

		        }

		        // Save Option or Delete Option if Empty
		        if ( ! empty( $the_value ) ) {

		        	if ( isset( $default_options[ $option ] ) && $the_value == $default_options[ $option ] ) {
		        		yz_delete_option( $option );
		        	} else {
		        		yz_update_option( $option, $the_value, false );
		        	}

		        } else {
		        	yz_delete_option( $option );
		        }

		        // Update Active Style.
		        if ( in_array( $option, $all_styles ) ) {

		        	// Get Option Key.
		        	$option_key = array_search( $option, $active_styles );

		        	if ( $option_key !== false ) {

			        	if ( ! empty( $the_value ) && isset( $active_styles[ $option_key ] ) ) {
			        		continue;
			        	}

			        	if ( empty( $the_value ) ) {
			        		if ( isset( $active_styles[ $option_key ] ) ) {
			        			unset( $active_styles[ $option_key ] );
			        		}
			        		continue;
			        	}
			        }

		        	$active_styles[] = $option;
		        }

		    }

		    if ( ! empty( $active_styles ) ) {

		    	// Get Unique Values.
		    	$active_styles = array_filter( array_unique( $active_styles ) );

		    	// Save New Styles.
        		yz_update_option( 'yz_active_styles', $active_styles, false );

		    } else {
				yz_delete_option( BP_ROOT_BLOG, 'yz_active_styles' );
		    }

	    }

		// Save "Disable Delete Accounts"
		$disable_account_deletion = 'bp-disable-account-deletion';

        if ( isset( $options[ $disable_account_deletion ] ) ) {
	    	if ( 'on' == $options[ $disable_account_deletion ] ) {
	    		yz_update_option( $disable_account_deletion, 0 );
	    	} else {
	    		yz_update_option( $disable_account_deletion, 1 );
	    	}
	    }

		// Save Registration Value
		$register_opts = 'users_can_register';
        if ( isset( $options[ $register_opts ] ) ) {
	    	if ( 'on' == $options[ $register_opts ] ) {
	    		yz_update_option( $register_opts, 1 );
	    	} else {
	    		yz_update_option( $register_opts, 0 );
	    	}
	    }

	    // If User want to save Youzer Plugin Pages.
	    if ( isset( $data['youzer_pages'] ) ) {
		    $this->save_youzer_pages( $data['youzer_pages'] );
	    }

	    // If User want to save Logy.
	    if ( isset( $data['logy_pages'] ) ) {
		    $this->save_logy_pages( $data['logy_pages'] );
	    }

	    // Save Ads.
		if ( isset( $data['yz_ads_form'] ) ) {
			$this->save_ads( $data['yz_ads'] );
		}

	    // Save Social Networks.
	    if ( isset( $data['yz_networks_form'] ) ) {
	    	$this->save_social_networks( $data['yz_networks'] );
	    }

	    // Save Custom Widgets.
	    if ( isset( $data['yz_custom_widgets_form'] ) ) {
	    	$this->save_custom_widgets( $data['yz_custom_widgets'] );
	    }

	    // Save Custom Widgets.
	    if ( isset( $data['yz_custom_tabs_form'] ) ) {
	    	$this->save_custom_tabs( $data['yz_custom_tabs'] );
	    }

	    // Save User Tags.
	    if ( isset( $data['yz_user_tags_form'] ) ) {
	    	$this->save_user_tags( $data['yz_user_tags'] );
	    }

	    // Save Profile Structure.
	    if ( isset( $data['yz_profile_stucture'] ) ) {

		    $hidden = array();

	    	// Get Data
	    	$main_widgets = isset( $data['yz_profile_main_widgets'] ) ? $data['yz_profile_main_widgets'] : array();
	    	$sidebar_widgets = isset( $data['yz_profile_sidebar_widgets'] ) ? $data['yz_profile_sidebar_widgets'] : array();
	    	$left_sidebar_widgets = isset( $data['yz_profile_left_sidebar_widgets'] ) ? $data['yz_profile_left_sidebar_widgets'] : array();


	    	// Update Options
	    	yz_update_option( 'yz_profile_main_widgets', $main_widgets );
	    	yz_update_option( 'yz_profile_sidebar_widgets', $sidebar_widgets );
	    	yz_update_option( 'yz_profile_left_sidebar_widgets', $left_sidebar_widgets );

		    $all_widgets = array_merge( $main_widgets, $sidebar_widgets, $left_sidebar_widgets );

		    foreach ( $all_widgets as $widget_name => $visibility ) {
	            if ( $visibility == 'invisible' ) {
	                $hidden[] = $widget_name;
	            }
		    }

		    if ( ! empty( $hidden ) ) {
		        yz_update_option( 'yz_profile_hidden_widgets', $hidden );
		    } else {
		        yz_delete_option( 'yz_profile_hidden_widgets' );
		    }

	    	// Hook.
	    	do_action( 'yz_after_saving_profile_structure' );
	    }

	    if ( isset( $data['yz_unallowed_activities'] ) ) {

	    	$unallowed_activities = array();

	    	foreach ( $data['yz_unallowed_activities'] as $activity_type => $activity_visibilty ) {

	    		if ( $activity_visibilty != 'on' ) {

	    			$unallowed_activities[] = $activity_type;

	    			if ( $activity_type == 'activity_status' ) {
	    				$unallowed_activities[] = 'activity_update';
	    			}

	    		}

	    	}

			// if ( in_array( 'friendship_accepted', $unallowed_activities ) && in_array( 'friendship_created', $unallowed_activities ) ) {
			// 	$unallowed_activities[] = 'friendship_accepted,friendship_created';
			// 	foreach ( array( 'friendship_accepted', 'friendship_created' ) as $type ) {
			// 		if ( ( $key = array_search( $type, $unallowed_activities ) ) !== false) {
			// 			unset( $unallowed_activities[ $key ] );
			// 		}
			// 	}
			// }

			if ( empty( $unallowed_activities ) ) {
				yz_delete_option( 'yz_unallowed_activities' );
			} else {
				yz_update_option( 'yz_unallowed_activities', $unallowed_activities );
			}

	    }

	    if ( isset( $data['yz_profile_tabs'] ) ) {
			// require_once YZ_PUBLIC_CORE . 'class-yz-tabs.php';
			$new_tabs = $data['yz_profile_tabs'];
			$tabs = array();
			$old_tabs = yz_get_profile_primary_nav();
			$default_tabs = yz_profile_tabs_default_value();
			foreach ( $old_tabs as $old_tab ) {

				if ( isset( $new_tabs[ $old_tab['slug'] ] ) ) {

					$new_tab = $new_tabs[ $old_tab['slug'] ];

					if ( ! empty( $new_tab['position'] ) && $new_tab['position'] != $old_tab['position'] && is_numeric( $new_tab['position'] ) ) {
						$tabs[ $old_tab['slug'] ]['position'] = $new_tab['position'];
					}

					$old_title = _bp_strip_spans_from_title( $old_tab['name'] );

					if ( ! empty( $new_tab['name']) && $new_tab['name'] != $old_title ) {
						$count = strstr( $old_title, '<span' );
						$tabs[ $old_tab['slug'] ]['name'] = ! empty( $count ) ? $new_tab['name'] . $count : $new_tab['name'];
					}

					if ( $new_tab['visibility'] != 'on' ) {
						$tabs[ $old_tab['slug'] ]['visibility'] = 'off';
					}

					if ( $new_tab['icon'] != 'fas fa-globe-asia' ) {
						if ( isset( $default_tabs[ $old_tab['slug'] ]['icon'] ) ) {
							if ( $new_tab['icon'] != $default_tabs[ $old_tab['slug'] ]['icon'] ) {
								$tabs[ $old_tab['slug'] ]['icon'] =  $new_tab['icon'];
							}
						} else {
							$tabs[ $old_tab['slug'] ]['icon'] = $new_tab['icon'];
						}
					}

					if ( isset( $new_tab['deleted']  ) && $new_tab['deleted'] == 'on' ) {
						$tabs[ $old_tab['slug'] ]['deleted'] = 'on';
					}

				}
			}

			if ( empty( $tabs ) ) {
				yz_delete_option( 'yz_profile_tabs' );
			} else {
				yz_update_option( 'yz_profile_tabs', $tabs );
			}

	    }

	    // Actions
	    do_action( 'yz_panel_save_settings', $data );

		wp_send_json_success( array( 'result' => 1, 'message' => __( 'Success !', 'youzer' ) ) );
		exit();

	}

	/**
	 * # Save Pages.
	 */
	function save_logy_pages( $logy_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $logy_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using the same page more than once.', 'youzer' ) );
			}
		}

		// Update Pages in Database.
		$update_pages = yz_update_option( 'logy_pages', $logy_pages, false );

		if ( $update_pages ) {
			foreach ( $logy_pages as $page => $id ) {
				// Update Option ID
				yz_update_option( $page, $id );
			}
		}
	}

	/**
	 * # Save Social Networks.
	 */
	function save_social_networks( $networks ) {

		if ( empty( $networks ) ) {
			yz_delete_option( 'yz_social_networks' );
			return false;
		}

    	$update_options = yz_update_option( 'yz_social_networks', $networks, false );

		// Update Next Network ID
    	if ( $update_options ) {
			yz_update_option( 'yz_next_snetwork_nbr', $this->get_next_ID( $networks, 'snetwork' ), false );
    	}

	}

	/**
	 * # Save Custom Tabs.
	 */
	function save_custom_tabs( $tabs ) {

		if ( empty( $tabs ) ) {
			yz_delete_option( 'yz_custom_tabs' );
			return false;
		}

		// Update Tabs.
    	$update_options = yz_update_option( 'yz_custom_tabs', $tabs, false );

		// Update Next ID
    	if ( $update_options ) {
			yz_update_option( 'yz_next_custom_tab_nbr', $this->get_next_ID( $tabs, 'custom_tab' ), false );
    	}

	}

	/**
	 * # Save User Tags.
	 */
	function save_user_tags( $tags ) {

		if ( empty( $tags ) ) {
			yz_delete_option( 'yz_user_tags' );
			return false;
		}

		// Update Types.
    	$update_options = yz_update_option( 'yz_user_tags', $tags, false );

		// Update Next ID
    	if ( $update_options ) {
			yz_update_option( 'yz_next_user_tag_nbr', $this->get_next_ID( $tags, 'user_tag' ) );
    	}

	}

	/**
	 * # Save Ads.
	 */
	function save_ads( $ads ) {

		$yz_ads = array();

		if ( ! empty( $ads ) ) {
			foreach ( $ads as $ad => $data ) {
				$yz_ads[ $ad ] = $data;
			}
		}

		// Update ads List.
    	$update_options = yz_update_option( 'yz_ads', $yz_ads, false );

    	// If ADS not updated stop function right here.
		if ( ! $update_options ) {
			return false;
		} else {
			// Update Next Ad ID
			yz_update_option( 'yz_next_ad_nbr', $this->get_next_ID( $yz_ads, 'ad' ), false );
    	}

	    // Get Overview and Sidebar Widgets
	    $overview_wgs = yz_options( 'yz_profile_main_widgets' );
	    $sidebar_wgs = yz_options( 'yz_profile_sidebar_widgets' );
	    $left_sidebar_wgs = yz_options( 'yz_profile_left_sidebar_widgets' );

	    // Merge Overview & Sidebar widgets
	    $all_widgets = array_merge( $overview_wgs, $sidebar_wgs, $left_sidebar_wgs );

	    // Get Ads Widgets
	    $ads_widgets = $this->get_ads_widgets( $all_widgets );

	    if ( ! empty( $ads_widgets ) ) {

		    // Delete Removed ADS.
		    foreach ( $ads_widgets as $widget_name => $visibility ) {

		        // if widget name is not found.
		        if ( ! isset( $yz_ads[ $widget_name ] ) ) {

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $sidebar_wgs[ $widget_name ] ) ) {
		                unset( $sidebar_wgs[ $widget_name ]  );
		            }

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $left_sidebar_wgs[ $widget_name ] ) ) {
		                unset( $left_sidebar_wgs[ $widget_name ]  );
		            }

	                // if the removed widget in the overview remove it.
		            if ( isset( $overview_wgs[ $widget_name ] ) ) {
		                unset( $overview_wgs[ $widget_name ]  );
		            }

		        }

		    }

	    }

	    foreach ( $yz_ads as $ad_id => $data ) {
	        if ( ! isset( $all_widgets[ $ad_id ] ) ) {
	        	$sidebar_wgs[ $ad_id ] = 'visible';
	        }
	    }

		// Update Overview & Sidebar Widgets.
		yz_update_option( 'yz_profile_main_widgets', $overview_wgs );
		yz_update_option( 'yz_profile_sidebar_widgets', $sidebar_wgs );
		yz_update_option( 'yz_profile_left_sidebar_widgets', $left_sidebar_wgs );

	}

	/**
	 * # Save Custom Widgets.
	 */
	function save_custom_widgets( $widgets ) {

		$yz_cw = array();

		if ( ! empty( $widgets ) ) {
			foreach ( $widgets as $widget => $data ) {
				$yz_cw[ $widget ] = $data;
			}
		}

		// Update ads List.
    	$update_options = yz_update_option( 'yz_custom_widgets', $yz_cw, false );

    	// If widgets not updated stop function right here.
		if ( ! $update_options ) {
			return false;
		} else {
			// Update Next ID
			yz_update_option( 'yz_next_custom_widget_nbr', $this->get_next_ID( $yz_cw, 'custom_widget' ) );
    	}

	    // Get Overview and Sidebar Widgets
	    $overview_wgs = yz_options( 'yz_profile_main_widgets' );
	    $sidebar_wgs  = yz_options( 'yz_profile_sidebar_widgets' );
	    $left_sidebar_wgs = yz_options( 'yz_profile_left_sidebar_widgets' );

	    // Merge Overview & Sidebar widgets
	    $all_widgets = array_merge( $overview_wgs, $sidebar_wgs, $left_sidebar_wgs );

	    // Get Custom Widgets.
	    $custom_widgets = $this->get_custom_widgets( $all_widgets );

	    if ( ! empty( $custom_widgets ) ) {

		    // Delete Removed widgets.
		    foreach ( $custom_widgets as $widget_name => $visibility ) {

		        // if widget name is not found.
		        if ( ! isset( $yz_cw[ $widget_name ] ) ) {

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $sidebar_wgs[ $widget_name ] ) ) {
		                unset( $sidebar_wgs[ $widget_name ]  );
		            }

		            // if the removed widget in the sidebar remove it.
		            if ( isset( $left_sidebar_wgs[ $widget_name ] ) ) {
		                unset( $left_sidebar_wgs[ $widget_name ]  );
		            }

	                // if the removed widget in the overview remove it.
		            if ( isset( $overview_wgs[ $widget_name ] ) ) {
		                unset( $overview_wgs[ $widget_name ]  );
		            }

		        }

		    }

	    }

	    foreach ( $yz_cw as $widget_key => $data ) {
	        if ( ! isset( $all_widgets[ $widget_key ] ) ) {
	        	$sidebar_wgs[ $widget_key ] = 'visible';
	        }
	    }

		// Update Overview & Sidebar Widgets.
		yz_update_option( 'yz_profile_main_widgets', $overview_wgs );
		yz_update_option( 'yz_profile_sidebar_widgets', $sidebar_wgs );
		yz_update_option( 'yz_profile_left_sidebar_widgets', $left_sidebar_wgs );
	}

	/**
	 * # Save Youzer Pages.
	 */
	function save_youzer_pages( $youzer_pages ) {

		// Get How much time page is repeated.
		$page_counts = array_count_values( $youzer_pages );

		// if page is already used show error messsage.
		foreach ( $page_counts as $id => $nbr ) {
			if ( $nbr > 1 ) {
				die( __( 'You are using the same page more than once.', 'youzer' ) );
			}
		}

		// Update Youzer Pages in Database.
		$update_pages = update_option( 'youzer_pages', $youzer_pages, false );

		if ( $update_pages ) {
			foreach ( $youzer_pages as $page => $id ) {
				// Update Option ID
				update_option( $page, $id );
			}
		}
	}

	/**
	 * Reset Settings
	 */
	function reset_settings() {

		do_action( 'yz_before_reset_tab_settings' );

		// Get Reset Type.
		$reset_type = $_POST['reset_type'];

	    if ( 'tab' == $reset_type ) {
			check_ajax_referer( 'youzer-settings-data', 'security' );
	    	$result  = $this->reset_tab_settings( $_POST['youzer_options'] );
	    } elseif ( 'all' == $reset_type ) {
	    	$result = $this->reset_all_settings();
	    }

	}

	/**
	 * Reset All Settings.
	 */
	function reset_all_settings() {

		do_action( 'yz_before_reset_all_settings' );

		// Delete Active Styles.
	    yz_delete_option( 'yz_active_styles' );

		// Reset Membership Settings.
		if ( yz_is_membership_system_active() ) {
			$this->membership_reset_settings();
		}

		// Get Default Options.
		$default_options = yz_default_options();

		// Reset Options
		foreach ( $default_options as $option => $value ) {
			if ( yz_option( $option ) ) {
				yz_update_option( $option, $value, false );
			}
		}

		// Reset Styling Input's
        foreach ( yz_styling()->get_all_styles() as $key ) {
			if ( yz_option( $key['id'] ) ) {
				yz_delete_option( $key['id'] );
			}
        }

        // Reset Gradient Elements
        foreach ( yz_styling()->get_gradient_elements() as $key ) {

			if ( yz_option( $key['left_color'] ) ) {
				yz_delete_option( $key['left_color'] );
			}

			if ( yz_option( $key['right_color'] ) ) {
				yz_delete_option( $key['right_color'] );
			}

        }

		// Specific Options
		$specific_options = array(
			'yz_profile_404_photo',
			'yz_profile_404_cover',
			'yz_default_groups_cover',
			'yz_default_groups_avatar',
			'yz_default_profiles_cover',
			'yz_default_profiles_avatar',
			'yz_profile_custom_scheme_color'
		);

		// Reset Specific Options
		foreach ( $specific_options as $option ) {
			if ( yz_option( $option ) ) {
				yz_delete_option( $option );
			}
		}

		wp_send_json_success( array( 'result' => 1, 'message' => __( 'Success !', 'youzer' ) ) );
		exit();

	}

	/**
	 * Reset Current Tab Settings.
	 */
	function reset_tab_settings( $tab_options ) {

		if ( empty( $tab_options ) ) {
			return false;
		}

    	// Get Active Styles.
    	$active_styles = yz_option( 'yz_active_styles' );

		// Reset Tab Options
		foreach ( $tab_options as $option => $value ) {

			// Rest Options.
			if ( yz_option( $option ) ) {
				yz_delete_option( $option );
			}

			// Delete Reseted Active Styles.
			if ( ! empty( $active_styles ) && isset( $value['color'] ) ) {

				// Get Option Key.
				$style_key = array_search( $option, $active_styles );

				// Remove Style from the list.
				if ( $style_key !== false ) {
					unset( $active_styles[ $style_key ] );
				}

			}

		}

		// Save Active Styles
		if ( ! empty( $active_styles ) ) {
			yz_update_option( 'yz_active_styles', $active_styles, false );
		} else {
			yz_delete_option( 'yz_active_styles' );
		}

		wp_send_json_success( array( 'result' => 1, 'message' => __( 'Success !', 'youzer' ) ) );
		exit();

	}


	/**
	 * Get Fields Next ID.
	 */
	function get_next_ID( $items, $item ) {

		// Set Up Variables.
		$keys = array_keys( $items );

		// Get Keys Numbers.
		foreach ( $keys as $key ) {
			$key_number = preg_match_all( '/\d+/', $key, $matches );
			$new_keys[] = $matches[0][0];
		}

		// Get ID's Data.
		$new_ID = max( $new_keys );
		$old_ID = yz_option( 'yz_next_' . $item . '_nbr' );
		$max_ID = ( $new_ID < $old_ID ) ? $old_ID : $new_ID;

		// Return Biggest Key.
		return $max_ID + 1;
	}

    /**
     * Get Exist ADS widgets
     */
    function get_custom_widgets( $widgets ) {

        // Set Up new array
        $custom_widgets = array();

        foreach ( $widgets as $widget_name => $visibility ) {
            // If key contains 'yz_custom_widget_'.
            if ( false !== strpos( $widget_name, 'yz_custom_widget_' ) ) {
                $custom_widgets[ $widget_name ] = $visibility;
            }
        }

        return $custom_widgets;
    }


    /**
     * Get Exist ADS widgets
     */
    function get_ads_widgets( $widgets ) {

        // Set Up new array
        $ads_widgets = array();

        foreach ( $widgets as $widget_name => $data ) {
            // If key contains 'yz_ad_'.
            if ( false !== strpos( $widget_name, 'yz_ad_' ) ) {
                $ads_widgets[ $widget_name ] = $data;
            }
        }

        return $ads_widgets;
    }

	/**
	 * Reset Settings.
	 */
	function membership_reset_settings() {

		if ( defined( 'LOGY_CORE' ) ) {

			// Include Styling.
			include LOGY_CORE . 'class-logy-styling.php';

			// Init Class.
            $styling = new Logy_Styling();

			// Reset Styling Input's
	        foreach ( $styling->styles_data() as $key ) {
				if ( yz_option( $key['id'] ) ) {
					delete_option( $key['id'] );
				}
	        }

		}

		// Specific Options.
		$specific_options = array(
			'logy_login_cover',
			'logy_signup_cover',
			'logy_lostpswd_cover'
		);

		// Reset Specific Options.
		foreach ( $specific_options as $option ) {
			if ( yz_option( $option ) ) {
				yz_delete_option( $option );
			}
		}

		// Get Providers.
		$providers = logy_get_providers();

		// Reset Social Provider Input's.
        foreach ( $providers as $provider ) {

        	// Transform Provider Name to lower case.
        	$provider = strtolower( $provider );

        	// Reset Provider Status's
			if ( yz_option( 'logy_' . $provider . '_app_status' ) ) {
				yz_delete_option( 'logy_' . $provider . '_app_status' );
			}

        	// Reset Provider Keys.
			if ( yz_option( 'logy_' . $provider . '_app_key' ) ) {
				yz_delete_option( 'logy_' . $provider . '_app_key' );
			}

        	// Reset Provider Secret Keys.
			if ( yz_option( 'logy_' . $provider . '_app_secret' ) ) {
				yz_delete_option( 'logy_' . $provider . '_app_secret' );
			}

        	// Reset Provider Notes.
			if ( yz_option( 'logy_' . $provider .'_setup_steps' ) ) {
				yz_delete_option( 'logy_' . $provider .'_setup_steps' );
			}

        }
	}

}

$ajax = new Youzer_Admin_Ajax();