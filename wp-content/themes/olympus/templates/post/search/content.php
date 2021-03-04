<?php
$query = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
?>

<h2><a href="<?php the_permalink(); ?>"><?php echo olympus_highlight_searched( $query, get_the_title() ); ?></a></h2>
<?php the_excerpt(); ?>
<p>
	<?php echo olympus_highlight_searched( $query, get_the_excerpt() ); ?>
</p>
