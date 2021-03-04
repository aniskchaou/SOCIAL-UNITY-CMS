<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * @var $before_widget
 * @var $after_widget
 * @var $title
 * @var $posts
 */
$img_width  = 403;
$img_height = 290;

olympus_render( $before_widget );
olympus_render( $title );
?>
<ul class="widget">
    <?php
    if ( $posts ) {
        foreach ( $posts as $single ) {
            global $post;
            $post = $single;
            setup_postdata( $post );

            $time = get_the_time( DATE_ATOM );
            ?>
            <li class="ui-block">
                <article class="hentry blog-post">
                    <div class="post-thumb">
                        <?php olympus_generate_thumbnail( $img_width, $img_height, true ); ?>
                    </div>
                    <div class="post-content">
                        <?php echo olympus_post_category_list( get_the_ID(), ' ', true ); ?>

	                    <?php the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="h4 entry-title post-title">', '</a>' ); ?>

                        <?php
                        if ( 'show' === $description ) {
                            echo olympus_html_tag( 'p', array(), olympus_generate_short_excerpt( get_the_ID(), 12, false ) );
                        }
                        ?>

                        <div class="author-date">
                            <?php esc_html_e( 'by', 'olympus' ); ?> <?php olympus_post_author( false ); ?>
                            - <?php olympus_posted_time(); ?>
                        </div>

                        <div class="post-additional-info inline-items">
                            <?php echo olympus_get_post_reactions( 'compact' ); ?>
                            <div class="comments-shared">
                                <?php olympus_comments_count(); ?>
                            </div>
                        </div>
                    </div>
                </article>
            </li>
            <?php
        }
        wp_reset_postdata();
    } else {
        ?>
        <li class="text-danger">
            <article class="hentry post">
                <?php esc_html_e( 'Sorry, no posts matched you criteria.', 'olympus' ) ?>
            </article>
        </li>
        <?php
    }
    ?>
</ul>

<?php
olympus_render( $after_widget );

