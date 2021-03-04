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
$olympus       = Olympus_Options::get_instance();
$ajax_blog_obj = Olympus_Core::get_extension( 'ajax-blog' );

if ( !$ajax_blog_obj ) {
    return;
}

$post_style = $olympus->get_option( 'blog_style', 'classic', $olympus::SOURCE_CUSTOMIZER );

$wrapper_extra_atts = array();

$wrapper_atts = array(
    'data-nonce' => wp_create_nonce( '_crumina_ajax_blog' ),
);

$preloader = $olympus->getOptionCustomizer( 'blog_sort_panel_preloader' );
$preloader = fw_akg( 'url', $preloader, $ajax_blog_obj->locate_URI( '/static/img' ) . '/spinner.gif' );

$wrapper_atts[ 'id' ]    = $ajax_blog_obj::GRID_ID;
$wrapper_atts[ 'style' ] = "background: url({$preloader}) center top / 100px no-repeat; padding-top: 100px;";

if ( $post_style === 'masonry' ) {
    $wrapper_extra_atts = array( 'class' => 'sorting-container row post-list', 'data-layout' => 'masonry' );
} else if ( $post_style === 'grid' ) {
    $wrapper_extra_atts = array( 'class' => 'row post-list' );
} else {
    $wrapper_extra_atts = array( 'class' => 'post-list' );
}

$wrapper_atts = array_merge( $wrapper_atts, $wrapper_extra_atts );
?>

<div <?php echo olympus_attr_to_html( $wrapper_atts ); ?>></div>