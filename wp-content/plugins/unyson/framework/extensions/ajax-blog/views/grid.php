<?php
set_query_var('sidebar-conf', $sidebarConf);
if ( $query->have_posts() ) {
    while ( $query->have_posts() ) {
        $query->the_post();
        $format = get_post_format();
        get_template_part( $templatePart,  $format );
    }
    wp_reset_postdata();
} else {
    get_template_part( 'templates/content/content-search-none' );
}

