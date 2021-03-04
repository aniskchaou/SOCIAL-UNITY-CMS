<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @property string pagination
 *
 * @package olympus
 */
$olympus    = Olympus_Options::get_instance();
$post_style = $olympus->get_option_final( 'blog_style', 'classic', array('final-source' => 'customizer') );

set_query_var( 'sidebar-conf', olympus_sidebar_conf() );

if ( $post_style === 'masonry' ) {
    wp_enqueue_script( 'isotope' );
    $wrapper_atts = array( 'class' => 'sorting-container row', 'data-layout' => 'masonry' );
} elseif ( $post_style === 'grid' ) {
    $wrapper_atts = array( 'class' => 'row' );
} else {
    $wrapper_atts = array( 'class' => 'post-list' );
}
?>

<div <?php echo olympus_attr_to_html( $wrapper_atts ); ?>>

    <?php olympus_query_posts(); ?>

    <?php if ( have_posts() ) { ?>
            <?php
            while ( have_posts() ) : the_post();
                get_template_part( 'templates/post/' . $post_style . '/content', get_post_format() );
            endwhile;
            ?>
    <?php } else { ?>
        <div class="ui-block">
            <div class="ui-block-content">
                <?php get_template_part( 'templates/content/content-none' ) ?>
            </div>
        </div>
    <?php } ?>
</div>
<?php
global $wp_query;
olympus_paging_nav( $wp_query );
wp_reset_query();
?>