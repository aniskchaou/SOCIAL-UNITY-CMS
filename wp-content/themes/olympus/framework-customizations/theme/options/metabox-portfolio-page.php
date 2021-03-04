<?php if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}

$options = array(

	'sorting_panel' => array(
		'type'    => 'multi-picker',
		'label'   => false,
		'desc'    => false,
		'picker'  => array(
			'value' => array(
				'label'   => esc_html__( 'Sort panel', 'olympus' ),
				'type'         => 'switch',
				'desc'    => esc_html__( 'Panel before items with taxonomy categories', 'olympus' ),
				'left-choice'  => array(
					'value' => 'no',
					'label' => esc_html__( 'Hide', 'olympus' )
				),
				'right-choice' => array(
					'value' => 'yes',
					'label' => esc_html__( 'Show', 'olympus' )
				),
				'value'        => 'yes',
			)
		),
		'choices' => array(
			'yes' => array(
				'action' => array(
					'type'    => 'select',
					'label'   => esc_html__( 'Action on panel link click', 'olympus' ),
					'choices' => array(
						'sort'    => esc_html__( 'Sort items on click', 'olympus' ),
						'load'     => esc_html__( 'Open Category archive', 'olympus' ),
					),
				)
			)
		),
	),
	'pagination_type' => array(
		'label'   => esc_html__( 'Type of pages pagination', 'olympus' ),
		'type'    => 'select',
		'desc'    => esc_html__( 'Select one of pagination types', 'olympus' ),
		'choices' => array(
			'numbers'     => esc_html__( 'Numbers links', 'olympus' ),
			'prev_next'     => esc_html__( 'Previous, next links', 'olympus' ),
			'loadmore'    => esc_html__( 'Load more ajax', 'olympus' ),

		),
	),
	'order'           => array(
		'label'   => esc_html__( 'Order', 'olympus' ),
		'type'    => 'select',
		'desc'    => esc_html__( 'Designates the ascending or descending order of items', 'olympus' ),
		'choices' => array(
			'default' => esc_html__( 'Default', 'olympus' ),
			'DESC'    => esc_html__( 'Descending', 'olympus' ),
			'ASC'     => esc_html__( 'Ascending', 'olympus' ),
		),
	),
	'orderby'         => array(
		'label'   => esc_html__( 'Order posts by', 'olympus' ),
		'type'    => 'select',
		'desc'    => esc_html__( 'Sort retrieved posts by parameter.', 'olympus' ),
		'choices' => array(
			'default'       => esc_html__( 'Default', 'olympus' ),
			'date'          => esc_html__( 'Order by date', 'olympus' ),
			'name'          => esc_html__( 'Order by name', 'olympus' ),
			'comment_count' => esc_html__( 'Order by number of comments', 'olympus' ),
			'author'        => esc_html__( 'Order by author', 'olympus' ),
		),
	),
	'taxonomy_select' => array(
		'type'       => 'multi-select',
		'label'      => esc_html__( 'Categories', 'olympus' ),
		'help'       => esc_html__( 'Click on field and type category name to find  category', 'olympus' ),
		'population' => 'taxonomy',
		'source'     => 'fw-portfolio-category',
		'limit'      => 100,
	),
	'exclude'         => array(
		'type'  => 'checkbox',
		'value' => false,
		'label' => esc_html__( 'Exclude selected', 'olympus' ),
		'desc'  => esc_html__( 'Show all categories except that selected in "Categories" option', 'olympus' ),
		'text'  => esc_html__( 'Exclude', 'olympus' ),
	),
	'more_text'        => array(
		'label' => esc_html__( 'Read More link text', 'olympus' ),
		'desc'  => esc_html__( 'Text for link that open inner page', 'olympus' ),
		'type'  => 'text',
		'value' => esc_html__( 'View Case', 'olympus' )
	),
	'per_page'        => array(
		'label' => esc_html__( 'Items per page', 'olympus' ),
		'desc'  => esc_html__( 'How many portfolios show per page', 'olympus' ),
		'help'  => esc_html__( 'Please input number here. Leave empty for default value', 'olympus' ),
		'type'  => 'text',
	),

);