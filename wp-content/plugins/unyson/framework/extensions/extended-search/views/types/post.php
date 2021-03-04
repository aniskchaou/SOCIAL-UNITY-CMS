<?php
/**
 * @var object $the_query
 * @var int $found_posts
 */
?>

<div class="search-help-result">
	<h3 class="search-help-result-title">
		<span class="count-result"><?php olympus_render( $found_posts, ' ', _n( 'result', 'results', $found_posts, 'olympus' ) ); ?></span>
		<?php esc_html_e( 'found', 'olympus' ); ?>
	</h3>
	<?php if ( $found_posts != 0 ) { ?>
		<ul class="search-help-result-list">
			<?php while ( $the_query->have_posts() ) : $the_query->the_post();
				?>
				<li>
					<?php
						$author_id  = get_the_author_meta( 'ID' );
						$profession = get_the_author_meta( 'user_profession' );
						$query = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );

						?>
						<?php echo olympus_post_category_list( null, null, true ); ?>
						<h2><a href="<?php the_permalink(); ?>"><?php echo olympus_highlight_searched( $query, get_the_title() ); ?></a></h2>
						<p>
							<?php echo olympus_highlight_searched( $query, get_the_excerpt() ); ?>
						</p>

						<div class="single-post-additional inline-items">
							<div class="post__author author vcard inline-items">
								<?php echo get_avatar( $author_id, 26 ); ?>
								<div class="author-date not-uppercase">
									<a class="h6 post__author-name fn"
									href="<?php the_author_meta( 'url' ); ?>"><?php the_author_meta( 'display_name' ); ?></a>
									<?php if ( $profession ) { ?>
										<div class="author_prof">
											<?php olympus_render( $profession ); ?>
										</div>
									<?php } ?>
								</div>
							</div>
							<div class="post-date-wrap inline-items">
								<i class="olympus-icon-Week-Calendar-Icon"></i>
								<div class="post-date">
									<a class="h6 date" href="#"><?php the_modified_time( 'F jS, Y' ); ?></a>
									<span>Last Updated</span>
								</div>
							</div>
						</div>
				</li>
				<?php
			endwhile;
			wp_reset_postdata();
			?>
		</ul>
		<?php
	} else {
		?>
		<div class="row">
			<?php get_template_part( 'templates/content/content-search-none' ); ?>
		</div>
		<?php
	}
	?>
</div>