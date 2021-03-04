<?php
/**
 * @var object $ext
 * @var array $atts
 */

$query = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
$search_type = filter_input( INPUT_GET, 'search_type', FILTER_SANITIZE_STRING );
$cur_page = filter_input( INPUT_GET, 'spage', FILTER_SANITIZE_NUMBER_INT );
$cur_page = isset($cur_page) ? $cur_page : 1;
$posts_per_page = get_option( 'posts_per_page' );
$post_cur_page = $cur_page;
if($search_type == ''){
    $post_cur_page = 1;
}

$search_types = array();

$args_all = array(
    'posts_per_page'  => -1,
    'post_type'		 => 'any',
    'post_status'	 => 'publish',
    's'				 => $query
);
$the_all_query = new WP_Query( $args_all );
$posts_all_types = $the_all_query->get_posts();

$post_type_obj	 = array();
$post_types		 = array();
$post_type_ids = array();
if ( !empty( $posts_all_types ) ) {
    foreach ( $posts_all_types as $post ) {
        $post_types[ $post->post_type ][] = $post;
        $post_type_ids[ $post->post_type ][] = $post->ID;
        if( isset($search_types[$post->post_type]['post_found']) ){
            $search_types[$post->post_type]['post_found'] = intval($search_types[$post->post_type]['post_found']) + 1;
        } else {
            $search_types[$post->post_type]['post_found'] = 1;
        }
        if ( empty( $post_type_obj[ $post->post_type ] ) ) {
            $post_type_obj[ $post->post_type ] = get_post_type_object( $post->post_type );
        }
    }

    foreach ( $post_types as $ptype => $post_type ) {
        if ( isset( $post_type_obj[ $ptype ]->label ) ) {
            $search_types[$ptype]['name'] = $post_type_obj[ $ptype ]->label;
        }
    }
}

$all_post_types_query = $user_query = $group_query = $bbp_topics_args = $the_product_query = array();

if ( class_exists( 'BuddyPress' ) && class_exists( 'Youzer' ) ) {
    $user_cur_page = $cur_page;
    if($search_type != '' && $search_type != 'user'){
        $user_cur_page = 1;
    }
    $search_types[ 'user' ] = array('name' => esc_html__( 'Users', 'crum-ext-extended-search' ));

    $members = array('total' => 0);
    if ( function_exists( 'bp_is_active' ) ) {
        $members = bp_core_get_users( array(
            'per_page'  => $posts_per_page,
	        'page'  => $user_cur_page,
            'search_terms' => esc_attr( $query ),
            'populate_extras' => false 
        ) );
    }
    $user_query = (isset($members['users'])) ? extended_search_user_by_name( $members['users'], esc_attr($query) ) : array();
    $search_types['user']['post_found'] = (int) count($user_query);

    if ( bp_is_active( "groups" ) ) {
        $search_types[ 'group' ] = array('name' => esc_html__( 'Groups', 'crum-ext-extended-search' ));
    }
    $group_cur_page = $cur_page;
    if($search_type != '' && $search_type != 'group'){
        $group_cur_page = 1;
    }
    $groups = array('total' => 0);
    if ( function_exists( 'bp_is_active' ) && bp_is_active( "groups" ) ) {
		$groups = groups_get_groups( array(
            'per_page' => $posts_per_page,
	        'page'  => $group_cur_page,
            'search_terms' => esc_attr( $query ), 
            'populate_extras' => false 
        ) );
    }
    $group_query = (isset($groups['groups'])) ? extended_search_group_by_name( $groups['groups'], esc_attr($query) ) : array();
    $search_types['group']['post_found'] = (int) count($group_query);
}

if ( class_exists( 'bbPress' ) ) {
    $forum_cur_page = $cur_page;
    if($search_type != '' && $search_type != 'forum'){
        $forum_cur_page = 1;
    }
    $search_types[ 'forum' ] = array('name' => esc_html__( 'Forums', 'crum-ext-extended-search' ));
    $bbp_topics_args = array(
        'posts_per_page' => $posts_per_page,
        'paged'			 => $forum_cur_page,
        's'				 => $query,
    );

    $bbp_has_topics = bbp_has_topics( $bbp_topics_args );
    $search_types['forum']['post_found'] = (int) $bbp_has_topics;
}
$default_type = 'post';
foreach ( $search_types as $key => $type ) {
    if(isset($search_types[$key]['post_found']) && $search_types[$key]['post_found'] != 0){
        $default_type = $key;
        break;
    }
}
$curent_type = array_key_exists( $search_type, $search_types ) ? $search_type : $default_type;

