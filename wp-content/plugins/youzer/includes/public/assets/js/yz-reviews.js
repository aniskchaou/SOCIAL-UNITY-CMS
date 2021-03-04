( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		var yz_review_button;

		/**
		 * Add Review
		 */
		$( document ).on( 'click', '#yz-add-review' , function( e ) {

    		e.preventDefault();

			var data = $( '#yz-review-form' ).serialize() +
			"&action=yz_handle_user_reviews" +
			"&operation=" + $( this ).attr( 'data-action' ) +
			"&security=" + Youzer.security_nonce;

    		var submit_button = $( this );

    		var button_title = submit_button.text();

    		// Disable Submit Button.
    		submit_button.attr( 'disabled', 'disabled' );

		    // Show Loader.
		    submit_button.addClass( 'loading' );

			// Process.
			$.post( ajaxurl, data, function( response ) {

				// Remove loading spinner.
		    	submit_button.removeClass( 'loading' );

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

		    		// Disable Submit Button.
		    		submit_button.attr( 'disabled', false );

					return false;

				} else {

		    		submit_button.closest( '#yz-modal' ).find( '.yz-modal-close-icon' ).trigger( 'click' );

		    		// Change Button Title.
					if ( yz_review_button.parent().attr( 'class' ) != 'yz-item-tools' ) {
	    				yz_review_button.find( '.yz-tool-name' ).text( res.edit_review );
					}

					// Update Button Action
					if ( res.action == 'edit' ) {
						yyz-modalz_review_button.attr( 'data-review-id', res.review_id );
						yz_review_button.attr( 'data-action', 'edit' );

						yz_review_button.find( '.yz-tool-name' ).text( res.button_title );
						yz_review_button.find( '.yz-tool-icon i' ).removeClass().addClass( 'fas fa-edit' );
						if ( yz_review_button.attr( 'data-yztooltip' ) !== undefined ) {
							yz_review_button.attr( 'data-yztooltip', res.button_title );
						}
					} else if ( res.action == 'delete_button' ) {
		    			yz_review_button.remove();
					}

	            	// Show Error Message
	            	$.yz_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

	    		// Enable Submit Button.
	    		submit_button.attr( 'disabled', 'disabled' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

    	});

    	/**
    	 * Display User Review Form
    	 */
		$( document ).on( 'click', '.yz-review-btn, .yz-review-tool.yz-edit-tool' , function( e ) {

    		e.preventDefault();

    		// Set Global
    		yz_review_button = $( this );

    		// Init Vars
    		var yz_curent_btn = $( this ), user_id, review_id = null, button_icon = null;

    		// Get User ID.
    		if ( yz_review_button.hasClass( 'yz-review-btn' ) ) {
    			review_id = $( this ).attr( 'data-review-id' );
    			user_id = $( this ).parent( '.yz-tools' ).data( 'user-id' );
    		} else {
    			review_id = $( this ).parent( '.yz-item-tools' ).data( 'review-id' );
    			user_id = $( this ).parent( '.yz-item-tools' ).data( 'user-id' );
    		}

    		// Disable Click On Displaying Share Box.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Add Loading Class.
    		yz_curent_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				user_id: user_id,
				review_id: review_id,
				security: Youzer.security_nonce,
				action : 'yz_get_user_review_form',
				operation: $( this ).attr( 'data-action' ),
			};

			// Process Verification.
			$.post( Youzer.ajax_url, data, function( response ) {

            	// Get Response Data.
				if ( $.yz_isJSON( response ) ) {

            		var res = $.parseJSON( response );

		    		// Remove Loading Class.
		    		yz_curent_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

					return false;

				}

				// Mark Button As laoded.
				yz_curent_btn.attr( 'data-loaded', 'true' );

	    		// Remove Loading Class.
	    		yz_curent_btn.removeClass( 'loading' );

	    		var $form = $( response );

				// Append Content.
				$.yz_show_modal( $form );

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		yz_curent_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

		});


    	/**
    	 * Delete User Review.
    	 */
		$( document ).on( 'click', '#yz-delete-review, .yz-review-tool.yz-delete-tool' , function( e ) {

    		e.preventDefault();

    		var submit_button = $( this ),
    			button_title = submit_button.text(),
    			review_id;

    		// Disable Submit Button.
    		submit_button.attr( 'disabled', 'disabled' );

		    // Show Loader.
		    submit_button.addClass( 'loading' );

			// Create New Form Data.
		    var formData = new FormData();

		    if ( submit_button.hasClass( 'yz-delete-tool' ) ) {
		    	review_id = $( this ).parent( '.yz-item-tools' ).data( 'review-id' );
		    } else {
		    	review_id = submit_button.closest( '.yz-modal' ).find( 'input[name="review_id"]' ).val()
		    }

		    // Fill Form with Data.
		    formData.append( 'review_id', review_id );
		    formData.append( 'action', 'yz_delete_user_review' );
			formData.append( 'security', Youzer.security_nonce );

			$.ajax({
                type: "POST",
                data: formData,
                url: Youzer.ajax_url,
		        contentType: false,
		        processData: false,
		        success: function( response ) {

					// Remove Loading Spinner.
			    	submit_button.removeClass( 'loading' );

					// Disable Delete Button.
					submit_button.attr( 'disabled', false );

	            	// Get Response Data.
	            	var res = $.parseJSON( response );

					if ( res.error ) {

		            	// Show Error Message
		            	$.yz_DialogMsg( 'error', res.error );

			    		// Disable Submit Button.
			    		submit_button.attr( 'disabled', false );

						return false;

					} else {

		    			if ( submit_button.hasClass( 'yz-delete-tool' ) ) {
		    				submit_button.closest( '.yz-review-item' ).fadeOut( 300, function() {
				    			$( this ).remove();
		    				});
		    			} else {

		    				submit_button.closest( '#yz-modal' ).find( '.yz-modal-close-icon' ).trigger( 'click' );

				    		// Change Button Title.
				    		yz_review_button.find( '.yz-tool-name' ).text( res.edit_review );

			    			yz_review_button.remove();
		    			}

		            	// Show Error Message
		            	$.yz_DialogMsg( 'success', res.msg );

						return false;
					}

		        }


				});


		});

	});

})( jQuery );