<?php if ( ! defined( 'ABSPATH' ) ) {
	die( 'Direct access forbidden.' );
}

/******
 *
 * Woocommerce additional hooks and actions and theme customizations.
 *
 *****/
if ( ! function_exists( 'olympus_woocommerce_support' ) ) {
	/**
	 * Declares WooCommerce modules support.
	 */
	function olympus_woocommerce_support() {

		// Add New Woocommerce 3.0.0 Product Gallery support
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-slider' );
        add_theme_support( 'woocommerce', array(
            'product_grid' => array(
                'default_rows'    => 3,
                'min_rows'        => 2,
                'default_columns' => 2,
                'min_columns'     => 1,
                'max_columns'     => 6,
            ),
        ) );

        // hook in and customizer form fields.
		add_filter( 'woocommerce_form_field_args', 'olympus_wc_form_field_args', 10, 3 );
	}
}
add_action( 'after_setup_theme', 'olympus_woocommerce_support' );

/**
 * Filter hook function monkey patching form classes
 * Author: Adriano Monecchi http://stackoverflow.com/a/36724593/307826
 *
 * @param string $args Form attributes.
 * @param string $key Not in use.
 * @param null   $value Not in use.
 *
 * @return mixed
 */
function olympus_wc_form_field_args( $args, $key, $value = null ) {
	// Start field type switch case.
	switch ( $args['type'] ) {
		/* Targets all select input type elements, except the country and state select input types */
		case 'select' :
			// Add a class to the field's html element wrapper - woocommerce
			// input types (fields) are often wrapped within a <p></p> tag.
			$args['class'][] = 'form-group';
			// Add a class to the form input itself.
			$args['input_class']       = array( 'form-control', 'input-lg' );
			$args['label_class']       = array( 'control-label' );
			$args['custom_attributes'] = array(
				'data-plugin'      => 'select2',
				'data-allow-clear' => 'true',
				'aria-hidden'      => 'true',
				// Add custom data attributes to the form input itself.
			);
			break;
		// By default WooCommerce will populate a select with the country names - $args
		// defined for this specific input type targets only the country select element.
		case 'country' :
			$args['class'][]     = 'form-group single-country';
			$args['label_class'] = array( 'control-label' );
			break;
		// By default WooCommerce will populate a select with state names - $args defined
		// for this specific input type targets only the country select element.
		case 'state' :
			// Add class to the field's html element wrapper.
			$args['class'][] = 'form-group';
			// add class to the form input itself.
			$args['input_class']       = array( '', 'input-lg' );
			$args['label_class']       = array( 'control-label' );
			$args['custom_attributes'] = array(
				'data-plugin'      => 'select2',
				'data-allow-clear' => 'true',
				'aria-hidden'      => 'true',
			);
			break;
		case 'password' :
		case 'text' :
		case 'email' :
		case 'tel' :
		case 'number' :
			$args['class'][]     = 'form-group';
			$args['input_class'] = array( 'form-control', 'input-lg' );
			$args['label_class'] = array( 'control-label' );
			break;
		/*case 'textarea' :
			$args['input_class'] = array( 'form-control', 'input-lg' );
			$args['label_class'] = array( 'control-label' );
			break;*/
		case 'checkbox' :
			$args['label_class'] = array( 'custom-control custom-checkbox' );
			$args['input_class'] = array( 'custom-control-input', 'input-lg' );
			break;
		case 'radio' :
			$args['label_class'] = array( 'custom-control custom-radio' );
			$args['input_class'] = array( 'custom-control-input', 'input-lg' );
			break;
		default :
			$args['class'][]     = 'form-group';
			$args['input_class'] = array( 'form-control', 'input-lg' );
			$args['label_class'] = array( 'control-label' );
			break;
	} // end switch ($args).
	return $args;
}

add_action( 'after_setup_theme', 'olympus_woocommerce_modifications' );

/**
 * Functions modifications
 */

