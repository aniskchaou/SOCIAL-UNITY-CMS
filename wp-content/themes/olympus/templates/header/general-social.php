<?php
/**
 * The template for displaying one of theme headers
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package olympus
 */
$olympus         = Olympus_Options::get_instance();
$show_users_menu = $olympus->get_option( 'top-panel-users-menu', 'yes', $olympus::SOURCE_CUSTOMIZER );
$show_search     = $olympus->get_option( 'top-panel-search', 'yes', $olympus::SOURCE_CUSTOMIZER );
$login_user_icon = $olympus->get_option( 'login-panel-options-icon', '', $olympus::SOURCE_CUSTOMIZER );
$show_friend_requests	 = $olympus->get_option( 'top-panel-friend-requests', 'yes', $olympus::SOURCE_CUSTOMIZER  );
$show_messages			 = $olympus->get_option( 'top-panel-messages', 'yes', $olympus::SOURCE_CUSTOMIZER  );
$show_notifications		 = $olympus->get_option( 'top-panel-notifications', 'yes', $olympus::SOURCE_CUSTOMIZER  );


if ( is_array( $login_user_icon ) && $login_user_icon['type'] !== 'none' ) {
	$open_login_popup_icon = olympus_generate_icon_html( $login_user_icon, 'universal-olympus-icon olymp-menu-icon olymp-login-icon' );
} else {
	$open_login_popup_icon = '<i class="olymp-login-icon olymp-menu-icon olympus-icon-Login-Icon"></i>';
}

$sign_form_popup = $olympus->get_option( 'sign-form-popup', array( 'sign-form-picker' => 'native' ), $olympus::SOURCE_CUSTOMIZER );

if ( olympus_akg( 'sign-form-picker', $sign_form_popup ) === 'youzer' && function_exists( 'yz_get_login_page_url' ) ) {
	$popup_content = 'youzer';
} elseif ( olympus_akg( 'sign-form-picker', $sign_form_popup ) === 'digits' ) {
	$popup_content = 'digits';
} elseif ( olympus_akg( 'sign-form-picker', $sign_form_popup ) === 'custom' ) {
	$popup_content = 'custom';
} else {
	$popup_content = 'native';
}
if ( function_exists( 'crumina_get_reg_form_html' ) && $popup_content === 'native' ) {
	add_action('wp_footer','olympus_append_login_form_to_html');
}


?>

