( function( $ ) {

	'use strict';

	$( document ).ready( function() {

    	$( '.yz-verify-btn' ).on( 'click', function( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Verification. 
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars
    		var yz_curent_verf_btn, yz_verf_btn_icon, yz_verf_btn_title;
    		yz_curent_verf_btn = $( this );

    		// Add Loading Class.
    		yz_curent_verf_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				verification_action: $( this ).attr( 'data-action' ),
				action: 'yz_handle_account_verification',
				user_id: $( this ).data( 'user-id' ),
				security: $( this ).data( 'nonce')
			};

			// Process Verification.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		yz_curent_verf_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		yz_curent_verf_btn.removeClass( 'loading' );

					// Get Button Icon.
					yz_verf_btn_icon = ( res.action == 'verify' ) ? 'fas fa-check' : 'fas fa-times';

					// Update Button Icon.
					yz_curent_verf_btn.find( '.yz-tool-icon i' ).attr( 'class', yz_verf_btn_icon );

					// Get Button Title.
					yz_verf_btn_title = ( res.action == 'verify' ) ? res.verify_account : res.unverify_account;

					// Update Button title.
					yz_curent_verf_btn.find( '.yz-tool-name' ).text(  yz_verf_btn_title );

					// Update Tooltip Title
					if ( yz_curent_verf_btn.attr( 'data-yztooltip' ) !== undefined ) {
						yz_curent_verf_btn.attr( 'data-yztooltip', yz_verf_btn_title );
					}

					// Update Button Action
					yz_curent_verf_btn.attr( 'data-action', res.action );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		yz_curent_verf_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

		});
		
    	// Remove All Buddypress Default Tooltips.
		$.yz_remove_buddypress_tooltops = function ( $action ) {

			var yz_tooltip_text;
			// Delete All Classes.
			$( '.bp-tooltip' ).each( function() {
				// Get Tooltip Text.
				yz_tooltip_text = $( this ).attr( 'data-bp-tooltip' );
				// Remove HTML Tags.
				yz_tooltip_text = $( '<div>' ).html( yz_tooltip_text ).text();
				// Replace Text.
		        $( this ).attr( 'data-bp-tooltip', yz_tooltip_text );
		    });

		}

		// Init Function
		$.yz_remove_buddypress_tooltops();

	});

})( jQuery );