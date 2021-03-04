<ul class="filter-icons reaction-toggle-parent reactions-count-used-<?php echo $postID; ?>"
    data-post="<?php echo $postID; ?>"
    data-nonce="<?php echo wp_create_nonce( $nonce ) ?>">
        <?php
        foreach ( $availableReactions as $reaction ) {
            $type = $reaction[ 'ico' ];
            if ( !isset( $reactions[ $type ] ) ) {
                continue;
            }
            ?>

        <li>
            <a href="#" data-type="<?php echo $type; ?>" class="post-add-icon inline-items reaction-toggle-icon disabled">
                <img src="<?php echo "{$img_path}/{$type}.png"; ?>" alt="icon">
                <span><?php echo $reactions[ $type ][ 'count' ]; ?></span>
            </a>
        </li>
        <?php
    }
    ?>
</ul>