<header class="header <?php echo ( ! is_user_logged_in() ) ? 'header--logout' : ''; ?>" id="site-header"
	<?php if ( 'custom' === $popup_content ) {
		echo 'x-data="olympusModal()" @keydown.escape="modalClose()"';
	} ?> >
	<div class="header-content-wrapper">

		<?php if ( $show_search === 'yes' ) { ?>
			<form id="top-search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="GET" class="search-bar w-search notification-list friend-requests">
				<div class="form-group with-button">
					<div class="selectize-control form-control js-user-search multi">
						<div class="selectize-input items not-full has-options">
							<input type="text" autocomplete="off" name="s" id="s" value="<?php echo filter_input( INPUT_GET, 's' ); ?>" placeholder="<?php esc_attr_e( 'Search here people or pages...', 'olympus' ); ?>">
						</div>
						<div class="selectize-dropdown multi form-control js-user-search mCustomScrollbar">
							<div class="selectize-dropdown-content"></div>
						</div>
					</div>
					<button>
						<i class="olympus-icon olympus-icon-Magnifying-Glass-Icon"></i>
						<svg class="olymp-search-spinner" width="135" height="135" viewBox="0 0 135 135" xmlns="http://www.w3.org/2000/svg">
							<path d="M67.447 58c5.523 0 10-4.477 10-10s-4.477-10-10-10-10 4.477-10 10 4.477 10 10 10zm9.448 9.447c0 5.523 4.477 10 10 10 5.522 0 10-4.477 10-10s-4.478-10-10-10c-5.523 0-10 4.477-10 10zm-9.448 9.448c-5.523 0-10 4.477-10 10 0 5.522 4.477 10 10 10s10-4.478 10-10c0-5.523-4.477-10-10-10zM58 67.447c0-5.523-4.477-10-10-10s-10 4.477-10 10 4.477 10 10 10 10-4.477 10-10z">
								<animateTransform
									attributeName="transform"
									type="rotate"
									from="0 67 67"
									to="-360 67 67"
									dur="2.5s"
									repeatCount="indefinite" />
							</path>
							<path d="M28.19 40.31c6.627 0 12-5.374 12-12 0-6.628-5.373-12-12-12-6.628 0-12 5.372-12 12 0 6.626 5.372 12 12 12zm30.72-19.825c4.686 4.687 12.284 4.687 16.97 0 4.686-4.686 4.686-12.284 0-16.97-4.686-4.687-12.284-4.687-16.97 0-4.687 4.686-4.687 12.284 0 16.97zm35.74 7.705c0 6.627 5.37 12 12 12 6.626 0 12-5.373 12-12 0-6.628-5.374-12-12-12-6.63 0-12 5.372-12 12zm19.822 30.72c-4.686 4.686-4.686 12.284 0 16.97 4.687 4.686 12.285 4.686 16.97 0 4.687-4.686 4.687-12.284 0-16.97-4.685-4.687-12.283-4.687-16.97 0zm-7.704 35.74c-6.627 0-12 5.37-12 12 0 6.626 5.373 12 12 12s12-5.374 12-12c0-6.63-5.373-12-12-12zm-30.72 19.822c-4.686-4.686-12.284-4.686-16.97 0-4.686 4.687-4.686 12.285 0 16.97 4.686 4.687 12.284 4.687 16.97 0 4.687-4.685 4.687-12.283 0-16.97zm-35.74-7.704c0-6.627-5.372-12-12-12-6.626 0-12 5.373-12 12s5.374 12 12 12c6.628 0 12-5.373 12-12zm-19.823-30.72c4.687-4.686 4.687-12.284 0-16.97-4.686-4.686-12.284-4.686-16.97 0-4.687 4.686-4.687 12.284 0 16.97 4.686 4.687 12.284 4.687 16.97 0z">
								<animateTransform
									attributeName="transform"
									type="rotate"
									from="0 67 67"
									to="360 67 67"
									dur="8s"
									repeatCount="indefinite" />
							</path>
						</svg>
					</button>
				</div>
			</form>
		<?php } ?>

		<?php if ( is_user_logged_in() ) { ?>
			<div id="notification-panel-top" class="control-block">
				<?php get_template_part( 'templates/user/notifications' ); ?>
			</div>

		<?php if ( function_exists('bp_is_active') && bp_is_active( 'friends' ) && ($show_friend_requests === 'yes' || $show_messages === 'yes' || $show_notifications === 'yes') ) {?>
			<div id="notification-panel-bottom" class="notification-panel-bottom">
				<div class="control-block"></div>
			</div>
			<?php } ?>

			<?php if ( $show_users_menu === 'yes' ) { ?>
				<div class="fixed-sidebar right"
					 x-data="{ sidebarOpen: false }"
					 x-bind:class="{ 'menu-open': sidebarOpen }"
					 x-on:keydown.escape="sidebarOpen = false"
					 x-on:click.away="sidebarOpen = false"

				>
					<div class="side-menu-open js-sidebar-open"
					   @click="sidebarOpen = ! sidebarOpen"
					   x-bind:class="{ 'active': sidebarOpen }"
					>
						<i class="user-icon olympus-icon-User-Icon" data-toggle="tooltip" data-placement="left" data-original-title="<?php esc_attr_e( 'Open menu', 'olympus' ); ?>"></i>
						<i class="olymp-close-icon olympus-icon-Close-Icon" data-toggle="tooltip" data-placement="left" data-original-title="<?php esc_attr_e( 'Close menu', 'olympus' ); ?>"></i>
					</div>
					<div class="fixed-sidebar-right" id="sidebar-right">
						<div id="profile-panel-responsive" class="mCustomScrollbar ps ps--theme_default" data-mcs-theme="dark">
							<?php get_template_part( 'templates/user/vcard' ); ?>
						</div>
					</div>
				</div>

			<?php } ?>
		<?php } elseif ( $show_users_menu === 'yes' ){ ?>
			<a
				<?php if ( 'youzer' === $popup_content ) { ?>
					href="<?php echo yz_get_login_page_url(); ?>" data-show-youzer-login="true" class="side-menu-open"
				<?php }  elseif ( 'digits' === $popup_content ) { ?>
					href="<?php echo get_home_url() ?>?login=true" class="side-menu-open digits-login-modal"
				<?php } else { ?>
					href="#!" @click="modalOpen()" class="side-menu-open"
				<?php }
				?>
			   data-toggle="tooltip" data-placement="left"
			   data-original-title="<?php esc_attr_e( 'Login / Register', 'olympus' ); ?>">
				<?php olympus_render( $open_login_popup_icon ) ?>
			</a>
		<?php } ?>
	</div>
</header>