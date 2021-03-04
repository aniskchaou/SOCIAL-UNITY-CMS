<?php

class Youzer_Group {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {

		if ( null === self::$instance ) {

			self::$instance = new self;

			self::$instance->create_new_tabs();

			add_filter( 'bp_get_options_nav_request-membership', '__return_false' );

			// Group Navbar Content
			add_action( 'youzer_group_navbar', array( self::$instance, 'navbar' ) );

			// Group Main Content
			add_action( 'yz_group_main_content', array( self::$instance, 'main_content' ) );

			// Group Body
			add_action( 'yz_group_main_column', array( self::$instance, 'body' ) );

			// Load Groups Scripts
			add_action( 'wp_enqueue_scripts', array( self::$instance, 'scripts' ) );

		}

		return self::$instance;

	}

	function __construct() { /** Do Nothing Here **/ }

	/**
	 * Create New Tabs.
	 */
	function create_new_tabs() {

		// Check if its a group page.
		if ( ! bp_is_single_item() ) {
			return false;
		}

		global $bp;

		$group = $bp->groups->current_group;

		// Add Group 'Infos' Nav.
		bp_core_new_subnav_item(
			array(
				'slug' => 'group-info',
				'parent_slug' => $group->slug,
				'name' => __( 'Info', 'youzer' ),
				'parent_url' => bp_get_group_permalink( $group ),
				'screen_function' =>  array( $this, 'group_info_screen' ),
				'position' => 10
			)
		);


		if ( bp_is_item_admin() && 'private' == bp_get_group_status( $group ) ) {

			// Get Requests Number
			$requests_nbr = $this->get_group_membership_requests_count( $group->id );

			if ( '0' != $requests_nbr ) {
				// Create 'Requests' Subnav.
				bp_core_new_subnav_item( array(
						'name' => sprintf( __( 'Requests %s', 'youzer' ), '<span>' . number_format( $requests_nbr ) . '</span>' ),
						'parent_slug' => $group->slug,
						'slug' => 'membership-requests',
						'parent_url' => trailingslashit( bp_get_group_permalink( $group ) . 'admin' ),
						'screen_function' => 'groups_screen_group_admin',
						'position' => 60
					)
				);
			}

		}

	}

	/**
	 * # Get Group Layout
	 */
	function layout() {
	    // Set Up Variable
	    $group_layout = yz_option( 'yz_group_header_layout', 'hdr-v1' );
	    return false !== strpos( $group_layout, 'yz-card' ) ? 'yz-vertical-layout' : 'yz-horizontal-layout';
	}

	/**
	 * # Navbar Menu.
	 */
	function navbar() { ?>

		<nav id="yz-profile-navmenu" class="<?php echo $this->get_navbar_class(); ?>">
			<div class="yz-inner-content">
				<div class="yz-open-nav">
					<button class="yz-responsive-menu"><span>toggle menu</span></button>
				</div>
				<ul class="yz-profile-navmenu item-list-tabs no-ajax" id="object-nav" aria-label="Group primary navigation" role="navigation">

					<?php bp_get_options_nav(); ?>

					<?php

					/**
					 * Fires after the display of group options navigation.
					 *
					 * @since 1.2.0
					 */
					do_action( 'bp_group_options_nav' ); ?>

				</ul>

				<div id="yz-group-buttons"><?php

					/**
					 * Fires in the group header actions section.
					 *
					 * @since 1.2.6
					 */
					do_action( 'bp_group_header_actions' ); ?>

				</div><!-- #item-buttons -->

			</div>

		</nav>

		<?php
	}

	/**
	 * # Navbar Class.
	 */
	function get_navbar_class() {

		// Create Empty Array.
		$navbar_class = array( 'yz-group-navmenu' );

		// Get Options.
		$header_layout = yz_option( 'yz_group_header_layout', 'hdr-v1' );

		// Add a class depending on another one.
		if ( 'hdr-v2' == $header_layout || 'hdr-v7' == $header_layout ) {
			$navbar_class[] = "yz-boxed-navbar";
		}

	 	// Return Class Name.
		return yz_generate_class( $navbar_class );
	}

