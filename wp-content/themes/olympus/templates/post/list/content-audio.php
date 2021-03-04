<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
$olympus       = Olympus_Options::get_instance();
$post_elements = $olympus->get_option_final( 'blog_post_elements', array(), array('final-source' => 'customizer') );

$oembed = $olympus->get_option( 'audio_oembed', '', Olympus_Options::SOURCE_POST );

$img_width  = 415;
$img_height = 300;
?>

<div class="ui-block">
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post blog-post-v3' ); ?>>
        <div class="post-thumb">

            <?php
            $oembed     = wp_oembed_get( $oembed, array( 'width' => $img_width, 'height' => $img_height ) );
            if ( $oembed ) {
                olympus_render( $oembed );
            } else {
                ?>
                <p class="text-danger">
                    <?php esc_html_e( 'Not found', 'olympus' ); ?>
                </p>
                <?php
            }

            if ( 'yes' === olympus_akg( 'blog_post_categories', $post_elements, 'yes' ) ) {
                echo olympus_post_category_list( get_the_ID(), ' ', true );
            }
            ?>

        </div>
        <div class="post-content">

            <?php if ( 'yes' === olympus_akg( 'blog_post_meta', $post_elements, 'yes' ) ) { ?>
                <div class="author-date">
                    <?php esc_html_e( 'by', 'olympus' ); ?> <?php olympus_post_author( 30 ); ?>
                    - <?php olympus_posted_time(); ?>
                </div>
            <?php } ?>

            <?php the_title( '<h3 class="post-title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h3>' ); ?>

            <?php
            if ( 'yes' === olympus_akg( 'blog_post_excerpt/value', $post_elements, 'yes' ) ) {
                $excerpt_length = olympus_akg( 'blog_post_excerpt/yes/length', $post_elements, '20' );
                echo olympus_html_tag( 'p', array(), olympus_generate_short_excerpt( get_the_ID(), $excerpt_length, false ) );
            }
            ?>
            <?php
            wp_link_pages( array(
                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'olympus' ),
                'after'  => '</div>',
            ) );
            ?>
            <div class="post-additional-info inline-items">
                <?php
                if ( 'yes' === olympus_akg( 'blog_post_reactions', $post_elements, 'yes' ) ) {
                    echo olympus_get_post_reactions( 'compact' );
                }
                ?>
                <div class="comments-shared">
                    <?php olympus_comments_count(); ?>
                    <?php
                    if ( function_exists( 'crumina_blog_post_share_btns' ) ) {
                        crumina_blog_post_share_btns( get_the_ID() );
                    }
                    ?>
                </div>
            </div>
        </div>
    </article><!-- #post-<?php the_ID(); ?> -->
</div>