<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package olympus
 */
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'footer' ) ) {

if ( 'landing-template.php' !== get_page_template_slug() ) {

	global $allowedtags;
	global $allowedposttags;
	$my_theme = wp_get_theme();

	$footer_class = array( 'footer' );

	$olympus		 = Olympus_Options::get_instance();
	$to_top_options	 = $olympus->get_option( 'scroll_top_icon', array(), $olympus::SOURCE_CUSTOMIZER );
	$to_top_show	 = olympus_akg( 'value', $to_top_options, 'show' );

	$description_options	 = $olympus->get_option( 'site_description', false, $olympus::SOURCE_CUSTOMIZER );
	$description_enable		 = olympus_akg( 'show', $description_options, 'no' );
	$show_footer_logo		 = olympus_akg( 'yes/footer-logo/show', $description_options, 'no' );
	$description_logo		 = olympus_akg( 'yes/footer-logo/yes/logo-options', $description_options, false );
	$description_text		 = olympus_akg( 'yes/text', $description_options, '' );
	$description_text_align	 = olympus_akg( 'yes/text-alignment', $description_options, false );
	$description_columns	 = olympus_akg( 'yes/text_columns', $description_options, 0 );
	$description_socials	 = olympus_akg( 'yes/social_networks', $description_options, array() );

	$footer_wide_content = $olympus->get_option( 'footer_wide_content', 'container', $olympus::SOURCE_CUSTOMIZER );
	$footer_copyright	 = $olympus->get_option( 'footer_copyright', 'Copyright &copy; ' . date( "Y" ) . ' <a href="' . esc_url( $my_theme->get( 'AuthorURI' ) ) . '"></a>Premium WordPress theme ' . esc_url( $my_theme->get( 'Theme Name' ) ), $olympus::SOURCE_CUSTOMIZER );

	$desc_columns_class = 'col-lg-' . $description_columns . ' col-md-' . $description_columns . ' col-sm-12 col-xs-12';

	if ( 'yes' === $description_enable ) {
		$column = intval( 12 - $description_columns );
		if ( $column < 2 ) {
			$column = 12;
		}
		$sidebar_columns = 'col-lg-' . $column . ' col-md-' . $column . ' col-sm-12 col-xs-12';
	} else {
		$sidebar_columns = 'col-lg-12 col-md-12 col-sm-12 col-xs-12';
	}

	if ( empty( $footer_copyright ) ) {
		$footer_class[] = 'has-not-copyright';
	}
	?>

	</div><!-- #content -->

	<!-- Footer -->
	<section class="<?php echo esc_attr( implode( ' ', $footer_class ) ) ?>" id="footer" x-data="{ olympusAtTop: true }">
	    <div class="<?php echo esc_attr( $footer_wide_content ); ?>">

			<?php
			ob_start();

			if ( ( 'yes' === $description_enable && (!empty( $description_logo ) || !empty( $description_text ) ) ) ||
					( 'yes' === $description_enable && (!empty( $description_socials ) && is_array( $description_socials ) ) )
			) {
				?>
				<div class="<?php echo esc_attr( $desc_columns_class ) ?>">
					<div class="w-about widget" itemscope itemtype="http://schema.org/Organization">
						<?php
						if ( 'yes' === $show_footer_logo ) {
							echo '<div class="logo-block">';
							olympus_logo( $description_logo, $description_text_align );
							echo '</div>';
						}
						?>
						<div class="w-about-content-wrap <?php echo esc_attr($description_text_align); ?>">
						<?php
						if ( !empty( $description_text ) ) {
							echo wp_kses( wpautop( $description_text ), $allowedposttags );
						}
						?>
						<?php if ( !empty( $description_socials ) && is_array( $description_socials ) ) { ?>
							<ul class="socials">
								<?php
								foreach ( $description_socials as $social ) {
									if ( !$social[ 'link' ] || !$social[ 'icon' ] ) {
										continue;
									}
									?>
									<li>
										<a class="soc-item" target="_blank" href="<?php echo esc_attr( $social[ 'link' ] ); ?>">
											<?php get_template_part( "/templates/socials/{$social[ 'icon' ]}" ); ?>
										</a>
									</li>
								<?php } ?>
							</ul>
						<?php } ?>
					</div>
					</div>
				</div>
			<?php } ?>
			<?php if ( is_active_sidebar( 'sidebar-footer' ) ) { ?>
				<div class="<?php echo esc_attr( $sidebar_columns ); ?>">
					<div class="row">
						<?php
						ob_start();
						dynamic_sidebar( 'sidebar-footer' );
						$output					 = ob_get_clean();
						$footer_sibebar_columns	 = $olympus->get_option( 'footer-widget-block-columns', 3, $olympus::SOURCE_CUSTOMIZER );
						$footer_sibebar_columns	 = 'col-lg-' . $footer_sibebar_columns . ' col-md-6 col-sm-6 col-xs-12 mb-4 mb-lg-0';
						echo str_replace( 'columns_class_replace', $footer_sibebar_columns, $output );
						?>
					</div>
				</div>
				<?php
			}
			$footer_content = ob_get_clean();

			if ( !empty( trim( $footer_content ) ) ) {
				echo olympus_html_tag( 'div', array( 'class' => 'row footer-content' ), $footer_content );
			}
			?>

			<?php if ( !empty( $footer_copyright ) ) { ?>
				<!-- SUB Footer -->
				<div class="sub-footer-copyright">
					<span class="site-copyright-text">
						<?php echo wp_kses( $footer_copyright, $allowedtags ) ?>
					</span>
				</div>
				<!-- ... end SUB Footer -->
			<?php } ?>
			<?php if ( 'show' === $to_top_show ) { ?>
				<span class="smooth-scroll"
					  @scroll.window="olympusAtTop = (window.pageYOffset > 800) ? false : true"
					  x-cloak>
					<a id="back-to-top" data-scroll class="back-to-top" href="#"
					   :class="{ 'btt-visible' : !olympusAtTop }"
					>
						<?php echo olympus_icon_font( 'olympus-icon-Back-to-Top-Arrow-Icon back-icon' ) ?>
					</a>
				</span>
			<?php } ?>
	    </div>
	</section>
	<!-- ... end Footer -->
<?php
} else {
	echo '</div>';
}

}else{
	echo '</div>';
}
?>
<?php wp_footer(); ?>

</div>
</body>
</html>
