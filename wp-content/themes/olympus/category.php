<?php
$olympus             = Olympus_Options::get_instance();
$post_style          = $olympus->get_option_final( 'blog_style', 'classic', array('final-source' => 'customizer') );
$layout              = olympus_sidebar_conf( $post_style === 'list' ? true : false );
$main_class          = 'full' !== $layout[ 'position' ] ? 'site-main content-main-sidebar' : 'site-main content-main-full';
$container_width     = 'container';
$ajax_blog_panel     = olympus_get_post_sort_panel();
?>

<?php get_header(); ?>

<div id="primary" class="<?php echo esc_attr( $container_width ) ?>">
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <?php olympus_render( $ajax_blog_panel ); ?>
        </div>
    </div>
    <div class="row primary-content-wrapper">
        <div class="<?php echo esc_attr( $layout[ 'content-classes' ] ) ?>">
            <main id="main" class="<?php echo esc_attr( $main_class ) ?>">
                <?php $stunning_visibility = $olympus->get_option_final( 'header-stunning-visibility', 'yes' ); ?>

                <?php if ( $stunning_visibility !== 'yes' ) { ?>
                    <div class="container">
                        <div class="row">
                            <h1 class="entry-title"><?php single_cat_title(); ?></h1>
                        </div>
                    </div>
                <?php } ?>

                <?php
                if ( !empty( $ajax_blog_panel ) ) {
                    get_template_part( 'templates/loop/ajax' );
                } else {
                    $panel_components = $olympus->get_option_final( 'blog_sort_panel', 'panel-cats', array('final-source' => 'customizer') );
                    $panel_type       = olympus_akg( 'type', $panel_components, 'hide' );
                    if ( 'hide' !== $panel_type && olympus_theme_categorized_blog() ) {
                        get_template_part( 'templates/common/' . $panel_type );
                    }

                    get_template_part( 'templates/loop/standard' );
                }
                ?>
            </main><!-- #main -->
        </div>
        <?php if ( 'full' !== $layout[ 'position' ] ) { ?>
            <div class="<?php echo esc_attr( $layout[ 'sidebar-classes' ] ) ?>">
                <?php get_sidebar(); ?>
            </div>
        <?php } ?>
    </div><!-- #row -->
</div><!-- #primary -->

<?php get_footer(); ?>