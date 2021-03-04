<?php
/**
 * BuddyPress - Activity Stream Comment
 *
 * This template is used by bp_activity_comments() functions to show
 * each activity.
 *
 */

/**
 * Fires before the display of an activity comment.
 *
 * @since 1.5.0
 */
do_action( 'bp_before_activity_comment' ); ?>
<li id="acomment-<?php bp_activity_comment_id(); ?>">

	<div class="comment-container">

		<div class="acomment-avatar">
			<a href="<?php bp_activity_comment_user_link(); ?>">
				<?php bp_activity_avatar( 'type=thumb&user_id=' . bp_get_activity_comment_user_id() ); ?>
			</a>
		</div>

		<div class="comment-inner">

			<div class="acomment-meta">
				<a href="<?php echo bp_get_activity_comment_user_link() ?>"><?php echo bp_get_activity_comment_name(); ?></a>
				<div class="acomment-meta-time">

					<?php do_action( 'yz_activity_comment_before_time_meta' ) ?>

					<a href="<?php echo bp_get_activity_comment_permalink(); ?>" class="activity-time-since"><i class="far fa-clock"></i><span class="time-since" data-livestamp="<?php echo bp_core_get_iso8601_date( bp_get_activity_comment_date_recorded() ); ?>"><?php echo bp_get_activity_comment_date_recorded(); ?></span></a>

					<?php do_action( 'yz_activity_comment_after_time_meta' ); ?>

				</div>

				<?php do_action( 'yz_activity_comment_after_meta' ); ?>

			</div>

			<div class="acomment-attachments"><?php do_action( 'yz_activity_comment_attachments' ); ?></div>
			<div class="acomment-content"><?php bp_activity_comment_content(); ?></div>

		</div>


		<div class="acomment-options" data-activity-id="<?php bp_activity_comment_id(); ?>">

			<?php do_action( 'bp_before_activity_comment_options' ); ?>

			<?php if ( is_user_logged_in() && bp_activity_can_comment_reply( bp_activity_current_comment() ) ) : ?>

				<a href="#acomment-<?php bp_activity_comment_id(); ?>" class="acomment-reply bp-primary-action" id="acomment-reply-<?php bp_activity_id(); ?>-from-<?php bp_activity_comment_id(); ?>"><?php _e( 'Reply', 'youzer' ); ?></a>

			<?php endif; ?>

			<?php if ( bp_activity_user_can_delete() ) : ?>

				<a href="<?php bp_activity_comment_delete_link(); ?>" class="delete acomment-delete confirm bp-secondary-action" rel="nofollow"><?php _e( 'Delete', 'youzer' ); ?></a>

			<?php endif; ?>

			<?php

			/**
			 * Fires after the defualt comment action options display.
			 *
			 * @since 1.6.0
			 */
			do_action( 'bp_activity_comment_options' ); ?>

		</div>

		<div class="yz-clear"></div>

	</div>


	<?php bp_activity_recurse_comments( bp_activity_current_comment() ); ?>
</li>

<?php

/**
 * Fires after the display of an activity comment.
 *
 * @since 1.5.0
 */
do_action( 'bp_after_activity_comment' ); ?>