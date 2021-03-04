<?php
$olympus            = Olympus_Options::get_instance();
$page_for_posts     = get_option( 'page_for_posts' );
$page_for_posts_url = $page_for_posts ? get_the_permalink( $page_for_posts ) : home_url() . '/';
$cat_id             = is_category() ? get_queried_object_id() : 0;

$cat_args    = array();
$cat_filter	 = $olympus->get_option_final( 'categories', array(), array('final-source' => 'customizer') );
$cat_exclude = $olympus->get_option_final( 'cat_exclude', false, array('final-source' => 'customizer') );

if ( ! empty( $cat_filter ) ) {
	$cat_filter = implode( ',', $cat_filter );

	if ( $cat_exclude ) {
		$cat_args['exclude'] = $cat_filter;
	} else {
		$cat_args['include'] = $cat_filter;
	}
}
$categories = get_categories( $cat_args );
?>
<div class="cat-list-wrap">
    <ul class="cat-list-bg-style align-center categories sorting-menu">
        <li class="cat-list__item <?php echo ( ! $cat_id ) ? 'active' : ''; ?>"><a
                    href="<?php echo esc_url( $page_for_posts_url ); ?>"
                    class=""><?php esc_html_e( 'All categories', 'olympus' ) ?></a></li>

		<?php foreach ( $categories as $category ) { ?>
            <li class="cat-list__item <?php  olympus_render( ( $category->term_id === $cat_id ) ? 'active' : '' ); ?>"><a
                        href="<?php echo esc_attr( get_category_link( $category->term_id ) ); ?>"
                        class=""><?php olympus_render( $category->name ); ?></a></li>
		<?php } ?>
    </ul>
</div>