<?php

class YZ_Link {

    /**
     * # Content.
     */
    function widget() {

        // Get Widget Data
        $link_url = esc_url( yz_data( 'wg_link_url' ) );

        if ( empty( $link_url ) ) {
            return;
        }

        $image_id = yz_data( 'wg_link_img' );

        ?>

        <div class="yz-link-content link-with-img">
            <?php if ( $image_id ) : ?><img loading="lazy" <?php echo yz_get_image_attributes( $image_id, 'youzify-wide', 'profile-link-widget' );?> alt=""><?php endif; ?>
            <div class="yz-link-main-content">
                <div class="yz-link-inner-content">
                    <div class="yz-link-icon"><i class="fas fa-link"></i></div>
                    <p><?php echo sanitize_text_field( yz_data( 'wg_link_txt' ) ); ?></p>
                    <a href="<?php echo $link_url; ?>" class="yz-link-url" target="_blank" rel="nofollow noopener"><?php echo yz_esc_url( $link_url ); ?></a>
                </div>
            </div>
        </div>

        <?php

    }

}