<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
	$stunning_visibility = olympus_stunning_visibility();
	if ( $stunning_visibility !== 'yes' && ! olympus_is_composer() ) {
		the_title( '<h1 class="entry-title">', '</h1>' );
	} ?>

    <div class="page-content">
        <?php
        the_content();

        wp_link_pages( array(
            'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'olympus' ),
            'after'  => '</div>',
        ) );
        ?>
    </div><!-- .entry-content -->

    <?php if ( get_edit_post_link() ) : ?>
        <footer class="entry-footer">
            <?php
            edit_post_link(
                    sprintf(
                            wp_kses(
                                    /* translators: %s: Name of current post. Only visible to screen readers */
                                    __( 'Edit <span class="screen-reader-text">%s</span>', 'olympus' ),
                                    array(
                                        'span' => array(
                                            'class' => array(),
                                        ),
                                    )
                            ),
                            get_the_title()
                    ),
                    '<span class="edit-link">',
                    '</span>'
            );
            ?>
        </footer><!-- .entry-footer -->
    <?php endif; ?>
</article><!-- #apge-<?php the_ID(); ?> -->
