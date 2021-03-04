<?php
$olympus = Olympus_Options::get_instance();
$prefix  = $olympus->olympus_stunning_get_option_prefix();

$customizer = $olympus->get_option( "{$prefix}header-stunning-customizer", array(), $olympus::SOURCE_CUSTOMIZER );
$visible    = olympus_stunning_header_is_visible();
if ( ! $visible || is_404() ) {
	return;
}

if(function_exists('yz_is_404_profile')){
    if ( yz_is_404_profile() ) {
        return;
    }
}

$ctype_visibility = $olympus->get_option_final( "header-stunning-visibility", 'default', array( 'final-source' => 'current-type' ) );

$classes           = apply_filters( 'fw_ext_stunning_header_container_classes', array( 'crumina-stunning-header' ) );
$bg_image_default  = '';
$customize_content = $olympus->get_option_final( 'header-stunning-customize/yes/header-stunning-customize-content', array() );
if ( olympus_akg( 'customize', $customize_content, 'no' ) === 'yes' && $ctype_visibility !== 'default' ) {
	$title_show       = olympus_akg( 'yes/header-stunning-content-popup/stunning_title_show/show', $customize_content, 'yes' );
	$breadcrumbs_show = olympus_akg( 'yes/header-stunning-content-popup/stunning_breadcrumbs_show', $customize_content, 'yes' );
	$title_text       = olympus_akg( 'yes/header-stunning-content-popup/stunning_title_show/yes/title', $customize_content, '' );
	$text             = olympus_akg( 'yes/header-stunning-content-popup/stunning_text', $customize_content, '' );
} else {
	$title_show       = olympus_akg( 'yes/stunning_title_show/show', $customizer, 'yes' );
	$breadcrumbs_show = olympus_akg( 'yes/stunning_breadcrumbs_show', $customizer, 'yes' );
	$title_text       = olympus_akg( 'yes/stunning_title_show/yes/title', $customizer, '' );
	$text             = olympus_akg( 'yes/stunning_text', $customizer, '' );
}

$customize_styles = $olympus->get_option_final( 'header-stunning-customize/yes/header-stunning-customize-styles', array() );
if ( olympus_akg( 'customize', $customize_styles, 'no' ) === 'yes' && $ctype_visibility !== 'default' ) {
	$bottom_image    = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bottom_image/url', $customize_styles, '' );
	$text_align      = olympus_akg( 'yes/header-stunning-styles-popup/stunning_text_align', $customize_styles, '' );
	$bg_animate      = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bg_animate_picker/stunning_bg_animate', $customize_styles, 'yes' );
	$bg_animate_type = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bg_animate_picker/yes/stunning_bg_animate_type', $customize_styles, 'fixed' );
	$bg_image        = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bg_image/data/css/background-image', $customize_styles, $bg_image_default );
} else {
	$bottom_image    = olympus_akg( 'yes/stunning_bottom_image/url', $customizer, '' );
	$text_align      = olympus_akg( 'yes/stunning_text_align', $customizer, '' );
	$bg_animate      = olympus_akg( 'yes/stunning_bg_animate_picker/stunning_bg_animate', $customizer, 'yes' );
	$bg_animate_type = olympus_akg( 'yes/stunning_bg_animate_picker/yes/stunning_bg_animate_type', $customizer, 'fixed' );
	$bg_image        = olympus_akg( 'data/css/background-image', olympus_akg( 'yes/stunning_bg_image', $customizer, '' ), $bg_image_default );
}

//Add addit classes for container
if ( 'yes' === $bg_animate ) {
	$classes[] = 'crumina-stunning-header--with-animation';
}

if ( $bottom_image ) {
	$classes[] = 'has-img-bottom';
}

if ( is_search() ) {
	$text_align = 'stunning-header--content-center';
	$classes[]  = 'stunning-search';
}

$classes[] = $text_align;

