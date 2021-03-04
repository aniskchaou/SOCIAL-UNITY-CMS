<?php

/**
 * Include MyCRED Files.
 */
function yz_init_mycred() {

	if ( ! yz_is_mycred_active() ) {
		return;	
	}

	// Balance Functions.
    require YZ_PUBLIC_CORE . 'mycred/yz-mycred-balance.php';

	// Badges Functions.
	if ( defined( 'myCRED_BADGE_VERSION' ) ) {
    	require YZ_PUBLIC_CORE . 'mycred/yz-mycred-badges.php';
	}

}

add_action( 'setup_theme', 'yz_init_mycred' );

/**
 * MyCRED Enqueue scripts.
 */
function yz_mycred_scripts( $hook_suffix ) {

    if ( ! yz_is_mycred_active() )  {
        return;
    }
    
    // Register MyCRED Css.
    wp_register_style( 'yz-mycred', YZ_PA . 'css/yz-mycred.min.css', array(), YZ_Version );

    // Call MyCRED Css.
    wp_enqueue_style( 'yz-mycred' );

}

add_action( 'wp_enqueue_scripts', 'yz_mycred_scripts' );

// /**
//  * # Default Options 
//  */
// function yz_mycred_default_options( $options ) {

//     // Options.
//     $yzsq_options = array(
// 		'yz_enable_mycred' => 'on',
// 		'yz_badges_tab_icon' => 'fas fa-trophy',
// 		'yz_enable_cards_mycred_badges' => 'on',
// 		'yz_wg_max_card_user_badges_items' => 4,
// 		'yz_mycred-history_tab_icon' => 'fas fa-history',
// 		'yz_author_box_max_user_badges_items' => 3,
// 		'yz_enable_author_box_mycred_badges' => 'on',
// 		'yz_mycred_badges_tab_title' => __( 'Badges', 'youzer' ),
// 		'yz_ctabs_mycred-history_thismonth_icon' => 'far fa-calendar-alt',
// 		'yz_ctabs_leaderboard_month_icon' => 'far fa-calendar-alt',
// 		'yz_ctabs_mycred-history_today_icon' => 'fas fa-calendar-check',
// 		'yz_ctabs_leaderboard_today_icon' => 'fas fa-calendar-check',
// 		'yz_ctabs_mycred-history_mycred-history_icon' => 'fas fa-calendar',
// 		'yz_ctabs_mycred-history_thisweek_icon' => 'fas fa-calendar-times',
// 		'yz_ctabs_leaderboard_week_icon' => 'fas fa-calendar-plus',
// 		'yz_ctabs_mycred-history_yesterday_icon' => 'fas fa-calendar-minus',
// 		'yz_ctabs_achievements_all_icon' => 'fas fa-award',
// 		'yz_ctabs_achievements_earned_icon' => 'fas fa-user-check',
// 		'yz_ctabs_achievements_unearned_icon' => 'fas fa-user-times',
//     );

//     return yz_array_merge( $options, $yzsq_options );
// }

// add_filter( 'yz_default_options', 'yz_mycred_default_options' );


/**
 * Edit My Cred Title
 */
function yz_edit_mycred_tab_title( $title ) {

	ob_start();

	?>

	<div class="yz-tab-title-box">
		<div class="yz-tab-title-icon"><i class="fas fa-history"></i></div>
		<div class="yz-tab-title-content">
			<h2><?php echo $title; ?></h2>
			<span><?php _e( 'This is the user points log.', 'youzer' );?></span>
		</div>
	</div>

	<?php

	$output = ob_get_contents();
	ob_end_clean();

	return $output;

}

add_filter( 'mycred_br_history_page_title' , 'yz_edit_mycred_tab_title' );


/**
 * Leader Board Widget.
 */
function yz_mycred_leader_board_widget( $layout, $template, $user, $position, $data ) {

	if ( apply_filters( 'yz_mycred_leader_board_widget', true ) ) {
		$avatar = bp_core_fetch_avatar( array( 'item_id' => $user['ID'], 'type' => 'thumb' ) );
		$layout = '<li class="yz-leaderboard-item"><div class="yz-leaderboard-avatar"><span class="yz-leaderboard-position"># ' . $position .'</span>'. $avatar . '</div><div class="yz-leaderboard-content"><a class="yz-leaderboard-username" href="' . bp_core_get_user_domain( $user['ID'] ).'">' . bp_core_get_user_displayname( $user['ID'] ) . '</a><div class="yz-leaderboard-points">' . sprintf( _n( '%s ' . $data->core->core['name']['singular'], '%s ' . $data->core->core['name']['plural'], $user['cred'], 'youzer' ), $user['cred'] ) . '</div></li>';
	}
	return $layout;
}

add_filter( 'mycred_ranking_row', 'yz_mycred_leader_board_widget', 10, 5 );


/**
 * Add Statitics Options
 */
// function yz_add_mycred_statitics( $statistics ) {
// 	$statistics['points'] = 
// 	return $statistics;
// }

// add_filter( 'yz_get_user_statistics_details', 'yz_add_mycred_statitics' );

/**
 * Get Statistics Value
 */
function yz_get_mycred_statistics_values( $value, $user_id, $type ) {

	if ( $type == 'points' ) {
		return mycred_get_users_balance( $user_id );
	}

	return $value;

}

add_filter( 'yz_get_user_statistic_number', 'yz_get_mycred_statistics_values', 10, 3 );