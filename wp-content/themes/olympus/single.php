<?php
/**
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
get_header();
$layout     = olympus_sidebar_conf();
$main_class = 'full' !== $layout[ 'position' ] ? 'site-main content-main-sidebar' : 'site-main content-main-full';
?>
<div id="primary" class="container">
    <div class="row medium-padding80">
        <main id="main" class="<?php echo esc_attr( $layout[ 'content-classes' ] ) ?>">
            <?php
            while ( have_posts() ) : the_post();
                get_template_part( 'templates/content/content', 'page' );
                // If comments are open or we have at least one comment, load up the comment template.
                if ( comments_open() || get_comments_number() ) :
                    comments_template();
                endif;
            endwhile; // End of the loop.
            ?>
        </main><!-- #main -->
        <?php if ( 'full' !== $layout[ 'position' ] ) { ?>
            <aside class="<?php echo esc_attr( $layout[ 'sidebar-classes' ] ) ?>">
                <?php get_sidebar(); ?>
            </aside>
        <?php } ?>
    </div><!-- #row -->
</div><!-- #primary -->
<?php
get_footer();
