<?php

add_action( 'wp_enqueue_scripts', function () {
	$css     = '';
	$olympus = Olympus_Options::get_instance();

	// Root colors
	$primary_accent_color = get_option( 'primary-accent-color', '#ff5e3a' );
	$primary_color_darken = olympus_luminance( $primary_accent_color, - 0.1 );

	$secondary_accent_color = get_option( 'secondary-accent-color', '#38a9ff' );
	$secondary_color_darken = olympus_luminance( $secondary_accent_color, - 0.1 );

	$third_accent_color = get_option( 'third-accent-color', '#7c5ac2' );

	$icons_color = get_option( 'icons-color', '#9a9fbf' );

	$primary_font_color = $olympus->get_option( 'primary_font_color', '#939ba3', $olympus::SOURCE_CUSTOMIZER );
	$accent_font_color  = $olympus->get_option( 'accent_font_color', '#515365', $olympus::SOURCE_CUSTOMIZER );

	$css .= "html:root {\n";
	if ( $primary_font_color ) {
		$css .= "--body-font-color: {$primary_font_color};\n";
	}
	if ( $accent_font_color ) {
		$css .= "--heading-font-color: {$accent_font_color};\n";
	}

	if ( $primary_accent_color ) {
		$css .= "--primary-accent-color: {$primary_accent_color};\n";
	}
	if ( $primary_color_darken ) {
		$css .= "--primary-accent-color-darken: {$primary_color_darken};\n";
	}
	if ( $secondary_accent_color ) {
		$css .= "--secondary-accent-color: {$secondary_accent_color};\n";
	}
	if ( $secondary_color_darken ) {
		$css .= "--secondary-accent-color-darken: {$secondary_color_darken};\n";
	}
	if ( $third_accent_color ) {
		$css .= "--third-accent-color: {$third_accent_color};\n";
	}
	if ( $icons_color ) {
		$css .= "--icon-color: {$icons_color};\n";
	}

	$font_body = $olympus->get_option( 'typography_body', array(), $olympus::SOURCE_CUSTOMIZER );
	$font_body_family = olympus_akg( 'family', $font_body, 'Default' );
	$font_body_color  = olympus_akg( 'color', $font_body, '' );
	
	if ( ! empty( $font_body_family ) && 'Default' !== $font_body_family ) {
		$css .= "--body-font-family: {$font_body_family}, sans-serif;\n";
		$font_body_variant = olympus_akg( 'variation', $font_body, '' );
		if ( $font_body_variant ) {
			$css .= "--body-font-weight: {$font_body_variant};\n";
		} elseif ( false === $font_body['google_font'] ) {
			$css .= "--body-font-weight: {$font_body['weight']};\n";
		}
	}

	if ( !empty( $font_body_color ) ) {
		$css .= "--body-font-color: {$font_body_color};\n";
	}

	$font_body_size = olympus_akg( 'size', $font_body, '' );
	if ( ! empty( $font_body_size ) ) {
		$css .= "--body-font-size: {$font_body_size}px;\n";
	}

	$css .= '}';


	/* Left panel style Customization */

	$side_panel_bg    = get_option( 'side-panel-bg-color', '' );

	if ( $side_panel_bg ) {
		$css .= '.fixed-sidebar-left{ background-color: ' . $side_panel_bg . '}';
	}

	// Logo size
	$logo_max_height = get_option( 'custom-logo-height', '' );
	if ( $logo_max_height ) {
		$css .= ".header--standard .logo .img-wrap img {max-height:{$logo_max_height}px;}";
	}

	// Header social styles
	$header_social_bg_color        = $olympus->get_option( 'header_social_bg_color', '#3f4257', $olympus::SOURCE_CUSTOMIZER );
	$header_social_form_bg_color   = $olympus->get_option( 'header_social_form_bg_color', '#494c62', $olympus::SOURCE_CUSTOMIZER );
	$header_social_form_text_color = $olympus->get_option( 'header_social_form_text_color', '#9a9fbf', $olympus::SOURCE_CUSTOMIZER );
	$header_social_title_color     = $olympus->get_option( 'header_social_title_color', '#ffffff', $olympus::SOURCE_CUSTOMIZER );

	if ( $header_social_bg_color ) {
		$css .= "#site-header {background-color:{$header_social_bg_color};}";
	}

	if ( $header_social_form_bg_color ) {
		$css .= "#site-header .search-bar .form-group.with-button button {background-color:{$header_social_form_bg_color};}";
		$css .= "#site-header .search-bar.w-search {background-color:{$header_social_form_bg_color};}";
	}

	if ( $header_social_title_color ) {
		$css .= "#site-header .page-title > * {color:{$header_social_title_color};}";
		$css .= "#site-header .control-icon {color:{$header_social_title_color};}";
		$css .= "#site-header .control-block .author-title {color:{$header_social_title_color};}";
	}

	if ( $header_social_form_text_color ) {
		$css .= "#site-header .search-bar .form-group.with-button input {color:{$header_social_form_text_color};}";
		$css .= "#site-header .search-bar .form-group.with-button input::placeholder {color:{$header_social_form_text_color};}";
		$css .= "#site-header .search-bar .form-group.with-button button {color:{$header_social_form_text_color};}";
		$css .= "#site-header .control-block .author-subtitle {color:{$header_social_form_text_color};}";
	}

	// Header general styles
	$header_general_bg_color   = $olympus->get_option( 'header_general_bg_color', '#ffffff', $olympus::SOURCE_CUSTOMIZER );
	$header_general_logo_color = $olympus->get_option( 'header_general_logo_color', '#3f4257', $olympus::SOURCE_CUSTOMIZER );
	$header_general_cart_color = $olympus->get_option( 'header_general_cart_color', '#9a9fbf', $olympus::SOURCE_CUSTOMIZER );

	if ( $header_general_bg_color ) {
		$css .= "#header--standard {background-color:{$header_general_bg_color};}";
		$css .= "#header--standard .primary-menu {background-color:{$header_general_bg_color};}";
	}

	if ( $header_general_logo_color ) {
		$css .= "#header--standard .logo {color:{$header_general_logo_color};}";
	}

	if ( $header_general_cart_color ) {
		$css .= "#header--standard li.cart-menulocation > a {color:{$header_general_cart_color};}";
	}

	// Footer styles
	$footer_text_color  = $olympus->get_option( 'footer_text_color', '', $olympus::SOURCE_CUSTOMIZER );
	$footer_title_color = $olympus->get_option( 'footer_title_color', '', $olympus::SOURCE_CUSTOMIZER );
	$footer_link_color  = $olympus->get_option( 'footer_link_color', '', $olympus::SOURCE_CUSTOMIZER );
	$footer_bg_image    = olympus_akg( 'data/css/background-image', $olympus->get_option( 'footer_bg_image', '', $olympus::SOURCE_CUSTOMIZER ), '' );
	$footer_bg_cover    = $olympus->get_option( 'footer_bg_cover', '', $olympus::SOURCE_CUSTOMIZER );
	$footer_bg_color    = $olympus->get_option( 'footer_bg_color', '', $olympus::SOURCE_CUSTOMIZER );

	if ( $footer_text_color ) {
		$css .= "#footer {color:{$footer_text_color};}";
	}
	if ( $footer_bg_color ) {
		$css .= "#footer {background-color:{$footer_bg_color};}";
	}
	if ( $footer_bg_image ) {
		$css .= "#footer {background-image: {$footer_bg_image};}";

		if ( $footer_bg_cover ) {
			$css .= "#footer {background-size: cover;}";
		}
	}
	if ( $footer_title_color ) {
		$css .= "#footer .socials .soc-item {color:{$footer_title_color};}";
		$css .= "#footer .socials .soc-item:hover {color:{$footer_title_color}; opacity: 0.8;}";
		$css .= "#footer .logo-title {color:{$footer_title_color};}";
		$css .= "#footer .sub-title {color:{$footer_title_color};}";
		$css .= "#footer .title {color:{$footer_title_color};}";

		$css .= "#footer h1 {color:{$footer_title_color};}";
		$css .= "#footer h2 {color:{$footer_title_color};}";
		$css .= "#footer h3 {color:{$footer_title_color};}";
		$css .= "#footer h4 {color:{$footer_title_color};}";
		$css .= "#footer h5 {color:{$footer_title_color};}";
		$css .= "#footer h6 {color:{$footer_title_color};}";
	}
	if ( $footer_link_color ) {
		$css .= "#footer a {color:{$footer_link_color};}";
		$css .= "#footer a:hover {color:{$footer_link_color}; opacity: 0.8;}";
	}

	//Back to top btn
	$totop_bg_color   = $olympus->get_option( 'totop_bg_color', array(), $olympus::SOURCE_CUSTOMIZER );
	$totop_icon_color = $olympus->get_option( 'totop_icon_color', array(), $olympus::SOURCE_CUSTOMIZER );
	if ( $totop_bg_color ) {
		$css .= "#back-to-top {background-color:{$totop_bg_color};}";
	}
	if ( $totop_icon_color ) {
		$css .= "#back-to-top i {color:{$totop_icon_color};}";
	}

	// Font styles
	$css .= olympus_generate_font_styles( 'h1' );
	$css .= olympus_generate_font_styles( 'h2' );
	$css .= olympus_generate_font_styles( 'h3' );
	$css .= olympus_generate_font_styles( 'h4' );
	$css .= olympus_generate_font_styles( 'h5' );
	$css .= olympus_generate_font_styles( 'h6' );
	$css .= olympus_generate_font_styles( 'nav' );
	$css .= olympus_generate_font_styles( 'left_menu' );

	// Customize general design
	$general_body_bg_color = olympus_general_body_bg_color();

	if ( ! empty( $general_body_bg_color ) ) {
		$css .= "body.olympus-theme.bg-custom-color {background-color: " . $general_body_bg_color . ";}";
		$css .= "html:root {--body-bg-color: {$general_body_bg_color};}";
	}

	$general_body_bg_image = olympus_general_body_bg_image();

	if ( ! empty( $general_body_bg_image['background-image'] ) ) {
		$css .= "body.olympus-theme.bg-custom-image {background-image: url(" . $general_body_bg_image['background-image'] . ");}";
	}

	if ( ! empty( $general_body_bg_image['background-position'] ) ) {
		$css .= "body.olympus-theme.bg-custom-image {background-position: " . $general_body_bg_image['background-position'] . ";}";
	}

	if ( ! empty( $general_body_bg_image['background-size'] ) ) {
		$css .= "body.olympus-theme.bg-custom-image {background-size: " . $general_body_bg_image['background-size'] . ";}";
	}

	if ( ! empty( $general_body_bg_image['background-repeat'] ) ) {
		$css .= "body.olympus-theme.bg-custom-image {background-repeat: " . $general_body_bg_image['background-repeat'] . ";}";
	}

	if ( ! empty( $general_body_bg_image['background-attachment'] ) ) {
		$css .= "body.olympus-theme.bg-custom-image {background-attachment: " . $general_body_bg_image['background-attachment'] . ";}";
	}

	// Grid bg
	$post_thumb_bg_color = get_option( 'post-thumb-bg-color', '#ffffff' );

	if ( $post_thumb_bg_color ) {
		$css .= ".may-contain-custom-bg .ui-block {background-color: {$post_thumb_bg_color}}";
	}

	//Side panel bg
	$side_panel_bg_color = get_option( 'side-panel-bg-color', '#ffffff' );
	if ( $side_panel_bg_color ) {
		$css .= ".fixed-sidebar-left {background-color: {$side_panel_bg_color}}";
	}

	// Custom container width
	$content_width        = $olympus->get_option_final( "full-content", 'default', array( 'final-source' => 'current-type' ) );
	$container_width      = $olympus->get_option_final( 'custom-content-width/container/custom-container-width', 1170 );
	$global_content_width = $olympus->get_option( "full-content", 'default', $olympus::SOURCE_CUSTOMIZER );
	if ( $content_width == 'default' ) {
		$content_width   = $global_content_width;
		$container_width = $olympus->get_option( 'custom-content-width/container/custom-container-width', 1170, $olympus::SOURCE_CUSTOMIZER );
	}

	if ( isset( $content_width ) && $content_width == 'container' ) {
		$css .= "body.boxed-width #primary {max-width: {$container_width}px; margin: 0 auto;}";
	}
	// Row gaps
	$sections_padding               = $olympus->get_option( 'sections_padding/sections_padding_picker', 'medium', $olympus::SOURCE_CUSTOMIZER );
	$sections_padding_custom_top    = $olympus->get_option( 'sections_padding/custom/top', '100', $olympus::SOURCE_CUSTOMIZER );
	$sections_padding_custom_bottom = $olympus->get_option( 'sections_padding/custom/bottom', '100', $olympus::SOURCE_CUSTOMIZER );
	if ( $sections_padding == 'small' ) {
		$sections_padding_val_top    = 40;
		$sections_padding_val_bottom = 40;
	} elseif ( $sections_padding == 'medium' ) {
		$sections_padding_val_top    = 80;
		$sections_padding_val_bottom = 80;
	} elseif ( $sections_padding == 'large' ) {
		$sections_padding_val_top    = 120;
		$sections_padding_val_bottom = 120;
	} elseif ( $sections_padding == 'custom' ) {
		$sections_padding_val_top    = $sections_padding_custom_top;
		$sections_padding_val_bottom = $sections_padding_custom_bottom;
	}

	$css .= ".elementor-section:not(.elementor-inner-section),body.olympus-theme div.section-theme-padding{padding-top: {$sections_padding_val_top}px; padding-bottom: {$sections_padding_val_bottom}px;}";

	// Preloader
	$preloader          = $olympus->get_option( 'enable-preloader', 'no', $olympus::SOURCE_CUSTOMIZER );
	$preloader_bg_color = $olympus->get_option( 'preloader-settings/yes/background-color', '#ffffff', $olympus::SOURCE_CUSTOMIZER );
	if ( $preloader == 'yes' ) {
		$css .= ".olympus-preloader {background-color: {$preloader_bg_color}}";
	}

	$badge_background_color = (isset(get_option('yz_verified_badge_background_color')['color']) && get_option('yz_verified_badge_background_color')['color'] != '') ? get_option('yz_verified_badge_background_color')['color'] : '';
	if($badge_background_color != ''){
		$css .= ".yz-account-verified, .icon-status.online {background-color: {$badge_background_color} !important}";
		$css .= "#yz-members-list .is-online .yz-item-avatar::before {border-color: {$badge_background_color} !important}";
	}
	  

	wp_add_inline_style( 'olympus-main', $css );
	wp_add_inline_style( 'olympus-minify', $css );
	
}, 9999 );