<?php
/**
 * BuddyPress - Members Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter()
 *
 */

/**
 * Fires before the display of the members loop.
 *
 * @since 1.2.0
 */

do_action( 'bp_before_members_loop' ); ?>

<?php if ( bp_has_members( bp_ajax_querystring( 'members' )) ) : ?>

	<?php

	/**
	 * Fires before the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_members_list' ); ?>

	<ul id="yz-members-list" class="<?php echo yz_members_list_class() ?>" aria-live="assertive" aria-relevant="all">

	<?php while ( bp_members() ) : bp_the_member(); ?>

		<li <?php bp_member_class(); ?>>

			<div class="yzm-user-data">

				<?php if ( bp_is_directory() ) : ?>

					<?php yz_get_user_tools( bp_get_member_user_id() ); ?>

					<?php yz_members_directory_user_cover( bp_get_member_user_id() ); ?>

				<?php endif; ?>

			<div class="yz-item-avatar">
				<a href="<?php bp_member_permalink(); ?>"><?php bp_member_avatar( 'type=full&width=100&height=100' ); ?></a>
			</div>

			<div class="item">
				<div class="item-title">
					<a class="yz-fullname" href="<?php bp_member_permalink(); ?>"><?php bp_member_name(); ?></a>
				</div>

				<div class="item-meta">
					<?php if ( bp_current_action( 'my-friends' ) ) { ?>
						<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_member_last_active( array( 'relative' => false ) ) ); ?>"><?php bp_member_last_active(); ?></span>

					<?php } else { ?>
						<span class="yz-name"><?php echo yz_get_md_user_meta( bp_get_member_user_id() ); ?></span>
					<?php } ?>

					<?php

						/**
						 * Fires inside the display of a directory member item.
						 *
						 * @since 1.1.0
						 */
						do_action( 'bp_directory_members_item_meta' ); ?>


				</div>

				<?php

				/**
				 * Fires inside the display of a directory member item.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_directory_members_item' ); ?>

				<?php
				 /***
				  * If you want to show specific profile fields here you can,
				  * but it'll add an extra query for each member in the loop
				  * (only one regardless of the number of fields you show):
				  *
				  * bp_member_profile_data( 'field=the field name' );
				  */
				?>
			</div>

			<?php
				if ( bp_is_directory() ) {
					yz_get_member_statistics_data( bp_get_member_user_id() );
				}
			?>

			<?php if ( 'on' == yz_option( 'yz_enable_md_cards_actions_buttons', 'on' ) && is_user_logged_in() ) : ?>

			<div class="yzm-user-actions">

				<?php

				/**
				 * Fires inside the members action HTML markup to display actions.
				 *
				 * @since 1.1.0
				 */
				do_action( 'bp_directory_members_actions' ); ?>

			</div>

			<?php endif; ?>

			<?php do_action( 'yz_after_directory_members_actions' ); ?>

			<div class="clear"></div>

			</div>

		</li>

	<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the display of the members list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_members_list' ); ?>

	<?php bp_member_hidden_fields(); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="member-dir-count-bottom">
			<?php bp_members_pagination_count(); ?>
		</div>

		<?php if ( bp_get_members_pagination_links() ) : ?>
		<div class="pagination-links" id="member-dir-pag-bottom">
			<?php bp_members_pagination_links(); ?>
		</div>
		<?php endif; ?>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( "Sorry, no members were found.", 'youzer' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of the members loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_members_loop' );