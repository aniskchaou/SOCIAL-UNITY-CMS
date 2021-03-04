<?php
/**
 * BuddyPress Activity templates
 */

/**
 * Fires before the activity directory listing.
 *
 * @since 1.5.0
 */

/**
 * # Get Acitivity Class.
 */

do_action( 'bp_before_directory_activity' ); ?>

<div id="youzer">

<div id="<?php echo apply_filters( 'yz_activity_template_id', 'yz-bp' ); ?>" class="youzer yz-page noLightbox <?php echo yz_get_activity_page_class(); ?>">

	<div class="yz-content">

		<main class="yz-page-main-content">

			<?php

			/**
			 * Fires before the activity directory display content.
			 *
			 * @since 1.2.0
			 */
			do_action( 'bp_before_directory_activity_content' ); ?>

			<?php if ( 'on' == yz_option( 'yz_enable_activity_directory_filter_bar', 'on' ) && apply_filters( 'yz_enable_activity_directory_filter_bar', true ) ) : ?>

			<div class="yz-mobile-nav">
				<div class="yz-mobile-nav-item yz-show-activity-menu"><div class="yz-mobile-nav-container"><i class="fas fa-bars"></i><a><?php _e( 'Menu', 'youzer' ); ?></a></div></div>
				<div class="yz-mobile-nav-item yz-show-activity-search"><div class="yz-mobile-nav-container"><i class="fas fa-search"></i><a><?php _e( 'Search', 'youzer' ); ?></a></div></div>
				<div class="yz-mobile-nav-item yz-show-activity-filter"><div class="yz-mobile-nav-container"><i class="fas fa-sliders-h"></i><a><?php _e( 'Filter', 'youzer' ); ?></a></div></div>
			</div>

			<div id="yz-wall-nav">
				<div class="item-list-tabs activity-type-tabs" aria-label="<?php esc_attr_e( 'Sitewide activities navigation', 'youzer' ); ?>" role="navigation">
					<ul>
						<?php

						/**
						 * Fires before the listing of activity type tabs.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_before_activity_type_tab_all' ); ?>

						<li id="activity-all" class="selected"><a href="<?php bp_activity_directory_permalink(); ?>"><?php printf( __( 'All Members %s', 'youzer' ), '<span>' . bp_get_total_member_count() . '</span>' ); ?></a></li>

						<?php if ( is_user_logged_in() ) : ?>

							<?php

							/**
							 * Fires before the listing of friends activity type tab.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_activity_type_tab_friends' ); ?>

							<?php if ( bp_is_active( 'friends' ) ) : ?>

								<?php if ( bp_get_total_friend_count( bp_loggedin_user_id() ) ) : ?>

									<li id="activity-friends"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_friends_slug() . '/'; ?>"><?php printf( __( 'My Friends %s', 'youzer' ), '<span>' . bp_get_total_friend_count( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

								<?php endif; ?>

							<?php endif; ?>

							<?php

							/**
							 * Fires before the listing of groups activity type tab.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_activity_type_tab_groups' ); ?>

							<?php if ( bp_is_active( 'groups' ) ) : ?>

								<?php if ( bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>

									<li id="activity-groups"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/' . bp_get_groups_slug() . '/'; ?>"><?php printf( __( 'My Groups %s', 'youzer' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

								<?php endif; ?>

							<?php endif; ?>

							<?php

							/**
							 * Fires before the listing of favorites activity type tab.
							 *
							 * @since 1.2.0
							 */
							do_action( 'bp_before_activity_type_tab_favorites' ); ?>

							<?php if ( bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) ) : ?>

								<li id="activity-favorites"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/favorites/'; ?>"><?php printf( __( 'My Favorites %s', 'youzer' ), '<span>' . bp_get_total_favorite_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>

							<?php endif; ?>

							<?php if ( bp_activity_do_mentions() ) : ?>

								<?php

								/**
								 * Fires before the listing of mentions activity type tab.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_before_activity_type_tab_mentions' ); ?>

								<li id="activity-mentions"><a href="<?php echo bp_loggedin_user_domain() . bp_get_activity_slug() . '/mentions/'; ?>"><?php _e( 'Mentions', 'youzer' ); ?><?php if ( bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ) : ?> <strong><span><?php printf( _nx( '%s new', '%s new', bp_get_total_mention_count_for_user( bp_loggedin_user_id() ), 'Number of new activity mentions', 'youzer' ), bp_get_total_mention_count_for_user( bp_loggedin_user_id() ) ); ?></span></strong><?php endif; ?></a></li>

							<?php endif; ?>

						<?php endif; ?>

						<?php

						/**
						 * Fires after the listing of activity type tabs.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_activity_type_tabs' ); ?>
					</ul>
				</div><!-- .item-list-tabs -->

				<div class="item-list-tabs activity-type-tabs-subnav no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Activity secondary navigation', 'youzer' ); ?>" role="navigation">
					<ul>
						<?php

						/**
						 * Fires before the display of the activity syndication options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_activity_syndication_options' ); ?>

						<li id="activity-filter-select" class="last">
							<div class="yz-activity-show-filter"><i class="fas fa-sliders-h"></i></div>
							<div class="yz-dropdown-area">
							<label for="activity-filter-by"><?php _e( 'Filter Activities By :', 'youzer' ); ?></label>
							<select id="activity-filter-by" class="yz-bar-select">
								<option value="-1"><?php _e( '&mdash; Everything &mdash;', 'youzer' ); ?></option>

								<?php bp_activity_show_filters(); ?>

								<?php

								/**
								 * Fires inside the select input for activity filter by options.
								 *
								 * @since 1.2.0
								 */
								do_action( 'bp_activity_filter_options' ); ?>

							</select>
							</div>
						</li>
						<li class="yz-activity-show-search">

							<div class="yz-activity-show-search-form"><i class="fas fa-search"></i></div>
							<div class="yz-dropdown-area">
								<div class="yz-activity-search">
									<form action="<?php echo bp_get_activity_directory_permalink(); ?>">
									<input type="text" name="s" placeholder="<?php _e( 'Search Activities ...', 'youzer' ); ?>">
									<button><i class="fas fa-search"></i></button>
									</form>
								</div>
							</div>
						</li>
					</ul>
				</div><!-- .item-list-tabs -->

		</div>

		<?php endif; ?><!-- Enable/Disable Global Activity Filter bar. -->
		<div class="yz-right-sidebar-layout">

		<div class="yz-main-column">

			<div class="yz-column-content">

				<?php if ( is_user_logged_in() ) : ?>

					<?php bp_get_template_part( 'activity/post-form' ); ?>

				<?php endif; ?>

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

				/**
				 * Fires before the display of the activity list.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_before_directory_activity_list' ); ?>

				<div class="activity" aria-live="polite" aria-atomic="true" aria-relevant="all">

					<?php bp_get_template_part( 'activity/activity-loop' ); ?>

				</div><!-- .activity -->

				<?php

				/**
				 * Fires after the display of the activity list.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_after_directory_activity_list' ); ?>

				<?php

				/**
				 * Fires inside and displays the activity directory display content.
				 */
				do_action( 'bp_directory_activity_content' ); ?>
			</div>
		</div>

		<div class="yz-sidebar-column yz-group-sidebar youzer-sidebar">
			<div class="yz-column-content">
				<?php do_action( 'yz_global_wall_sidebar' ); ?>
			</div>
		</div>

		</div>
	<?php

	/**
	 * Fires after the activity directory display content.
	 *
	 * @since 1.2.0
	 */
	do_action( 'bp_after_directory_activity_content' ); ?>

	<?php

	/**
	 * Fires after the activity directory listing.
	 *
	 * @since 1.5.0
	 */
	do_action( 'bp_after_directory_activity' ); ?>

	</main>

	</div><!-- .yz-content -->

</div>
</div>
