( function( $ ) {

    'use strict';

    $( document ).ready( function( $ ) {

        // Hide Form Message After a while.
        var fade_message = function() {
            var t;
            $( '#youzer-action-message' ).fadeOut( 1000 );
            clearTimeout( t );
        }

        // Check if element is already Exist or not
         $.yz_isAlreadyExist = function( options ) {

            // s = settings.
            var s = $.extend( {
                value   : null,
                selector: null,
                type    : null,
                old_title: null
            }, options );

            if ( s.value ) {
                // Change value to lowercase.
                s.value = s.value.toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
            }
            // in case user updating ad data allow keeping same name.
            if ( s.old_title ) {
                // Change value to lowercase.
                s.old_title = s.old_title.toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
                if ( s.old_title == s.value ) return false;
            }

            // Search for value in document.
            if ( s.type == 'text' ) {
                var array = s.selector.map( function() {
                    return $( this ).text().toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
                } ).get();
            } else if ( s.type == 'value' ) {
                var array = s.selector.map( function() {
                    return $( this ).val().toLowerCase().replace( /[^a-zA-Z0-9]/g, '_' );
                } ).get();
            }

            return array.indexOf( s.value ) > -1;
        }

        /**
         * Panel Message.
         */
        $.ShowPanelMessage = function( options ) {
            var o = $.extend( { type: 'error' }, options ), container, msg, t;
            if ( o.type == 'error' ) {
                $( '.uk-error-popup' ).find( '.uk-msg-content' ).empty().append( '<p>' + o.msg + '</p>' );
                $( '.uk-error-popup' ).addClass( 'is-visible' );
            } else if ( o.type == 'success' ) {
                $( '#youzer-action-message' ).html(
                    '<div class="youzer_msg success_msg">' +
                        '<div class="youzer-msg-icon">' +
                            '<i class="fas fa-check"></i>' +
                        '</div>' +
                        '<span>' + o.msg + '</span>' +
                    '</div>'
                ).show();
                t = setTimeout( fade_message, 200 );
            }
        }

        /**
         * Popup.
         */

        // Close Popup
        $( '.uk-popup' ).on( 'click', function( e ) {
            if ( $( e.target ).is( '.uk-popup-close' ) || $( e.target ).is( '.uk-popup' ) ) {
                e.preventDefault();
                $( this ).removeClass( 'is-visible' );
            }
        });

        // Close Popup if you user Clicked Cancel
        $( '.uk-close-popup' ).on( 'click', function( e ) {
            e.preventDefault();
            $( '.uk-popup' ).removeClass( 'is-visible' );
        });

        // Close Popup When Clicking The ESC Keyboard Button
        $( document ).keyup( function( e ) {
            if ( e.which == '27' ) {
                $( '.uk-popup' ).removeClass( 'is-visible' );
            }
        });

        /**
         * Init Responsive Menus.
         */
        $.yz_ResponsiveFunctions = function() {
            // Hide Account Menus if width < 768.
            if ( $( window ).width() < 769 ) {
                $( '.account-menus ul' ).fadeOut();
                $( '.yz-menu-head i' ).attr( 'class', 'fas fa-caret-down' );
            } else {
                $( '.account-menus ul' ).fadeIn();
                $( '.yz-menu-head i' ).attr( 'class', 'fas fa-caret-up' );
            }
        }
        $.yz_ResponsiveFunctions();

    });

})( jQuery );