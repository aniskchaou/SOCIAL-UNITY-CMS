<?php
/**
 * The template for displaying search page
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package olympus
 */

$query = get_search_query();
?>

<?php get_header(); ?>

	<div id="primary">
		<?php
		$search_ext = Olympus_Core::get_extension( 'extended-search' );

		if ( $search_ext ) {
			olympus_render(
				$search_ext->get_view(
					'search', array(
					'ext'  => $search_ext,
					'atts' => array()
				)
				)
			);


		} else { ?>
			<div id="primary">
				<?php 
				$olympus = Olympus_Options::get_instance();
				$visibility = $olympus->get_option( "header-stunning-visibility", 'yes', $olympus::SOURCE_CUSTOMIZER );
				if( $visibility !== 'yes' ){ ?>
				<section class="search-page-panel simple-serach">
					<div class="container">
						<div class="row">
							<div class="col col-xl-10 m-auto col-lg-10 col-md-12 col-sm-12 col-12">
								<form class="form-inline search-form" action="<?php echo esc_url( home_url() ); ?>" method="GET">
									<div class="form-group label-floating">
										<label class="control-label" for="s"><?php esc_html_e( 'What do you search?', 'olympus' ); ?></label>
										<input class="form-control bg-white" name="s" type="text"
											   value="<?php echo esc_attr( $query ); ?>">
									</div>

									<button class="btn btn-purple btn-lg" type="submit"><?php esc_html_e( 'Search', 'olympus' ); ?></button>
								</form>
							</div>
						</div>
					</div>
				</section>
				<?php } ?>

				<section class="primary-content-wrapper">
					<div class="container">
						<div class="row">
							<div class="col col-xl-10 m-auto col-lg-10 col-md-12 col-sm-12 col-12">
								<!-- Search Help Result -->
								<?php if ( $query ) { ?>
									<div class="search-help-result">
										<h3 class="search-help-result-title">
											<span class="count-result"><?php olympus_render( $wp_query->found_posts, ' ', _n( 'result', 'results', $wp_query->found_posts, 'olympus' ) ); ?></span>
											<?php esc_html_e( 'found', 'olympus' ); ?>
										</h3>
										<?php if ( have_posts() ) { ?>
											<ul class="search-help-result-list">
												<?php while ( have_posts() ) : the_post(); ?>
													<li>
														<?php
														$post_type = get_post_type();
														get_template_part( 'templates/post/search/content', $post_type );
														?>
													</li>
												<?php endwhile; ?>
											</ul>
										<?php } else {
											?>
											<div class="row">
												<?php get_template_part( 'templates/content/content-search-none' ); ?>
											</div>
											<?php
										}
										?>

									</div>
									<?php get_template_part( 'templates/paginate/numbers' ); ?>
								<?php } else { ?>
									<h2 class="search-help-result-title text-danger"><?php esc_html_e( 'Search query is empty!', 'olympus' ); ?></h2>
								<?php } ?>
								<!-- ... end Search Help Result -->
							</div>
						</div>
					</div>
				</section>
			</div><!-- #primary -->
		<?php }
		?>
	</div><!-- #primary -->

<?php get_footer(); ?>