( function( $ ) {

    'use strict';

	$( document ).ready( function() {

		if ( jQuery().niceSelect ) {
			$( '.youzer select' ).not( '[multiple="multiple"]' ).niceSelect();
		}

	    /**
	     * Show Files Browser
	     */
		$( document ).on( 'click', '.yz-upload-photo', function( e ) {
			e.preventDefault();
			var uploader = $( this ).closest( '.yz-uploader-item' );

			uploader.find( '.yz_upload_file' ).attr( 'data-source', $( 'input[name=yz_widget_source]' ).val() );
			uploader.find( '.yz_upload_file' ).attr( 'data-user-id', $( 'input[name=yz_widget_user_id]' ).val() );
			uploader.find( '.yz_upload_file' ).trigger( 'click' );

		});

	    /*
	     * Images Uploader
	     */
		$( document ).on( 'change', '.yz_upload_file', function( e ) {

	        e.stopPropagation();

	        var formData = new FormData(),
		  		file 	  = $( this ),
		  		field 	  = $( this ).closest( '.yz-uploader-item' ),
		  		preview   = field.find( '.yz-photo-preview' ),
		  		old_attachment_id = field.find( '.yz-photo-url' ).val();

		  	// Append Data.
		  	formData.append( 'nonce', $( this ).closest( 'form' ).find( "input[name='security']" ).val() );
	       	formData.append( 'file', $( this )[0].files[0] );
	       	formData.append( 'user_id', $( this ).attr( 'data-user-id' ) );
	       	formData.append( 'source', $( this ).attr( 'data-source' ) );
		  	formData.append( 'action', 'upload_files' );

	        $.ajax( {
	            url         : Youzer.ajax_url,
	            type        : "POST",
	            data        : formData,
	            contentType : false,
	            cache       : false,
	            processData : false,
	            beforeSend  : function() {
	            	// Display Loader.
	            	var loader = '<div class="yz-load-photo"><i class="fas fa-spinner fa-spin"></i></div>';
	            	$( loader ).hide().appendTo( preview ).fadeIn( 800 );
	            },
	            success : function( data ) {

	            	// Remove File From Input.
	            	file.val( '' );

	            	// Get Response Data.
	            	var res = $.parseJSON( data );

		            if ( res.error ) {
	            		// Hide Loader.
	            		preview.find( '.yz-load-photo' ).fadeOut( 300 ).remove();
		            	// Show Error Message
                		$.yz_DialogMsg( 'error', res.error );
	            		return;
		            }

				  	// Delete The Old Photo.
				  	if ( old_attachment_id ) {
				  		$.yz_DeletePhoto( old_attachment_id );
				  	}

		   			// Save Photo.
	            	preview.find( '.yz-load-photo' ).fadeOut( 300, function() {
	            		// Hide Loader.
	            		$( this ).remove();
	            		// Display Photo Preview.
	            		preview.fadeOut( 100, function() {
		            		$( this ).css( 'background-image', 'url(' + res.url + ')' ).fadeIn( 400 );
		            		// Update Photo Url
		            		field.find( '.yz-photo-url' ).val( res.attachment_id ).change();
		            		// Activate Trash Icon.
		            		field.find( '.yz-delete-photo' ).addClass( 'yz-show-trash' );
			        		// Save Form Data
			        		$.post( Youzer.ajax_url, field.closest( 'form' ).serialize() + '&die=true' );
	            		});
	            	});
	            }
	        });
	    });

        // Open Items.
        $( '.yz-account-menu' ).on( 'click', function( e ) {

        	var next_menu = $( this ).next( '.yz-account-menus' );

        	if ( next_menu.hasClass( 'yz-show-account-menus' ) ) {
        		next_menu.removeClass( 'yz-show-account-menus' ).css( 'display', 'block' );
        	}

        	// Show / Hide Menu.
            $( this ).next( '.yz-account-menus' ).slideToggle();

        });

	    /**
	     * # Remove Image
	     */
		$( document ).on( 'click', '.yz-delete-photo', function( e ) {

			// Set up Variables.
			var uploader = $( this ).closest( '.yz-uploader-item' );

			// Remove Image from Directory.
			$.yz_DeletePhoto( uploader.find( '.yz-photo-url' ).val() );

			// Remove Image Url
			uploader.find( '.yz-photo-url' ).val( '' ).trigger( 'change' );

			// Reset Preview Image.
		    uploader.find( '.yz-photo-preview' ).css( 'background-image', 'url(' + Yz_Account.default_img + ')' );

		    // Hide Trash Icon.
		    $( this ).removeClass( 'yz-show-trash' );

    		// Save Form Data
    		$.post( Youzer.ajax_url, uploader.closest( 'form' ).serialize() + '&die=true' );

		});

	    /*
	     * Delete Photo
	     */
		$.yz_DeletePhoto = function( attachment_id ) {

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'attachment_id', attachment_id );
		    formData.append( 'action', 'yz_delete_attachment' );

			$.ajax({
                type: "POST",
                data: formData,
                url: Youzer.ajax_url,
		        contentType: false,
		        processData: false
			});
	    }

	    // Update Account Photo with the new uploaded photo.
	    $( '.yz-account-photo .yz-photo-url' ).on( 'change' , function( e ) {
			e.preventDefault();
			// Get Account Photo url.
			var account_photo = $( this ).val();
			// If Input Value Empty Use Default Image
			if ( ! account_photo ) {
				account_photo = Yz_Account.default_img;
			}
			// Change Account Photo.
		    $( '.yz-account-img' ).fadeOut( 200, function() {
		    	$( this ).css( 'background-image', 'url(' + account_photo + ')' ).fadeIn( 200 );
		    });
		});

    	$( '.yz-user-provider-unlink' ).on( 'click', function( e ) {

    		e.preventDefault();

    		// Disable Click On Processing Unlinking.
    		if ( $( this ).hasClass( 'loading' ) ) {
    			return false;
    		}

    		// Init Vars.
    		var yz_provider_parent = $( this ).closest( '.yz-user-provider-connected' ),
    			yz_curent_unlink_btn = $( this );

    		// Add Loading Class.
    		yz_curent_unlink_btn.addClass( 'loading' );

    		// Get Button Data.
			var data = {
				action: 'yz_unlink_provider_account',
				provider: $( this ).data( 'provider' ),
				security: $( this ).data( 'nonce')
			};

			// Process Ajax Request.
			$.post( ajaxurl, data, function( response ) {

            	// Get Response Data.
            	var res = $.parseJSON( response );

				if ( res.error ) {

		    		// Remove Loading Class.
		    		yz_curent_unlink_btn.removeClass( 'loading' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'error', res.error );

					return false;

				} else if ( res.action ) {

		    		// Remove Loading Class.
		    		yz_curent_unlink_btn.removeClass( 'loading' );

		    		// Clear Token input.
		    		yz_provider_parent.find( '.yz-user-provider-token' ).val( '' );

					// Remove Provider.
					yz_provider_parent.find( '.yz-user-provider-box' ).remove();
					yz_provider_parent.removeClass().addClass( 'yz-user-provider-unconnected' );

	            	// Show Error Message
	            	$.yz_DialogMsg( 'success', res.msg );

					return false;
				}

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		yz_curent_unlink_btn.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});

		});

	});

})( jQuery );