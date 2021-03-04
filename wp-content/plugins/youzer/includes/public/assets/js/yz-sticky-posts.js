( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Pin / Unpin Post.
		 */
		$( document ).on( 'click',  '.yz-pin-tool', function ( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Verification.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars
    		var yz_curent_pin_btn, yz_pin_btn_title;
    		yz_curent_pin_btn = $( this );

    		// Add Loading Class.
    		yz_curent_pin_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				action: 'yz_handle_sticky_posts',
				security: Youzer.security_nonce,
				operation: $( this ).attr( 'data-action' ),
				component: $( this ).closest( '.activity-item ' ).hasClass( 'groups' ) ? 'groups' : 'activity',
				post_id: $( this ).closest( '.yz-activity-tools' ).attr( 'data-activity-id' ),
			};

			// Process Verification.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		yz_curent_pin_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		yz_curent_pin_btn.removeClass( 'loading' );

		    		// Update Button Icon & Activity Class.
					if ( res.action == 'pin' ) {
						yz_curent_pin_btn.find( '.yz-tool-icon i' ).removeClass( 'fa-flip-vertical');
						yz_curent_pin_btn.closest( '.activity-item' ).removeClass( 'yz-pinned-post' );
					} else if ( res.action == 'unpin' ) {
						yz_curent_pin_btn.find( '.yz-tool-icon i' ).addClass( 'fa-flip-vertical');
						yz_curent_pin_btn.closest( '.activity-item' ).addClass( 'yz-pinned-post' );
					}

					// Get Button Title.
					yz_pin_btn_title = ( res.action == 'pin' ) ? res.pin : res.unpin;

					// Update Button title.
					yz_curent_pin_btn.find( '.yz-tool-name' ).text(  yz_pin_btn_title );

					// Update Button Action
					yz_curent_pin_btn.attr( 'data-action', res.action );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		yz_curent_pin_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

		});

	});

})( jQuery );