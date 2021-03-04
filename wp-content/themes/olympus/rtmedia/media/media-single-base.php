<?php global $rt_ajax_request, $rtmedia; ?>

<div class="ui-block">
    <div class="ui-block-content">

        <div class="rtmedia-container rtmedia-single-container">
            <div class="rtm-lightbox-container clearfix">
                <?php
                do_action( 'rtmedia_before_media' );

                if ( have_rtmedia() ) : rtmedia();

                    global $rtmedia_media;
                    $type  = !empty( $rtmedia_media->media_type ) ? $rtmedia_media->media_type : 'none';
                    $class = '';

                    $if_comments_enable = isset( $rtmedia->options[ 'general_enableComments' ] ) ? $rtmedia->options[ 'general_enableComments' ] : 0;

                    // Count of likes.
                    if ( isset( $rtmedia_media->likes ) ) {
                        $count = intval( $rtmedia_media->likes );
                    } else {
                        $count = 0;
                    }

                    // Add hide class to this element when "comment on media" is not enabled.
                    if ( !intval( $count ) && '0' === $if_comments_enable ) {
                        $class = 'hide';
                    }
                    ?>
                    <div id="rtmedia-single-media-container"
                         class="rtmedia-single-media rtm-single-media rtm-media-type-<?php echo esc_attr( $type ); ?>">
                        <span class="rtmedia-media-title">
                            <?php echo esc_html( rtmedia_title() ); ?>
                        </span>
                        <div class="rtmedia-media"
                             id="rtmedia-media-<?php echo esc_attr( rtmedia_id() ); ?>"><?php rtmedia_media( true ); ?></div>

                        <?php
                        /**
                         * call function to display single media pagination
                         * By: Yahil
                         */
                        rtmedia_single_media_pagination();
                        ?>
                    </div>

                    <div class="rtmedia-single-meta rtm-single-meta">
                        <div class="rtmedia-scroll">
                            <div class="rtmedia-item-actions rtm-single-actions rtm-item-actions clearfix">
                                <?php do_action( 'rtmedia_actions_without_lightbox' ); ?>
                                <?php rtmedia_actions(); ?>
                            </div>

                            <div class="rtmedia-actions-before-description clearfix">
                                <?php do_action( 'rtmedia_actions_before_description', rtmedia_id() ); ?>
                            </div>

                            <div class="rtmedia-media-description more">
                                <?php rtmedia_description(); ?>
                            </div>

                            <?php if ( rtmedia_comments_enabled() ) { ?>
                                <div class="rtmedia-item-comments">
                                    <div class="rtmedia-actions-before-comments clearfix">
                                        <?php do_action( 'rtmedia_actions_before_comments' ); ?>
                                    </div>

                                    <div class="rtm-like-comments-info <?php echo esc_attr( $class ) ?>">
                                        <?php show_rtmedia_like_counts(); ?>
                                        <div class="rtmedia-comments-container">
                                            <?php olympus_rtmedia_comments(); ?>
                                        </div>
                                    </div>

                                    <?php
                                    if ( is_user_logged_in() ) {
                                        rtmedia_comment_form();
                                    }
                                    ?>
                                </div>

                            <?php } ?>

                            <?php do_action( 'rtmedia_actions_after_comments_form' ); ?>
                        </div>
                    </div>

                <?php else : ?>
                    <p class="rtmedia-no-media-found"><?php
                        apply_filters( 'rtmedia_no_media_found_message_filter', esc_html_e( 'Sorry !! There\'s no media found for the request !!', 'olympus' ) );
                        ?>
                    </p>
                <?php endif; ?>

                <?php do_action( 'rtmedia_after_media' ); ?>
            </div>
        </div>
    </div>
</div>