function olympus_woocommerce_modifications() {

    /**
     * Template modifications
     */

	// Remove breadcrumbs
	remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
	/* Replace pagination */
	remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
	add_action( 'woocommerce_after_shop_loop', 'olympus_paging_nav', 10 );

	/* Modify products in loop */
	// Remove default actions
	remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
	// add theme actions
	add_action( 'woocommerce_before_shop_loop_item', 'olympus_woo_loop_product_thumbnail', 10 );
	add_action( 'woocommerce_shop_loop_item_title', 'olympus_woo_loop_product_block_title', 10 );

	/* Modify Shop sorting panel*/
	// Remove default actions
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
	// add theme sorting panel
	add_action( 'woocommerce_before_shop_loop', 'olympus_woo_sorting_panel', 20 );

	/* Modify Single Shop Item */
	// Remove default actions

	remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
	add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_sale_flash', 10 );

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    // add theme sorting panel
	add_action( 'woocommerce_single_product_summary', 'olympus_woo_single_product_title', 5 );


	/* Modify Shop Item Review*/
	// Remove default actions
	remove_action('woocommerce_review_before','woocommerce_review_display_gravatar',10);
	remove_action('woocommerce_review_meta','woocommerce_review_display_meta',10);
    // add theme actions
	add_action('woocommerce_review_before','olympus_woo_review_title',10);
	add_action('woocommerce_review_after_comment_text','woocommerce_review_display_meta',10);

	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );

}

/**
 * Change number or products per row to 3
 **/
if ( ! function_exists( 'olympus_loop_columns' ) ) {
	function olympus_loop_columns() {
		$columns      = get_option( 'woocommerce_catalog_columns', 4 );
		return $columns;
	}
}

/*
 * Ajaxify cart
*/
add_filter('woocommerce_add_to_cart_fragments', 'olympus_woocommerce_header_add_to_cart_fragment');

function olympus_woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	get_template_part( 'parts/header/menu-item', 'cart' );
	$fragments['div.cart-menulocation'] = ob_get_clean();

	return $fragments;

}

/**
 * Define image sizes
 */
function olympus_woocommerce_image_dimensions() {
	global $pagenow;

	if ( ! isset( $_GET['activated'] ) || $pagenow != 'themes.php' ) {
		return;
	}
	$catalog   = array(
		'width'  => '280',    // px
		'height' => '280',    // px
		'crop'   => 1        // true
	);
	$single    = array(
		'width'  => '600',    // px
		'height' => '600',    // px
		'crop'   => 0        // true
	);
	$thumbnail = array(
		'width'  => '80',    // px
		'height' => '80',    // px
		'crop'   => 1        // false
	);
	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog );        // Product category thumbs
	update_option( 'shop_single_image_size', $single );        // Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail );    // Image gallery thumbs
	update_option( 'woocommerce_enable_lightbox', false );
}

add_action( 'after_switch_theme', 'olympus_woocommerce_image_dimensions', 1 );

/**
 * Product Thumbnail [woocommerce loop items]
 */
if ( ! function_exists( 'olympus_woo_loop_product_thumbnail' ) ) {
	function olympus_woo_loop_product_thumbnail() {
		echo '<a href="' . esc_url( get_the_permalink() ) . '" class="woocommerce-LoopProduct-link woocommerce-loop-product__link product-thumb">';
		echo woocommerce_get_product_thumbnail(); // WPCS: XSS ok.
		wc_get_template( 'loop/sale-flash.php' );
		echo '</a>';
	}
}

/**
 * Item categories and title [woocommerce loop items]
 **/
if ( ! function_exists( 'olympus_woo_loop_product_block_title' ) ) {
	function olympus_woo_loop_product_block_title() {
		global $product;
		echo '<div class="block-title">';
		echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-category">', '</span>' );
		echo '<a href="' . get_the_permalink() . '" class="h5 title">' . get_the_title() . '</a>';
		echo '</div>';
	}
}

