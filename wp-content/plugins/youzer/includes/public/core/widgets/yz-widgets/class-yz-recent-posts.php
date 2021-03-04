<?php

class YZ_Recent_Posts {

    /**
     * # Content.
     */
    function widget() {

    	// Get Data .
        $recent_posts = get_posts( array(
            'author'  => bp_displayed_user_id(),
            'orderby' => 'date',
            'order'   => 'desc',
            'numberposts' => yz_option( 'yz_wg_max_rposts', 3 )
        ) );

        if ( empty( $recent_posts ) ) {
            return;
        }

		?>

        <div class="yz-posts-by-author yz-recent-posts yz-rp-img-circle">
            <?php foreach ( $recent_posts as $post ) : ?>
            <div class="yz-post-item">
                <?php yz_get_post_thumbnail( array( 'attachment_id' => get_post_thumbnail_id( $post->ID ), 'size' => 'thumbnail', 'element' => 'profile-recent-posts-widget' ) );
                ?>
                <div class="yz-post-head">
                    <h2 class="yz-post-title">
                        <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
                    </h2>
                    <div class="yz-post-meta">
                        <ul><li><?php echo get_the_date( '', $post->ID ); ?></li></ul>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php

    }
}