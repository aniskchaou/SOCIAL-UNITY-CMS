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

$img_width  = 415;
$img_height = 300;
?>

<div class="ui-block">
    <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post blog-post-v3' ); ?>>
        <div class="post-thumb crumina-module-slider">
            <?php if ( !empty( $gallery_images ) ) { ?>
                <div class="swiper-container">
                    <div class="swiper-wrapper js-zoom-gallery">
                        <?php foreach ( $gallery_images as $image ) { ?>
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url( olympus_resize( $image[ 'url' ], $img_width, $img_height, true ) ) ?>"
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
            <?php } else if ( has_post_thumbnail() ) { ?>
                <?php olympus_generate_thumbnail( $img_width, $img_height, true ); ?>
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