if ( ! function_exists( 'woocommerce_product_archive_description' ) ) {
	/**
	 * Show a shop page description on product archives.
	 *
	 * @subpackage  Archives
	 */
	function woocommerce_product_archive_description() {
		// Don't display the description on search results page
		if ( is_search() ) {
			return;
		}

		if ( is_post_type_archive( 'product' ) && 0 === absint( get_query_var( 'paged' ) ) ) {
			if ( is_shop() ) {
				$shop_page    = wc_get_page_id( 'shop' );
				$builder_meta = get_post_meta( $shop_page, 'kc_data', true );
				if ( isset( $builder_meta['mode'] ) && 'kc' === $builder_meta['mode'] ) {
					$page_content = get_post_field( 'post_content_filtered', $shop_page );
				} else {
					$shop_page   = get_post( $shop_page );
					$page_content = $shop_page->post_content;
				}
				echo apply_filters( 'the_content', $page_content );
			}
		}

	}
}

// Per Page Dropdown
function olympus_woo_show_product_perpage() {
	$per_page = intval(olympus_loop_columns() * get_option( 'woocommerce_catalog_rows', 3 ));

	$count_posts = wp_count_posts('product');
	$prod_count = $count_posts->publish;

	if ( $prod_count > 0 && $per_page !== 0 ) {
		$limit = intval( $prod_count / intval( $per_page ) ) + 1;

		?>
			<select name="per_page" class="selectpicker form-control">
				<?php
				$x = 1;
				while ( $x <= $limit ) {
					$value    = $per_page * $x;
					$selected = selected( $per_page, $value, false );
					$label    = esc_html__( 'Display ', 'olympus' ) . $value . esc_html__( ' Products Per Page', 'olympus' );
					echo "<option value='{$value}' {$selected}>{$label}</option>";
					$x ++;
				} ?>
			</select>

		<?php
	}
}

function olympus_woo_sorting_panel() {
    $shop_page = wc_get_page_id( 'shop' );
    $product_categories = filter_input(INPUT_GET, 'product_categories');
	wp_enqueue_script( 'bootstrap-select' );
    ?>
    <div class="ui-block responsive-flex1200">
		<form class="crum-woocommerce-sort crum-get-form" method="get" action="<?php the_permalink($shop_page) ?>">
            <div class="ui-block-title">
                <div class="w-select">
                    <div class="title"><?php esc_html_e( 'Filter By:', 'olympus' ) ?></div>
                    <fieldset class="form-group">
                        <?php
                        wc_product_dropdown_categories( array(
                            'hierarchical' => 0,
                            'class'        => 'selectpicker form-control',
                        ) );
                        ?>
                    </fieldset>
                </div>

                <div class="w-select">
                    <fieldset class="form-group">
                        <?php olympus_woocommerce_catalog_ordering(true); ?>
                    </fieldset>
                </div>

                <button type="submit" class="btn btn-primary btn-md-2"><?php esc_html_e( 'Filter Products', 'olympus' ) ?></button>
            </div>
        </form>
    </div>
    <?php
}

/**
 *  Shop rating display function
 *
 * @param int $rating Product rating
 * @param bool $number Show rating number with stars
 *
 */
if ( ! function_exists( 'olympus_shop_rating_html' ) ) {
	function olympus_shop_rating_html( $rating = 0, $numeric = false ) {
		if ( 0 === $rating ) {
			return;
		}

		$int_rating = intval( $rating );
		echo '<ul class="rait-stars">';
		for ( $i = 1; $i <= 5; $i ++ ) {
			if ( $i <= $int_rating ) {
				$icon_class = 'fa fa-star star-icon c-primary';
			} else {
				$icon_class = 'fa fa-star star-icon';
			}
			echo '<li><i class="' . esc_attr( $icon_class ) . '" aria-hidden="true"></i></li>';
		}
		if ( $numeric ) {
			echo '<li class="numerical-rating">' . $rating .' / 5 </li>';
		}
		echo '</ul>';
	}
}

/**
 * Item categories, title and  price [woocommerce item details]
 **/
if ( ! function_exists( 'olympus_woo_single_product_title' ) ) {
	function olympus_woo_single_product_title() {
		global $product; ?>
        <div class="main-content-wrap">
            <div class="block-title">
                <?php echo wc_get_product_category_list( $product->get_id(), ', ', '<span class="product-category">', '</span>' ); ?>
                <?php echo olympus_html_tag('h1', array('class'=>'title entry-title h2 bold'),$product->get_title()) ?>
	            <?php $int_rating = intval( $product->get_average_rating() );
	            olympus_shop_rating_html( $int_rating ); ?>
            </div>

            <div class="block-price">
                <div class="product-price"><?php echo olympus_render($product->get_price_html()); ?></div>
            </div>
        </div>
		<?php
	}
}

