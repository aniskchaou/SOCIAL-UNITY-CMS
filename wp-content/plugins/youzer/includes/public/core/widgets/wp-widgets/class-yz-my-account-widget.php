<?php

/**
 * User Account Menu Widget
 */

class YZ_My_Account_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_my_account_widget',
			__( 'Youzer - My Account', 'youzer' ),
			array( 'description' => __( 'User account menu', 'youzer' ) )
		);
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance, $this->default_options() );

		?>

		<!-- Hide Sections. -->
		<p>
			<label><?php _e( 'Hide Sections:', 'youzer' ); ?></label><br>
			<?php foreach( $instance['hide_sections'] as $name => $item ) : ?>
		    <input id="<?php echo $this->get_field_id( 'hide_sections' ) . $name; ?>" name="<?php echo $this->get_field_name( 'hide_sections' ); ?>[<?php echo $name; ?>]" type="checkbox" <?php checked( $instance['hide_sections'][ $name ]['hide'], 'on' ); ?>><label for="<?php echo $this->get_field_id( 'hide_sections' ) . $name; ?>"><?php echo $item['name']; ?></label><br>
		    <?php endforeach; ?>
		</p>

		<!-- Hide Links. -->
		<p>
			<label><?php _e( 'Hide Links:', 'youzer' ); ?></label><br>
			<?php foreach( $instance['hide_links'] as $name => $item ) : ?>
		    <input id="<?php echo $this->get_field_id( 'hide_links' ) . $name; ?>" name="<?php echo $this->get_field_name( 'hide_links' ); ?>[<?php echo $name; ?>]" type="checkbox" <?php checked( $instance['hide_links'][ $name ]['hide'], 'on' ); ?>>
		    <label for="<?php echo $this->get_field_id( 'hide_links' ) . $name; ?>"><?php echo $item['name']; ?></label><br>
		    <?php endforeach; ?>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		// Update Fields..
		$instance = $old_instance;

		// Save Hide Links
		foreach ( $this->hide_links() as $name => $item ) {
			// Get Value.
			$value = $new_instance['hide_links'][ $name ];
			// Save Values.
			$instance['hide_links'][ $name ] = $item;
			$instance['hide_links'][ $name ]['hide'] = ! empty( $value ) ? $value : 'off';
		}

		// Save Hide Sections
		foreach ( $this->hide_sections() as $name => $item ) {
			// Get Value.
			$value = $new_instance['hide_sections'][ $name ];
			// Save Values.
			$instance['hide_sections'][ $name ] = $item;
			$instance['hide_sections'][ $name ]['hide'] = ! empty( $value ) ? $value : 'off';
		}

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Hide Widget User Not Logged-In.
		if ( ! is_user_logged_in() ) {
			return false;
		}

		// Get Account Widget.
		$this->get_account_menu( $instance );

	}

	/**
	 * Get User Account Menu.
	 */
	function get_account_menu( $args ) {

		// Init Vars.
		$hide_links = $args['hide_links'];
		$hide_sections = $args['hide_sections'];

		// Get User id.
		$user_id = get_current_user_id();

		// Get User Avatar.
		$avatar = bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'full' ) );

		// Get User Profile Link.
		$profile_url = bp_core_get_user_domain( $user_id );

		?>

		<div class="yz-my-account-widget">

			<div class="yz-widget-header">
				<a href="<?php echo $profile_url; ?>" class="yz-head-avatar yz-avatar-border-radius"><?php echo $avatar; ?></a>
				<div class="yz-widget-head">
					<span class="yz-hello"><?php _e ( 'Hello !' , 'youzer' ); ?></span>
					<a href="<?php echo $profile_url; ?>" class="yz-user-name"><?php echo bp_core_get_user_displayname( $user_id ); ?></a>
				</div>
			</div>

			<div class="yz-menu-links yz-menu-icon-circle yz-menu-icon-colorful">

			<?php if ( 'off' == $hide_sections['account']['hide'] ) : ?>

			<div class="yz-links-section">

				<span class="yz-section-title"><?php _e( 'Account', 'youzer' ); ?></span>

	        	<?php if ( bp_is_active( 'messages' ) && 'off' == $hide_links['messages']['hide'] ) : ?>

	            	<?php $msgs_nbr = bp_get_total_unread_messages_count(); ?>
	            	<?php $msg_title = ( $msgs_nbr > 0 ) ? sprintf( __( 'Messages %s' , 'youzer' ), '<span class="yz-link-count">' . $msgs_nbr . '</span>' ) : __( 'Messages' , 'youzer' ); ?>

					<a href="<?php echo bp_nav_menu_get_item_url( 'messages' ); ?>" class="yz-link-item yz-link-inbox">
						<i class="fas fa-inbox"></i>
						<div class="yz-link-title"><?php echo $msg_title ;?></div>
					</a>

				<?php endif; ?>

		        <?php if ( bp_is_active( 'notifications' ) && 'off' == $hide_links['notifications']['hide'] ) : ?>

		            <?php $notification_nbr = bp_notifications_get_unread_notification_count(); ?>

					<?php $notifications_title = ( $notification_nbr > 0 ) ? sprintf( __( 'Notifications %s' , 'youzer' ), '<span class="yz-link-count">' . $notification_nbr . '</span>' ) : __( 'Notifications' , 'youzer' ); ?>

					<a href="<?php echo bp_nav_menu_get_item_url( 'notifications' ); ?>" class="yz-link-item yz-link-notifications">
						<i class="fas fa-bell"></i>
						<div class="yz-link-title"><?php echo $notifications_title ;?></div>
					</a>

				<?php endif; ?>

	   			<?php if ( bp_is_active( 'friends' ) && 'off' == $hide_links['friendship-requests']['hide'] ) : ?>

		            <?php

		            // Get Buttons Data
	                $friend_requests = bp_friend_get_total_requests_count();
	                $requests_link = trailingslashit( bp_loggedin_user_domain() . bp_get_friends_slug() ) . 'requests';

		            ?>

		            <?php if (  $friend_requests > 0 ) : ?>

						<a href="<?php echo $requests_link; ?>" class="yz-link-item yz-link-friendship-requests">
							<i class="fas fa-handshake"></i>
							<div class="yz-link-title"><?php echo sprintf( __( 'Friendship Requests %s' , 'youzer' ), '<span class="yz-link-count">' . $friend_requests . '</span>' ); ?></div>
						</a>

					<?php endif; ?>

				<?php endif; ?>

				<?php do_action( 'yz_after_myaccount_widget_account_links' ); ?>
			</div>

			<?php endif; ?>

   			<?php if ( 'off' == $hide_sections['settings']['hide'] ) : ?>

			<div class="yz-links-section">

				<span class="yz-section-title"><?php _e( 'Settings', 'youzer' ); ?></span>

				<?php if ( 'off' == $hide_links['profile-settings']['hide'] ) : ?>
				<a href="<?php echo yz_get_profile_settings_url( false, $user_id ); ?>" class="yz-link-item yz-link-profile-settings">
					<i class="fas fa-user"></i>
					<div class="yz-link-title"><?php _e( 'Profile Settings' , 'youzer' ); ?></div>
				</a>
				<?php endif; ?>

				<?php if (  bp_is_active( 'settings' ) && 'off' == $hide_links['account-settings']['hide'] ) : ?>
				<a href="<?php echo bp_core_get_user_domain( $user_id ) . bp_get_settings_slug(); ?>" class="yz-link-item yz-link-account-settings">
					<i class="fas fa-cogs"></i>
					<div class="yz-link-title"><?php _e( 'Account Settings' , 'youzer' ); ?></div>
				</a>
				<?php endif; ?>

				<?php if ( 'off' == $hide_links['widgets-settings']['hide'] ) : ?>
				<a href="<?php echo yz_get_widgets_settings_url( false, $user_id ); ?>" class="yz-link-item yz-link-widgets-settings">
					<i class="fas fa-th"></i>
					<div class="yz-link-title"><?php _e( 'Widgets Settings' , 'youzer' ); ?></div>
				</a>
				<?php endif; ?>

				<?php do_action( 'yz_after_myaccount_widget_settings_links' ); ?>

			</div>

			<?php endif; ?>


			<?php if ( 'off' == $hide_links['logout']['hide'] ) : ?>
			<a href="<?php echo wp_logout_url(); ?>" class="yz-link-item yz-link-logout">
				<i class="fas fa-power-off"></i>
				<div class="yz-link-title"><?php _e( 'Log Out' , 'youzer' ); ?></div>
			</a	>
			<?php endif; ?>

			</div>

		</div>

		<?php
	}

	/**
	 * Hide Sections Options
 	 */
	function hide_sections() {
		$options = array(
			'account' => array( 'name' => __( 'Account', 'youzer' ), 'hide' => 'off' ),
			'settings' => array( 'name' => __( 'Settings', 'youzer' ), 'hide' => 'off' ),
		);
		return $options;
	}

	/**
	 * Hide Links Options
 	 */
	function hide_links() {

		$options = array(
			'messages' => array( 'name' => __( 'Messages', 'youzer' ), 'hide' => 'off' ),
			'notifications' => array( 'name' => __( 'Notifications', 'youzer' ), 'hide' => 'off' ),
			'friendship-requests' => array( 'name' => __( 'Friendship Requests', 'youzer' ), 'hide' => 'off' ),
			'profile-settings' => array( 'name' => __( 'Profile Settings', 'youzer' ), 'hide' => 'off' ),
			'account-settings' => array( 'name' => __( 'Account Settings', 'youzer' ), 'hide' => 'off' ),
			'widgets-settings' => array( 'name' => __( 'Widgets Settings', 'youzer' ), 'hide' => 'off' ),
			'logout' => array( 'name' => __( 'Logout', 'youzer' ), 'hide' => 'off' )
		);

		return $options;
	}

	/**
	 * Default Options
 	 */
	function default_options() {

		$default_options = array(
	        'hide_sections' => $this->hide_sections(),
	        'hide_links' => $this->hide_links(),
	    );

		return $default_options;
	}

}