<?php
/**
 * @var object $ext
 * @var array $atts
 * @var int $posts_per_page
 * @var int $cur_page
 * @var string $search_type
 * @var int $max_num_pages
 */

?>

<nav>
	<?php
	$args	 = array(
		'format'	 => '?spage=%#%&search_type=' . $search_type,
		'base'	 => '?spage=%#%&search_type=' . $search_type,
		'prev_text'	 => esc_html__( 'Previous', 'crum-ext-extended-search' ),
		'next_text'	 => esc_html__( 'Next', 'crum-ext-extended-search' ),
		'type'		 => 'array',
		'current'	 => $cur_page,
		'total'		 => $max_num_pages,
	);
	$links	 = paginate_links($args);
	?>

    <ul class="pagination justify-content-center">
		<?php
		if ( is_array( $links ) ) {
			foreach ( $links as $link ) {
				$classes = preg_match( '/span/', $link ) ? 'page-item disabled' : 'page-item';
				$item	 = str_replace( array( '<span', '</span>', 'page-numbers', 'current', 'href=\'\'', 'href=""' ), array( '<a href="#" ', '</a>', 'page-link', 'active', 'href="?page=1"', 'href="?page=1"' ), $link );
				?>
				<li class="<?php olympus_render( $classes ); ?>"><?php olympus_render( $item ); ?></li>
				<?php
			}
		}
		?>
    </ul>
</nav>