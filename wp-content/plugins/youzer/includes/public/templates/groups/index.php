<?php
/**
 * BuddyPress - Groups
 */

/**
 * Fires at the top of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_directory_groups_page' ); ?>

<?php $icons_style = yz_option( 'yz_tabs_list_icons_style', 'yz-tabs-list-gradient' ); ?>

<div id="youzer">
	
<div id="<?php echo apply_filters( 'yz_group_template_id', 'yz-bp' ); ?>" class="youzer <?php echo yz_groups_directory_class(); ?>">

	<main class="yz-page-main-content">

		<div id="yz-groups-directory">

		<?php

		/**
		 * Fires before the display of the groups.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_directory_groups' ); ?>

		<?php

		/**
		 * Fires before the display of the groups content.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_before_directory_groups_content' ); ?>

		<?php if ( apply_filters( 'yz_display_groups_directory_filter', true )  ) : ?>
		<div class="yz-mobile-nav">
			<div id="directory-show-menu" class="yz-mobile-nav-item"><div class="yz-mobile-nav-container"><i class="fas fa-bars"></i><a><?php _e( 'Menu', 'youzer' ); ?></a></div></div>
			<div id="directory-show-search" class="yz-mobile-nav-item"><div class="yz-mobile-nav-container"><i class="fas fa-search"></i><a><?php _e( 'Search', 'youzer' ); ?></a></div></div>
			<div id="directory-show-filter" class="yz-mobile-nav-item"><div class="yz-mobile-nav-container"><i class="fas fa-sliders-h"></i><a><?php _e( 'Filter', 'youzer' ); ?></a></div></div>
		</div>
				
		<div class="yz-directory-filter">
			
		<div class="item-list-tabs" aria-label="<?php esc_attr_e( 'Groups directory main navigation', 'youzer' ); ?>">
			<ul>
				<li class="selected" id="groups-all"><a href="<?php bp_groups_directory_permalink(); ?>"><?php printf( __( 'All Groups %s', 'youzer' ), '<span>' . bp_get_total_group_count() . '</span>' ); ?></a></li>

				<?php if ( is_user_logged_in() && bp_get_total_group_count_for_user( bp_loggedin_user_id() ) ) : ?>
					<li id="groups-personal"><a href="<?php echo bp_loggedin_user_domain() . bp_get_groups_slug() . '/my-groups/'; ?>"><?php printf( __( 'My Groups %s', 'youzer' ), '<span>' . bp_get_total_group_count_for_user( bp_loggedin_user_id() ) . '</span>' ); ?></a></li>
				<?php endif; ?>

				<?php

				/**
				 * Fires inside the groups directory group filter input.
				 *
				 * @since 1.5.0
				 */
				do_action( 'bp_groups_directory_group_filter' ); ?>

			</ul>
		</div><!-- .item-list-tabs -->

		<div id="directory-show-search"><a><?php _e( 'Search', 'youzer' ); ?></a></div>
		<div id="directory-show-filter"><a><?php _e( 'Filter', 'youzer' ); ?></a></div>
				
			<div class="item-list-tabs" id="subnav" aria-label="<?php esc_attr_e( 'Groups directory secondary navigation', 'youzer' ); ?>" role="navigation">
				<ul>
				<?php

				/**
				 * Fires inside the groups directory group types.
				 *
				 * @since 1.2.0
				 */
				do_action( 'bp_groups_directory_group_types' ); ?>

				<li id="groups-order-select" class="last filter">

					<label for="groups-order-by"><?php _e( 'Order By:', 'youzer' ); ?></label>

					<select id="groups-order-by">
						<option value="active"><?php _e( 'Last Active', 'youzer' ); ?></option>
						<option value="popular"><?php _e( 'Most Members', 'youzer' ); ?></option>
						<option value="newest"><?php _e( 'Newly Created', 'youzer' ); ?></option>
						<option value="alphabetical"><?php _e( 'Alphabetical', 'youzer' ); ?></option>

						<?php

						/**
						 * Fires inside the groups directory group order options.
						 *
						 * @since 1.2.0
						 */
						do_action( 'bp_groups_directory_order_options' ); ?>
					</select>
				</li>
				<li id="yz-directory-search-box">			
					<?php /* Backward compatibility for inline search form. Use template part instead. */ ?>
					<?php if ( has_filter( 'bp_directory_groups_search_form' ) ) : ?>

						<div id="group-dir-search" class="dir-search" role="search">
							<?php bp_directory_groups_search_form(); ?>
						</div><!-- #group-dir-search -->

					<?php else: ?>

						<?php bp_get_template_part( 'common/search/dir-search-form' ); ?>

					<?php endif; ?>
				</li>
				</ul>
			</div>
		</div>

		<?php endif; ?>

		<form action="" method="post" id="groups-directory-form" class="dir-form">

			<div id="template-notices" role="alert" aria-atomic="true">
				<?php

				/** This action is documented in bp-templates/bp-legacy/buddypress/activity/index.php */
				do_action( 'template_notices' ); ?>

			</div>

			<div id="groups-dir-list" class="groups dir-list">
				<?php bp_get_template_part( 'groups/groups-loop' ); ?>
			</div><!-- #groups-dir-list -->

			<?php

			/**
			 * Fires and displays the group content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_directory_groups_content' ); ?>

			<?php wp_nonce_field( 'directory_groups', '_wpnonce-groups-filter' ); ?>

			<?php

			/**
			 * Fires after the display of the groups content.
			 *
			 * @since 1.1.0
			 */
			do_action( 'bp_after_directory_groups_content' ); ?>

		</form><!-- #groups-directory-form -->

		<?php

		/**
		 * Fires after the display of the groups.
		 *
		 * @since 1.1.0
		 */
		do_action( 'bp_after_directory_groups' ); ?>

		</div><!-- #buddypress -->

	</main>

</div>

</div>
<?php

/**
 * Fires at the bottom of the groups directory template file.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_directory_groups_page' );
