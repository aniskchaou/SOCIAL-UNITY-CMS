( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Pin / Unpin Post.
		 */
		$( document ).on( 'click',  ".yz-bookmark-tool", function ( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Verification. 
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars
    		var yz_curent_btn, yz_btn_title;
    		yz_curent_btn = $( this );

    		// Add Loading Class.
    		yz_curent_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				security: Youzer.security_nonce,
				action: 'yz_handle_posts_bookmark',
				item_type: $( this ).data( 'item-type' ),
				operation: $( this ).attr( 'data-action' ),
				item_id: $( this ).closest( '.yz-activity-tools' ).attr( 'data-activity-id' ),
			};

			// Process.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		yz_curent_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		yz_curent_btn.removeClass( 'loading' );

		    		// Update Button Icon & Activity Class.
					if ( res.action == 'save' ) {
						yz_curent_btn.find( '.yz-tool-icon i' ).removeClass().addClass( 'fas fa-bookmark' );
					} else if ( res.action == 'unsave' ) {
						yz_curent_btn.find( '.yz-tool-icon i' ).removeClass().addClass( 'fas fa-times' );
					}

					// Get Button Title.
					yz_btn_title = ( res.action == 'save' ) ? res.save_post : res.unsave_post;

					// Update Button title.
					yz_curent_btn.find( '.yz-tool-name' ).text( yz_btn_title );

					// Update Button Action
					yz_curent_btn.attr( 'data-action', res.action );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		yz_curent_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

		});
	
	});

})( jQuery );