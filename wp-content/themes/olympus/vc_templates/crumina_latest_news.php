<?php
$wrapper_attributes = array();

$css      = $id       = $class    = $count    = $category = '';

extract( $atts );

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'crumina-latest-news', 'wpb_content_element' );

$wrapper_attributes[] = 'class=" ' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}

$posts_attrs = array(
    'orderby' => 'date',
    'order'   => 'DESC',
);

$count = (int) $count;
if ( $count ) {
    $posts_attrs[ 'numberposts' ] = $count;
}

$category = (int) $category;
if ( $category ) {
    $posts_attrs[ 'tax_query' ] = array(
        array(
            'taxonomy'         => 'category',
            'field'            => 'term_id',
            'terms'            => $category,
            'include_children' => false
        )
    );
}

$posts = get_posts( $posts_attrs );
?>

<?php if ( $posts ) { ?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="row post-list">
            <?php
            $img_width  = 403;
            $img_height = 290;
            foreach ( $posts as $item ) {
                global $post;
                $post = $item;
                setup_postdata( $post );
                ?>
                <div class="col col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                    <div class="ui-block">
                        <article class="hentry blog-post ajax-post-full-height">
                            <div class="post-thumb">
                                <?php olympus_generate_thumbnail( $img_width, $img_height, true ); ?>
                            </div>
                            <div class="post-content">
                                <?php echo olympus_post_category_list( get_the_ID(), ' ', true ); ?>

	                            <?php the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="h4 entry-title post-title">', '</a>' ); ?>

                                <?php echo olympus_html_tag( 'p', array(), olympus_generate_short_excerpt( get_the_ID(), 12, false ) ); ?>

                                <div class="author-date">
                                    <?php esc_html_e( 'by', 'olympus' ); ?> <?php olympus_post_author( false ); ?>
                                    - <?php olympus_posted_time(); ?>
                                </div>

                            </div>
							<div class="post-additional-info inline-items">
		                        <?php echo olympus_get_post_reactions( 'compact' ); ?>
								<div class="comments-shared">
			                        <?php olympus_comments_count(); ?>
								</div>
							</div>
                        </article>
                    </div>
                </div>
                <?php
            }

            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php
}?>