<?php

class YZ_Posts_Tab {

	/**
	 * Tab Content
	 */
	function tab() {

		// Prepare Posts Arguments.
		$args = array(
			'post_type'		 => apply_filters( 'yz_profile_posts_tab_post_type', 'post' ),
			'order' 		 => 'DESC',
			'paged' 		 => get_query_var( 'page' ) ? get_query_var( 'page' ) : 1,
			'post_status'	 => 'publish',
			'posts_per_page' => yz_option( 'yz_profile_posts_per_page', 5 ),
			'author' 		 => bp_displayed_user_id(),
		);

		echo '<div class="yz-tab yz-posts"><div id="yz-main-posts" class="yz-tab yz-tab-posts">';
		$this->posts_core( $args );
		yz_loading();
		echo '</div></div>';

		// Pagination Script.
 		yz_profile_posts_comments_pagination();

	}

	/**
	 * # Post Core .
	 */
	function posts_core( $args ) {

		// Init Vars.
		$posts_exist = false;

		$blogs_ids = is_multisite() ? get_sites() : array( (object) array( 'blog_id' => 1 ) );

		$blogs_ids = apply_filters( 'yz_profile_posts_tab_blog_ids', $blogs_ids );

		// Posts Pagination
		$posts_page = ! empty( $_POST['page'] ) ? $_POST['page'] : 1 ;

		// Get Base
		$base = isset( $_POST['base'] ) ? $_POST['base'] : get_pagenum_link( 1 );

		echo '<div class="yz-posts-page" data-post-page="' . $posts_page . '">';

		// Show / Hide Post Elements
		$display_meta 		= yz_option( 'yz_display_post_meta', 'on' );
		$display_date 		= yz_option( 'yz_display_post_date', 'on' );
		$display_cats 		= yz_option( 'yz_display_post_cats', 'on' );
		$display_excerpt	= yz_option( 'yz_display_post_excerpt', 'on' );
		$display_readmore 	= yz_option( 'yz_display_post_readmore', 'on' );
		$display_comments 	= yz_option( 'yz_display_post_comments', 'on' );
		$display_meta_icons = yz_option( 'yz_display_post_meta_icons', 'on' );

		foreach ( $blogs_ids as $b ) {

		    switch_to_blog( $b->blog_id );

			// init WP Query
			$posts_query = new WP_Query( $args );

		?>


			<?php if ( $posts_query->have_posts() ) : $posts_exist = true; ?>

			<?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>

			<?php

				// Get Post Data
				$post 				= get_post( $args['author'] );
				$post_id 			= $posts_query->post->ID;
				$post_excerpt 		= yz_get_excerpt( get_the_content(), 25 );
				$post_comments_nbr 	= wp_count_comments( $post_id );

			?>

			<div class="yz-tab-post">

				<?php yz_get_post_thumbnail( array( 'attachment_id' => get_post_thumbnail_id( $post_id ), 'size' => 'medium', 'element' => 'profile-posts-tab' ) ); ?>

				<div class="yz-post-container">

					<div class="yz-post-inner-content">

						<div class="yz-post-head">

							<h2 class="yz-post-title">
								<a href="<?php the_permalink( $post_id ); ?>"><?php echo get_the_title( $post_id ); ?></a>
							</h2>

							<?php if ( 'on' == $display_meta ) : ?>

							<div class="yz-post-meta">

								<ul>

									<?php if ( 'on' == $display_date ) : ?>
										<li>
											<?php if ( 'on' == $display_meta_icons ) : ?>
												<i class="far fa-calendar-alt"></i>
											<?php endif; ?>
											<?php echo get_the_date( '', $post_id ); ?>
										</li>
									<?php endif; ?>

									<?php if ( 'on' == $display_cats ) : ?>

									<?php yz_get_post_categories( $post_id, $display_meta_icons ); ?>

									<?php endif; ?>

									<?php if ( 'on' == $display_comments ) : ?>
										<li>
											<?php if ( 'on' == $display_meta_icons ) : ?>
												<i class="far fa-comments"></i>
											<?php endif; ?>
											<?php echo $post_comments_nbr->total_comments; ?>
										</li>
									<?php endif; ?>

								</ul>

							</div>

							<?php endif; ?>

						</div>
						<?php if ( 'on' == $display_excerpt ) : ?>
						<div class="yz-post-text">
							<p><?php echo do_shortcode( $post_excerpt ); ?></p>
						</div>
						<?php endif; ?>

						<?php if ( 'on' == $display_readmore ) : ?>
							<a href="<?php the_permalink( $post_id ); ?>" class="yz-read-more">
								<div class="yz-rm-icon">
									<i class="fas fa-angle-double-right"></i>
								</div>
								<?php echo apply_filters( 'yz_profile_tab_posts_read_more_button', __( 'Read More', 'youzer' ) ); ?>
							</a>
						<?php endif; ?>

					</div>

				</div>

			</div>

			<?php endwhile;?>
			<?php wp_reset_postdata(); ?>
		    <?php $this->pagination( $posts_query->max_num_pages, $base ); ?>
			<?php endif; ?>


		<?php

		    restore_current_blog();
		}

		if ( ! $posts_exist ) {
			echo '<div class="yz-info-msg yz-failure-msg"><div class="yz-msg-icon"><i class="fas fa-exclamation-triangle"></i></div>
		 	<p>'. __( 'Sorry, no posts found!', 'youzer' ) . '</p></div>';
		}

		echo '</div>';
	}

	/**
	 * # Pagination.
	 */
	function pagination( $numpages = '', $base = null ) {

		// Get current Page Number
		$paged = ! empty( $_POST['page'] ) ? $_POST['page'] : 1 ;

		// Get Total Pages Number
		if ( $numpages == '' ) {
			global $wp_query;
			$numpages = $wp_query->max_num_pages;
			if ( ! $numpages ) {
				$numpages = 1;
			}
		}

		// Get Next and Previous Pages Number
		if ( ! empty( $paged ) ) {
			$next_page = $paged + 1;
			$prev_page = $paged - 1;
		}

		// Pagination Settings
		$pagination_args = array(
			'base'            		=> $base . '%_%',
			'format'          		=> 'page/%#%',
			'total'           		=> $numpages,
			'current'         		=> $paged,
			'show_all'        		=> False,
			'end_size'        		=> 1,
			'mid_size'        		=> 2,
			'prev_next'       		=> True,
			'prev_text'       		=> '<div class="yz-page-symbole">&laquo;</div><span class="yz-next-nbr">'. $prev_page .'</span>',
			'next_text'       		=> '<div class="yz-page-symbole">&raquo;</div><span class="yz-next-nbr">'. $next_page .'</span>',
			'type'            		=> 'plain',
			'add_args'        		=> false,
			'add_fragment'    		=> '',
			'before_page_number' 	=> '<span class="yz-page-nbr">',
			'after_page_number' 	=> '</span>',
		);

		// Call Pagination Function
		$paginate_links = paginate_links( $pagination_args );

		// Print Pagination
		if ( $paginate_links ) {
			echo sprintf( '<nav class="yz-pagination" data-base="%1s">' , $base );
			echo '<span class="yz-pagination-pages">';
			printf( __( 'Page %1$d of %2$d' , 'youzer' ), $paged, $numpages );
			echo "</span><div class='posts-nav-links yz-nav-links'>$paginate_links</div></nav>";
		}

	}

}