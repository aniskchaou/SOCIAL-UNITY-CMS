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

$gallery_images = $olympus->get_option( 'gallery_images', array(), Olympus_Options::SOURCE_POST );

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

    <?php if ( !empty( $gallery_images ) ) { ?>
        <div class="post-thumb crumina-module-slider">
            <div class="swiper-container">
                <div class="swiper-wrapper js-zoom-gallery">
                    <?php foreach ( $gallery_images as $image ) { ?>
                        <div class="swiper-slide">
                            <img src="<?php echo esc_url( olympus_resize( $image[ 'url' ], 774, 460, true ) ) ?>"
                                 alt="<?php esc_attr_e( 'Gallery', 'olympus' ); ?>">
                            <a href="<?php echo esc_url( $image[ 'url' ] ); ?>" class="post-type-icon">
                                <?php echo olympus_icon_font( 'olympus-icon-Camera-Icon' ) ?>
                            </a>
                        </div>
                    <?php } ?>
                </div>

                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
            </div>
        </div>

    <?php } else if ( has_post_thumbnail() ) { ?>
        <div class="post-thumb d-inline-block">
            <?php the_post_thumbnail( 'large' ); ?>
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
