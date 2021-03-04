<?php

if ( !is_admin() ) {
    add_action( 'wp_enqueue_scripts', function () {
        $css = '';
        if (class_exists('Olympus_Options')) {
            $olympus = Olympus_Options::get_instance();
            $prefix = $olympus->olympus_stunning_get_option_prefix();
            // Stuning header
            $header_stunning_visibility       = $olympus->get_option_final( "header-stunning-visibility", 'default', array( 'final-source' => 'current-type' ) );
            $header_stunning_customize_styles = $olympus->get_option_final( 'header-stunning-customize/yes/header-stunning-customize-styles', array() );

            if ( olympus_akg( 'customize', $header_stunning_customize_styles, 'no' ) === 'yes' && $header_stunning_visibility !== 'default' ) {
                $sh_text_color     = olympus_akg( 'yes/header-stunning-styles-popup/stunning_text_color', $header_stunning_customize_styles, '' );
                $sh_padding_top    = olympus_akg( 'yes/header-stunning-styles-popup/stunning_padding_top', $header_stunning_customize_styles, '' );
                $sh_padding_bottom = olympus_akg( 'yes/header-stunning-styles-popup/stunning_padding_bottom', $header_stunning_customize_styles, '' );
                $sh_bg_cover       = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bg_animate_picker/no/stunning_bg_cover', $header_stunning_customize_styles, 'no' );
                $sh_bg_color       = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bg_color', $header_stunning_customize_styles, '' );
                $sh_bg_image       = olympus_akg( 'yes/header-stunning-styles-popup/stunning_bg_image/data/css/background-image', $header_stunning_customize_styles, '' );
            } else {
                $customizer = $olympus->get_option( "{$prefix}header-stunning-customizer", array(), $olympus::SOURCE_CUSTOMIZER );

                $sh_text_color     = olympus_akg( 'yes/stunning_text_color', $customizer, '' );
                $sh_padding_top    = olympus_akg( 'yes/stunning_padding_top', $customizer, '' );
                $sh_padding_bottom = olympus_akg( 'yes/stunning_padding_bottom', $customizer, '' );
                $sh_bg_cover       = olympus_akg( 'yes/stunning_bg_animate_picker/no/stunning_bg_cover', $customizer, 'no' );
                $sh_bg_color       = olympus_akg( 'yes/stunning_bg_color', $customizer, '' );
                $sh_bg_image       = olympus_akg( 'data/css/background-image', olympus_akg( 'yes/stunning_bg_image', $customizer, '' ), '' );
            }

            if ( $sh_text_color ) {
                $css .= "#stunning-header {color:{$sh_text_color};}";
                $css .= "#stunning-header .stunning-header-content-wrap {color:{$sh_text_color};}";
                $css .= "#stunning-header .stunning-header-content-wrap * {color:{$sh_text_color};}";
            }

            if ( $sh_padding_top ) {
                $css .= "#stunning-header {padding-top:{$sh_padding_top};}";
            }

            if ( $sh_padding_bottom ) {
                $css .= "#stunning-header {padding-bottom:{$sh_padding_bottom};}";
            }

            if ( 'yes' === $sh_bg_cover ) {
                $css .= "#stunning-header .crumina-heading-background {background-size: cover;}";
            }

            if ( $sh_bg_image && $sh_bg_image !== 'none' ) {
                $css .= "#stunning-header .crumina-heading-background {background-image: " . $sh_bg_image . ";}";
            }

            if ( $sh_bg_color ) {
                $css .= "#stunning-header {background-color:{$sh_bg_color};}";
            }
        }

        wp_add_inline_style( 'crumina-stunning-header', $css );
        wp_add_inline_style( 'olympus-minify', $css );
    });
}

