( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Check if Div is Empty
		function isEmpty( el ) {
		    return ! $.trim( el.html() );
		}

    	$( '.yzmsg-show-search' ).on( 'click', function( e ) {
    		$( '.item-list-tabs #search-message-form' ).fadeToggle();
		});

    	// Load Widget With Effects
		function yz_profile_load_widget() {
			if ( $( '.yz_effect' )[0] ) {
				$( '.yz_effect' ).viewportChecker( {
				    classToAdd: 'animated',
				    classToRemove: 'invisible',
					removeClassAfterAnimation: true,
				    offset:'10%',
				    callbackFunction: function( elem, action ) {
						elem.addClass( elem.data( 'effect' ) );
				    }
				});
			}
		}

		yz_profile_load_widget();

		// Profile View More Menu
	    $( '.yz-navbar-view-more' ).click( function( e ) {
	    	if ( $( e.target ).closest('.yz-nav-view-more-menu' ).length === 0 ) {
		    	e.preventDefault();
		    	$( this ).find( '.yz-nav-view-more-menu' ).fadeToggle();
	    	}
	    });

		// Show/Hide Message
		$( '.yz-nav-settings' ).click( function( e ) {
	        e.preventDefault();
			// Hide Account Settings Menu to avoid any Conflect.
			if (  $( '.yz-responsive-menu' ).hasClass( 'is-active' ) ) {
				$( '.yz-responsive-menu' ).removeClass( 'is-active'  );
				$( '.yz-profile-navmenu' ).fadeOut();
			}
	        // Get Parent Box.
			var settings_box = $( this ).closest( '.yz-settings-area' );
			// Toggle Menu.
			settings_box.toggleClass( 'open-settings-menu' );
			// Display or Hide Box.
	        settings_box.find( '.yz-settings-menu' ).fadeToggle( 400 );
		});

			    var original_sidebar = $( '.yzb-author' ).closest( '.yz-sidebar-column' );
    	// Init Profile Settings Menu
		function yz_init_account_settings_menu() {

			if ( $( '.yz-profile-login' )[0] ) {
				var account_menu = $( '.yz-profile-login' );
			    if ( $( window ).width() < 769 ) {
	        		account_menu.prependTo( '.yz-main-column .yz-column-content' );
			    } else {
	        		account_menu.prependTo( '.yz-sidebar-column .yz-column-content' );
			    }
	        }

	        if ( $( '.yzb-author' )[0] ) {
				var header = $( '.yzb-author' );
			    if ( $( window ).width() < 769 ) {
	        		header.prependTo( $( original_sidebar ).parent() );
			    } else {
	        		header.prependTo( $( original_sidebar ) );
			    }
	        }
		}


		// Init Account Menu
		yz_init_account_settings_menu();

		// Skill Bar Script
		if ( $( '.yz-skillbar' )[0] ) {
			/**
			 * Load Skills On Scroll
			 */
			$.yz_initSkills = function() {
				if ( $( window ).scrollTop() + $( window ).height() >= $( '.yz-skillbar' ).offset().top ) {
		            if ( ! $( '.yz-skillbar' ).attr( 'loaded' ) ) {
		                $( '.yz-skillbar' ).attr( 'loaded', true );
						$( '.yz-skillbar' ).each( function() {
							$( this ).find( '.yz-skillbar-bar' ).animate( {
								width: $( this ).attr( 'data-percent' )
							}, 2000 );
						});
		            }
		        }
			}
			// Init Skills.
			$.yz_initSkills();
			$( window ).scroll( function() {
		    	$.yz_initSkills();
			});
		}

		var resizeTimer;

		$( window ).on( 'resize', function ( e ) {
		    clearTimeout( resizeTimer );
		    resizeTimer = setTimeout( function () {
		        if ( $( window ).width() > 768 ) {
		        	$( '.yz-profile-navmenu' ).fadeIn( 1000 );
		        } else {
		        	if ( $( '.yz-responsive-menu' ).hasClass( 'is-active' ) ) {
		        		$( '.yz-profile-navmenu' ).fadeIn( 600 );
		        	} else {
		        		$( '.yz-profile-navmenu' ).fadeOut( 600 );
		        		$( '.yz-responsive-menu' ).removeClass( 'is-active' );
		        	}
		        }
		        // Init Account Menu
				yz_init_account_settings_menu();
		    }, 1 );
		});

		// // Set Content Height
		// $.yz_set_content_height = function() {
		// 	// Set Content Min Height
		// 	if ( $( '.yzb-author' )[0] ) {
		// 		var header_height = $( '#yz-profile-header' ).height();
		// 		$( '.yz-profile-content' ).css( 'min-height', header_height + 35 );
		// 	}
		// }

		// // Init Content Height
		// $.yz_set_content_height();

		// // Set Content Hight on Resize.
		// $( window ).resize( function() {
		// 	$.yz_set_content_height();
		// });

		// Zoom Flick Photo
	    $( '.yz-pf-zoom, .yz-flickr-zoom' ).on( 'click' , function() {
	    	$( this ).next( '.yz-lightbox-img' ).click();
	    });

		// Hide Settings Menu if User Clicked Outside.
		$( document ).mouseup( function( e ) {

		    if ( ! $( '.yz-settings-area' ).hasClass( 'open-settings-menu' ) ) {
		    	return;
		    }

		    // Set Up Variables.
		    var settings_button = $( '.yz-nav-settings' ),
		    	settings_menu   = $( '.yz-settings-menu' );

	        // Hide Menu.
	        if (
	        	! settings_menu.is( e.target ) &&
	        	! settings_button.is( e.target ) &&
	        	settings_menu.has( e.target ).length === 0 &&
	        	settings_button.has( e.target ).length === 0
	        ) {
				// Toggle Menu.
	        	$( '.yz-settings-area' ).removeClass( 'open-settings-menu' );
				// Hide Box.
	            settings_menu.slideToggle( 250 );
	        }

		});

	});

})( jQuery );

/**
 *	Add Js to HTML body
 */
( function( e, t, n ) {

	'use strict';

    var r = e.querySelectorAll( 'html' )[0];
    r.className = r.className.replace( /(^|\s)no-js(\s|$)/, "$1js$2" );

})( document, window, 0 );