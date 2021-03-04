<?php

/**
 * Statistics Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Get the statistics
$stats = bbp_get_statistics(); ?>

<div class="yz-forums-statistics-items" role="main">

	<?php do_action( 'bbp_before_statistics' ); ?>

	<div class="yz-forums-statistics-item yz-statistics-registered-user">
		<div class="yz-forums-statistics-icon">
			<i class="fas fa-users"></i>
		</div>
		<div class="yz-forums-statistics-content">
			<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['user_count'] ); ?></div>
			<div class="yz-forums-statistics-desc"><?php _e( 'Registered Users', 'youzer' ); ?></div>
		</div>
	</div>

	<div class="yz-forums-statistics-item yz-statistics-forums">
		<div class="yz-forums-statistics-icon">
			<i class="far fa-comments"></i>
		</div>
		<div class="yz-forums-statistics-content">
			<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['forum_count'] ); ?></div>
			<div class="yz-forums-statistics-desc"><?php _e( 'Forums', 'youzer' ); ?></div>
		</div>
	</div>

	<div class="yz-forums-statistics-item yz-statistics-topics">
		<div class="yz-forums-statistics-icon">
			<i class="fas fa-pencil-alt"></i>
		</div>
		<div class="yz-forums-statistics-content">
			<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['topic_count'] ); ?></div>
			<div class="yz-forums-statistics-desc"><?php _e( 'Topics', 'youzer' ); ?></div>
		</div>
	</div>

	<div class="yz-forums-statistics-item yz-statistics-replies">
		<div class="yz-forums-statistics-icon">
			<i class="fas fa-comment-dots"></i>
		</div>
		<div class="yz-forums-statistics-content">
			<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['reply_count'] ); ?></div>
			<div class="yz-forums-statistics-desc"><?php _e( 'Replies', 'youzer' ); ?></div>
		</div>
	</div>

	<div class="yz-forums-statistics-item yz-statistics-topic-tags">
		<div class="yz-forums-statistics-icon">
			<i class="fas fa-tags"></i>
		</div>
		<div class="yz-forums-statistics-content">
			<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['topic_tag_count'] ); ?></div>
			<div class="yz-forums-statistics-desc"><?php _e( 'Topic Tags', 'youzer' ); ?></div>
		</div>
	</div>

	<?php if ( ! empty( $stats['empty_topic_tag_count'] ) ) : ?>

		<div class="yz-forums-statistics-item yz-statistics-empty-topic-tags">
			<div class="yz-forums-statistics-icon">
				<i class="fas fa-tag"></i>
			</div>
			<div class="yz-forums-statistics-content">
				<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['empty_topic_tag_count'] ); ?></div>
				<div class="yz-forums-statistics-desc"><?php _e( 'Empty Topic Tags', 'youzer' ); ?></div>
			</div>
		</div>

	<?php endif; ?>

	<?php if ( !empty( $stats['topic_count_hidden'] ) ) : ?>

		<div class="yz-forums-statistics-item yz-statistics-hidden-topics">
			<div class="yz-forums-statistics-icon">
				<i class="fas fa-file-alt"></i>
			</div>
			<div class="yz-forums-statistics-content">
				<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['topic_count_hidden'] ); ?></div>
				<div class="yz-forums-statistics-desc"><?php _e( 'Hidden Topics', 'youzer' ); ?></div>
			</div>
		</div>

	<?php endif; ?>

	<?php if ( !empty( $stats['reply_count_hidden'] ) ) : ?>

		<div class="yz-forums-statistics-item yz-statistics-hidden-replies">
			<div class="yz-forums-statistics-icon">
				<i class="far fa-comment"></i>
			</div>
			<div class="yz-forums-statistics-content">
				<div class="yz-forums-statistics-nbr"><?php echo esc_html( $stats['reply_count_hidden'] ); ?></div>
				<div class="yz-forums-statistics-desc"><?php _e( 'Hidden Replies', 'youzer' ); ?></div>
			</div>
		</div>

	<?php endif; ?>

	<?php do_action( 'bbp_after_statistics' ); ?>

</div>

<?php unset( $stats );