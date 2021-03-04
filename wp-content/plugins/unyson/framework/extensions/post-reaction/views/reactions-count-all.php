<ul class="crum-reaction-ext filter-icons reaction-toggle-parent reactions-count-all-<?php echo $postID; ?>"
    data-post="<?php echo $postID; ?>"
    data-nonce="<?php echo wp_create_nonce( $nonce ) ?>">
        <?php
        foreach ( $availableReactions as $reaction ) {
            $type    = $reaction[ 'ico' ];
            $tooltip = $reaction[ 'title' ];
            $classes = '';

            if ( isset( $reactions[ $type ] ) ) {
                if ( $reactions[ $type ][ 'reacted' ] === true ) {
                    $classes = 'reacted';
                    $tooltip = sprintf( __( 'Remove my %s reaction', 'crum-ext-post-reaction' ), $reaction[ 'title' ] );
                }
            } else {
                $reactions[ $type ] = array(
                    'count' => 0
                );
            }
            ?>

        <li class="<?php echo $classes; ?>">
            <a href="#" data-type="<?php echo $type; ?>" class="post-add-icon inline-items reaction-toggle-icon" data-toggle="tooltip" data-placement="left" data-original-title="<?php echo esc_attr( $tooltip ); ?>">
                <img src="<?php echo "{$img_path}/{$type}.png"; ?>" alt="icon">
                <span><?php echo $reactions[ $type ][ 'count' ]; ?></span>
            </a>
        </li>
        <?php
    }
    ?>
</ul>