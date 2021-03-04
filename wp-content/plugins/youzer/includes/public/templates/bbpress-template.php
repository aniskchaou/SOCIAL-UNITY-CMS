<?php
/*
 * Template Name: Youzer - Bbpress Template
 * Description: Youzer Plugin Pages Template.
 */
get_header();

do_action( 'yz_before_youzer_template_content' );

?>

<div class="youzer <?php echo yz_forums_page_class(); ?>">
	
	<main class="yz-page-main-content">

		<div class="yz-main-column">
			<?php
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
				    the_content();
					endwhile;
				endif;
			?>
		</div>

		<?php if ( yz_show_forum_sidebar() ) : ?>
		<div class="yz-sidebar-column yz-forum-sidebar youzer-sidebar">
			<div class="yz-column-content">
				<?php do_action( 'yz_forum_sidebar' ); ?>
			</div>
		</div>
		<?php endif; ?>

	</main>

</div>

<?php

do_action( 'yz_after_youzer_template_content' );

get_footer();