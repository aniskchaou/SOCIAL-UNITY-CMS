<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
the_post();
$layout          = olympus_sidebar_conf();
$main_class      = 'full' !== $layout[ 'position' ] ? 'site-main content-main-sidebar' : 'site-main content-main-full';
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
        <div class="<?php echo esc_attr( $layout[ 'content-classes' ] ) ?>">
            <main id="main" class="<?php echo esc_attr( $main_class ) ?>">
                <div class="ui-block single-post-v3-wrap">
                    <article id="post-<?php the_ID(); ?>" <?php post_class( 'hentry blog-post single-post-olympus single-post-v3' ); ?>>

                        <?php
                        if ( 'yes' === $single_featured_show ) {
                            echo olympus_generate_thumbnail_bpt();
                        }
                        ?>

                        <?php
                        if ( 'yes' !== $stunning_visibility ) {
                            the_title( '<h1 class="post-title entry-title">', '</h1>' );
                        }
                        ?>

                        <div class="categories-and-reactions-wrap">
                            <?php echo olympus_post_category_list( get_the_ID(), ' ', true ); ?>
                            <?php
                            if ( 'yes' === $single_reaction_show[ 'show' ] ) {
                                echo olympus_get_post_reactions( 'used' );
                            }
                            ?>
                        </div>

                        <?php
                        if ( 'yes' === $single_meta_show ) {
                            olympus_post_meta();
                        }
                        ?>

                        <div class="post-content-wrap entry-content">

                            <?php if ( 'yes' === $single_share_show && function_exists( 'crumina_single_post_share_btns' ) ) { ?>
                                <div class="control-block-button post-control-button">
                                    <?php olympus_comments_count() ?>

                                    <?php crumina_single_post_share_btns( 'rounded' ) ?>

                                </div>
                            <?php } ?>

                            <div class="post-content">
                                <?php the_content(); ?>

                                <div class="tags-list"><?php the_tags(); ?></div>
                            </div>


                        </div><!-- .entry-content -->
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
                        <?php
                        wp_link_pages( array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'olympus' ),
                            'after'  => '</div>',
                        ) );
                        ?>
                    </article><!-- #post-<?php the_ID(); ?> -->
                    <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template( $file = '/blog-comments.php' );
                    endif;
                    ?>
                </div>
            </main><!-- #main -->
        </div>
        <?php if ( 'full' !== $layout[ 'position' ] ) { ?>
            <div class="<?php echo esc_attr( $layout[ 'sidebar-classes' ] ) ?>">
                <?php get_sidebar(); ?>
            </div>
        <?php } ?>
    </div><!-- #row -->
</div><!-- #primary -->
<?php if ( 'yes' === $single_related_show[ 'show' ] ) { ?>
    <div class="medium-padding80">
        <div class="container">
            <?php get_template_part( 'templates/common/related', 'posts' ); ?>
        </div>
    </div>
<?php } ?>

