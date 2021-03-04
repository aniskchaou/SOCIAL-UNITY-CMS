<?php

class YZ_Quote {

    /**
     * # Content.
     */
    function widget() {

        // Get User Data
        $quote_txt = sanitize_textarea_field( yz_data( 'wg_quote_txt' ) );

        if ( empty( $quote_txt ) ) {
            return;
        }

        $image_id = yz_data( 'wg_quote_img' );

        yz_styling()->gradient_styling( array(
            'selector'      => 'body .quote-with-img:before',
            'left_color'    => 'yz_wg_quote_gradient_left_color',
            'right_color'   => 'yz_wg_quote_gradient_right_color'
            )
        );

        ?>

        <div class="yz-quote-content quote-with-img">
            <?php if ( ! empty( $image_id ) ) : ?><img loading="lazy" <?php echo yz_get_image_attributes( $image_id, 'youzify-wide', 'profile-quote-widget' ); ?> alt=""><?php endif; ?>
            <div class="yz-quote-main-content">
                <div class="yz-quote-icon"><i class="fas fa-quote-right"></i></div>
                <blockquote><?php echo nl2br( $quote_txt ); ?></blockquote>
                <h3 class="yz-quote-owner"><?php echo yz_data( 'wg_quote_owner' ); ?></h3>
            </div>
        </div>

        <?php

    }

}