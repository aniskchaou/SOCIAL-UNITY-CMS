<?php
/**
 * BuddyPress - Groups Loop
 *
 * Querystring is set via AJAX in _inc/ajax.php - bp_legacy_theme_object_filter().
 */

?>

<?php

/**
 * Fires before the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_before_groups_loop' ); ?>

<?php if ( bp_has_groups( bp_ajax_querystring( 'groups' ) ) ) : ?>

	<?php

	/**
	 * Fires before the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_before_directory_groups_list' ); ?>

	<ul id="yz-groups-list" class="<?php echo yz_groups_list_class() ?>" aria-live="assertive" aria-atomic="true" aria-relevant="all">

	<?php while ( bp_groups() ) : bp_the_group(); ?>

		<li <?php bp_group_class(); ?>>

			<div class="yz-group-data">

				<?php if ( bp_is_groups_directory() ) yz_get_group_tools( bp_get_group_id() ) ?>

				<?php yz_groups_directory_group_cover( bp_get_group_id() ); ?>

					<?php if ( bp_is_groups_directory() ) : ?>

						<a href="<?php bp_group_permalink(); ?>" class="item-avatar">
							<div class="yz-group-avatar"><?php echo bp_core_fetch_avatar( array( 'object' => 'group', 'avatar_dir' => 'group-avatars', 'item_id' => bp_get_group_id(), 'width' => '100', 'height' => '100', 'type' => 'full') ); ?></div>
						</a>

					<?php else : ?>

						<div class="item-avatar">
							<a href="<?php bp_group_permalink(); ?>"><?php bp_group_avatar( 'type=full&width=100&height=100' ); ?></a>
						</div>

					<?php endif; ?>

				<div class="item">
					<div class="item-title"><?php bp_group_link(); ?></div>
					<?php do_action( 'bp_directory_groups_after_group_name' ); ?>
					<div class="item-meta">
						<?php if ( bp_is_groups_directory() ) : ?>
							<div class="group-status">
								<?php echo yz_get_group_status( bp_get_group_status() ); ?>
							</div>
						<?php else : ?>
							<span class="activity" data-livestamp="<?php bp_core_iso8601_date( bp_get_group_last_active( 0, array( 'relative' => false ) ) ); ?>"><?php printf( __( 'Active %s', 'youzer' ), bp_get_group_last_active() ); ?></span>
						<?php endif; ?>
					</div>

					<?php

					/**
					 * Fires inside the listing of an individual group listing item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_directory_groups_item' ); ?>

				</div>

				<?php
					if ( bp_is_groups_directory() ) {
						yz_get_group_statistics_data( bp_get_group_id() );
					}
				?>

				<?php if ( 'on' == yz_option( 'yz_enable_gd_cards_actions_buttons', 'on' ) && is_user_logged_in() ) : ?>
				<div class="action"><?php

					/**
					 * Fires inside the action section of an individual group listing item.
					 *
					 * @since 1.1.0
					 */
					do_action( 'bp_directory_groups_actions' ); ?></div>

				<?php endif; ?>

				<?php do_action( 'yz_after_bp_directory_groups_actions' ); ?>

				<div class="clear"></div>

			</div>
		</li>

	<?php endwhile; ?>

	</ul>

	<?php

	/**
	 * Fires after the listing of the groups list.
	 *
	 * @since 1.1.0
	 */
	do_action( 'bp_after_directory_groups_list' ); ?>

	<div id="pag-bottom" class="pagination">

		<div class="pag-count" id="group-dir-count-bottom">
			<?php bp_groups_pagination_count(); ?>
		</div>

		<?php if ( bp_get_groups_pagination_links() ) : ?>
		<div class="pagination-links" id="group-dir-pag-bottom">
			<?php bp_groups_pagination_links(); ?>
		</div>
		<?php endif; ?>

	</div>

<?php else: ?>

	<div id="message" class="info">
		<p><?php _e( 'There were no groups found.', 'youzer' ); ?></p>
	</div>

<?php endif; ?>

<?php

/**
 * Fires after the display of groups from the groups loop.
 *
 * @since 1.2.0
 */
do_action( 'bp_after_groups_loop' );
