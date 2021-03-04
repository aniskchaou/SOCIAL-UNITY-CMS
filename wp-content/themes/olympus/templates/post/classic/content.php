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

$categories_show = 'yes' === olympus_akg( 'blog_post_categories', $post_elements, 'yes' ) ? 'yes' : false;
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'ui-block' ); ?>>

    <header class="entry-header">

        <?php
        if ( 'yes' === olympus_akg( 'blog_post_meta', $post_elements, 'yes' ) ) {
            olympus_post_meta_extended( $categories_show );
        }
        ?>

        <?php the_title( '<h2 class="entry-title post-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' ); ?>
    </header><!-- .entry-header -->

    <?php if ( has_post_thumbnail() ) { ?>
        <div class="post-thumb d-inline-block">
            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'large' ); ?></a>
        </div>
    <?php } ?>

    <div class="entry-content post-content clearfix">
        <?php
        if ( 'yes' === olympus_akg( 'blog_post_excerpt/value', $post_elements, 'yes' ) ) {
            if ( !has_excerpt() ) {
                the_content( sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                esc_html__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'olympus' ), array(
                    'span' => array(
                        'class' => array(),
                    ),
                )
                ), get_the_title()
                ) );
            } else {
                the_excerpt();
            }
        }
        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'olympus' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .entry-content -->
    <div class="entry-footer post-additional-info inline-items">

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
</article><!-- #post-<?php the_ID(); ?> -->
