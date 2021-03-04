<?php
/**
 * Template Name: Landing (No Header & Footer)
 */
$olympus         = Olympus_Options::get_instance();
$post_style      = $olympus->get_option_final( 'blog_style', 'classic', array('final-source' => 'customizer') );
$layout          = olympus_sidebar_conf( $post_style === 'list' ? true : false );
$main_class      = 'full' !== $layout[ 'position' ] ? 'site-main content-main-sidebar' : 'site-main content-main-full';
$container_width = 'container-fluid';
$ajax_blog_panel = olympus_get_post_sort_panel();
?>

<?php get_header( 'landing' ); ?>

<div id="content" class="site-content hfeed site">
    <div id="primary" class="<?php echo esc_attr( $container_width ) ?>">
        <?php while ( have_posts() ) : the_post() ?>
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <?php
                    get_template_part( 'templates/content/content', 'page' );
                    ?>
                </div>
            </div>
            <?php
        endwhile; // End of the loop.
        wp_reset_query();
        ?>
    </div><!-- #primary -->
</div>

<?php get_footer( 'landing' ); ?>