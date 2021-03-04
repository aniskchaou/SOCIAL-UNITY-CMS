<div class="crum-reaction-list inline-items reactions-count-compact-<?php echo $postID; ?>">

	<?php if ( ! empty( $reactions['total'] ) ) { ?>
        <ul class="crum-reaction-ext friends-harmonic reaction-toggle-parent"
            data-post="<?php echo $postID; ?>"
            data-nonce="<?php echo wp_create_nonce( $nonce ) ?>">
			<?php foreach ( $availableReactions as $reaction ) {
				$type = $reaction['ico'];
				if ( ! isset( $reactions[ $type ] ) ) {
					continue;
				}
				?>
                <li>
                    <a href="#" data-type="<?php echo $type; ?>" class="reaction-toggle-icon disabled">
                        <img src="<?php echo "{$img_path}/{$type}.png"; ?>" alt="icon">
                    </a>
                </li>
			<?php } ?>
        </ul>

		<?php if ( isset( $reactions['total'] ) ) { ?>
            <div class="names-people-likes">
				<?php echo $reactions['total']; ?>
            </div>
		<?php } ?>
	<?php } else { ?>
    <ul class="crum-reaction-ext friends-harmonic">
        <li><img src="<?php echo "{$img_path}/crumina-reaction-empty.png"; ?>" alt="no reactions"></li>
    </ul>
        <div class="names-people-likes">0</div>

	<?php } ?>
</div>