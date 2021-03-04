<nav>
    <?php
    $links = paginate_links( array(
        'prev_text' => esc_html__( 'Previous', 'olympus' ),
        'next_text' => esc_html__( 'Next', 'olympus' ),
        'type'      => 'array',
    ) );
    ?>

    <ul class="pagination justify-content-center">
        <?php
        if ( is_array( $links ) ) {
            foreach ( $links as $link ) {
                $classes = preg_match( '/span/', $link ) ? 'page-item disabled' : 'page-item';
                $item    = str_replace( array( '<span', '</span>', 'page-numbers', 'current', 'href=\'\'', 'href=""' ), array( '<a href="#" ', '</a>', 'page-link', 'active', 'href="?page=1"', 'href="?page=1"' ), $link );
                ?>
                <li class="<?php olympus_render( $classes ); ?>"><?php olympus_render( $item ); ?></li>
                <?php
            }
        }
        ?>
    </ul>
</nav>