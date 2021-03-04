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
the_post();

$layout     = olympus_sidebar_conf();
$main_class = 'full' !== $layout[ 'position' ] ? 'site-main content-main-sidebar' : 'site-main content-main-full';

//Check if use js composer
if ( get_post_meta( get_the_ID(), '_wpb_vc_js_status', true ) ) {
    $prim_classes = '';
    $wrap_classes = 'primary-content-wrapper';
} else {
    $prim_classes = 'container';
    $wrap_classes = 'row primary-content-wrapper';
}
?>
<div id="primary" class="<?php echo esc_attr( $prim_classes ); ?>">
    <div class="<?php echo esc_attr( $wrap_classes ); ?>">
        <main id="main" class="<?php echo esc_attr( $layout[ 'content-classes' ] ) ?>">
            <?php
            $olympus             = Olympus_Options::get_instance();
            $stunning_visibility = $olympus->get_option_final( 'header-stunning-visibility', 'yes' );
            ?>

            <?php if ( $stunning_visibility !== 'yes' ) { ?>
                <div class="container">
                    <div class="row">
                        <h1 class="entry-title mt-3 mb-3"><?php the_title(); ?></h1>
                    </div>
                </div>
            <?php } ?>
            <?php
            if ( class_exists( 'FW_Ext_Crumina_Contact_Form' ) ) {
                echo FW_Ext_Crumina_Contact_Form::contact_form_html( array(), get_the_ID() );
            } else {
                ?>
                <h2 class="text-danger sorting-item"><?php esc_html_e( 'Contact form extension is required!', 'olympus' ); ?></h2>
                <?php
            }
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
