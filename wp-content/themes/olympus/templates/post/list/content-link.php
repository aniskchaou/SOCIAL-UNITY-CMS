<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */

$content    = get_the_content();
$post_url   = get_url_in_content( $content );
$post_url   = $post_url ? $post_url : '#';
$link_parts = wp_parse_args( parse_url( $post_url ), array(
    'host' => ''
) );

?>
<div class="ui-block">
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>

    <div class="post-thumb bg-link" style="background-image: url(<?php echo esc_attr( get_the_post_thumbnail_url(get_the_ID(), 'large')) ?>)">

        <div class="overlay overlay-dark"></div>

        <div class="post-content">
	        <?php the_title( '<a class="h2 post-title" href="' . esc_url( $post_url  ) . '" rel="nofollow"  target="_blank">', '</a>' ); ?>

            <a href="<?php echo esc_url( $post_url ); ?>" class="site-link" rel="nofollow" target="_blank"><?php echo esc_html($link_parts['host']); ?></a>
            <a href="<?php echo esc_url( $post_url ); ?>" class="post-link" rel="nofollow"  target="_blank">
	            <?php echo olympus_icon_font( 'olympus-icon-Link-Icon' ); ?>
            </a>

        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
</div>