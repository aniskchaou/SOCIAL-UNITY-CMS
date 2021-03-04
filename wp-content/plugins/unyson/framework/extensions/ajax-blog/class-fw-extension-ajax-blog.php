<?php

if ( !defined( 'FW' ) ) {
	die( 'Forbidden' );
}

class FW_Extension_Ajax_Blog extends FW_Extension {

	const VERSION		 = '1.0.23';
	const FILTER_ID	 = 'ajax-filter-panel';
	const GRID_ID		 = 'ajax-grid';
	const MASONRY_SEL	 = 'sorting-item';

	public $type	 = null;
	public $nav_type = null;

	protected function _init() {
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScript' ) );

		add_action( 'wp_ajax_crumina_ajax_blog_get_posts', array( $this, 'getPosts' ) );
		add_action( 'wp_ajax_nopriv_crumina_ajax_blog_get_posts', array( $this, 'getPosts' ) );

		add_filter( 'crumina_option_blog_sort_panel', array( $this, 'extendSortPanelTypesOption' ) );
		add_filter( 'crumina_option_blog_nav_style', array( $this, 'extendNavStyleOption' ) );
	}

	/**
	 * Enqueue scripts
	 */
	public function enqueueScript() {
		// Load Scripts only on Blog page Template.
		// if ( ! is_page_template( 'blog-template.php' ) ) {
		// 	return;
		// }
		if ( is_page_template( 'blog-template.php' ) || ($this->is_main_blog()) ) {
			$type			 = $this->getOptionCustomizer( 'blog_style', 'classic' );
			$categories		 = $this->getOptionCustomizer( 'categories', array() );
			$cat_exclude	 = $this->getOptionCustomizer( 'cat_exclude', false );
			$posts_per_page	 = $this->getOptionCustomizer( 'posts_per_page', 12 );
			$preloader		 = $this->getOptionCustomizer( 'blog_sort_panel_preloader' );

			if ( $type === 'masonry' ) {
				wp_enqueue_script( 'isotope', $this->locate_URI( '/static/js/isotope.pkgd.js' ), array( 'imagesloaded' ), '3.0.4', true );
			}

			wp_enqueue_script( 'bootstrap-select',  $this->locate_URI( '/static/js/bootstrap-select.min.js' ), array( 'jquery' ), '1.13.14', true );

			wp_enqueue_script( 'crumina-ajax-blog-scripts', $this->locate_URI( '/static/js/scripts.js' ), array( 'jquery', 'imagesloaded' ), self::VERSION );

			$localize = array(
				'ajax'			 => admin_url( 'admin-ajax.php' ),
				'filter_id'		 => self::FILTER_ID,
				'grid_id'		 => self::GRID_ID,
				'masonry_sel'	 => self::MASONRY_SEL,
				'nav_type'		 => $this->getOptionCustomizer( 'blog_pagination', 'nav' ),
				'type'			 => $type,
				'preloader'		 => fw_akg( 'url', $preloader, $this->locate_URI( '/static/img' ) . '/spinner.gif' ),
				'obj_id'		 => get_queried_object_id(),
				'template_part'	 => "templates/post/{$type}/content",
				'categories'	 => !empty( $categories ) ? implode( ",", $categories ) : 0,
				'cat_exclude'	 => $cat_exclude ? 1 : 0,
				'posts_per_page' => (int) $posts_per_page,
				'paginate_base'	 => str_replace( 9999999999, '%#%', get_pagenum_link( 9999999999 ) )
			);

			if ( function_exists( 'olympus_sidebar_conf' ) ) {
				$localize[ 'sidebar_conf' ] = olympus_sidebar_conf( false );
			} else {
				$localize[ 'sidebar_conf' ] = array();
			}

			wp_localize_script( 'crumina-ajax-blog-scripts', 'crumina_ajax_blog', $localize );
		}else{
			return;
		}
	}