	/**
	 * # Group Main Content.
	 */
	function main_content() { ?>

		<div class="yz-right-sidebar-layout">

			<div class="yz-main-column">
				<div class="yz-column-content">
					<?php do_action( 'yz_group_main_column' ); ?>
				</div>
			</div>

			<?php if ( $this->show_group_sidebar() ) : ?>
			<div class="yz-sidebar-column yz-group-sidebar youzer-sidebar">
				<div class="yz-column-content">
					<?php do_action( 'yz_group_sidebar' ); ?>
				</div>
			</div>
			<?php endif; ?>

		</div>
		<!-- Scroll to top -->
		<?php if ( 'on' == yz_option( 'yz_display_group_scrolltotop', 'off' ) ) : ?>
			<?php yz_scroll_to_top(); ?>
		<?php endif; ?>

		<?php do_action( 'yz_group_content' ); ?>

		<?php
	}

	/**
	 * Display Group Sidebar.
	 */
	function show_group_sidebar() {

		if ( is_super_admin() ) {
			return true;
		}

		global $bp;

		// Get Current Group Status.
		$status = $bp->groups->current_group->status;

		// Get Current Group ID
		$group_id = $bp->groups->current_group->id;

		if ( $status == 'private' && ( ! is_user_logged_in() || ! groups_is_user_member( bp_loggedin_user_id(), $group_id ) ) )  {
			return false;
		}

		return true;
	}
	/**
	 * Body
	 */
	function body() { ?>
		<div id="yz-group-body">

			<?php

			/**
			 * Fires before the display of the group home body.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_group_body' );

			/**
			 * Does this next bit look familiar? If not, go check out WordPress's
			 * /wp-includes/template-loader.php file.
			 *
			 * @todo A real template hierarchy? Gasp!
			 */

				// Looking at home location
				if ( bp_is_group_home() ) :

					if ( bp_group_is_visible() ) {

						// Load appropriate front template
						bp_groups_front_template_part();

					} else {

						/**
						 * Fires before the display of the group status message.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_before_group_status_message' ); ?>

						<div id="message" class="info">
							<p><?php bp_group_status_message(); ?></p>
						</div>

						<?php

						/**
						 * Fires after the display of the group status message.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_after_group_status_message' );

					}

				// Not looking at home
				else :

					// Group Admin
					if ( bp_is_group_admin_page() ) :
						bp_get_template_part( 'groups/single/admin' );

					// Group Activity
					elseif ( bp_is_group_activity()   ) :
						bp_get_template_part( 'groups/single/activity' );

					// Group Members
					elseif ( bp_is_group_members()    ) :
						bp_groups_members_template_part();

					// Group Invitations
					elseif ( bp_is_group_invites()    ) :
						bp_get_template_part( 'groups/single/send-invites' );

					// Membership request
					elseif ( bp_is_group_membership_request() ) :
						bp_get_template_part( 'groups/single/request-membership' );

					// Anything else (plugins mostly)
					else :
						bp_get_template_part( 'groups/single/plugins'      );

					endif;

				endif;

			/**
			 * Fires after the display of the group home body.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_after_group_body' ); ?>

		</div><!-- #yz-group-body -->

		<?php

	}

	/**
	 * # Cover.
	 */
	function cover( $group_id = null ) {
		echo yz_get_group_cover( $group_id );
	}

	/**
	 * Photo
	 */
	function photo( $args = null ) {

		// Set Up Variable.
		$target = isset( $args['target'] ) ? $args['target'] : 'header';
		$group_id = isset( $args['group_id'] ) ? $args['group_id'] : bp_get_group_id();

		// Get Avatar Border Style
		$border_style = yz_option( 'yz_group_' . $target . '_avatar_border_style', 'circle' );
		$show_border  = yz_option( 'yz_enable_group_' . $target . '_avatar_border', 'on' );

		// Get Data
		$photo_effect = yz_option( 'yz_group_photo_effect', 'on' );

		$img_path = bp_core_fetch_avatar( array(
			'avatar_dir' => 'group-avatars',
			'object'	 => 'group',
			'item_id'    => $group_id,
			'type'	  	 => 'full'
			)
		);

		// Prepare Photo Class
		$photo_class 	= array();
		$photo_class[] 	= 'yz-profile-photo';
		$photo_class[] 	= "yz-photo-$border_style";

		if ( 'on' == $show_border ) {
			$photo_class[] = 'yz-photo-border';
		}

		if ( 'on' == $photo_effect && 'circle' == $border_style ) {
			$photo_class[] = 'yz-profile-photo-effect';
		}

		echo "<div class='" . yz_generate_class( $photo_class ) . "'>";
		echo "<div class='yz-profile-img'>$img_path</div>";
		echo "</div>";
	}

	/**
	 * # Name.
	 */
	function name() {
		echo "<div class='yz-name'>";
		echo "<h2>". sanitize_text_field( bp_get_current_group_name() ) . "</h2>";
		echo "</div>";
	}

	/**
	 * # Meta.
	 */
	function meta() {

		// Show / Hide Elements
		$display_privacy  = yz_option( 'yz_display_group_header_privacy', 'on' );
		$display_activity = yz_option( 'yz_display_group_header_activity', 'on' );

		if ( 'on' == $display_privacy || 'on' == $display_activity ) :

			echo '<div class="yz-usermeta"><ul>';

				do_action( 'yz_before_group_header_meta' );

				if ( 'on' == $display_privacy ) {
					echo '<li>';
					$this->status();
					echo '</li>';
				}

				if ( 'on' == $display_activity ) {
					echo '<li>';
					echo '<i class="far fa-clock"></i>';
					echo "<span>" . bp_get_group_last_active() ."</span>";
					echo '</li>';
				}

				do_action( 'yz_after_group_header_meta' );

			echo '</ul></div>';

		endif;

	}

	/**
	 * # Group Status.
	 */
	function status( $group = null ) {

		// Get Group Type.
		$group_type = bp_get_group_status( $group );

		// Get Group Status Data
		if ( 'public' == $group_type ) {
			$icon = 'fas fa-globe-asia';
			$type = __( "Public Group", 'youzer' );
		} elseif ( 'hidden' == $group_type ) {
			$icon = 'fas fa-user-secret';
			$type = __( "Hidden Group", 'youzer' );
		} elseif ( 'private' == $group_type ) {
			$icon = 'fas fa-lock';
			$type = __( "Private Group", 'youzer' );
		} else {
			$icon = 'fas fa-users';
			$type = ucwords( $group_type ) . ' ' . __( 'Group', 'youzer' );
		}

		// Print Location
		echo '<i class="' . $icon . '"></i>';
		echo '<span>' . $type . '</span>';
	}

	/**
	 * # Group Statistics.
	 */
	function statistics( $args = null ) {

		// Set Up Variable.
		$target = isset( $args['target'] ) ? $args['target'] : 'header';

		// Show / Hide Elements.
		$display_posts 	 = yz_option( 'yz_display_group_' . $target . '_posts', 'on' );
		$display_members = yz_option( 'yz_display_group_' . $target . '_members', 'on' );

		if ( 'on' != $display_posts && 'on' != $display_members ) {
			return false;
		}

		// Get Group ID.
		$group_id = isset( $args['group_id'] ) ? $args['group_id'] : bp_get_group_id();

		// Get Data.
		$members_number = bp_get_group_total_members();
		$posts_number 	= yz_get_group_total_posts_count( $group_id );

		// Get Statistics Data.
		$data = yz_get_args(
			array(
				'statistics_bg' 	=> yz_option( 'yz_group_' . $target . '_use_statistics_bg', 'on' ),
				'statistics_border' => yz_option( 'yz_group_' . $target . '_use_statistics_borders', 'on' ),
		), $args );

		// Get Statistics Class Name.
		$statistics_class[] = "yz-user-statistics";
		$statistics_class[] = ( 'on' == $data['statistics_bg'] ) ? 'yz-statistics-bg' : null;
		$statistics_class[] = ( 'on' == $data['statistics_border'] ) ? 'yz-use-borders' : null;

		?>

		<div class="<?php echo yz_generate_class( $statistics_class ); ?>">
			<ul>

				<?php  do_action( 'yz_before_group_header_statistics' ); ?>

				<?php if ( 'on' == $display_posts && bp_is_active( 'activity' ) ) : ?>
					<li>
						<div class="yz-snumber" title="<?php echo $posts_number; ?>"><?php echo $this->get_statistic_number( $posts_number ); ?></div>
						<h3 class="yz-sdescription"><?php _e( 'Posts', 'youzer' ); ?></h3>
					</li>
				<?php endif; ?>

				<?php if ( 'on' == $display_members ) : ?>
					<?php $members_number = str_replace( array( ' ', '&nbsp;', ',' ),'',  $members_number ); ?>
					<li>
						<div class="yz-snumber" title="<?php echo $members_number; ?>"><?php echo $this->get_statistic_number( $members_number ); ?></div>
						<h3 class="yz-sdescription"><?php _e( 'Members', 'youzer' ); ?></h3>
					</li>
				<?php endif; ?>

				<?php  do_action( 'yz_after_group_header_statistics' ); ?>

			</ul>
		</div>

		<?php
	}

	/**
	 * Convert Statistics Number
	 */
	function get_statistic_number( $number ) {

		// if Number equal 0 return it.
		if ( 0 == $number ) {
			return 0;
		}

		// Define Number Letters.
		$abbrevs = array(
			12 	=> __( 'T', 'youzer' ),
			9 	=> __( 'B', 'youzer' ),
			6 	=> __( 'M', 'youzer' ),
			3 	=> __( 'K', 'youzer' ),
			0 	=> ''
		);

		// Get Number Letter
		foreach( $abbrevs as $exponent => $abbrev ) {
			if ( $number >= pow( 10, $exponent ) ) {
				$display_num = $number / pow( 10, $exponent );
				$decimals = ( $exponent >= 3 && round( $display_num ) < 100 ) ? 1 : 0;
				$number_format = number_format( $display_num, $decimals );
				return $number_format . $abbrev;
			}
		}

	}

	/**
	 * # Author Box Head.
	 */
	function box_head( $target, $user_id = null ) {

		// Get Data
		$last_name 	= yz_data( 'last_name', $user_id );
		$first_name	= yz_data( 'first_name', $user_id );
		$username 	= sanitize_user( yz_data( 'user_login', $user_id ) );
		$full_name 	= sanitize_text_field( "$first_name $last_name" );

		?>
			<div class="yzb-head-content">
				<h2><?php echo $full_name; ?></h2>
				<?php $this->box_meta( $target, $user_id ); ?>
			</div>
		<?php

	}

	/**
	 * # Ratings.
	 */
	function ratings() {
		// Soon.
	}

	/**
	 * # Social Networks.
	 */
	function networks( $args = null ) {

		// Prevent This Function for Now i will add it in coming updates.
		return false;

		// Set Up Variable.
		$group_id = isset( $args['group_id'] ) ? $args['group_id'] : null;
		$element = isset( $args['target'] ) ? $args['target'] : 'header';

		if ( ! is_group_have_networks( $group_id ) ) {
			return false;
		}

		// Get Social Networks
		$social_networks = yz_option( 'yz_group_social_networks' );

		// Display Networks Icons
		$display_networks = yz_option( 'yz_display_group_' . $element . '_networks', 'on' );

		// if Element is Widget Make it Networks Visible.
		if ( 'widget' == $element ) {
			$element = 'wg';
			$display_networks = 'on';
		}

		// Check Networks Visibility.
		if ( 'on' != $display_networks || empty( $social_networks ) ) {
			return false;
		}

		// Get networks Data.
		$data = yz_get_args(
			array(
				'networks_type'   => yz_option( 'yz_group_' . $element . '_sn_bg_type', 'silver' ),
				'networks_format' => yz_option( 'yz_group_' . $element . '_sn_bg_style', 'circle' ),
		), $args );

		// Get Networks Size
		$networks_size = yz_option( 'yz_group_wg_sn_icons_size', 'full-width' );

		if ( 'wg' == $element ) {
			$networks_class[] = "yz-icons-$networks_size";
		}

		// Prepare Networks Class .
		$networks_class[] = "yz-$element-networks";
		$networks_class[] = "yz-icons-{$data['networks_type']}";
		$networks_class[] = "yz-icons-{$data['networks_format']}";
		$networks_class[] = "yz-networks-$user_id";

		// Networks Action
		do_action( 'youzer_before_group_networks', $args );

		// Get Networks Type
		$networks_class = yz_generate_class( $networks_class );

		echo "<ul class='$networks_class'>";

		foreach ( $social_networks as $network => $data ) {

			// Get Widget Data
			$icon = $data['icon'];
			$name = sanitize_text_field( $data['name'] );
			$link = esc_url( yz_data( $network, $user_id ) );

			if ( $link && $icon ) {
				echo "<li class='$network'><a href='$link'>";
				echo "<i class='$icon'></i>";
				if ( 'wg' == $element && 'full-width' == $networks_size ) {
					echo $name;
				}
				echo '</a></li>';
			}

		}

		echo '</ul>';
	}

	/**
	 * Scripts
	 */
	function scripts() {

		// if ( ! bp_is_groups_component() ) {
		// 	return;
		// }
		if ( bp_is_group() ) {

			// Groups Custom Styling
			$styling = yz_styling();
			$styling->custom_styling( 'groups' );
			$styling->custom_snippets( 'groups' );
			unset( $styling );

        }

       	wp_enqueue_style( 'yz-groups', YZ_PA .'css/yz-groups.min.css', array( 'yz-bp-uploader' ), YZ_Version );

		// Init Vars
		$jquery = array( 'jquery' );

	    // Load Carousel CSS and JS.
        wp_enqueue_style( 'yz-carousel-css', YZ_PA . 'css/owl.carousel.min.css', array(), YZ_Version );
        wp_enqueue_script( 'yz-carousel-js', YZ_PA . 'js/owl.carousel.min.js', $jquery, YZ_Version, true );
        wp_enqueue_script( 'yz-slider', YZ_PA . 'js/yz-slider.min.js', $jquery, YZ_Version, true );

	}

	/**
	 * Add New Group Infos Page.
	 */
	function group_info_screen() {

		add_action( 'bp_template_title', array( $this, 'group_info_title' ) );
		add_action( 'bp_template_content', array( $this, 'group_info_content' ) );

	    // Load Tab Template
	    bp_core_load_template( 'buddypress/groups/single/plugins' );

	}

	/**
	 * Get Group Infos Page Title.
	 */
	function group_info_title() {
		_e( 'Information', 'youzer' );
	}

	/**
	 * Get Group Infos Page Content.
	 */
	function group_info_content() {

		global $bp;

		$group = $bp->groups->current_group;

		?>

		<div class="yz-group-infos-widget">
			<div class="yz-group-widget-title">
				<i class="fas fa-file-alt"></i>
				<?php echo _e( 'Description', 'youzer' ); ?>
			</div>
			<div class="yz-group-widget-content"><?php echo apply_filters( 'the_content', html_entity_decode( $group->description ) ); ?></div>
		</div>

		<?php

	}

	/**
	 * Get Group Membership requests Count.
	 */
	function get_group_membership_requests_count( $group_id ) {
		global $bp, $wpdb;

		// Result.
		$requests_count = $wpdb->get_var( "SELECT COUNT(*) FROM {$bp->groups->table_name_members} WHERE is_confirmed = 0 AND inviter_id = 0 AND group_id = $group_id" );

		return $requests_count;

	}
}

/**
 * Get a unique instance of Youzer Groups.
 */
function yz_groups() {
	return Youzer_Group::get_instance();
}

/**
 * Launch Youzer Groups!
 */
yz_groups();