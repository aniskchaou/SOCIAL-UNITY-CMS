<div class="clients-grid" id="<?php echo $panel_id; ?>">

    <ul class="cat-list-bg-style align-center categories sorting-menu">
        <?php $cat_id = is_category() ? get_queried_object_id() : 0; ?>
        <li class="cat-list__item <?php echo (!$cat_id) ? 'active' : ''; ?>"><a href="0" data-url="<?php echo esc_url( $page_for_posts_url ); ?>" class=""><?php esc_html_e( 'All Categories', 'crum-ext-ajax-blog' ) ?></a></li>
        
        <?php 
        foreach ( $categories as $category ) { ?>
            <li class="cat-list__item <?php echo ($category->term_id === $cat_id) ? 'active' : ''; ?>"><a data-url="<?php echo esc_attr( get_category_link( $category->term_id ) ); ?>" href="<?php echo esc_attr( $category->term_id ); ?>" class=""><?php echo ($category->name); ?></a></li>
        <?php } ?>

    </ul>

</div>