?>
	<section id="stunning-header" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
			 data-animate-type="<?php echo esc_attr( $bg_animate_type ); ?>">
		<?php
		if ( $bg_image && $bg_image !== 'none' ) {
			$bg_classes = array( 'crumina-heading-background' );
			?>
			<div class="<?php echo esc_attr( implode( ' ', $bg_classes ) ); ?>"></div>
		<?php } ?>

		<div class="container">

			<div class="stunning-header-content-wrap">
				<?php if ( $title_show === 'yes' ) {
					echo '<div class="stunning-content-item">';

					if ( ! empty( $title_text ) ) {
						echo '<h1 class="stunning-header-title">' . esc_html( $title_text ) . '</h1>';
					} elseif ( is_home() ) {
						?>
						<h1 class="stunning-header-title"><?php esc_html_e( 'Latest posts', 'olympus' ); ?></h1>
					<?php } elseif ( is_search() ) { ?>
						<span class="stunning-header-title h1 page-title">
                        <?php printf( esc_html__( 'Search Results for: %s', 'olympus' ), '<h1 class="stunning-header-title d-inline">"' . get_search_query() . '"</h1>' ); ?>
                    </span>
					<?php } elseif ( is_404() ) { ?>
						<h1 class="stunning-header-title"><?php esc_html_e( '404 Error Page', 'olympus' ); ?></h1>
						<?php
					} elseif ( function_exists( 'is_shop' ) && is_shop() ) {
						if ( is_shop() && apply_filters( 'woocommerce_show_page_title', true ) ) {
							?>
							<h2 class="stunning-header-title h1"><?php woocommerce_page_title(); ?></h2>
						<?php } elseif ( is_product() ) { ?>
							<h2 class="stunning-header-title h1"><?php esc_html_e( 'Product Details', 'olympus' ); ?></h2>
							<?php
						} elseif ( is_cart() || is_checkout() || is_checkout_pay_page() ) {
							the_title( '<h1 class="stunning-header-title h1">', '</h1>' );
						}
					} elseif ( is_page() || is_singular( 'fw-portfolio' ) || is_singular( 'post' ) ) {
						the_title( '<h1 class="stunning-header-title">', '</h1>' );
					} elseif ( function_exists( 'tribe_is_event_query' ) && tribe_is_event_query() ) {
						?>
						<h1 class="stunning-header-title"><?php esc_html_e( 'Events', 'olympus' ); ?></h1>
						<?php
					} elseif ( is_archive() ) {
						if ( is_tag() ) { ?>
							<h1 class="stunning-header-title"><?php single_tag_title( '', true ); ?></h1>
							<?php
						} else {
							?>
							<h1 class="stunning-header-title"><?php the_archive_title(); ?></h1>
						<?php }
					} elseif ( is_tax() ) {
						echo '<h1 class="stunning-header-title">' . esc_html( get_queried_object()->name ) . '</h1>';
					} else {
						the_title( '<h1 class="stunning-header-title">', '</h1>' );
					}

					echo '</div>';
				}

				if ( ! empty( $text ) ) { ?>
					<div class="stunning-content-item">
						<div class="stunning-header-text">
							<?php if ( ! empty( $text ) ) {
								global $allowedtags;
								echo wp_kses( do_shortcode( $text ), $allowedtags );
							} else if ( is_category() ) {
								echo category_description();
							}
							?>
						</div>
					</div>
				<?php }

				if ( 'yes' === $breadcrumbs_show && function_exists( 'fw_ext_breadcrumbs' ) && ! is_search() ) {
					echo '<div class="stunning-content-item">';
					fw_ext_breadcrumbs( '/' );
					echo '</div>';
				}
				?>

			</div>
			<?php
			if ( is_search() ) {
				$s_query = filter_input( INPUT_GET, 's', FILTER_SANITIZE_STRING );
				?>
				<section class="search-page-panel">
					<div class="container">
						<div class="row">
							<div class="col col-xl-12 m-auto col-lg-12 col-md-12 col-sm-12 col-12">
								<form class="form-inline search-form" action="<?php echo esc_url( home_url() ); ?>" method="GET">
									<div class="form-group label-floating">
										<label class="control-label" for="s"><?php esc_html_e( 'What do you search?', 'olympus' ); ?></label>
										<input class="form-control bg-white" name="s" type="text"
											   value="<?php echo esc_attr( $s_query ); ?>">
									</div>

									<button class="btn btn-purple btn-lg" type="submit"><?php esc_html_e( 'Search', 'olympus' ); ?></button>
								</form>
							</div>
						</div>
					</div>
				</section>
			<?php }
			if ( $bottom_image ) { ?>
				<div class="stunning-header-img-bottom">
					<img src="<?php echo esc_attr( $bottom_image ); ?>"
						 alt="<?php esc_attr_e( 'Bottom image', 'olympus' ); ?>" loading="lazy">
				</div>
			<?php } ?>

		</div>
	</section>
<?php

function olympus_stunning_header_is_visible() {
	$olympus = Olympus_Options::get_instance();
	$prefix  = $olympus->olympus_stunning_get_option_prefix();
	if ( ! is_search() ) {
		$visibility = $olympus->get_option_final( "header-stunning-visibility", 'default', array( 'final-source' => 'current-type' ) );
	} else {
		$visibility = 'default';
	}
	$ctype_visibility = $olympus->get_option( "{$prefix}header-stunning-visibility", "yes", $olympus::SOURCE_CUSTOMIZER );
	if ( $visibility == 'default' ) {
		$visibility = $ctype_visibility;
	}

	$visibility = apply_filters( 'fw_ext_stunning_header_visibility', $visibility );

	return $visibility === 'yes' ? true : false;
}