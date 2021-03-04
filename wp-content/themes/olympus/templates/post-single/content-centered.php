<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
the_post();

$container_width = 'container';
$padding_class   = '';

$olympus = Olympus_Options::get_instance();

$single_related_show_default = array(
    'show' => 'yes',
    'yes'  => array(
        'meta'    => 'yes',
        'excerpt' => 'yes'
    )
);

$single_reaction_show_default = array(
    'show' => 'yes',
    'yes'  => array(
        'type'   => 'without-counter',
        'design' => 'colored'
    )
);

$single_post_elements = $olympus->get_option_final( 'single_post_elements', array() );
if ( olympus_akg( 'customize', $single_post_elements, 'no' ) === 'yes' ) {
    $single_meta_show     = olympus_akg( 'yes/single_post_elements_popup/single_meta_show', $single_post_elements, 'yes' );
    $single_featured_show = olympus_akg( 'yes/single_post_elements_popup/single_featured_show', $single_post_elements, 'yes' );
    $single_share_show    = olympus_akg( 'yes/single_post_elements_popup/single_share_show', $single_post_elements, 'yes' );
    $single_related_show  = olympus_akg( 'yes/single_post_elements_popup/single_related_show', $single_post_elements, $single_related_show_default );
    $single_reaction_show = olympus_akg( 'yes/single_post_elements_popup/single_reaction_show', $single_post_elements, $single_reaction_show_default );
} else {
    $single_meta_show     = $olympus->get_option( 'single_meta_show', 'yes', $olympus::SOURCE_CUSTOMIZER );
    $single_featured_show = $olympus->get_option( 'single_featured_show', 'yes', $olympus::SOURCE_CUSTOMIZER );
    $single_share_show    = $olympus->get_option( 'single_share_show', 'yes', $olympus::SOURCE_CUSTOMIZER );
    $single_related_show  = $olympus->get_option( 'single_related_show', $single_related_show_default, $olympus::SOURCE_CUSTOMIZER );
    $single_reaction_show = $olympus->get_option( 'single_reaction_show', $single_reaction_show_default, $olympus::SOURCE_CUSTOMIZER );
}

$stunning_visibility = olympus_stunning_visibility();
?>
<div id="primary" class="<?php echo esc_attr( $container_width ) ?>">
    <div class="row <?php echo esc_attr( $padding_class ) ?>">
        <main id="main" class="col-xl-8 m-auto col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="ui-block single-post-v2-wrap">
                <article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry blog-post single-post-olympus single-post-v2' ); ?>>

                    <?php
                    if ( 'yes' === $single_featured_show ) {
                        echo olympus_generate_thumbnail_bpt();
                    }
                    ?>

                    <?php
                    if ( 'yes' !== $stunning_visibility ) {
                        the_title( '<h1 class="entry-title post-title">', '</h1>' );
                    }
                    ?>

                    <?php echo olympus_post_category_list( get_the_ID(), ' ', true ); ?>

                    <?php if ( 'yes' === $single_meta_show ) { ?>
                        <div class="single-post-additional inline-items">
                            <div class="post__author author vcard inline-items">
                                <?php $author_id = get_the_author_meta( 'ID' ); ?>
                                <?php echo get_avatar( $author_id, 40 ); ?>
                                <div class="author-date not-uppercase">
                                    <a class="h6 post__author-name fn"
                                       href="<?php echo get_author_posts_url( $author_id ); ?>">
                                           <?php the_author_meta( 'display_name', $author_id ); ?>
                                    </a>
                                    <?php if ( get_the_author_meta( 'user_profession', $author_id ) ) { ?>
                                        <div class="author_prof">
                                            <?php the_author_meta( 'user_profession', $author_id ); ?>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="post-date-wrap inline-items">
                                <?php echo olympus_icon_font( 'olympus-icon-Small-Calendar-Icon' ); ?>
                                <div class="post-date">
                                    <?php olympus_posted_time(); ?>
                                    <span><?php esc_html_e( 'Date', 'olympus' ) ?></span>
                                </div>
                            </div>
                            <div class="post-comments-wrap inline-items">
                                <?php echo olympus_icon_font( 'olympus-icon-Comment-Icon olymp-comments-post-icon' ); ?>
                                <div class="post-comments">
                                    <?php olympus_comments_count( false ); ?>
                                    <span><?php esc_html_e( 'Comments', 'olympus' ) ?></span>
                                </div>
                            </div>
                        </div>
                    <?php } ?>

                    <div class="post-content-wrap">
                        <div class="post-content">
                            <?php
                            the_content();

                            wp_link_pages( array(
                                'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'olympus' ),
                                'after'  => '</div>',
                            ) );
                            ?>

                            <div class="tags-list"><?php the_tags(); ?></div>
                        </div>
                    </div>

                    <?php
                    if ( 'yes' === $single_reaction_show[ 'show' ] ) {
                        switch ( $single_reaction_show[ 'yes' ][ 'type' ] ) {
                            case 'with-counter':
                                $reactions_type = 'all';
                                break;
                            default:
                                $reactions_type = 'plain';
                                break;
                        }

                        switch ( $single_reaction_show[ 'yes' ][ 'design' ] ) {
                            case 'colored':
                                $reactions_classes = 'choose-reaction inline reaction-colored';
                                break;
                            default:
                                $reactions_classes = 'choose-reaction inline';
                                break;
                        }

                        $reactions_html = olympus_get_post_reactions( $reactions_type );
                        if ( $reactions_html ) {
                            ?>
                            <div class="clearfix"></div>
                            <div class="<?php echo esc_attr( $reactions_classes ); ?>">
                                <div class="title"><?php echo
									sprintf( '%s <span>%s</span>',
										esc_html__( 'Choose your', 'olympus' ),
										esc_html__( 'Reaction!', 'olympus' ) );
									?></div>

                                <?php olympus_render( $reactions_html ); ?>
                            </div>
                            <?php
                        }
                    }
                    ?>

                    <?php if ( 'yes' === $single_share_show && function_exists( 'crumina_single_post_share_btns' ) ) { ?>
                        <div class="socials-shared">
                            <?php crumina_single_post_share_btns( 'rectangle' ) ?>
                        </div>
                    <?php } ?>
                </article><!-- #post-<?php the_ID(); ?> -->

                <?php
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template( $file = '/blog-comments.php' );
                endif;
                ?>
            </div>
            <?php if ( 'yes' === $single_related_show[ 'show' ] ) { ?>
                <div class="related-posts mt60">
                    <?php get_template_part( 'templates/common/related', 'posts' ); ?>
                </div>
            <?php } ?>

        </main><!-- #main -->
    </div>
</div><!-- #primary -->