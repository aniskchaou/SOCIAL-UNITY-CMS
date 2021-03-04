<?php

/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

?>

<ul id="bbp-topic-<?php bbp_topic_id(); ?>" <?php bbp_topic_class(); ?>>

	<li class="bbp-topic-title">

		<?php do_action( 'bbp_theme_before_topic_title' ); ?>
		
		<div class="yz-forums-topic-item">
			<div class="yz-forums-topic-icon"><?php echo yz_bbp_get_topic_icon(); ?></div>
			<div class="yz-forums-topic-head">
				<a class="yz-forums-topic-title" href="<?php bbp_topic_permalink(); ?>"><?php bbp_topic_title(); ?></a>

				<?php if ( bbp_is_user_home() ) : ?>

					<?php if ( bbp_is_favorites() ) : ?>

						<span class="bbp-row-actions">

							<?php do_action( 'bbp_theme_before_topic_favorites_action' ); ?>

							<?php bbp_topic_favorite_link( array( 'before' => '', 'favorite' => '<span  data-yztooltip="' . __( 'Like', 'youzer' ) . '"><i class="fas fa-thumbs-up"></i></span>', 'favorited' => '<span  data-yztooltip="' . __( 'Unlike', 'youzer' ) . '"><i class="fas fa-thumbs-down"></i></span>' ) ); ?>

							<?php do_action( 'bbp_theme_after_topic_favorites_action' ); ?>

						</span>

					<?php elseif ( bbp_is_subscriptions() ) : ?>

						<span class="bbp-row-actions">

							<?php do_action( 'bbp_theme_before_topic_subscription_action' ); ?>

							<?php bbp_topic_subscription_link( array( 'before' => '', 'subscribe' => '<span  data-yztooltip="' . __( 'Subscribe', 'youzer' ) . '"><i class="fas fa-bell"></i></span>', 'unsubscribe' => '<span  data-yztooltip="' . __( 'Unsubscribe', 'youzer' ) . '"><i class="fas fa-bell-slash"></i></span>' ) ); ?>

							<?php do_action( 'bbp_theme_after_topic_subscription_action' ); ?>

						</span>

					<?php endif; ?>

				<?php endif; ?>
				<div class="yz-forums-topic-meta">
					<div class="yz-forums-topic-author"><?php echo bbp_get_topic_author_link( array( 'size' => '20' ) ); ?></div>
					<div class="yz-forums-topic-forum">
						<i class="far fa-folder-open"></i>
						<a href="<?php echo bbp_get_forum_permalink( bbp_get_topic_forum_id() ); ?>"><?php echo bbp_get_forum_title( bbp_get_topic_forum_id() ); ?></a>
					</div>
				</div>
			</div>
		</div>
		
		<?php do_action( 'bbp_theme_after_topic_title' ); ?>

		<?php bbp_topic_pagination(); ?>

		<?php do_action( 'bbp_theme_before_topic_meta' ); ?>

		<p class="yz-bbp-topic-meta">

			<?php do_action( 'bbp_theme_before_topic_started_by' ); ?>

			<?php do_action( 'bbp_theme_after_topic_started_by' ); ?>

			<?php if ( !bbp_is_single_forum() || ( bbp_get_topic_forum_id() !== bbp_get_forum_id() ) ) : ?>

				<?php do_action( 'bbp_theme_before_topic_started_in' ); ?>

				<!-- <span class="bbp-topic-started-in"><?php printf( __( 'in: <a href="%1$s">%2$s</a>', 'youzer' ), bbp_get_forum_permalink( bbp_get_topic_forum_id() ), bbp_get_forum_title( bbp_get_topic_forum_id() ) ); ?></span> -->

				<?php do_action( 'bbp_theme_after_topic_started_in' ); ?>

			<?php endif; ?>

		</p>

		<?php do_action( 'bbp_theme_after_topic_meta' ); ?>

		<?php bbp_topic_row_actions(); ?>

	</li>

	<li class="bbp-topic-voice-count"><i class="fas fa-microphone-alt" area-hidden="true"></i><?php bbp_topic_voice_count(); ?></li>

	<li class="bbp-topic-reply-count"><i class="far fa-comments" area-hidden="true"></i><?php bbp_show_lead_topic() ? bbp_topic_reply_count() : bbp_topic_post_count(); ?></li>

	<li class="yz-bbp-freshness">

		<div class="yz-bbp-freshness-data">

			<div class="yz-bbp-freshness-author-img">
				<?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'size' => 40, 'type' => 'avatar' ) ); ?>
			</div>

			<div class="yz-bbp-freshness-content">
					
				<?php do_action( 'bbp_theme_before_topic_freshness_author' ); ?>

				<div class="yz-bbp-freshness-author"><?php bbp_author_link( array( 'post_id' => bbp_get_topic_last_active_id(), 'type' => 'name' ) ); ?></div>

				<?php do_action( 'bbp_theme_after_topic_freshness_author' ); ?>
		
				<?php do_action( 'bbp_theme_before_topic_freshness_link' ); ?>

				<div class="yz-bbp-freshness-time"><?php bbp_topic_freshness_link(); ?></div>

				<?php do_action( 'bbp_theme_after_topic_freshness_link' ); ?>

			</div>
		</div>
	</li>

</ul><!-- #bbp-topic-<?php bbp_topic_id(); ?> -->
