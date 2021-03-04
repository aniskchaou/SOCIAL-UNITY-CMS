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

//Check if use js composer
$vc_js_status = (string) get_post_meta( get_the_ID(), '_wpb_vc_js_status', true );
$prim_classes = olympus_is_composer() ? 'container-fluid' : 'container';

$olympus = Olympus_Options::get_instance();
$content_width = $olympus->get_option_final( "full-content", 'default', array( 'final-source' => 'current-type' ) );
$global_content_width = $olympus->get_option( "full-content", 'default', $olympus::SOURCE_CUSTOMIZER );
if($content_width == 'default' ) {
    $content_width = $global_content_width;
}

if(isset($content_width) && $content_width == 'container'){
    $prim_classes = 'container';
}
?>
<div id="primary" class="<?php echo esc_attr( $prim_classes ); ?>">
    <div class="row primary-content-wrapper">
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
