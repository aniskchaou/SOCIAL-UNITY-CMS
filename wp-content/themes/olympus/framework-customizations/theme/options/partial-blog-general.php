<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

$olympus = Olympus_Options::get_instance();
$blog_style_value = $olympus->get_option( 'blog_style', 'classic', $olympus::SOURCE_SETTINGS );
$blog_pagination_value = $olympus->get_option( 'blog_pagination', 'nav', $olympus::SOURCE_SETTINGS );
$post_order_value = $olympus->get_option( 'post_order', 'DESC', $olympus::SOURCE_SETTINGS );
$post_order_by_value = $olympus->get_option( 'post_order_by', 'date', $olympus::SOURCE_SETTINGS );
$categories_value = $olympus->get_option( 'categories', array(), $olympus::SOURCE_SETTINGS );
$cat_exclude_value = $olympus->get_option( 'cat_exclude', false, $olympus::SOURCE_SETTINGS );
$posts_per_page_value = $olympus->get_option( 'posts_per_page', 12, $olympus::SOURCE_SETTINGS );

$options = array(
    'blog_style'      => apply_filters( 'crumina_option_blog_style', array(
        'label'   => esc_html__( 'Blog style', 'olympus' ),
        'desc'    => esc_html__( 'Select default style for display posts. Alternatively can be changed in page with template called "Blog page"', 'olympus' ),
        'type'    => 'radio',
        'value'   => $blog_style_value,
        'choices' => array(
            'classic' => esc_html__( 'Classic', 'olympus' ),
            'grid'    => esc_html__( 'Grid', 'olympus' ),
            'list'    => esc_html__( 'List', 'olympus' ),
            'masonry' => esc_html__( 'Masonry', 'olympus' ),
        ),
    ) ),
    'blog_pagination' => apply_filters( 'crumina_option_blog_nav_style', array(
        'label'   => esc_html__( 'Pagination style', 'olympus' ),
        'desc'    => esc_html__( 'Select default style for pagination. Loadmore work with Ajax sort panels only', 'olympus' ),
        'type'    => 'radio',
        'value'   => $blog_pagination_value,
        'choices' => array(
            'nav' => esc_html__( 'Numeric', 'olympus' ),
        ),
    ) ),
    'post_order'      => apply_filters( 'crumina_option_blog_post_order', array(
        'label'   => esc_html__( 'Order', 'olympus' ),
        'type'    => 'radio',
        'value'   => $post_order_value,
        'desc'    => esc_html__( 'Designates the ascending or descending order of items', 'olympus' ),
        'choices' => array(
            'DESC' => esc_html__( 'Descending', 'olympus' ),
            'ASC'  => esc_html__( 'Ascending', 'olympus' ),
        ),
    ) ),
    'post_order_by'   => apply_filters( 'crumina_option_blog_post_order_by', array(
        'label'   => esc_html__( 'Order By', 'olympus' ),
        'type'    => 'radio',
        'desc'    => esc_html__( 'Sort retrieved posts by parameter.', 'olympus' ),
        'value'   => $post_order_by_value,
        'choices' => array(
            'date'          => esc_html__( 'Order by date', 'olympus' ),
            'name'          => esc_html__( 'Order by name', 'olympus' ),
            'comment_count' => esc_html__( 'Order by number of comments', 'olympus' ),
            'author'        => esc_html__( 'Order by author', 'olympus' ),
        ),
    ) ),
    'categories'      => array(
        'type'       => 'multi-select',
        'label'      => esc_html__( 'Categories', 'olympus' ),
        'help'       => esc_html__( 'Click on field and type category name to find  category', 'olympus' ),
        'population' => 'taxonomy',
        'source'     => 'category',
        'value'      => $categories_value, 
        'limit'      => 100,
    ),
    'cat_exclude'     => array(
        'type'  => 'checkbox',
        'label' => esc_html__( 'Exclude selected', 'olympus' ),
        'desc'  => esc_html__( 'Show all categories except that selected in "Categories" option', 'olympus' ),
        'text'  => esc_html__( 'Exclude', 'olympus' ),
        'value' => $cat_exclude_value,
    ),
    'posts_per_page'  => array(
        'label' => esc_html__( 'Items per page', 'olympus' ),
        'desc'  => esc_html__( 'How many posts show per page', 'olympus' ),
        'help'  => esc_html__( 'Please input number here. Leave empty for default value', 'olympus' ),
        'type'  => 'text',
        'value' => $posts_per_page_value
    ),
);
