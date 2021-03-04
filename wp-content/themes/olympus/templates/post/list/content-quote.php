<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */

$olympus       = Olympus_Options::get_instance();
$post_elements = $olympus->get_option( '', array() );

$quote_author     = olympus_akg( 'quote_author', $post_elements, '' );
$quote_dopinfo    = olympus_akg( 'quote_dopinfo', $post_elements, '' );
$quote_avatar     = olympus_akg( 'quote_avatar/url', $post_elements, '' );
$quote_overlay_color = olympus_akg( 'overlay_color', $post_elements, '' );


if ( has_post_thumbnail() ) {
	$poster_class       = 'custom-bg';
	$post_thumbnail_id  = get_post_thumbnail_id( get_the_ID() );
	$post_thumbnail_url = wp_get_attachment_url( olympus_resize( $post_thumbnail_id, $img_width, $img_height ) );
	$poster_style       = 'style="background-image:url(' . esc_url( $post_thumbnail_url ) . ');"';
} else {
	$poster_style = '';
	$poster_class = '';
}
$overlay_style = ! empty( $quote_overlay_color ) ? 'style="background-color:' . esc_attr( $quote_overlay_color ) . ';"' : '';

?>
<div class="ui-block">
<article id="post-<?php the_ID(); ?>" <?php post_class('blog-post'); ?>>
    <div class="post-thumb <?php echo esc_attr( $poster_class ); ?>" <?php olympus_render( $poster_style ) // WPCS: XSS OK. ?>>
        <div class="overlay" <?php olympus_render( $overlay_style ) ?>></div>

        <div class="post-content">
            <div class="quote-icon">
				<i class="olympus-icon-Quote-Icon"></i>
            </div>
            <div class="h2 post-title custom-color"><?php echo get_the_content(); ?></div>
            <div class="post__author author vcard">
				<?php if ( ! empty( $quote_avatar ) ) {
					echo '<div class="testimonial-img-author">';
					echo olympus_html_tag( 'img', array(
						'src' => olympus_resize( $quote_avatar, 80, 80, false ),
						'alt' => $quote_author
					), false );
					echo '</div>';
				} ?>
				<?php if ( ! empty( $quote_author ) ) { ?>
                    <span class="h6 post__author-name fn"><?php echo esc_html( $quote_author ) ?></span>
				<?php }
				if ( ! empty( $quote_dopinfo ) ) { ?>
                    <div class="author-prof"><?php echo esc_html( $quote_dopinfo ) ?></div>
				<?php } ?>
            </div>
        </div>
    </div>
</article><!-- #post-<?php the_ID(); ?> -->
</div>