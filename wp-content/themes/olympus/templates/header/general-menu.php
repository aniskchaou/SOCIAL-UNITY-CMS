<?php
/**
 * The template for displaying one of theme headers
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package olympus
 */
$olympus           = Olympus_Options::get_instance();
$customize_content = $olympus->get_option_final( 'header-general-top-customize-content', array() );

if ( olympus_akg( 'customize', $customize_content, 'no' ) === 'yes' ) {
	$menu_cart_icon = olympus_akg( 'yes/header-general-content-popup/menu_cart_icon', $customize_content, 'hide' );
} else {
	$menu_cart_icon = $olympus->get_option( 'menu_cart_icon', 'show', $olympus::SOURCE_CUSTOMIZER );
}
?>

<div class="header--standard" id="header--standard">
    <div class="container">
        <div class="row">
            <div class="col col-12 header--standard-wrap">
                <a href="<?php echo esc_url( home_url() ) ?>" class="logo">
					<?php
					$logo_text        = get_option( 'custom-logo-text', get_bloginfo( 'name' ) );
					$logo_description = get_option( 'custom-logo-description', get_bloginfo( 'description' ) );
					$logo_uppercase = get_option( 'custom-logo-uppercase', '1' );
					?>

					<?php if ( has_custom_logo() ) { ?>
                        <div class="img-wrap">
							<?php
							$logo_id = get_theme_mod( 'custom_logo' );
							$logo    = wp_get_attachment_image_src( $logo_id, 'full' );
							?>
                            <img src="<?php echo esc_url( $logo[0] ); ?>" alt="<?php echo esc_attr( $logo_text ); ?>">
                        </div>
					<?php } ?>

                    <div class="title-block <?php if($logo_uppercase == '1'){echo 'text-uppercase';} ?>">
                        <h6 class="logo-title"><?php olympus_render( $logo_text ); ?></h6>
                        <div class="sub-title"><?php olympus_render( $logo_description ); ?></div>
                    </div>
                </a>

                <nav class="primary-menu">

                    <a href="#" class="open-responsive-menu showhide">
                        <i class="olympus-icon-Menu-Icon"></i>
                        <i class="olympus-icon-Close-Icon"></i>
                    </a>

					<?php
					$menu_args = array(
						'theme_location' => 'primary',
						'menu_id'        => 'primary-menu',
						'menu_class'     => 'primary-menu-menu',
						'container'      => 'ul',
						'fallback_cb'    => 'olympus_menu_fallback'
					);

					if ( class_exists( 'Olympus_Mega_Menu_Custom_Walker' ) ) {
						$menu_args['walker'] = new Olympus_Mega_Menu_Custom_Walker();
					}

					wp_nav_menu( $menu_args );
					?>
					<?php if ( $menu_cart_icon === 'show' ) { ?>
                        <ul class="nav-add shoping-cart more">
							<?php get_template_part( "/templates/header/menu-item", "cart" ); ?>
                        </ul>
					<?php } ?>
                </nav>
            </div>
        </div>
    </div>
</div>