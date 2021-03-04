<?php

/**
 * is Ajax Login Enabled
 */
add_action( 'wp_footer', 'logy_get_popup_login_form' );

function logy_get_popup_login_form() {

    ?>

    <div class="yz-popup-login" style="opacity: 0; visibility: hidden;">
        <?php echo do_shortcode( '[youzer_login]' ); ?>
    </div>
    <script type="text/javascript">
    ( function( $ ) {

        $( document ).ready( function() {

            // Add Close Button to Login Popup.
            $( '.yz-popup-login .logy-form-header' )
            .append( '<i class="fas fa-times yz-close-login"></i>' );

            // Display Login Popup.
            $( 'a[data-show-youzer-login="true"],.yz-show-youzer-login-popup a' ).on( 'click', function( e ) {

                e.preventDefault();

                $( '.yz-popup-login' ).addClass( 'yz-is-visible' );

            });

            // Close Login Popup.
            $( '.yz-popup-login' ).on( 'click', function( e ) {
                if ( $( e.target ).is( '.yz-close-login' ) || $( e.target ).is( '.yz-popup-login' ) ) {
                    e.preventDefault();
                    $( this ).removeClass( 'yz-is-visible' );
                }
            });

            // Close Dialog if you user Clicked Cancel
            $( '.yz-close-login' ).on( 'click', function( e ) {
                e.preventDefault();
                $( '.yz-popup-login' ).removeClass( 'yz-is-visible' );
            });

        });

    })( jQuery );

    </script>

    <?php
}


/**
 * Add Login Popup Pages Class.
 */
function yz_add_login_popup_page_class( $items ) {

    // Get Login Page ID.
    $login_page_id = logy_page_id( 'login' );

    if ( ! empty( $login_page_id ) ) {
	    foreach( $items as $key => $item ) {
	        if ( $item->object_id == $login_page_id ) {
	            $item->classes[] = 'yz-show-youzer-login-popup';
	        }
	    }
    }

    return $items;
}

// add_filter( 'wp_nav_menu_objects', 'yz_add_login_popup_page_class', 10 );


/**
 * is Ajax Login Enabled
 */
function yz_add_login_page_attribute( $atts, $item, $args ) {

    // Get Login Page ID.
    $login_page_id = logy_page_id( 'login' );

	// Add Attribute.
    if ( ! empty( $login_page_id ) ) {
	    if ( $item->object_id == $login_page_id ) {
	        $atts['data-show-youzer-login'] = 'true';
	    }
    }

    return $atts;
}

add_filter( 'nav_menu_link_attributes', 'yz_add_login_page_attribute', 10, 3 );