$olympus = Olympus_Options::get_instance();
$visibility = $olympus->get_option( "header-stunning-visibility", 'yes', $olympus::SOURCE_CUSTOMIZER );
if( $visibility !== 'yes' ){ ?>
<section class="search-page-panel simple-serach">
	<div class="container">
		<div class="row">
		    <div class="col col-xl-10 m-auto col-lg-10 col-md-12 col-sm-12 col-12">
				<form class="form-inline search-form" action="<?php echo esc_url( home_url() ); ?>" method="GET">
					<div class="form-group label-floating">
						<label class="control-label" for="s"><?php esc_html_e( 'What do you search?', 'crum-ext-extended-search' ); ?></label>
					    <input class="form-control bg-white" name="s" type="text" value="<?php echo esc_attr( $query ); ?>">
					</div>

					<button class="btn btn-purple btn-lg" type="submit"><?php esc_html_e( 'Search', 'crum-ext-extended-search' ); ?></button>
				</form>
			</div>
		</div>
	</div>
</section>
<?php } ?>

<section class="primary-content-wrapper">
	<div class="container">
		<ul class="cat-list-bg-style align-center sorting-menu">
            <?php
                foreach ( $search_types as $key => $type ) {
                $search_type_link = add_query_arg(array('s' => $query, 'search_type' => $key), home_url( '/' ));
                if(isset($search_types[$key]['post_found']) && $search_types[$key]['post_found'] != 0){
            ?>
                <li class="cat-list__item <?php if($curent_type == $key){echo 'active';} ?>">
                    <a href="<?php echo $search_type_link; ?>"><?php echo esc_html( $type['name'] ); ?></a>
                </li>
            <?php } } ?>
        </ul>
    </div>
</section>

<section class="primary-content-wrapper">
	<div class="container">
		<div class="row">
			<div class="col col-xl-10 m-auto col-lg-10 col-md-12 col-sm-12 col-12">
                <?php 
                    if ( $query ) {
                    $max_pages = 1;
                    $found_posts = (isset($search_types[$curent_type]['post_found'])) ? $search_types[$curent_type]['post_found'] : 0;
                    if($curent_type == 'user'){
                        echo $ext->get_view( "types/user", array(
                            'found_posts' => $found_posts,
                            'the_query' => $user_query,
                            'query' => esc_attr($query)
                        ) );
                        $max_pages = ceil( (int) $found_posts / (int) $posts_per_page );
                    }elseif($curent_type == 'group'){
                        echo $ext->get_view( "types/group", array(
                            'found_posts' => $found_posts,
                            'the_query' => $group_query,
                            'query' => esc_attr($query)
                        ) );
                        $max_pages = ceil( (int) $found_posts / (int) $posts_per_page );
                    }elseif($curent_type == 'forum'){
                        echo $ext->get_view( "types/forum", array(
                            'found_posts' => $found_posts,
                            'the_query' => $bbp_topics_args
                        ) );
                        $bbp = bbpress();
                        $found_topics = (int) isset( $bbp->topic_query->found_posts ) ? $bbp->topic_query->found_posts : $bbp->topic_query->post_count;
                        $max_pages = ceil( (int) $found_topics / (int) $posts_per_page );
                    }else{
                        $post_types_ids_arr = (isset($post_type_ids[$curent_type])) ? $post_type_ids[$curent_type] : array();
                        $args_all_types = array(
                            'per_page'  => $posts_per_page,
                            'post_type' => $curent_type,
                            'paged'	=> $post_cur_page,
                            'post__in' => $post_types_ids_arr
                        );
                        if( $curent_type == 'tribe_events' ){
                            $args_all_types['eventDisplay'] = 'custom';
                            $args_all_types['posts_per_page'] = $posts_per_page;
                        }
                        $all_q = new WP_Query( $args_all_types );
                        echo $ext->get_view( "types/any", array(
                            'found_posts' => $found_posts,
                            'the_query' => $all_q
                        ) );
                        $max_pages = ceil( (int) $found_posts / (int) $posts_per_page );
                    }
                    echo $ext->get_view( 'paginate', array(
                        'ext'			 => $ext,
                        'atts'			 => $atts,
                        'posts_per_page' => $posts_per_page,
                        'cur_page'		 => $cur_page,
                        'search_type'    => $curent_type,
                        'max_num_pages'	 => $max_pages
                    ) );
                ?>

                <?php }else{ ?>
                    <h2 class="search-help-result-title text-danger"><?php esc_html_e( 'Search query is empty!', 'crum-ext-extended-search' ); ?></h2>
                <?php } ?>
            </div>
        </div>
    </div>
</section> 