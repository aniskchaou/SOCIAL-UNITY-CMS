<?php global $rt_ajax_request, $rtmedia; ?>

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

                <span class="mfp-arrow mfp-arrow-left mfp-prevent-close rtm-lightbox-arrows" type="button" title="<?php esc_attr_e( 'Previous Media', 'olympus' ); ?>"></span>
                <span class="mfp-arrow mfp-arrow-right mfp-prevent-close" type="button" title="<?php esc_attr_e( 'Next Media', 'olympus' ); ?>"></span>

                <div class="rtmedia-media"
                     id="rtmedia-media-<?php echo esc_attr( rtmedia_id() ); ?>"><?php rtmedia_media( true ); ?></div>

                <div class='rtm-ltb-action-container clearfix'>
                    <div class='rtm-ltb-title'>
                        <span
                            class="rtmedia-media-name <?php if ( rtmedia_album_name() ) { ?>rtmedia-media-name-width-50<?php } else { ?>rtmedia-media-name-width-100<?php } ?>">
                            &nbsp;<a href="<?php rtmedia_permalink(); ?>"
                                     title="<?php echo esc_attr( rtmedia_title() ); ?>"><?php echo esc_html( rtmedia_title() ); ?></a>
                        </span>

                        <?php if ( rtmedia_album_name() ) { ?>
                            <span class="rtmedia-album-name">
                                <span>&nbsp;<?php
                                    $media_under = apply_filters( 'rtmedia_update_under_album_text', esc_html__( 'under', 'olympus' ) );
                                    olympus_render( $media_under );
                                    ?></span>
                                <a href="<?php rtmedia_album_permalink(); ?>"
                                   title="<?php echo esc_attr( rtmedia_album_name() ); ?>"><?php echo esc_html( rtmedia_album_name() ); ?></a>
                            </span>
                        <?php } ?>
                    </div>

                    <div class="rtmedia-actions rtmedia-author-actions rtm-item-actions">
                        <?php rtmedia_actions(); ?>
                        <?php do_action( 'rtmedia_action_buttons_after_media', rtmedia_id() ); ?>
                    </div>
                </div>
            </div>

            <div class="rtmedia-single-meta rtm-single-meta">
                <div class="rtm-single-meta-contents<?php
                if ( is_user_logged_in() ) {
                    echo esc_attr( ' logged-in' );
                }
                ?>">

                    <div class="rtm-user-meta-details">
                        <div class="userprofile rtm-user-avatar">
                            <?php rtmedia_author_profile_pic( true ); ?>
                        </div>

                        <div class="username">
                            <?php rtmedia_author_name( true ); ?>
                        </div>

                        <div class="rtm-time-privacy clearfix">
                            <?php
                            echo wp_kses( get_rtmedia_date_gmt(), array( 'span' => array() ) );
                            echo wp_kses( get_rtmedia_privacy_symbol(), array(
                                'i' => array(
                                    'class' => array(),
                                    'title' => array(),
                                ),
                            ) );
                            ?>
                        </div>
                    </div>

                    <div class="rtmedia-actions-before-description clearfix">
                        <?php do_action( 'rtmedia_actions_before_description', rtmedia_id() ); ?>
                    </div>

                    <div class="rtmedia-media-description rtm-more">
                        <?php echo rtmedia_description( $echo = false ); ?>
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
                        </div>
                    <?php } else { ?>
                        <div class="rtmedia-item-comments">
                            <div class="rtmedia-actions-before-comments clearfix">
                                <div class="like-button-no-comments">
                                    <?php do_action( 'like_button_no_comments' ); ?>
                                </div>
                            </div>
                            <div class="rtm-like-comments-info <?php echo esc_attr( $class ) ?>">
                                <?php show_rtmedia_like_counts(); ?>
                            </div>
                        </div>
                    <?php } ?>

                </div>

                <?php if ( rtmedia_comments_enabled() && is_user_logged_in() ) { ?>
                    <div class='rtm-media-single-comments'>
                        <?php rtmedia_comment_form(); ?>
                    </div>
                <?php } ?>


                <?php do_action( 'rtmedia_actions_after_comments_form' ); ?>
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