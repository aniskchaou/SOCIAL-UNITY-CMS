<ul class="crum-reaction-ext reactions-btns-<?php echo $postID; ?> reaction-toggle-parent"
    data-post="<?php echo $postID; ?>"
    data-nonce="<?php echo wp_create_nonce( $nonce ) ?>">
        <?php
        foreach ( $availableReactions as $reaction ) {
            $type    = $reaction[ 'ico' ];
            $tooltip = $reaction[ 'title' ];
            $classes = '';

            if ( isset( $reactions[ $type ] ) ) {
                if ( $reactions[ $type ][ 'reacted' ] === true ) {
                    $tooltip = sprintf( __( 'Remove my %s reaction', 'crum-ext-post-reaction' ), $reaction[ 'title' ] );
                    $classes = 'reacted';
                }
            }
            ?>
        <li class="<?php echo $classes; ?>">
            <a href="#" data-type="<?php echo $type; ?>" class="reaction-toggle-icon">
                <img src="<?php echo "{$img_path}/{$type}.png"; ?>" alt="icon" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo esc_attr( $tooltip ); ?>">
            </a>
        </li>
    <?php } ?>
</ul>
