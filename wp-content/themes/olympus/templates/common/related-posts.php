<?php
$olympus = Olympus_Options::get_instance();

$number_posts = $olympus->get_option_final( 'single_post_style', 'classic', array('final-source' => 'customizer') ) === 'classic' ? 3 : 2;

$single_related_show_default = array(
    'show' => 'yes',
    'yes'  => array(
        'meta'    => 'yes',
        'excerpt' => 'yes'
    )
);

$single_post_elements = $olympus->get_option_final( 'single_post_elements', array() );
if ( olympus_akg( 'customize', $single_post_elements, 'no' ) === 'yes' ) {
    $single_related_show = olympus_akg( 'yes/single_post_elements_popup/single_related_show', $single_post_elements, $single_related_show_default );
} else {
    $single_related_show = $olympus->get_option( 'single_related_show', $single_related_show_default, $olympus::SOURCE_CUSTOMIZER );
}

$the_query = olympus_get_related_posts( 'post_tag', 'category', $number_posts );

$img_width  = 403;
$img_height = 290;

if ( 3 === $number_posts ) {
    $column_class = 'col-xl-4 col-lg-4 col-md-6';
} else {
    $column_class = 'col-xl-6 col-lg-6 col-md-6';
}

if ( $the_query ) {
    ?>

    <!-- Related posts Section-->

    <div class="related-posts">

        <div class="crumina-module crumina-heading with-title-decoration">
            <h5 class="heading-title"><?php esc_html_e( 'Related Articles', 'olympus' ) ?></h5>
        </div>

        <div class="row">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
                <div class="<?php echo esc_attr( $column_class ) ?>">
                    <div class="ui-block">
                        <article class="hentry blog-post ajax-post-full-height">
                            <div class="post-thumb">
                                <?php olympus_generate_thumbnail( $img_width, $img_height, true ); ?>
                            </div>
                            <div class="post-content">
                                <?php echo olympus_post_category_list( get_the_ID(), ' ', true ); ?>

                                <?php the_title( '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark" class="h4 entry-title post-title">', '</a>' ); ?>

                                <?php
                                if ( 'yes' === $single_related_show[ 'yes' ][ 'excerpt' ] ) {
                                    echo olympus_html_tag( 'p', array(), olympus_generate_short_excerpt( get_the_ID(), 12, false ) );
                                }
                                ?>

                                    <?php if ( 'yes' === $single_related_show[ 'yes' ][ 'meta' ] ) { ?>
                                    <div class="author-date">
            <?php esc_html_e( 'by', 'olympus' ); ?> <?php olympus_post_author( false ); ?>
                                        - <?php olympus_posted_time(); ?>
                                    </div>
        <?php } ?>

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
            <?php endwhile;
            ?>
        </div>
    </div>
    <!-- End Related posts -->
    <?php
}
wp_reset_postdata();
?>