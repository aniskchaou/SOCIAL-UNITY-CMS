<?php

class YZ_Post {

    /**
     * # Content.
     */
    function widget() {

        // Get Post ID.
        $post_id = yz_data( 'yz_profile_wg_post_id' );

        if ( empty( $post_id ) ) {
            return;
        }

        // Get Post Type.
        $post_type = yz_data( 'wg_post_type' );

        // Call Post TYPE FUNCTION
        $this->get_post_type( $post_id, $post_type );
    }

    /***
     * Get Post Type.
     */
    function get_post_type( $post_id, $post_type ) {

        // Get Post Data.
        $post = get_post( $post_id );

        if ( ! $post ) {
            return;
        }

        // Show / Hide Post Elements
        $display_icons = yz_option( 'yz_display_wg_post_meta_icons', 'on' );

        ?>

        <div class="yz-post-content">

            <?php yz_get_post_thumbnail( array( 'attachment_id' => get_post_thumbnail_id( $post_id ), 'size' => 'medium', 'element' => 'profile-post-widget' ) ); ?>

            <div class="yz-post-container">

                <div class="yz-post-inner-content">

                    <div class="yz-post-head">

                        <a class="yz-post-type"><?php echo $post_type; ?></a>

                        <h2 class="yz-post-title"><a href="<?php the_permalink( $post_id ); ?>"><?php echo $post->post_title; ?></a></h2>

                        <?php if ( 'on' == yz_option( 'yz_display_wg_post_meta', 'on' ) ) : ?>

                        <div class="yz-post-meta">

                            <ul>

                                <?php if ( 'on' == yz_option( 'yz_display_wg_post_date', 'on' ) ) : ?>
                                    <li>
                                        <?php
                                            if ( 'on' == $display_icons ) {
                                                echo '<i class="far fa-calendar-alt"></i>';
                                            }
                                            // Print date.
                                            echo get_the_date( 'F j, Y', $post_id );
                                        ?>
                                    </li>
                                <?php endif; ?>

                                <?php
                                    if ( 'on' == yz_option( 'yz_display_wg_post_cats', 'on' ) )  {
                                        yz_get_post_categories( $post_id, $display_icons );
                                    }
                                ?>

                                <?php if ( 'on' == yz_option( 'yz_display_wg_post_comments', 'on' ) ) : ?>
                                    <li>
                                        <?php

                                            if ( 'on' == $display_icons ) {
                                                echo '<i class="far fa-comments"></i>';
                                            }

                                            // Print Comments Number
                                            echo $post->comment_count;

                                        ?>
                                    </li>
                                <?php endif; ?>

                            </ul>

                        </div>

                        <?php endif; ?>

                    </div>

                    <?php if ( 'on' == yz_option( 'yz_display_wg_post_excerpt', 'on' ) ) : ?>
                        <div class="yz-post-text">
                            <p><?php echo yz_get_excerpt( $post->post_content, 35 ) ; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php  if ( 'on' == yz_option( 'yz_display_wg_post_tags', 'on' ) ) { $this->get_post_tags( $post_id ); } ?>

                    <?php if ( 'on' == yz_option( 'yz_display_wg_post_readmore', 'on' ) ) : ?>
                        <a href="<?php the_permalink( $post_id ); ?>" class="yz-read-more">
                            <div class="yz-rm-icon">
                                <i class="fas fa-angle-double-right"></i>
                            </div>
                            <?php _e( 'Read More', 'youzer' ); ?>
                        </a>
                    <?php endif; ?>

                </div>

            </div>

        </div>

        <?php

    }

    /**
     * # Get Post Tags
     */
    function get_post_tags( $post_id ) {

        ?>

        <ul class="yz-post-tags">

        <?php

            $tags_list = get_the_tags( $post_id );

            if ( $tags_list ) :

                foreach( $tags_list as $tag ) :
                    $link     = get_tag_link( $tag->term_taxonomy_id );
                    $tag_name = $tag->name;
                    $tag_link = "<a href='$link'>$tag_name</a>";
                    echo "<li><span class='yz-tag-symbole'>#</span>$tag_link</li>";
                endforeach;

            endif;

        ?>

        </ul>

        <?php
    }

}