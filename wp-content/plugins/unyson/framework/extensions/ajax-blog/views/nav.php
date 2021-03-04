<?php
$total	 = $query->max_num_pages;
$current = $page ? $page : 1;

$links = paginate_links( array(
	'base'		 => $paginateBase,
	'total'		 => $total,
	'current'	 => $current,
	'prev_text'	 => __( 'Previous' ),
	'next_text'	 => __( 'Next' ),
	'type'		 => 'array',
		) );

if ( !$links ) {
	return '';
}
?>

<ul class="pagination justify-content-center">
	<?php foreach ( $links as $link ) { ?>
		<?php
		$classes = preg_match( '/span/', $link ) ? 'page-item disabled' : 'page-item';
		$item	 = str_replace( array( '<span', '</span>', 'page-numbers', 'current' ), array( '<a href="#" ', '</a>', 'page-link', 'active' ), $link );
		?>
		<li class="<?php echo $classes; ?>"><?php echo $item; ?></li>
	<?php } ?>
</ul>