	public function extendSortPanelTypesOption( $opt ) {
		$opt[ 'blog_sort_panel_preloader' ]													 = array(
			'type'			 => 'upload',
			'label'			 => __( 'Ajax preloader', 'crum-ext-ajax-blog' ),
			'desc'			 => esc_html__( 'Work with ajax panels', 'crum-ext-ajax-blog' ),
			'images_only'	 => true,
		);
		$opt[ 'blog_sort_panel' ][ 'picker' ][ 'type' ][ 'choices' ][ 'panel-ajax-cats' ]	 = esc_html__( 'Ajax Categories', 'crum-ext-ajax-blog' );
		$opt[ 'blog_sort_panel' ][ 'picker' ][ 'type' ][ 'choices' ][ 'panel-filters' ]		 = esc_html__( 'Ajax Filter options', 'crum-ext-ajax-blog' );
		$opt[ 'blog_sort_panel' ][ 'choices' ]												 = array(
			'panel-filters' => array(
				'reactions'	 => array(
					'label'			 => esc_html__( 'Reaction', 'crum-ext-ajax-blog' ),
					'desc'			 => esc_html__( 'Sort posts by user reactions', 'crum-ext-ajax-blog' ),
					'type'			 => 'switch',
					'right-choice'	 => array(
						'value'	 => 'yes',
						'label'	 => esc_html__( 'Enable', 'crum-ext-ajax-blog' ),
					),
					'left-choice'	 => array(
						'value'	 => 'no',
						'label'	 => esc_html__( 'Disable', 'crum-ext-ajax-blog' ),
					),
					'value'			 => 'yes',
				),
				'categories' => array(
					'label'			 => esc_html__( 'Categories', 'crum-ext-ajax-blog' ),
					'type'			 => 'switch',
					'right-choice'	 => array(
						'value'	 => 'yes',
						'label'	 => esc_html__( 'Enable', 'crum-ext-ajax-blog' ),
					),
					'left-choice'	 => array(
						'value'	 => 'no',
						'label'	 => esc_html__( 'Disable', 'crum-ext-ajax-blog' ),
					),
					'value'			 => 'yes',
				),
				'order'		 => array(
					'label'			 => esc_html__( 'Order', 'crum-ext-ajax-blog' ),
					'type'			 => 'switch',
					'right-choice'	 => array(
						'value'	 => 'yes',
						'label'	 => esc_html__( 'Enable', 'crum-ext-ajax-blog' ),
					),
					'left-choice'	 => array(
						'value'	 => 'no',
						'label'	 => esc_html__( 'Disable', 'crum-ext-ajax-blog' ),
					),
					'value'			 => 'yes',
				),
				'order-by'	 => array(
					'label'			 => esc_html__( 'Order By', 'crum-ext-ajax-blog' ),
					'type'			 => 'switch',
					'right-choice'	 => array(
						'value'	 => 'yes',
						'label'	 => esc_html__( 'Enable', 'crum-ext-ajax-blog' ),
					),
					'left-choice'	 => array(
						'value'	 => 'no',
						'label'	 => esc_html__( 'Disable', 'crum-ext-ajax-blog' ),
					),
					'value'			 => 'yes',
				),
				'search'	 => array(
					'label'			 => esc_html__( 'Search box', 'crum-ext-ajax-blog' ),
					'desc'			 => esc_html__( 'Enable posts search from panel', 'crum-ext-ajax-blog' ),
					'type'			 => 'switch',
					'right-choice'	 => array(
						'value'	 => 'yes',
						'label'	 => esc_html__( 'Enable', 'crum-ext-ajax-blog' ),
					),
					'left-choice'	 => array(
						'value'	 => 'no',
						'label'	 => esc_html__( 'Disable', 'crum-ext-ajax-blog' ),
					),
					'value'			 => 'yes',
				),
			),
		);

		return $opt;
	}

	public function extendNavStyleOption( $opt ) {
		$opt[ 'choices' ][ 'nav-loadmore' ] = esc_html__( 'Loadmore', 'crum-ext-ajax-blog' );
		return $opt;
	}

	public function getOptionFinal( $option_id, $default_value = '',
			$atts = array() ) {
		$option	 = '';
		$obj	 = get_queried_object();
		$atts	 = shortcode_atts( array(
			'obj_ID' => get_queried_object_id(),
			'type'	 => '',
				), (array) $atts );

		if ( !$atts[ 'type' ] ) {
			if ( is_singular() ) {
				$atts[ 'type' ] = 'singular';
			} elseif ( is_archive() ) {
				$atts[ 'type' ] = 'archive';
			}
		}

		if ( $atts[ 'type' ] === 'singular' ) {
			$option = fw_get_db_post_option( $atts[ 'obj_ID' ], $option_id );
		} elseif ( $atts[ 'type' ] === 'archive' && isset( $obj->taxonomy ) ) {
			$option = fw_get_db_term_option( $atts[ 'obj_ID' ], $obj->taxonomy, $option_id );
		}
		if ( empty( $option ) || (isset( $option[ 'type' ] ) ? $option[ 'type' ] === 'default' : $option === 'default') ) {
			$option = fw_get_db_settings_option( $option_id, $default_value );
		}
		return $option;
	}

	public function is_main_blog() {
		global  $post;
		$posttype = get_post_type($post );
		$blog = false;
		if( ((is_archive()) || (is_author()) || (is_home()) || (is_category()) || (is_tag())) && ( $posttype == 'post') ){
			$blog = true;
		}
		return $blog;
	}