/**
 * Item reviews [woocommerce item details]
 **/
if (! function_exists('olympus_woo_review_title')){
	function olympus_woo_review_title(){
        global $comment;
	     if( $title = get_comment_meta( $comment->comment_ID, 'crum_comment_title', true ) ){
		     $title = olympus_html_tag( 'h5', array( 'class' => 'title' ), $title );
	     }

	     olympus_render( $title );
    }

}

/**
 * Save our title (from the front end)
 */
add_action( 'comment_post', 'olympus_woo_insert_comment_title', 10, 1 );
function olympus_woo_insert_comment_title( $comment_id )
{
	if( isset( $_POST['crum_comment_title'] ) )
		update_comment_meta( $comment_id, 'crum_comment_title', esc_attr( $_POST['crum_comment_title'] ) );
}


// define the woocommerce_order_button_html callback
function filter_olympus_woocommerce_order_button_html( ) {
	$button_html = '<input type="submit" class="btn btn-primary btn-lg full-width" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr__( 'Place Order','olympus' ) . '" data-value="' . esc_attr__( 'Place Order','olympus' ) . '" />';
    return $button_html;
};

// add the filter
add_filter( 'woocommerce_order_button_html', 'filter_olympus_woocommerce_order_button_html', 10, 2 );

add_action( 'wp_enqueue_scripts', '_action_olympus_woocommerce_scripts', 9999 );
function _action_olympus_woocommerce_scripts() {
	$theme_version = olympus_get_theme_version();

	wp_enqueue_style( 'woocommerce-customization', get_template_directory_uri() . '/css/woocommerce-customization.css', false, $theme_version );
}

/**
 * Output the product sorting options.
 *
 * @param boll $plain Template type
 * 
 */
function olympus_woocommerce_catalog_ordering($plain = false) {
    if ( !wc_get_loop_prop( 'is_paginated' ) || !woocommerce_products_will_display() ) {
        return;
    }
    $show_default_orderby    = 'menu_order' === apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );
    $catalog_orderby_options = apply_filters( 'woocommerce_catalog_orderby', array(
        'menu_order' => esc_html__( 'Default sorting', 'olympus' ),
        'popularity' => esc_html__( 'Sort by popularity', 'olympus' ),
        'rating'     => esc_html__( 'Sort by average rating', 'olympus' ),
        'date'       => esc_html__( 'Sort by newness', 'olympus' ),
        'price'      => esc_html__( 'Sort by price: low to high', 'olympus' ),
        'price-desc' => esc_html__( 'Sort by price: high to low', 'olympus' ),
    ) );

    $default_orderby = wc_get_loop_prop( 'is_search' ) ? 'relevance' : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby', '' ) );
    $orderby         = isset( $_GET[ 'orderby' ] ) ? wc_clean( wp_unslash( $_GET[ 'orderby' ] ) ) : $default_orderby; // WPCS: sanitization ok, input var ok, CSRF ok.

    if ( wc_get_loop_prop( 'is_search' ) ) {
        $catalog_orderby_options = array_merge( array( 'relevance' => esc_html__( 'Relevance', 'olympus' ) ), $catalog_orderby_options );

        unset( $catalog_orderby_options[ 'menu_order' ] );
    }

    if ( !$show_default_orderby ) {
        unset( $catalog_orderby_options[ 'menu_order' ] );
    }

    if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
        unset( $catalog_orderby_options[ 'rating' ] );
    }

    if ( !array_key_exists( $orderby, $catalog_orderby_options ) ) {
        $orderby = current( array_keys( $catalog_orderby_options ) );
    }

    $template = $plain ? 'loop/orderby-plain.php' : 'loop/orderby.php';
    wc_get_template( $template, array(
        'catalog_orderby_options' => $catalog_orderby_options,
        'orderby'                 => $orderby,
        'show_default_orderby'    => $show_default_orderby,
    ) );
}
