( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Init Vars.
		var yz_atts_count = 0, yz_nxt_atts_id = 0, yz_atts_files = null;

		/**
		 * Append Attachments.
		 */
		$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {

			if ( originalOptions.hasOwnProperty( 'data' ) && originalOptions.data.hasOwnProperty( 'action' ) ) {

				var action = originalOptions.data.action;

				if ( action == 'new_activity_comment' ) {

					var attachment = $( '#ac-form-' + originalOptions.data.form_id ).find( 'input[name="attachments_files[]"]' ).map(function(idx, elem) { return $( elem ).val(); }).get();

					if ( attachment.length != 0 ) {
				        options.data += '&attachments_files=' + attachment;

						if ( originalOptions.data.content == '' ) {
					        options.data += '&content={{{yz_comment_attachment}}}';
						}
					}

				} else if ( action == 'messages_send_reply' ) {

					var attachment = $( '#send-reply' ).find( 'input[name="attachments_files[]"]' ).map( function(idx, elem) { return $( elem ).val(); }).get();

					if ( attachment.length != 0 ) {
				        options.data += '&attachments_files=' + attachment;

						if ( originalOptions.data.content == '' ) {
					        options.data += '&content={{{yz_message_attachment}}}';
						}
					}

				}

			}

		});

		/**
		 * Delete Attachments After Success.
		 */
		$( document ).ajaxSuccess( function( event, xhr, settings ) {
			if ( Object.prototype.toString.call( settings.data ) == '[object String]' ) {
				var response = xhr.responseText;
				if ( response[0] + response[1] != '-1' ) {
					var action = $.yz_getUrlParameter( settings.data, 'action' );
					if ( action == 'new_activity_comment' ) {
						$( '#ac-form-' + $.yz_getUrlParameter( settings.data, 'form_id' ) ).find( '.yz-delete-attachment' ).trigger( 'click' );
						$( '#ac-form-' + $.yz_getUrlParameter( settings.data, 'form_id' ) ).find( '.yz-wall-upload-btn' ).fadeIn();
			   		} else if ( action == 'messages_send_reply' ) {
						$( '#send-reply' ).find( '.yz-delete-attachment' ).trigger( 'click' );
						$( '#send-reply' ).find( '.yz-upload-btn' ).fadeIn();
			   		}
				}
		   	}
		});

		/**
		 * Submit form to Upload Files.
		 */
		$( document ).on( 'change', '.yz-upload-attachments', function ( e ) {

		    e.preventDefault();

			var $form = $( this ).closest( 'form' );

			// Hide Comment Upload Button.
			if ( $form.hasClass( 'ac-form' ) ) {
				$form.find( '.yz-wall-upload-btn' ).fadeOut();
				if ( $form.find( '.yz-attachment-item' )[0] ) {
					return false;
				}
			}

    		// Get Files.
    		yz_atts_files = $( this ).get( 0 );

    		// Upload Files.
			$.yz_UploadFiles( $( this ).closest( 'form' ), { 'attachments': yz_atts_files } );

		});

		/**
		 * Upload Files.
		 */
		$.yz_UploadFiles = function ( form, options ) {

			// Get Options.
        	var qto = $.extend({
        		// allowed_extensions : Yz_Wall.default_extentions,
        		// max_number : 3,
        		// max_size : 3
        	}, options ), dialog;

        	// Get Files.
        	var files = qto.attachments.files;

    		for ( var i = 0; i < files.length ; i++ ) {

				// Get File.
    			var file = files[i];

				// Check Files Number.
				if ( form.hasClass( 'yz-wall-form' ) ) {
					if ( ! $.yz_CheckFilesNumber( form ) ) {
						return false;
					}
				} else {
					if( form.find( '.yz-attachment-item' )[0] ) {
						return false;
					}
				}

        		// Get Attachment Item Html Code.
        		var qt_AttachmentItem = $.youzerAttachmentItem({
        			'file' : file,
        			'file_name': file.name
        		});

	        	if ( form.hasClass( 'ac-form' ) ) {
	        		form.find( '.yz-wall-upload-btn' ).fadeOut(1);
	        	} else if ( form.attr( 'id' ) ==  'send-reply' || form.attr( 'id' ) ==  'send_message_form' ) {
	        		form.find( '.yz-upload-btn' ).fadeOut(1);
	        	}

        		// Append Item To the Attachments List.
        		form.find( '.yz-form-attachments' ).append( qt_AttachmentItem );

        		// Upload File.
        		if ( i == 0 ) {
        			$.yz_UploadFile( form, file );
        		}

			}

		}

		/**
		 * Get Attachment Item HTML Code.
		 */
		$.youzerAttachmentItem = function ( options ) {

			// Get Option.
			var qto = $.extend( {}, options ), file_code, image_code, file_name;

			// Get File Name.
			file_name = $.yz_GetNameExcerpt( qto.file_name );

			// Get Files HTML Code.
			file_code =  '<div class="yz-attachment-item yz-file-preview">' +
							'<div class="yz-attachment-details">' +
								'<i class="fas fa-hourglass-half yz-file-icon"></i>' +
								'<span class="yz-file-name">' + file_name + '</span>' +
							'</div>' +
							'<div class="yz-file-progress">' +
								'<span class="yz-file-upload"></span>' +
							'</div>' +
							'<input type="hidden" class="yz-attachment-data" name="attachments_files[]" />' +
						'</div>';

			// Get Image Preview HTML Code.
			image_code =  '<div class="yz-attachment-item yz-image-preview">' +
							'<div class="yz-attachment-details">' +
								'<i class="fas fa-hourglass-half yz-file-icon"></i>' +
							'</div>' +
							'<div class="yz-file-progress">' +
								'<span class="yz-file-upload"></span>' +
							'</div>' +
							'<input type="hidden" class="yz-attachment-data" name="attachments_files[]" />' +
						'</div>';

			// Return Item Code.
			if ( $.yz_CheckIsFileImage( qto.file ) ) {
				return image_code;
			} else {
				return file_code;
			}

		}

		/**
		 * Upload Attachments.
		 */
		$.yz_UploadFile = function ( form, file ) {

			// Get Attachment Item.
			var item = form.find( '.yz-file-progress:first' ).parent( '.yz-attachment-item' );

			// Create New Form Data.
		    var formData = new FormData();

		    // Fill Form with Data.
		    formData.append( 'file', file );

		    if ( form.hasClass( 'yz-wall-form' ) ) {
		    	formData.append( 'target', 'activity' );
		    	formData.append( 'post_type', form.find( 'input:radio[name="post_type"]:checked' ).val() );
		    } else if ( form.hasClass( 'ac-form' ) ) {
		    	formData.append( 'target', 'comment' );
		    } else if ( form.closest( 'form' ).attr( 'id' ) == 'send-reply' || form.closest( 'form' ).attr( 'id' ) == 'send_message_form' ) {
		    	formData.append( 'target', 'message' );
		    }

		    formData.append( 'attachments_number', form.find( '.yz-attachment-item' ).length );
		    formData.append( 'action', 'yz_upload_wall_attachments' );
		    formData.append( 'security', Youzer.security_nonce );

		    // Upload File.
		    $.ajax({
		        type  : 'POST',
		        url   : Youzer.ajax_url,
		        data  : formData,
		        cache : false,
		        contentType: false,
		        processData: false,
		        xhr: function() {
	                var YouzerXhr = $.ajaxSettings.xhr();
	                if ( YouzerXhr.upload ) {

	                	// Disable submit button.
						form.find( '.yz-wall-post,.yz-update-post' ).attr( 'disabled', true );

	                    YouzerXhr.upload.addEventListener( 'progress', function( e ) {
						    if ( e.lengthComputable ) {

						   		// Set up Variables.
						        var max = e.total,
						        	current = e.loaded,
						        	Percentage = ( current * 100 ) / max;

						        // Get Progress Bar
						       	var progress_bar = item.find( '.yz-file-upload' );

						       	// Upload Started Class.
						       	var yz_loading_icon = 'fas fa-spinner fa-spin yz-file-icon';

						       	// Add loader icon
		        				item.find( '.yz-file-icon' ).attr( 'class', yz_loading_icon );

						       	// Update Upload status.
						        progress_bar.css( 'width', Percentage  + '%' );

						        if ( Percentage >= 100 ) {
						        	// Change Progress Bar Class .
						        	progress_bar.addClass( 'yz-file-uploaded' );
						        }

				    		}

	                    });
	                }
	                return YouzerXhr;
		        },

		        success: function( result ) {

	            	// Get Response Data.
	            	var res = $.parseJSON( result );

		            if ( res.error ) {
		            	// Show Error Message
		            	$.yz_DialogMsg( 'error', res.error );

		            	if ( form.hasClass( 'ac-form' ) ) {
		            		form.find( '.yz-wall-upload-btn' ).fadeIn();
		            	} else if ( form.attr( 'id' ) == 'send-reply' || form.attr( 'id' ) == 'send_message_form' ) {
		            		form.find( '.yz-upload-btn' ).fadeIn();
		            	}

		            	// Remove Item.
		            	item.remove();

						// Check Upload Progress to Enable Submit Field.
						$.yz_CheckUploadProgress( form );

	            		return false;
		            }

			        // Prepare Trash Icon
		        	var base_url = res.base_url;

		        	// Remove Progress Bar.
		        	item.find( '.yz-file-progress' ).fadeOut( 400, function() {

		        		// Remove Progress Div.
		        		$( this ).remove();

		        		// Let's Upload Next File.
		        		$.yz_upload_next_file( form );

						// Check Upload Progress to Enable Submit Field.
						$.yz_CheckUploadProgress( form );

		        	});

		        	// Delete Loader Icon.
					if ( $.yz_CheckIsFileImage( file ) ) {
			        	item.find( '.yz-file-icon' ).remove();
			        }

			   		// Change Loader Icon with paperclip icon.
		        	item.find( '.yz-file-icon' ).attr( 'class', 'fas fa-paperclip yz-file-icon' );

		        	// Add Trash Icon to the attachment item.
		        	item.find( '.yz-attachment-details' ).append( '<i class="fas fa-trash-alt yz-delete-attachment"></i>' );
		        	// delete myObj.test.key1;

					delete res.base_url;

		        	// Update Item Attachments Data.
					item.find( '.yz-attachment-data' ).val( JSON.stringify( res ) );

					var fileReader = new FileReader();

					if ( file.type.match( 'video.*' ) ) {

					    fileReader.onload = function() {

							var blob = new Blob( [ fileReader.result ], { type: file.type });
							var url = URL.createObjectURL( blob );
							var video = document.createElement( 'video' );
							var timeupdate = function() {
								if ( yz_video_snapImage() ) {
									video.removeEventListener( 'timeupdate', timeupdate );
									video.pause();
								}
							};

							video.addEventListener('loadeddata', function() {
								if ( yz_video_snapImage() ) {
								 video.removeEventListener( 'timeupdate', timeupdate );
								}
							});

						    var yz_video_snapImage = function() {
						        var canvas = document.createElement( 'canvas' );
						        canvas.width = video.videoWidth;
						        canvas.height = video.videoHeight;
						        canvas.getContext( '2d' ).drawImage( video, 0, 0, canvas.width, canvas.height );
						        var image = canvas.toDataURL( 'image/jpeg' );
						        var success = image.length > 100000;

						        if ( success ) {
									var video_data = JSON.parse( item.find( '.yz-attachment-data' ).val() );
									video_data.video_thumbnail = image;
									item.find( '.yz-attachment-data' ).val( JSON.stringify( video_data ) );
									URL.revokeObjectURL(url);
						        }

						        return success;
						    };

							video.addEventListener( 'timeupdate', timeupdate );
							video.preload = 'metadata';
							video.src = url;
							// Load video in Safari / IE11
							video.muted = true;
							video.playsInline = true;
							video.play();
					    };

					    fileReader.readAsArrayBuffer( file );

					}

					item.css( 'background-image', 'url(' + base_url + 'temp/' + res.original + ')' );

		        },

		        error : function( XMLHttpRequest, textStatus, errorThrown ) {

	            	// Remove Item.
	            	item.remove();

					$.yz_DialogMsg( 'error', textStatus );

	            	// Check Upload Progress to Enable Submit Field.
					$.yz_CheckUploadProgress( form );

	            	$.yz_upload_next_file( form );

		        }

		    });

		}

		/**
		 * Upload Next File
		 */
		$.yz_upload_next_file = function( form ) {

    		// Let's Upload Next File.
    		yz_atts_count++;

        	if ( yz_atts_files !== null && typeof yz_atts_files.files[ yz_atts_count ] !== 'undefined' ) {
        		$.yz_UploadFile( form, yz_atts_files.files[ yz_atts_count ] );
        	}

		}


		/**
		 * Delete Attachment .
		 */
        $( document ).on( 'click', '.yz-delete-attachment' , function( e ) {

        	// Get Form.
        	var form = $( this ).closest( 'form' );

        	if ( form.hasClass( 'ac-form' ) ) {
        		form.find( '.yz-wall-upload-btn' ).fadeIn();
        	} else if ( form.attr( 'id' ) ==  'send-reply' || form.attr( 'id' ) ==  'send_message_form' ) {
        		form.find( '.yz-upload-btn' ).fadeIn();
        	}

        	// Get Attachment item.
        	var attachment = $( this ).closest( '.yz-attachment-item' );

        	if ( attachment.find( '.yz-attachment-data' ).get( 0 ) ) {

	        	// Get File Data.
				var data = $.parseJSON( attachment.find( '.yz-attachment-data' ).val() );

				// Remove Attachment from Directory.
				$.yz_DeleteAttachment( form, data.original );

        	}

			// Remove Attachment from Form.
			attachment.remove();

        });

		/**
		 * Delete Attachment File.
		 */
		$.yz_DeleteAttachment = function( form, file ) {

			// Create New Form Data.
		    var formData = new FormData(), atts_nounce;

		    // Fill Form with Data.
		    formData.append( 'attachment', file );
			formData.append( 'security', Youzer.security_nonce );
		    formData.append( 'action', 'yz_delete_wall_attachment' );

			$.ajax({
                type: "POST",
                data: formData,
                url: ajaxurl,
		        contentType: false,
		        processData: false
			});

		}

		/**
		 * Get File Name Excerpt.
		 */
		$.yz_GetNameExcerpt = function ( name ) {

		    // Set up Variables.
			var strLen = 25,
		    	separator = '...';

		    // If file name not too long keep it.
		    if ( name.length <= strLen ) {
		    	return name;
		    }

		    // Set up Variables.
		    var sepLen = separator.length,
		        charsToShow = strLen - sepLen,
		        frontChars = Math.ceil(charsToShow/2),
		        backChars = Math.floor(charsToShow/2);

		    // Shorten File Name.
		    return name.substr( 0, frontChars ) + separator + name.substr(name.length - backChars);
		};

		/*
		 * Check If Uploaded File Is Image.
		 **/
		$.yz_CheckIsFileImage = function( file ) {
			var fileType = file['type'];
			var ValidImageTypes = [ "image/gif", "image/jpeg", "image/png" ];
			if ( $.inArray( fileType, ValidImageTypes ) < 0 ) {
			    return false;
			}
			return true;
		}

		/*
		 * Check Upload Progress !!??
		 **/
		$.yz_CheckUploadProgress = function( form ) {
			if ( ! form.find( '.yz-file-progress' )[0] ) {
				form.find( '.yz-upload-attachments' ).val( '' );
				form.find( '.yz-wall-actions button[type="submit"]' ).attr( 'disabled' , false );
				// Reset Vars.
				yz_atts_count = 0;
				yz_atts_files = null;
			}
		}

		/*
		 * Check Files Number.
		 **/
		$.yz_CheckFilesNumber = function( form ) {
			var activity_type = form.find( 'input:radio[name="post_type"]:checked' ).val();
			if ( 'activity_photo' != activity_type && 'activity_slideshow' != activity_type && form.find( '.yz-attachment-item' )[0] ) {
				yz_atts_files = null;
				$.yz_DialogMsg( 'error', Yz_Wall.max_one_file );
				return false;
			}
			return true;
		}

		$.yz_getUrlParameter = function( data, sParam ) {

		    var sURLVariables = data.split('&'),
		        sParameterName,
		        i;

		    for (i = 0; i < sURLVariables.length; i++) {
		        sParameterName = sURLVariables[i].split('=');

		        if (sParameterName[0] === sParam) {
		            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
		        }
		    }
		}
	});

})( jQuery );