	public function is_blog() {
		global  $post;
		$posttype = get_post_type($post );
		$blog = false;
		if( ((is_archive()) || (is_home())) && ( $posttype == 'post') ){
			$blog = true;
		}

		if(is_category() || is_tag() || (is_author())){
			$blog = false;
		}
		return $blog;
	}

	public function getOptionCustomizer( $option_id, $default_value = '', $atts = array() ){
		$option	 = '';
		$obj	 = get_queried_object();
		$atts	 = shortcode_atts( array(
			'obj_ID' => get_queried_object_id(),
			'type'	 => '',
				), (array) $atts );

		if ( !$atts[ 'type' ] ) {
			if ( is_singular() ) {
				$atts[ 'type' ] = 'singular';
			} elseif ( is_archive() ) {
				$atts[ 'type' ] = 'archive';
			}
		}

		$page_for_posts = get_option( 'page_for_posts' );
		if($page_for_posts > 0 && $this->is_blog()){
			$option = fw_get_db_post_option( $page_for_posts, $option_id, $default_value );
		}

		if ( $atts[ 'type' ] === 'singular' ) {
			$option = fw_get_db_post_option( $atts[ 'obj_ID' ], $option_id );
		} elseif ( $atts[ 'type' ] === 'archive' && isset( $obj->taxonomy ) ) {
			$option = fw_get_db_term_option( $atts[ 'obj_ID' ], $obj->taxonomy, $option_id );
		}
		if ( empty( $option ) || (isset( $option[ 'type' ] ) ? $option[ 'type' ] === 'default' : $option === 'default') ) {
			$option = fw_get_db_customizer_option( $option_id, $default_value );
		}
		return $option;
	}

	public function getOrderOptions() {
		return array(
			'DESC'	 => esc_html__( 'DESC', 'crum-ext-ajax-blog' ),
			'ASC'	 => esc_html__( 'ASC', 'crum-ext-ajax-blog' ),
		);
	}

	public function getOrderByOptions() {
		return array(
			'date'			 => esc_html__( 'Order by date', 'crum-ext-ajax-blog' ),
			'name'		     => esc_html__( 'Order by name', 'crum-ext-ajax-blog' ),
			'comment_count'	 => esc_html__( 'Order by number of comments', 'crum-ext-ajax-blog' ),
			'author'		 => esc_html__( 'Order by author', 'crum-ext-ajax-blog' ),
		);
	}

	private function hierarchical_category_object( $cat, $cat_args ) {

		$parent = array(
			'parent' => $cat
		);

		$args = array_merge($cat_args, $parent);

		$next = get_categories( $args );

		$cat_id = is_category() ? get_queried_object_id() : 0;
		$cats_data = array();

		if ( $next ) :
			foreach ( $next as $category ) :
				array_push($cats_data, $category);
			endforeach;
		endif;

		return $cats_data;
	}

	private function hierarchical_category_tree( $cat, $cat_args ) {

		$parent = array(
			'parent' => $cat
		);

		$args = array_merge($cat_args, $parent);

		$next = get_categories( $args );

		$cat_id = is_category() ? get_queried_object_id() : 0;

		if ( $next ) :
			foreach ( $next as $category ) :
				$cat_prefix = ($category->parent !== 0) ? '- ' : '';
				$selected =  ( $category->term_id === $cat_id ) ? 'selected' : '';
				echo '<option '.$selected.' data-url="'. esc_attr( get_category_link( $category->term_id ) ).'" value="'. $category->term_id .'">'. $cat_prefix . $category->name.'</option>';
				$this->hierarchical_category_tree( $category->term_id, $cat_args );
			endforeach;
		endif;
	}

