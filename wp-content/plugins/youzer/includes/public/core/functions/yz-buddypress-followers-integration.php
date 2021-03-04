<?php

/**
 * Get Users Follow Button !
 */
function yz_follow_message_button( $user_id ) {

	$user_id = ! empty( $user_id ) ? $user_id : bp_displayed_user_id();
	
	if ( bp_is_active( 'messages' ) ) {

	?>

	<div class="yz-follow-message-button">
		<?php 

            bp_follow_add_follow_button( 'leader_id=' . $user_id );

			if ( bp_is_active( 'messages' ) ) {
                yz_send_private_message_button( $user_id, '<span>' . __( 'Message', 'youzer' ) . '</span>' );
            }

        ?>
	</div>

	<?php

	} else {
        bp_follow_add_follow_button( 'leader_id=' . $user_id );
	}

}

add_action( 'yz_social_buttons', 'yz_follow_message_button' );

/**
 * Remove Js Script
 */
function yz_delete_buddypress_follwers_script() {

	// Remove Buddypress Follwers Default Script.
	wp_dequeue_script( 'bp-follow-js' );

	// Add the youzer compatible script.
	wp_enqueue_script( 'yz-follow-js', YZ_PA . 'js/yz-follow.min.js', array( 'jquery' ), YZ_Version );

}

add_action( 'wp_enqueue_scripts', 'yz_delete_buddypress_follwers_script', 999 );
		// remove_action( 'bp_adminbar_menus', 'bp_follow_group_buddybar_items' );

/**
 * # Setup Tabs.
 */
function yz_bpfollwers_tabs() {

	// $bp = buddypress();

	// Remove Settings Profile, General Pages
	bp_core_remove_nav_item( 'followers' );
	bp_core_remove_nav_item( 'following' );

	// Remove Follow Menu - Admin Bar.
	remove_action( 'bp_follow_setup_admin_bar', 'bp_follow_user_setup_toolbar' );

}

add_action( 'bp_actions', 'yz_bpfollwers_tabs', 99 );

/**
 * Add Statitics Options
 */
// function yz_add_follows_statitics( $statistics ) {
// 	return $statistics;
// }

// add_filter( 'yz_get_user_statistics_details', 'yz_add_follows_statitics' );

/**
 * Get Statistics Value
 */
function yz_get_follows_statistics_values( $value, $user_id, $type ) {

	switch ( $type ) {
		case 'followers':
			return bp_follow_get_the_followers_count( array( 'object_id' => $user_id ) );
			break;
		case 'following':
			return bp_follow_get_the_following_count( array( 'object_id' => $user_id ) );
			break;
		
		default:
			return $value;
			break;
	}

}

add_filter( 'yz_get_user_statistic_number', 'yz_get_follows_statistics_values', 10, 3 );

/**
 * Get Members Directory Follows Statistics.
 */
function yz_get_md_follows_statistics( $user_id ) {

	?>

    <?php if ( 'on' == yz_option( 'yz_enable_md_user_followers_statistics', 'on' ) ) :  ?>
       	<?php $followers_nbr = bp_follow_get_the_followers_count( array( 'object_id' => $user_id ) ); ?>
        <a href="<?php echo yz_get_user_profile_page( 'follows/followers', $user_id ); ?>" class="yz-data-item yz-data-followers" data-yztooltip="<?php echo sprintf( _n( '%s follower', '%s followers', $followers_nbr, 'youzer' ), $followers_nbr ); ?>">
            <span class="dashicons dashicons-rss"></span>
        </a>
    <?php endif; ?>

    <?php if ( 'on' == yz_option( 'yz_enable_md_user_following_statistics', 'on' ) ) :  ?>
       	<?php $following_nbr = bp_follow_get_the_following_count( array( 'object_id' => $user_id ) ); ?>
        <a href="<?php echo yz_get_user_profile_page( 'follows/following', $user_id ); ?>" class="yz-data-item yz-data-following" data-yztooltip="<?php echo sprintf( __( '%s following', 'youzer' ) , $following_nbr ); ?>">
            <span class="dashicons dashicons-redo"></span>
        </a>
    <?php endif; ?>

	<?php

}

add_action( 'yz_before_members_directory_card_statistics', 'yz_get_md_follows_statistics' );