"use strict"; 

/**
 * File customizer.js.
 *
 * Theme Customizer enhancements for a better user experience.
 *
 * Contains handlers to make Theme Customizer preview reload changes asynchronously.
 */

( function ( $ ) {
    // Colors Customize
    let root = document.documentElement;

    wp.customize( 'primary-accent-color', function ( value ) {
        value.bind( function ( newval ) {
            if ( newval ) {
                root.style.setProperty('--primary-accent-color', newval);
            } else {
                root.style.setProperty('--primary-accent-color', '#ff5e3a' );
            }
        } );
    } );

    wp.customize( 'secondary-accent-color', function ( value ) {
        value.bind( function ( newval ) {
            if ( newval ) {
                root.style.setProperty('--secondary-accent-color', newval);
            } else {
                root.style.setProperty('--secondary-accent-color', '#38a9ff' );
            }
        } );
    } );
    wp.customize( 'third-accent-color', function ( value ) {
        value.bind( function ( newval ) {
            if ( newval ) {
                root.style.setProperty('--third-accent-color', newval);
            } else {
                root.style.setProperty('--third-accent-color', '#7c5ac2' );
            }
        } );
    } );
    wp.customize( 'icons-color', function ( value ) {
        value.bind( function ( newval ) {
            if ( newval ) {
                root.style.setProperty('--icon-color', newval);
            } else {
                root.style.setProperty('--icon-color', '#9a9fbf' );
            }
        } );
    } );


    wp.customize( 'fw_options[primary_font_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                root.style.setProperty('--body-font-color', newval[0]['value']);
            } else {
                root.style.setProperty('--body-font-color', '#939ba3' );
            }
        } );
    } );
    wp.customize( 'fw_options[accent_font_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                root.style.setProperty('--heading-font-color', newval[0]['value']);
            } else {
                root.style.setProperty('--heading-font-color', '#939ba3' );
            }
        } );
    } );

    // Header social styles
    var $siteHeader = $( '#site-header' );

    wp.customize( 'fw_options[header_social_bg_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $siteHeader.css( { 'background-color': newval[0]['value'] } );
            } else {
                $siteHeader.css( { 'background-color': '#3f4257' } );
            }

        } );
    } );

    wp.customize( 'fw_options[header_social_form_bg_color]', function ( value ) {
        value.bind( function ( newval ) {
            var $elements = $( '.search-bar .form-group.with-button button, .search-bar.w-search', $siteHeader );

            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                $elements.css( { 'background-color': newval[0]['value'] } );
            } else {
                $elements.css( { 'background-color': '#494c62' } );
            }

        } );
    } );

    wp.customize( 'fw_options[header_social_form_text_color]', function ( value ) {
        value.bind( function ( newval ) {
            var $elements = $( '.search-bar .form-group.with-button input, .search-bar .form-group.with-button button, .control-block .author-subtitle', $siteHeader );

            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                $elements.css( { 'color': newval[0]['value'] } );
            } else {
                $elements.css( { 'color': '#9a9fbf' } );
            }

        } );
    } );

    wp.customize( 'fw_options[header_social_title_color]', function ( value ) {
        value.bind( function ( newval ) {
            var $elements = $( '.page-title > *, .control-icon, .control-block .author-title', $siteHeader );

            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                $elements.css( { 'color': newval[0]['value'], 'fill': newval[0]['value'] } );
            } else {
                $elements.css( { 'color': '#ffffff', 'fill': '#ffffff' } );
            }

        } );
    } );

    // Header general styles
    var $headerStandard = $( '#header--standard' );

    wp.customize( 'fw_options[header_general_bg_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $headerStandard.css( { 'background-color': newval[0]['value'] } );
                $( '.primary-menu', $headerStandard ).css( { 'background-color': newval[0]['value'] } );
            } else {
                $headerStandard.css( { 'background-color': '#ffffff' } );
                $( '.primary-menu', $headerStandard ).css( { 'background-color': '#ffffff' } );
            }

        } );
    } );

    wp.customize( 'fw_options[header_general_menu_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $( '.primary-menu-menu > li > a', $headerStandard ).css( { 'color': newval[0]['value'] } );
            } else {
                $( '.primary-menu-menu > li > a', $headerStandard ).css( { 'color': '#515365' } );
            }

        } );
    } );

    wp.customize( 'fw_options[header_general_logo_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $( '.logo', $headerStandard ).css( { 'color': newval[0]['value'] } );
            } else {
                $( '.logo', $headerStandard ).css( { 'color': '#3f4257' } );
            }

        } );
    } );

    wp.customize( 'fw_options[header_general_cart_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                $( 'li.cart-menulocation > a', $headerStandard ).css( { 'color': newval[0]['value'] } );
            } else {
                $( 'li.cart-menulocation > a', $headerStandard ).css( { 'color': '#9a9fbf' } );
            }

        } );
    } );
    // Left Panel Customization
    wp.customize( 'side-panel-bg-color', function ( value ) {
        value.bind( function ( newval ) {
            if ( newval ) {
                $( '.fixed-sidebar-left' ).css( { 'background-color': newval } );
            } else {
                $( '.fixed-sidebar-left' ).css( { 'background-color': '#fff' } );
            }
        } );
    } );

    //Youzer styles

    wp.customize( 'fw_options[enable_youzer_styles]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );
            if ( newval[0]['value'] === 'no' ) {
                $("link[href$='yz-icons-customization.css']").prop('disabled', true);
            } else {
                $("link[href$='yz-icons-customization.css']").prop('disabled', false);
            }
        } );
    } );


    //Footer styles
    var $footer = $( '#footer' );

    wp.customize( 'fw_options[footer_wide_content]', function ( value ) {
        value.bind( function ( newval ) {
            var $container = $( ' > div:first', $footer );

            newval = JSON.parse( newval );
            if ( newval[0]['value'].indexOf( 'container-fluid' ) === -1 ) {
                $container.removeClass( 'container-fluid' ).addClass( 'container' );
            } else {
                $container.removeClass( 'container' ).addClass( 'container-fluid' );
            }
        } );
    } );

    wp.customize( 'fw_options[footer_text_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $footer.css( { 'color': newval[0]['value'] } );
            } else {
                $footer.css( { 'color': '' } );
            }

        } );
    } );

    wp.customize( 'fw_options[footer_bg_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $footer.css( { 'backgroundColor': newval[0]['value'] } );
            } else {
                $footer.css( { 'backgroundColor': '' } );
            }

        } );
    } );

    wp.customize( 'fw_options[footer_bg_image]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0].value === 'custom' ) {
                $footer.css( { 'backgroundImage': 'url(' + newval[2].value + ')' } );
            }

        } );
    } );

    wp.customize( 'fw_options[footer_bg_cover]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0].value === 'true' ) {
                $footer.css( { 'backgroundSize': 'cover' } );
            } else {
                $footer.css( { 'backgroundSize': '' } );
            }

        } );
    } );

    wp.customize( 'fw_options[footer_title_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $( '.socials .soc-item, .logo-title, .sub-title, .title, h1, h2, h3, h4, h5, h6', $footer ).css( { 'color': newval[0]['value'] } );
            } else {
                $( '.socials .soc-item, .logo-title, .sub-title, .title, h1, h2, h3, h4, h5, h6', $footer ).css( { 'color': '' } );
            }

        } );
    } );

    wp.customize( 'fw_options[footer_link_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $( 'a:not(.soc-item)', $footer ).css( { 'color': newval[0]['value'] } );
            } else {
                $( 'a:not(.soc-item)', $footer ).css( { 'color': '' } );
            }

        } );
    } );

    //Back to top btn
    var $btt = $( '#back-to-top' );

    wp.customize( 'fw_options[totop_bg_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );
            if ( newval[0]['value'] ) {
                $btt.css( { 'backgroundColor': newval[0]['value'] } );
            } else {
                $btt.css( { 'backgroundColor': '' } );
            }

        } );
    } );

    wp.customize( 'fw_options[totop_icon_color]', function ( value ) {
        value.bind( function ( newval ) {
            newval = JSON.parse( newval );

            if ( newval[0]['value'] ) {
                $( 'i', $btt ).css( { 'color': newval[0]['value'] } );
            } else {
                $( 'i', $btt ).css( { 'color': '' } );
            }

        } );
    } );

} )( jQuery );

