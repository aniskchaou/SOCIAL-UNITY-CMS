<?php
/**
 * @var object $the_query
 * @var int $found_posts
 */
?>

<div class="search-help-result">
	<h3 class="search-help-result-title">
		<span class="count-result"><?php olympus_render( $found_posts, ' ', _n( 'result', 'results', $found_posts, 'crum-ext-extended-search' ) ); ?></span>
		<?php esc_html_e( 'found', 'crum-ext-extended-search' ); ?>
	</h3>
	<?php if ( $found_posts != 0 ) { ?>
		<ul class="search-help-result-list">
            <?php while ( $the_query->have_posts() ) : $the_query->the_post();
				?>
				<li>
					<?php
                    if( get_post_type() != 'product' ){
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
									<span><?php echo esc_html( 'Last Updated', 'crum-ext-extended-search' ); ?></span>
								</div>
							</div>
						</div>
                    <?php } else { 
                        $product = wc_get_product( get_the_ID() );
						$query   = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
					?>
						<div class="row">
							<div class="col-sm-2">
								<?php olympus_render( $product->get_image( array( 150, 150 ) ) ); ?>
							</div>
							<div class="col-sm-10">
								<div class="main-content-wrap">
									<div class="block-title">
										<?php echo wc_get_product_category_list( get_the_ID(), ', ', '<span class="product-category">', '</span>' ); ?>
										<h2>
											<a href="<?php the_permalink(); ?>"><?php echo olympus_highlight_searched( $query, $product->get_title() ); ?></a>
										</h2>
										<?php olympus_shop_rating_html( intval( $product->get_average_rating() ) ); ?>
									</div>

									<div class="block-price">
										<div class="product-price"><?php olympus_render( $product->get_price_html() ); ?></div>
									</div>

								</div>

								<?php echo olympus_highlight_searched( $query, $product->get_description() ); ?>
							</div>
						</div>
                    <?php } ?>
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