<?php
$olympus = Olympus_Options::get_instance();
if ( function_exists( 'buddypress' ) ) {
	$bp						 = buddypress();
	$user_ID				 = bp_loggedin_user_id();
	$show_friend_requests	 = $olympus->get_option( 'top-panel-friend-requests', 'yes', $olympus::SOURCE_CUSTOMIZER  );
	$show_messages			 = $olympus->get_option( 'top-panel-messages', 'yes', $olympus::SOURCE_CUSTOMIZER  );
	$show_notifications		 = $olympus->get_option( 'top-panel-notifications', 'yes', $olympus::SOURCE_CUSTOMIZER  );
	?>

	<?php if ( bp_is_active( 'friends' ) && $show_friend_requests === 'yes' ) { ?>
		<?php $total_friends_count = bp_friend_get_total_requests_count(); 
		// $requests = bp_get_friendship_requests( $user_ID );
		// $total_friends_count = ($requests != 0) ? count(explode(",", $requests)) : 0;
		?>
		<div id="notification-friends" class="control-icon has-items">
			<?php if ( $total_friends_count ) { ?>
				<div class="icon-status-wrap">
				<?php } else { ?>
					<a href="<?php echo bp_loggedin_user_domain() . bp_get_friends_slug(); ?>" class="icon-status-wrap">
					<?php } ?>
					<i class="olympus-icon olympus-icon-Happy-Face-Icon"></i>
					<?php if($total_friends_count != 0){ ?>
					<div class="label-avatar bg-blue"><?php echo esc_html( $total_friends_count ); ?></div>
					<?php } ?>
					<?php if ( !$total_friends_count ) { ?>
					</a>
				<?php } else { ?>
				</div>
			<?php } ?>
			<?php
			if ( $total_friends_count ) {
				$requests = bp_get_friendship_requests( $user_ID );
				$members_friends = bp_core_get_users( array( 'include' => $requests ) );
				?>
				<div class="more-dropdown more-with-triangle triangle-top-center">
					<div class="ui-block-title ui-block-title-small">
						<h6 class="text-uppercase title"><?php esc_html_e( 'Friend Requests', 'olympus' ); ?></h6>
						<a href="<?php echo esc_url( home_url( bp_get_members_slug() ) ); ?>"><?php esc_html_e( 'Find Friends', 'olympus' ); ?></a>
					</div>

					<div class="mCustomScrollbar" data-mcs-theme="dark">
						<ul class="notification-list friend-requests">
							<?php 
							if ( $members_friends[ 'total' ] != 0 ) { 
							foreach ( (array) $members_friends[ 'users' ] as $member ) {
								$image_args	= array(
									'item_id' => $member->ID,
									'width'	=> 80,
									'height' => 80
								);
								$image = olympus_get_avatar( $image_args );
								$friendship_id = friends_get_friendship_id( $member->ID, bp_loggedin_user_id() );
								$reject_request_link = wp_nonce_url( trailingslashit( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/reject/' . $friendship_id ), 'friends_reject_friendship' );
								$accept_request_link = wp_nonce_url( trailingslashit( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests/accept/' . $friendship_id ), 'friends_accept_friendship' );
							?>
							<li>
								<div class="author-thumb">
									<?php olympus_render( $image ); ?>
								</div>
								<div class="notification-event-wrap">
									<div class="notification-event">
										<a href="<?php echo esc_url( bp_core_get_user_domain( $member->ID ) ); ?>" class="h6 notification-friend"><?php echo esc_html( $member->display_name ); ?></a>
										<?php $mutual_friends = olympus_mutual_friend_total_count( $user_ID, $member->ID ); ?>
										<?php
										if ( $mutual_friends ) { ?>
											<span class="chat-message-item"><?php
											echo sprintf(
												_n( '%s mutual friend', '%s mutual friends', $mutual_friends, 'olympus' ),
												$mutual_friends
											);
										?></span>
										<?php } else { ?>
											<span class="chat-message-item"><?php esc_html_e( 'No mutual friends', 'olympus' ); ?></span>
										<?php } ?>
									</div>
								</div>
								
								<span class="notification-icon action">
									<a data-toggle="tooltip" data-id="<?php echo esc_attr($friendship_id); ?>" data-placement="top" data-action="accept_friendship" data-original-title="<?php esc_attr_e( 'Confirm', 'olympus' ) ?>" href="<?php echo esc_url($accept_request_link); ?>" class="accept-request">
										<span class="icon-add without-text">
											<i class="olympus-icon-Happy-Face-Icon"></i>
										</span>
									</a>
									<a data-toggle="tooltip" data-id="<?php echo esc_attr($friendship_id); ?>" data-placement="top" data-action="reject_friendship" data-original-title="<?php esc_attr_e( 'Reject', 'olympus' ) ?>" href="<?php echo esc_url($reject_request_link); ?>" class="accept-request request-del">
										<span class="icon-minus">
											<i class="olympus-icon-Happy-Face-Icon"></i>
										</span>
									</a>
								</span>
							</li>
							<?php }} ?>
						</ul>
					</div>

					<a href="<?php echo esc_attr( bp_loggedin_user_domain() . bp_get_friends_slug() . '/requests' ); ?>" class="view-all bg-blue"><?php esc_html_e( 'All Friendship Requests', 'olympus' ); ?></a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if ( bp_is_active( 'messages' ) && $show_messages === 'yes' ) { ?>
		<?php $total_messages_count = messages_get_unread_count(); ?>
		<div id="notification-message" class="control-icon has-items">
			<?php if ( $total_messages_count ) { ?>
				<div class="icon-status-wrap">
				<?php } else { ?>
					<a href="<?php echo esc_attr( bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox' ); ?>" class="icon-status-wrap">
					<?php } ?>
					<i class="olympus-icon olympus-icon-Chat---Messages-Icon"></i>
					<?php if($total_messages_count != 0){ ?>
					<div class="label-avatar bg-purple"><?php olympus_render($total_messages_count); ?></div>
					<?php } ?>
					<?php if ( !$total_messages_count ) { ?>
					</a>
				<?php } else { ?>
				</div>
			<?php } ?>
			<?php
			if ( bp_has_message_threads( array(
						'user_id'		 => $user_ID,
						'box'			 => 'inbox',
						'type'			 => 'unread',
						'search_terms'	 => '',
					) ) ) {
				?>
				<div class="more-dropdown more-with-triangle triangle-top-center">
					<div class="ui-block-title ui-block-title-small">
						<h6 class="text-uppercase title"><?php esc_html_e( 'Chat / Messages', 'olympus' ); ?></h6>
					</div>

					<div class="mCustomScrollbar" data-mcs-theme="dark">
						<ul class="notification-list chat-message">
							<?php while ( bp_message_threads() ) : bp_message_thread(); ?>
								<li class="message-unread">
									<a class="read-message full-block" href="<?php bp_message_thread_view_link(); ?>"></a>
									<div class="author-thumb">
										<?php bp_message_thread_avatar(); ?>
									</div>
									<div class="notification-event">
										<?php echo str_replace( '<a', '<a class="h6 notification-friend" ', bp_get_message_thread_from() ); ?>
										<span class="chat-message-item"><?php bp_message_thread_subject(); ?></span>
										<span class="notification-date"><?php bp_message_thread_last_post_date(); ?></span>
									</div>
									<span class="notification-icon">
										<i class="olympus-icon-Chat---Messages-Icon"></i>
									</span>

								</li>
							<?php endwhile; ?>
						</ul>
					</div>

					<a href="<?php echo esc_attr( bp_loggedin_user_domain() . bp_get_messages_slug() . '/inbox' ); ?>" class="view-all bg-purple"><?php esc_html_e( 'View All Messages', 'olympus' ); ?></a>
				</div>
			<?php } ?>
		</div>
	<?php } ?>
	<?php if ( bp_is_active( 'notifications' ) && $show_notifications === 'yes' ) { ?>
		<?php $notification_count = bp_notifications_get_unread_notification_count( $user_ID ); ?>
		<div id="notification-event" class="control-icon has-items">
			<?php if ( $notification_count ) { ?>
				<div class="icon-status-wrap">
				<?php } else { ?>
					<a href="<?php echo bp_get_notifications_permalink(); ?>" class="icon-status-wrap">
					<?php } ?>
					<i class="olympus-icon olympus-icon-Thunder-Icon"></i>
					<?php if($notification_count != 0){ ?>
					<div class="label-avatar bg-primary"><?php olympus_render( $notification_count ); ?></div>
					<?php } ?>
					<?php if ( !$notification_count ) { ?>
					</a>
				<?php } else { ?>
				</div>
			<?php } ?>
			<?php
			$old_action			 = $bp->current_action;
			$bp->current_action	 = 'unread';
			if ( bp_has_notifications( array(
						'user_id'		 => $user_ID,
						'search_terms'	 => '',
					) ) ) {
				?>
				<div class="more-dropdown more-with-triangle triangle-top-center">
					<div class="ui-block-title ui-block-title-small">
						<h6 class="text-uppercase title"><?php esc_html_e( 'Notifications', 'olympus' ); ?></h6>
					</div>

					<div class="mCustomScrollbar" data-mcs-theme="dark">
						<ul class="notification-list notification-all">
							<?php
							while ( bp_the_notifications() ) {
								bp_the_notification();
								$notification = $bp->__get( 'notifications' )->query_loop->notification;
								switch ( $notification->component_name ) {
									case 'activity':
										$icon	 = 'olympus-icon-Checked-Calendar-Icon';
										break;
									case 'messages':
										$icon	 = 'olympus-icon-Like-Post-Icon';
										break;
									case 'friends':
										$icon	 = 'olympus-icon-Happy-Face-Icon';
										break;
									default:
										$icon	 = 'olympus-icon-Check-Icon';
										break;
								}
								?>
								<li>
									<span class="notification-icon">
										<i class="<?php olympus_render( $icon ); ?>"></i>
									</span>
									<div class="notification-event">
										<div><?php bp_the_notification_description(); ?></div>
										<span class="notification-date">
											<?php bp_the_notification_time_since(); ?>
										</span>
									</div>

									<div class="icons-action">
										<?php
										echo str_replace( 'bp-tooltip', '', bp_get_the_notification_action_links( array(
											'sep'	 => '',
											'links'	 => array(
												bp_get_the_notification_mark_link( $user_ID ),
												bp_get_the_notification_delete_link( $user_ID )
											)
										) ) );
										?>
									</div>

								</li>
								<?php
							}
							?>

						</ul>
					</div>

					<div class="view-all view-all-half-item">
						<a href="<?php echo bp_get_notifications_unread_permalink(); ?>" class="bg-primary"><?php esc_html_e( 'View All', 'olympus' ); ?></a>
						<a id="bp-notifications-mark-read-all" href="<?php echo bp_get_notifications_unread_permalink(); ?>" data-nonce="<?php echo wp_create_nonce('bp-notifications-mark-read-all'); ?>" class="mark-read-all bg-blue"><?php esc_html_e( 'Mark All', 'olympus' ); ?></a>
					</div>

				</div>
				<?php
			}
			$bp->current_action = $old_action;
			?>
		</div>
		<?php
	}
}