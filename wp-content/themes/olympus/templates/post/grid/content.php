<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */
$layout = get_query_var( 'sidebar-conf', array( 'position' => 'full' ) );

$column_class	 = olympus_get_column_classes( $layout );
$column_class[]	 = 'may-contain-custom-bg';

$olympus		 = Olympus_Options::get_instance();
$post_elements	 = $olympus->get_option_final( 'blog_post_elements', array(), array('final-source' => 'customizer') );

$img_height_width = apply_filters( 'olympus_blog_loop_features_images', array());
$img_width	 = $img_height_width['width'];
$img_height	 = $img_height_width['height'];

?>

<div class="<?php echo esc_attr( implode( ' ', $column_class ) ) ?>">
    <div class="ui-block ajax-col-equal-height">
        <!-- Post -->
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'blog-post ajax-post-full-height' ); ?>>
            <div class="post-thumb">
                <a href="<?php the_permalink(); ?>"><?php olympus_generate_thumbnail( $img_width, $img_height, true ); ?></a>
            </div>
            <div class="post-content">
				<?php
				if ( 'yes' === olympus_akg( 'blog_post_categories', $post_elements, 'yes' ) ) {
					echo olympus_post_category_list( get_the_ID(), ' ', true );
				}
				?>

				<?php the_title( '<h4 class="post-title entry-title"><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h4>' ); ?>

				<?php
				if ( 'yes' === olympus_akg( 'blog_post_excerpt/value', $post_elements, 'yes' ) ) {
					$excerpt_length = olympus_akg( 'blog_post_excerpt/yes/length', $post_elements, '20' );
					echo olympus_html_tag( 'p', array(), olympus_generate_short_excerpt( get_the_ID(), $excerpt_length, false ) );
				}
				?>
				<?php
				wp_link_pages( array(
					'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'olympus' ),
					'after'	 => '</div>',
				) );
				?>

				<?php if ( 'yes' === olympus_akg( 'blog_post_meta', $post_elements, 'yes' ) ) { ?>
					<div class="author-date">
						<?php esc_html_e( 'by', 'olympus' ); ?> <?php olympus_post_author( false ); ?>
						- <?php olympus_posted_time(); ?>
					</div>
				<?php } ?>
            </div>
			<div class="post-additional-info inline-items">
		        <?php
		        if ( 'yes' === olympus_akg( 'blog_post_reactions', $post_elements, 'yes' ) ) {
			        echo olympus_get_post_reactions( 'compact' );
		        }
		        ?>
				<div class="comments-shared">
			        <?php olympus_comments_count(); ?>
					<?php
					if ( function_exists( 'crumina_blog_post_share_btns' ) ) {
						crumina_blog_post_share_btns( get_the_ID() );
					}
					?>
				</div>
			</div>
        </article><!-- #post-<?php the_ID(); ?> -->
    </div>
</div>
