<?php if ( bp_has_groups() ) : while ( bp_groups() ) : bp_the_group(); ?>

<?php do_action( 'youzer_group_before_group' ); ?>
<div id="youzer">

<div id="<?php echo apply_filters( 'yz_group_template_id', 'yz-bp' ); ?>" class="youzer <?php echo yz_group_page_class(); ?>">

	<?php do_action( 'youzer_group_before_content' ); ?>

	<div class="yz-content">

		<header id="yz-group-header" class="<?php echo yz_headers()->get_class( 'group' ); ?>">

			<?php do_action( 'youzer_group_header' ); ?>

		</header>

		<div class="yz-group-content">

			<div class="yz-inner-content">

				<?php do_action( 'youzer_group_navbar' ); ?>

				<main class="yz-page-main-content">

					<?php do_action( 'yz_group_main_content' ); ?>

				</main>

			</div>

		</div>

		<?php  do_action( 'youzer_group_sidebar' ); ?>

	</div>

	<?php do_action( 'youzer_group_after_content' ); ?>

</div>

</div>
<?php do_action( 'youzer_group_after_group' ); ?>

<?php endwhile; endif; ?>