	public function getFilterPanelHtml() {
		$reactions_obj		 = fw()->extensions->get( 'post-reaction' );
		$reactions_img_path	 = $reactions_obj->locate_URI( '/static/img' );
		$availableReactions	 = fw_get_db_ext_settings_option( 'post-reaction', 'available-reactions' );
		$enableReactions	 = fw_get_db_ext_settings_option( 'post-reaction', 'show-reactions' );
		$panel_id			 = self::FILTER_ID;
		$order				 = $this->getOptionCustomizer( 'post_order', 'DESC' );
		$order_by			 = $this->getOptionCustomizer( 'post_order_by', 'date' );
		$order_options		 = $this->getOrderOptions();
		$order_by_options	 = $this->getOrderByOptions();
		$page_for_posts		 = get_option( 'page_for_posts' );
		$page_for_posts_url	 = $page_for_posts ? get_the_permalink( $page_for_posts ) : get_site_url( '/' ) . '/';
		$panel_components	 = $this->getOptionCustomizer( 'blog_sort_panel', 'panel-cats' );
		$panel_type			 = fw_akg( 'type', $panel_components, 'panel-cats' );
		$view_path			 = $this->locate_path( "/views/{$panel_type}.php" );

		$cat_args	 = array(
			'hide_empty' => 0,
		);
		$cat_filter	 = $this->getOptionCustomizer( 'categories', array() );
		$cat_exclude = $this->getOptionCustomizer( 'cat_exclude', false );

		if ( !empty( $cat_filter ) ) {
			$cat_filter = implode( ',', $cat_filter );

			if ( $cat_exclude ) {
				$cat_args[ 'exclude' ] = $cat_filter;
			} else {
				$cat_args[ 'include' ] = $cat_filter;
			}
		}
		if($panel_type != 'panel-ajax-cats'){
			ob_start();
			$this->hierarchical_category_tree(  0, $cat_args );
			$categories = ob_get_clean();
		}else{
			$categories = $this->hierarchical_category_object(  0, $cat_args );
		}
 		

		return fw_render_view( $view_path, compact( 'reactions_img_path','availableReactions', 'enableReactions', 'categories', 'panel_id', 'page_for_posts_url', 'order', 'order_by', 'order_options', 'order_by_options', 'panel_components' ) );
	}

	public function getPosts() {

		$paginateBase	 = filter_input( INPUT_POST, 'paginateBase' );
		$reactions		 = filter_input( INPUT_POST, 'reactions', FILTER_SANITIZE_STRING );
		$category		 = filter_input( INPUT_POST, 'category', FILTER_VALIDATE_INT );
		$order			 = filter_input( INPUT_POST, 'order', FILTER_SANITIZE_STRING );
		$orderBy		 = filter_input( INPUT_POST, 'orderBy', FILTER_SANITIZE_STRING );
		$search			 = filter_input( INPUT_POST, 'search', FILTER_SANITIZE_STRING );
		$navType		 = filter_input( INPUT_POST, 'navType', FILTER_SANITIZE_STRING );
		$objID			 = filter_input( INPUT_POST, 'objID', FILTER_VALIDATE_INT );
		$templatePart	 = filter_input( INPUT_POST, 'templatePart', FILTER_SANITIZE_STRING );
		$page			 = filter_input( INPUT_POST, 'page', FILTER_VALIDATE_INT );
		$postsPerPage	 = filter_input( INPUT_POST, 'postsPerPage', FILTER_VALIDATE_INT );
		$sidebarConf	 = filter_input( INPUT_POST, 'sidebarConf', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );

		$categories	 = filter_input( INPUT_POST, 'categories', FILTER_SANITIZE_STRING );
		$catExclude	 = filter_input( INPUT_POST, 'catExclude', FILTER_VALIDATE_INT );

		$args = array(
			'paged'				 => $page ? $page : 1,
			'suppress_filters'	 => true,
			'post_type'			 => 'post',
			'post_status'		 => 'publish'
		);

		if ( $order ) {
			$args[ 'order' ] = $order;
		}

		if ( $postsPerPage ) {
			$args[ 'posts_per_page' ] = $postsPerPage;
		}

		if ( $orderBy ) {
			$args[ 'orderby' ] = $orderBy;
		}

		// Filter by categories
		if ( $category ) {
			if ( $catExclude ) {
				$args['category__not_in'] = $category;
			} else {
				$args['category__in'] = $category;
			}
		} else if ( $categories ) {
			$categories = explode( ',', $categories );

			foreach ( $categories as &$cat ) {
				$cat = $catExclude ? -(int) $cat : (int) $cat;
			}

			$args[ 'cat' ] = $categories;
		}

		if ( $search ) {
			$args[ 's' ] = $search;
		}

		if ( $reactions ) {
			$reactions				 = explode( ',', $reactions );
			$args[ 'meta_query' ]	 = array(
				'relation' => 'OR'
			);

			foreach ( $reactions as $reaction ) {
				$args[ 'meta_query' ][] = array(
					'key'		 => $reaction,
					'compare'	 => 'EXISTS'
				);
			}
		}

		$query				 = new WP_Query( $args );
		$grid_tmp			 = $this->locate_path( '/views/grid.php' );
		$grid				 = fw_render_view( $grid_tmp, compact( 'query', 'templatePart', 'sidebarConf' ) );
		$reactions_obj		 = fw()->extensions->get( 'post-reaction' );
		$reactions_img_path	 = $reactions_obj->locate_URI( '/static/img' );

		$nav_tmp = $this->locate_path( '/views/' . $navType . '.php' );

		$nav = fw_render_view( $nav_tmp, compact( 'query', 'page', 'objID', 'reactions_img_path', 'paginateBase' ) );

		wp_send_json_success( array(
			'grid'	 => $grid,
			'nav'	 => $nav,
		) );
	}

}