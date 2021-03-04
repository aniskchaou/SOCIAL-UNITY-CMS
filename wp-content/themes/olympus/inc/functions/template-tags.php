<?php
/**
 * Template tags functions.
 * Todo delete this after theme v1.
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}
/**
 * Custom template tags for this theme.
 *
 * TODO: Разобрать єто говнище
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package Olympus
 */

	if ( ! function_exists( 'olympus_get_posted_time' ) ) :
	/**
	 * Formatted Post time string
	 *
	 * @return string
	 */
	function olympus_get_posted_time() {
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';

		$time_string = sprintf( $time_string,
			esc_attr( get_the_date( 'c' ) ),
			esc_html( get_the_date() )
		);
		return $time_string;

	}
endif;

if ( ! function_exists( 'olympus_posted_time' ) ) :
	/**
	 * Formatted Post time string
	 *
	 * @param bool $inline
	 */
	function olympus_posted_time( $inline = true ) {
		$tag = true === $inline ? 'span' : 'div';
		$time_string = olympus_get_posted_time();
		echo olympus_html_tag( $tag, array( 'class' => 'post__date' ), $time_string );
	}
endif;

if ( ! function_exists( 'olympus_post_meta_extended' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
     *
	 * @param string $categories_show
	 */
	function olympus_post_meta_extended( $categories_show = 'yes' ) {
		$author_id = get_the_author_meta( 'ID' );
		$avatar    = get_avatar( $author_id, 40 );
		$content   = olympus_get_post_author( false ) . ' ';

		if ( $categories_show ) {
			$content .= esc_html__( 'published in', 'olympus' ) . ' ';
			$content .= olympus_post_category_list( get_the_ID(), ', ', false, 5, false ) . ' ';
		}

		$content .= olympus_get_posted_time();
		$content = olympus_html_tag( 'div', array( 'class' => 'author-date' ), $content );
		echo olympus_html_tag( 'div', array( 'class' => 'post__author author vcard inline-items' ), $avatar . $content );
	}
endif;
if ( ! function_exists( 'olympus_post_meta' ) ) :
	/**
	 * Prints HTML with meta information for the current post-date/time and author.
     *
	 */
	function olympus_post_meta() { ?>
        <div class="author-date">
            <div class="author-thumb">
				<?php $author_id = get_the_author_meta( 'ID' );
				echo get_avatar( $author_id, 30 ); ?>
            </div>
			<?php esc_html_e( 'by', 'olympus' ); ?>
			<?php olympus_post_author( false ); ?>
            - <?php olympus_posted_time(); ?>
        </div>
	<?php }
endif;

if ( ! function_exists( 'olympus_get_post_author' ) ) :
	/**
	 * Generate html markup for post author display.
	 *
	 * @param bool|int $avatar_size
	 *
	 * @return mixed
	 */
	function olympus_get_post_author( $avatar_size = 30 ) {
		$content ='';
        $author_id = get_the_author_meta( 'ID' );
        
		if ( $avatar_size ) {
			$content .= olympus_html_tag( 'div', array( 'class' => 'author-thumb' ), get_avatar( $author_id, $avatar_size ) );
		}
		$content .= olympus_html_tag( 'a', array(
			'class' => 'h6 post__author-name fn vcard font-weight-bold',
			'href'  => get_author_posts_url( $author_id )
		), get_the_author_meta( 'display_name', $author_id ) );
		return $content;
	}
endif;

if ( ! function_exists( 'olympus_post_author' ) ) :
	/**
	 * Output html markup for post author display.
     *
	 * @param int $avatar_size
	 */
	function olympus_post_author($avatar_size = 30) {
		echo olympus_get_post_author($avatar_size);
	}
endif;

if ( ! function_exists( 'olympus_comments_count' ) ) :
	/**
	 * Generate html markup for post comments count display.
	 *
	 * @param $icon bool Show/Hide  comments icon
	 */
	function olympus_comments_count( $icon = true ) {

		$link       = is_singular() ? '#comments' : get_the_permalink() . '#comments';
		$link_class = true === $icon ? 'post-add-icon inline-items' : 'h6 comments';
		if ( comments_open() && post_type_supports( get_post_type(), 'comments' ) && get_default_comment_status(get_post_type()) == 'open' ){
		?>
        <a href="<?php echo esc_url( $link ) ?>" class="<?php echo esc_attr( $link_class ) ?>">
			<?php if ( true === $icon ) {
				echo olympus_icon_font( 'olympus-icon-Speech-Balloon-Icon' );
			} ?>
            <span><?php echo esc_html( get_comments_number() ) ?></span>
        </a>
		<?php
		}
	}
endif;

if ( ! function_exists( ' olympus_post_category_list' ) ) :
	/**
	 * Prints HTML with meta information for the author.
	 *
	 * @param bool|false $post_id ID Post.
	 * @param string $separator Categories list separator.
	 * @param bool|false $colored Set category labels bg color from category metabox.
	 * @param integer $limit Limit for categories.
	 *
	 * @return string
	 */
	function olympus_post_category_list( $post_id = false, $separator = ' ', $colored = false, $limit = 5, $wrapper = true ) {
		$post_id         = $post_id ? $post_id : get_the_ID();
		$count           = 1;
		$categories_list = '';
		$color           = array( 'class' => '', 'style' => '' );
		$categories      = get_the_category( $post_id );
		foreach ( $categories as $category ) {
			if ( $count < $limit + 1 ) {
				if ( true === $colored ) {
					$color = _olympus_callback_get_cat_color( $category->term_id );
				}
				$categories_list .= '<a href="' . get_category_link( $category->term_id ) . '" class=" ' . $color['class'] . '" style="' . $color['style'] . '" title="' . sprintf( esc_html__( 'All posts from %s', 'olympus' ), $category->name ) . '">' . $category->name . '</a>';
				if ( count( $categories ) !== $count ) {
					$categories_list .= $separator;
				}
				$count ++;
			}
		}
        
        if ( $wrapper ) {
            return '<div class="post-category-list">' . $categories_list . '</div>';
        } else {
            return $categories_list;
        }
    }
endif;

if ( ! function_exists( 'olympus_logo' ) ):
	/**
	 * Returns logotype markup depends on theme options.
	 *
	 */
	function olympus_logo($logo_options, $class = '') {
		$logo_image_style = '';
		$logo_image    = olympus_akg( 'logo_image', $logo_options );
		$logo_image_url = olympus_akg( 'url', $logo_image, get_template_directory_uri(). 'images/logo-colored.png' );
		$logo_retina   = olympus_akg( 'logo_retina',$logo_options, false );
        $logo_title    = olympus_akg( 'logo_title', $logo_options, get_bloginfo( 'name' ) );
        $logo_subtitle = olympus_akg( 'logo_subtitle', $logo_options, get_bloginfo( 'description' ) );
        if ( 'yes' === $logo_retina && ! empty( $logo_image ) ) {
            $logo_id = olympus_akg( 'attachment_id', $logo_image, false );
			$image_atts = wp_get_attachment_metadata( $logo_id );
	        if ( ! empty( $image_atts ) && isset( $image_atts['width'] ) ) {
                $logo_image_style = 'width:' . intval( $image_atts['width'] / 2 ) . 'px; height:' . intval( $image_atts['height'] / 2 ) . 'px;';
            }
		}
?>
<a href="<?php echo esc_url( home_url() ) ?>" class="logo <?php echo esc_attr($class); ?>"  itemprop="url">
	<?php if ( ! empty( $logo_image ) ) {
		echo '<div class="img-wrap">';
		echo olympus_html_tag( 'img', array(
			'src' => esc_url( $logo_image_url ),
			'alt' => $logo_title,
			'style' => $logo_image_style,
			'itemprop'=>'logo'
		), false );
		echo '</div>';
	}
	if ( ! empty( $logo_title ) || ! empty( $logo_subtitle ) ) {
		echo '<div class="title-block">';
		if ( ! empty( $logo_title ) ) {
			echo olympus_html_tag( 'h6', array( 'class' => 'logo-title', 'itemprop'=>'name' ), esc_html( $logo_title ) );// WPCS: XSS ok.
		}
		echo olympus_html_tag( 'div', array( 'class' => 'sub-title' ), esc_html( $logo_subtitle ) );
		echo '</div>';
	} ?>
</a>
<?php
	}
endif;


if ( ! function_exists( 'olympus_comments_post' ) ) :
	/**
	 * olympus List Comments Callback
	 * callback function for wp_list_comments in olympus/comments.php
	 *
	 * @param object $comment Comment object.
	 * @param array $args Arguments for callback.
	 * @param int $depth Max. depth of comments tree.
	 */
	function olympus_comments_post( $comment, $args, $depth ) {
		do_action( 'olympus_comments', $comment, $args, $depth );
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
				?>
                <li <?php comment_class( 'comment-item' ); ?> id="comment-<?php comment_ID(); ?>">
                <div class="comment-content post-content comment">
                    <h5><?php esc_html_e( 'Pingback:', 'olympus' ); ?><?php comment_author_link(); ?> </h5>
					<?php edit_comment_link( esc_html__( 'Edit', 'olympus' ), '<div class="simple-article small"><span>', '</span></div>' ); ?>
                </div>

				<?php
				break;
			default :
				// Proceed with normal comments.
				global $comment_depth;

				if ( '1' === $comment_depth ) {
					$reply_comment = '';
				} else {
					$reply_comment = ' reply-comment';
				} ?>

                <li <?php comment_class( 'comment-item' . $reply_comment ); ?>id="div-comment-<?php comment_ID(); ?>">
				<?php if ( '0' === $comment->comment_approved ) : ?>
                <h5 class="comment-awaiting-moderation"> <?php esc_html_e( 'Your comment is awaiting moderation.', 'olympus' ); ?></h5>
			<?php endif; ?>
                <article <?php comment_class( 'comment-entry comment comments__article' ) ?>
                        id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope
                        itemtype="http://schema.org/Comment">

                    <div class="post__author author vcard inline-items">
	                    <?php echo get_avatar( $comment, 52 ); ?>

                        <div class="author-date">
                            <a class="h6 post__author-name fn" href="<?php comment_author_url(); ?>" itemprop="author">
                                <?php comment_author(); ?>
                            </a>
                            <div class="post__date">
                                <time class="comment-meta-item"
                                      datetime="<?php comment_date( 'Y-m-d' ) ?>T<?php comment_time( 'H:iP' ) ?>"
                                      itemprop="datePublished"><?php printf( _x( '%s ago', '%s = human-readable time difference', 'olympus' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
                            </div>
                        </div>
                    </div>

	                <?php comment_text();

	                comment_reply_link( array_merge( $args, array(
		                'reply_text' => esc_html__( 'Reply', 'olympus' ),
		                'depth'      => $depth,
		                'max_depth'  => $args['max_depth']
	                ) ), $comment, get_post() );

	                edit_comment_link( esc_html__( 'Edit', 'olympus' ), '', '' );
	                ?>

                </article>
                <!-- #comment-## -->
				<?php
				break;
		endswitch; // End comment_type check. ?>
	<?php }
endif;


if ( ! function_exists( 'olympus_comments_style_3' ) ) :
	/**
	 * olympus List Comments Callback
	 * callback function for wp_list_comments in olympus/comments.php
	 *
	 * @param object $comment Comment object.
	 * @param array $args Arguments for callback.
	 * @param int $depth Max. depth of comments tree.
	 */
	function olympus_comments_style_3( $comment, $args, $depth ) {
		do_action( 'olympus_comments', $comment, $args, $depth );
		$GLOBALS['comment'] = $comment;
		switch ( $comment->comment_type ) :
			case 'pingback' :
			case 'trackback' :
				// Display trackbacks differently than normal comments.
				?>
                <li <?php comment_class( 'comment-item' ); ?> id="comment-<?php comment_ID(); ?>">
                <div class="comment-content post-content comment">
                    <h5><?php esc_html_e( 'Pingback:', 'olympus' ); ?><?php comment_author_link(); ?> </h5>
					<?php edit_comment_link( esc_html__( 'Edit', 'olympus' ), '<div class="simple-article small"><span>', '</span></div>' ); ?>
                </div>

				<?php
				break;
			default :
				// Proceed with normal comments.
				global $comment_depth;

				if ( '1' === $comment_depth ) {
					$reply_comment = '';
				} else {
					$reply_comment = ' reply-comment';
				} ?>

                <li <?php comment_class( 'comment-item' . $reply_comment ); ?>id="div-comment-<?php comment_ID(); ?>">
				<?php if ( '0' === $comment->comment_approved ) : ?>
                <h5 class="comment-awaiting-moderation"> <?php esc_html_e( 'Your comment is awaiting moderation.', 'olympus' ); ?></h5>
			<?php endif; ?>
                <article <?php comment_class( 'comment-entry comment comments__article' ) ?>
                        id="comment-<?php comment_ID() ?>" itemprop="comment" itemscope
                        itemtype="http://schema.org/Comment">
                    <div class="post__author-thumb">
						<?php echo get_avatar( $comment, 56 ); ?>
                    </div>

                    <div class="comments-content">

                        <div class="post__author author vcard">

                            <div class="author-date">
                                <a class="h6 post__author-name fn" href="<?php comment_author_url(); ?>"
                                   itemprop="author"><?php comment_author(); ?></a>
                                <div class="post__date">
                                    <time class="comment-meta-item"
                                          datetime="<?php comment_date( 'Y-m-d' ) ?>T<?php comment_time( 'H:iP' ) ?>"
                                          itemprop="datePublished"><?php printf( _x( '%s ago', '%s = human-readable time difference', 'olympus' ), human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) ); ?></time>
                                </div>
                            </div>

                        </div>
                        <div class="comment-content comment">
							<?php comment_text() ?>
                        </div>
						<?php
						comment_reply_link( array_merge( $args, array(
							'reply_text' => esc_html__( 'Reply', 'olympus' ),
							'depth'      => $depth,
							'max_depth'  => $args['max_depth']
						) ), $comment, get_post() );
						edit_comment_link( esc_html__( 'Edit', 'olympus' ), '', '' );
						?>
                    </div>
                </article>
                <!-- #comment-## -->
				<?php
				break;
		endswitch; // End comment_type check. ?>
	<?php }
endif;

if ( ! function_exists( 'olympus_paging_nav' ) ) :
	/**
	 * Display navigation to next/previous set of posts when applicable.
	 *
	 * @param array|null $wp_query WordPress query.
	 * @param string $classes
	 *
	 */
	function olympus_paging_nav( $wp_query = null, $classes = '', $type = 'numbers' ) {

		if ( ! $wp_query ) {
			$wp_query = $GLOBALS['wp_query'];
		}

		$output = '';

		// Don't print empty markup if there's only one page.
		if ( $wp_query->max_num_pages < 2 ) {
			return;
		}


		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		//Set up previous - next links

		if ( 'prev_next' === $type ) {
			the_posts_pagination();

			return;
		}

		// Set up paginated links.
		$pages = paginate_links( array(
			'base'      => $pagenum_link,
			'format'    => $format,
			'total'     => $wp_query->max_num_pages,
			'current'   => $paged,
			'mid_size'  => 3,
			'prev_next' => true,
			'type'      => 'array',
			'add_args'  => array_map( 'urlencode', $query_args ),
			'prev_text' => esc_html__( 'Previous', 'olympus' ),
			'next_text' => esc_html__( 'Next', 'olympus' ),
		) );


		if ( is_array( $pages ) ) {
			$output .= '<nav aria-label="' . esc_html__( 'Posts pagination', 'olympus' ) . '">';
			$output .= '<ul class="pagination justify-content-center ' . esc_attr( $classes ) . '">';
			foreach ( $pages as $link ) {
				$classes = preg_match( '/span/', $link ) ? 'page-item disabled' : 'page-item';
				$item    = str_replace( array(
					'<span',
					'</span>',
					'page-numbers',
					'current',
					'href=\'\'',
					'href=""'
				), array( '<a href="#" ', '</a>', 'page-link', 'active', 'href="?page=1"', 'href="?page=1"' ), $link );
				$output  .= '<li class="' . esc_attr( $classes ) . '">' . $item . '</li>';
			}
			$output .= '</ul>';
			$output .= '</nav>';
		}

		olympus_render( $output );
	}
endif;


if ( ! function_exists( 'olympus_isotope_sorting_categories' ) ):
	/**
     * Generate categories panel for sorting
     *
	 * @param string $sort_type Sorting posts type: Isotope or Categories links
	 * @param string $taxonomy Taxonomy for sorting items panel
     * @param string|array $selected_terms Terms that selected on page template
	 */
	function olympus_isotope_sorting_categories( $sort_type = '', $taxonomy = 'category', $selected_terms = '' ) {
		$sort_wrapper_class = 'isotope' == $sort_type ? 'sorting-menu' : 'categories-menu';
		$term               = get_queried_object()->term_id;
		$term_id            = ( ! empty( $term ) ) ? $term : 0;
		$terms              = get_terms( $taxonomy, array( 'hide_empty' => true ) );
		$categories         = olympus_taxonomy_get_listing_categories( $selected_terms, $taxonomy );
		if ( ! empty( $categories ) && ( count( $categories ) > 1 ) ) : ?>
            <ul class="cat-list-bg-style align-center <?php echo esc_attr( $sort_wrapper_class ) ?>">
				<?php if ( 'sort' === $sort_type ) { ?>
                    <li class="cat-list__item active" data-filter="*">
                        <a href="#" class=""><?php esc_html_e( 'All Projects', 'olympus' ); ?></a>
                    </li>
					<?php foreach ( $categories as $category ) : ?>
                        <li class="cat-list__item" data-filter=".<?php echo esc_attr( $category->slug ) ?>">
                            <a href="#"><?php echo esc_html( $category->name ); ?></a>
                        </li>
					<?php endforeach; ?>
				<?php } else {
					foreach ( $terms as $term ) : ?>
						<?php $active = ( $term->term_id == $term_id ) ? 'active' : ''; ?>
                        <li class="cat-list__item <?php echo esc_attr( $active ) ?>">
                            <a href="<?php echo esc_url( get_term_link( $term->slug, $taxonomy ) ) ?>"><?php echo esc_html( $term->name ); ?></a>
                        </li>
					<?php endforeach; ?>
				<?php } ?>
            </ul>
		<?php endif;
	}
endif;

if ( ! function_exists( 'olympus_append_login_form_to_html' ) ):
	function olympus_append_login_form_to_html() {
		get_template_part( 'templates/user/popup', 'sign' );
	}
endif;