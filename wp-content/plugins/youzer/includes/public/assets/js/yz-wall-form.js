( function( $ ) {

	'use strict';


	$( document ).ready( function() {

		var load_wall_form_js = false;

		/**
		 * Show All Form Buttons
		 */
    	$( '.yzw-form-show-all' ).on( 'click', function( e ) {
    		$( '.yzw-form-show-all' ).fadeOut( function() {
    			$( '.yz-wall-opts-item' ).fadeIn();
    			$( this ).remove();
    		});
		});

		/**
		 * Load Wall Assets.
		 */
    	$( document ).on( 'input click', '.yz-wall-textarea, .ac-input', function() {
    		$( this ).next( '.yz-load-emojis' ).attr( 'data-cursor', $( this ).prop("selectionStart") );
    	});

    	$( document ).on( 'focus', '.yz-wall-textarea', function() {
    		if ( load_wall_form_js == false ) {
	    		// Load Live Preview Scripts.
    			if ( $( this ).closest( 'form' ).find( '.yz-lp-prepost' ).get( 0 ) ) {
			        $( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/yz-url-preview.min.css' } ).appendTo( 'head' );
			        $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-url-preview.min.js' } ).appendTo( 'head' );
    			}
	    		load_wall_form_js = true;
    		}
	    });

		/**
		 * Load Emojis JS.
		 */
    	$( document ).on( 'click', '.yz-load-emojis', function() {
        	var form = $( this ).closest( 'form' );
	        $( this ).find( 'i' ).attr(  'class', 'fas fa-spin fa-spinner' );
        	$( this ).addClass( 'loading' );
	        $( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/emojionearea.min.css' } ).appendTo( 'head' );
	        $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/emojionearea.min.js' } ).appendTo( 'head' );
	    });

		/**
		 * Submit Wall Posts.
		 */
		$( document ).on( 'click', '.yz-wall-post', function() {
			// Init Vars.
			var last_date_recorded = 0,
				button = $( this ),
				button_title = $( this ).text(),
				form   = button.closest( 'form#yz-wall-form' ),
				inputs = {}, post_data, object;

			// Get all inputs and organize them into an object {name: value}
			$.each( form.serializeArray(), function( key, input ) {
				// Only include public extra data
				if ( '_' !== input.name.substr( 0, 1 ) && 'whats-new' !== input.name.substr( 0, 9 ) ) {
					if ( ! inputs[ input.name ] ) {
						inputs[ input.name ] = input.value;
					} else {
						// Checkboxes/dropdown list can have multiple selected value
						if ( ! $.isArray( inputs[ input.name ] ) ) {
							inputs[ input.name ] = new Array( inputs[ input.name ], input.value );
						} else {
							inputs[ input.name ].push( input.value );
						}
					}
				}
			} );

			form.find( '*' ).each( function() {
				if ( $.nodeName( this, 'textarea' ) || $.nodeName( this, 'input' ) ) {
					$( this ).prop( 'disabled', true );
				}
			} );

			// Disable Emojionearea Editor.
	        if ( form.find( '.yz-wall-textarea' ).get(0).emojioneArea ) {
	        	form.find( '.yz-wall-textarea' ).data( 'emojioneArea' ).disable();
	        }

			/* Disable Button & Display Loader. */
			button.addClass( 'loading' );
			button.prop('disabled', true );
			form.addClass( 'submitted' );
			button.css( 'min-width', button.css( 'width' ) );
			button.html( '<i class="fas fa-spinner fa-spin"></i>' );

			/* Default POST values */
			var object = '';
			var item_id = form.find( '#whats-new-post-in' ).val();
			var content = form.find( '#whats-new' ).val();
			var firstrow = $( '#youzer ul.activity-list li' ).first();
			var activity_row = firstrow;
			var timestamp = null;

			// Checks if at least one activity exists
			if ( firstrow.length ) {

				if ( activity_row.hasClass( 'load-newest' ) ) {
					activity_row = firstrow.next();
				}

				timestamp = activity_row.prop( 'class' ).match( /date-recorded-([0-9]+)/ );
			}

			if ( timestamp ) {
				last_date_recorded = timestamp[1];
			}

			/* Set object for non-profile posts */
			if ( item_id > 0 ) {
				object = form.find( '#whats-new-post-object' ).val();
			}

			post_data = $.extend( {
				action: 'yz_post_update',
				'cookie': bp_get_cookies(),
				'_yz_wpnonce_post_update': $( '#_yz_wpnonce_post_update' ).val(),
				'content': content,
				'object': object,
				'item_id': item_id,
				'since': last_date_recorded,
				'_bp_as_nonce': $( '#_bp_as_nonce' ).val() || ''
			}, inputs );
			$.post( ajaxurl, post_data, function( response ) {

				form.find( '*' ).each( function() {
					if ( $.nodeName( this, 'textarea' ) || $.nodeName( this, 'input' ) ) {
						$( this ).prop( 'disabled', false );
					}
				});

	            if ( form.find( '.yz-wall-textarea' ).get( 0 ).emojioneArea ) {
	            	form.find( '.yz-wall-textarea' ).data( 'emojioneArea' ).enable();
	            }

            	// Get Response Data.
				if ( $.yz_isJSON( response ) ) {
            		var res = $.parseJSON( response );
					$.yz_DialogMsg( 'error', res.error );
				} else {

					// Show Check .
					button.html( '<i class="fas fa-check"></i>' ).hide().fadeIn( 'slow' );

					form.find( '.yz-delete-attachment' ).trigger( 'click' );

					if ( 0 === $('ul.activity-list').length ) {
						$( 'div.error' ).slideUp( 100 ).remove();
						$( '#message' ).slideUp( 100 ).remove();
						$( 'div.activity').append( '<ul id="activity-stream" class="activity-list item-list">' );
					}

					if ( firstrow.hasClass( 'load-newest' ) ) {
						firstrow.remove();
					}

					$( '#activity-stream' ).prepend( response );

					if ( ! last_date_recorded ) {
						$( '#activity-stream li:first' ).addClass( 'new-update just-posted' );
					}

					// Scroll To Added Post.
					if ( $( response ).get( 0 ) ) {
						$( 'body,html' ).animate({
						    scrollTop: $( '#' + $( response ).attr( 'id') ).offset().top - 65 + 'px'
						}, 1000 );
					}

					if ( 0 !== $( '#latest-update' ).length ) {
						var l   = $( '#activity-stream li.new-update .activity-content .activity-inner p' ).html(),
							v     = $( '#activity-stream li.new-update .activity-content .activity-header p a.view' ).attr('href'),
							ltext = $( '#activity-stream li.new-update .activity-content .activity-inner p' ).text(),
							u     = '';

						if ( ltext !== '' ) {
							u = l + ' ';
						}

						u += '<a href="' + v + '" rel="nofollow">' + BP_DTheme.view + '</a>';

						$( '#latest-update' ).slideUp( 300,function() {
							$( '#latest-update' ).html( u );
							$( '#latest-update' ).slideDown( 300 );
						});
					}

					$( 'li.new-update' ).hide().slideDown( 300 );
					$( 'li.new-update' ).removeClass( 'new-update' );
					$( '#whats-new' ).val( '' );

					// Init Slider.
					if ( inputs.post_type == 'activity_slideshow' )  {
						$.youzer_sliders_init();
					}

					// Reset Form.
					form.get( 0 ).reset();

					// Reset Text Form.
		            if ( form.find( '.yz-wall-textarea' ).get( 0 ).emojioneArea ) {
		            	form.find( '.yz-wall-textarea' ).get( 0 ).emojioneArea.setText( '' );
		            }

		            // Reset Tagged Users Form.
		            form.find( '.yz-tagged-user .yz-tagusers-delete-user, .yz-list-delete-item, .yz-list-close-icon' ).trigger( 'click' );

		            // Select First Element.
		            form.find( 'div.yz-activity-privacy' ).find( '.list div' ).first().trigger( 'click' );
		            form.find( '#whats-new-post-in' ).find( 'option' ).first().trigger( 'click' );

		            // Update Nice Select.
		            if ( ! window.hasOwnProperty( 'yz_disable_niceselect' ) ) {
		            	form.find( '#whats-new-post-in' ).niceSelect( 'update' );
		            }

		            // Update Post in field.
		            $( '#whats-new-post-in' ).trigger( 'change' );

		            form.find( '.yz-wall-options input:radio[name="post_type"]' ).first().trigger( 'change' );
		            form.find( '.yz-lp-prepost .lp-button-cancel' ).trigger( 'click' );

		            if ( inputs.post_type == 'activity_giphy' ) {
		            	form.find( '.yz-delete-giphy-item' ).trigger( 'click' );
		            	form.find( '.yz-giphy-submit-search' ).val( '' ).trigger( 'click' );
		            }


					// reset vars to get newest activities
					newest_activities = '';
					activity_last_recorded  = 0;

				}

				setTimeout( function() {
					// Change Submit Button Text.
					button.html( button_title ).fadeIn( 'slow' );
				}, 1000 );

				// Enable Submit Button.
				form.find( '.yz-wall-post,.yz-update-post' ).prop( 'disabled', false ).removeClass( 'loading' );

				// Hide Share Form
				if( form.parent().attr( 'id' ) == 'youzer-share-activity-wrapper' ) {
					$( '.yz-modal-close-icon' ).trigger( 'click' );
				}

			});

			return false;
		});

		/*
		 * Show/Hide Link Form.
		 **/
		$( document ).on( 'change', 'input:radio[name="post_type"]', function() {
			// Get Post type.
			var form = $( this ).closest( 'form' );
			var post_type = $( this ).val();
			var uploader = $( this ).data( 'uploader' );
			var inputs_lenght = $( 'input:radio[name="post_type"]' ).length;

			if ( inputs_lenght > 1 ) {

				if (  form.find( '.yz-wall-custom-form[data-post-type!="' + post_type + '"]' ).length == 0 ) {
	            	form.find( '.yz-wall-custom-form[data-post-type="' + post_type + '"]' ).fadeIn();
				} else {
		            form.find( '.yz-wall-custom-form[data-post-type!="' + post_type + '"]' ).fadeOut( 1, function() {
		            	form.find( '.yz-wall-custom-form[data-post-type="' + post_type + '"]' ).fadeIn();
		            });
				}

			} else {
	            form.find( '.yz-wall-custom-form[data-post-type="' + post_type + '"]' ).fadeIn();
			}

	        // Set Input Supported Elements.
	        if ( post_type == 'activity_video' ) {
	        	form.find( '.yz-upload-attachments' ).attr( 'accept', 'video/*' );
	        } else if ( post_type == 'activity_audio' ) {
	        	form.find( '.yz-upload-attachments' ).attr( 'accept', 'audio/*' );
	        } else if ( post_type == 'activity_file' ) {
	        	form.find( '.yz-upload-attachments' ).removeAttr( 'accept' );
	        } else {
	        	form.find( '.yz-upload-attachments' ).attr( 'accept', 'image/*' );
	        }

	        // Show/Hide Upload Button
	        if ( uploader == 'on' ) {
	            if ( post_type != 'activity_quote' && post_type != 'activity_link' && form.parent().attr( 'id' ) != 'youzer-edit-activity-wrapper' ) {
	            	form.find( '.yz-wall-actions .yz-wall-upload-btn' ).trigger( 'click' );
	            }
	            form.find( '.yz-wall-actions .yz-wall-upload-btn' ).fadeIn();
	        } else {
	            form.find( '.yz-wall-actions .yz-wall-upload-btn' ).fadeOut();
	        }

	        // Set Cursor on First Field.
	        if ( form.find( '.yz-wall-custom-form[data-post-type="' + post_type + '"]' ).find( 'input[type="text"]' )[0]  ) {
	        	form.find( '.yz-wall-custom-form[data-post-type="' + post_type + '"]' ).find( 'input[type="text"]' )[0].focus();
	        }

	        // Remove Old Attachments
	       	form.find( '.yz-attachment-item' ).remove();

	    });

		// Display Form Fields After Page Load.
		$( 'input:radio[name="post_type"]' ).first().trigger( 'change' );

		// // Init Vars.
		// var yz_atts_count = 0, yz_nxt_atts_id = 0, yz_atts_files = null;

		/**
		 * Open Files Uploader
		 */
		$( document ).on( 'click', '.yz-wall-upload-btn', function( e ) {

			if ( ! yz_load_attachments ) {
				$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-attachments.min.js' } ).appendTo( 'head' );
				$( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/yz-attachments.min.css' } ).appendTo( 'head' );
				yz_load_attachments = true;
			}

			$( this ).closest( 'form' ).find( '.yz-upload-attachments' ).click();

			// Check Files Number.
			// if ( $form.find( '.yz-file-progress' )[0] ) {
			// 	return false;
			// }

			// // Hide Comment Upload Button.
			// if ( $form.hasClass( 'ac-form' ) ) {
			// 	$form.find( '.yz-wall-upload-btn' ).fadeOut();
			// 	if ( $form.find( '.yz-attachment-item' )[0] ) {
			// 		return false;
			// 	}
			// }

			// $form;

		    e.preventDefault();

		});

		/**
		 * Display Tag Users Search Box & Friends List.
		 */
		$( document ).on( 'click', '.yz-tag-users-tool', function() {

			var form = $( this ).closest( 'form' );

        	// Hide All Form Opened Lists.
        	form.find( '.yz-feeling-form' ).fadeOut();

			if ( $( this ).hasClass( 'loaded' ) ) {
				form.find( '.yz-tagusers-form' ).fadeToggle();
				form.find( '.yz-tagusers-search-input' ).focus();
				return;
			}

			var button = $( this ),
				icon = button.find( 'i' ),
				old_icon = icon.attr( 'class' );

			// Display Loader.
			icon.attr( 'class', 'fas fa-spinner fa-spin' );

			// Get User Friends.
	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'json',
	            data: { 'action': 'yz_tag_users_get_user_friends' },
	            success: function( response ) {

	            	button.addClass( 'loaded' );
		        	var	list = $( '<div></div>' ).append( response.data );


		        	// Hide Selected Items.
		        	if ( form.parent().attr( 'id' ) == 'youzer-edit-activity-wrapper' ) {

		        		var tagged_users_ids = [];

		        		form.find( 'input[name="tagged_users[]"]' ).each(function() {
		        			list.find( '.yz-list-item[data-user-id="100"] ').fadeOut();
						    tagged_users_ids.push($(this).val());
						});

		        		list.find( '.yz-list-item' ).each(function( i, data ) {

						   if ( jQuery.inArray( $( this ).attr( 'data-user-id' ), tagged_users_ids ) !== -1 ) {
						   	$ ( this ).find( '.yz-wall-tag-user' ).hide();
						   }

						});

		        	}
	            	form.find( '.yz-tagusers-form' ).fadeIn( 200, function() {
	            		icon.attr( 'class', old_icon );
	            		form.find( '.yz-tagusers-search-input' ).focus();
	            		form.find( '.yz-wall-tagusers-list' ).html( list );
	            	});
	            }
	        });

		});

		/**
		 * Tag Users Search.
		 */
		$( document ).on( 'keyup', '.yz-list-search-input', function() {
			var form = $( this ).closest( '.yz-wall-list' );
			var value = this.value.toLowerCase().trim();
			form.find( '.yz-wall-list-items .yz-list-item' ).show().filter(function() {
				return $( this ).find( '.yz-item-title, .yz-item-description' ).text().toLowerCase().trim().indexOf( value ) == -1;
			}).hide();
		});

		/**
		 * Display Feeling/Activity Search Box & Categories..
		 */
		$( document ).on( 'click', '.yz-user-mood-tool', function() {

			var form = $( this ).closest( 'form' );

	        form.find( '.yz-tagusers-form' ).fadeOut();

			if ( $( this ).hasClass( 'loaded' ) ) {
				form.find( '.yz-feeling-form' ).fadeToggle();
				form.find( '.yz-feeling-search-input' ).focus();
				return;
			}

			// Set Place Holder Attribute.
			form.find( '.yz-feeling-form' ).attr( 'data-placeholder', form.find( '.yz-feeling-search-input' ).attr( 'placeholder' ) );

			var button = $( this ),
				icon = button.find( 'i' ),
				old_icon = icon.attr( 'class' );

			// Display Loader.
			icon.attr( 'class', 'fas fa-spinner fa-spin' );

			// Get User Friends.
	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'json',
	            data: { 'action': 'yz_feeling_activity_get_categories' },
	            success: function( response ) {
	            	// Hide All Form Opened Lists.
	            	button.addClass( 'loaded' );
	            	form.find( '.yz-feeling-form' ).fadeIn( 200, function() {
	            		icon.attr( 'class', old_icon );
	            		form.find( '.yz-feeling-search-input' ).focus();
	            		form.find( '.yz-wall-feeling-list' ).html( response.data );
	            	});
	            }
	        });

		});

		/**
		 * Select Feeling/Activity Category.
		 **/
		$( document ).on( 'click', '.yz-feeling-item', function( e ) {

			e.preventDefault();

			// Init Vars.
			var el = $( this ), parent = el.closest( '.yz-wall-feeling' );

			if ( $( this ).parent().hasClass( 'yz-list-category-items' ) ) {

				// Select Emoji.
				$.yz_select_mood_emojis( parent, el.attr( 'data-emoji' ), el.attr( 'data-category-title' ), el.find( '.yz-item-img' ).css( 'background-image' ) );

				return;
			}

			// Add Category Title.
			parent.find( '.yz-list-items-title' ).text( el.attr( 'data-category-title' ) );
			parent.find( 'input[name="mood_type"]' ).val( el.attr( 'data-category' ) );

			// Hide Categories.
			parent.find( '.yz-list-categories' ).fadeOut();
			parent.find( '.yz-feeling-close-icon' ).addClass( 'yz-feeling-go-back' );
			parent.find( '.yz-feeling-close-icon i' ).attr( 'class', 'far fa-arrow-alt-circle-left' );

			// Change Search Form Place Holder.
			parent.find( 'input[name="mood_search"]' ).val( '' ).attr( 'placeholder', el.find( '.yz-item-description' ).text() );
			parent.find( '.yz-list-search-icon i' ).attr( 'class', el.find( '.yz-item-icon i' ).attr( 'class' ) );

			// Show Category.
			var current_category = parent.find( '.yz-list-category-items[data-category="' + $( this ).attr( 'data-category' ) + '"]' );
			if ( current_category.length == 1 ) {
				current_category.fadeIn();
			} else {
				parent.find( '.yz-list-submit-button' ).fadeIn();
			}

		});

		/**
		 * Hide Feeling/Activity Box.
		 **/
		$( document ).on( 'click', '.yz-feeling-close-icon', function() {

			// Init Vars.
			var el = $( this ), parent = el.closest( '.yz-wall-feeling' );

			// Reset Form.
			parent.find( '.yz-feeling-submit-button' ).fadeOut();
			parent.find( '.yz-feeling-search-input' ).val( '' );
			parent.find( '.yz-feeling-search-input' ).attr( 'placeholder', parent.find( '.yz-feeling-form' ).attr( 'data-placeholder' ) );
			parent.find( '.yz-feeling-search-icon i' ).attr( 'class', 'fas fa-search' );

			if ( el.hasClass( 'yz-feeling-go-back' ) ) {
				el.find( 'i' ).attr( 'class', 'fas fa-times' );
				parent.find( '.yz-list-categories, .yz-list-item' ).fadeIn();
				parent.find( '.yz-list-category-items' ).fadeOut();
				el.removeClass( 'yz-feeling-go-back' );
				return;
			}

			// Hide Box.
			el.closest( '.yz-feeling-form' ).fadeOut();

		});

		/**
		 * Edit Feeling / Activity Item.
		 */
		$( document ).on( 'click', '.yz-wall-feeling .yz-list-edit-item', function() {

			// Init Vars.
			var el = $( this ), parent = el.closest( '.yz-wall-feeling' ), selected_items = parent.find( '.yz-list-selected-items' );

			selected_items.fadeOut( 200, function() {

				parent.find( '.yz-feeling-search-input' ).val( selected_items.find( '.yz-item-title' ).text() );

				// Delete Item.
				el.closest( '.yz-selected-item' ).fadeOut( 200, function() { $( this ).remove() });

				// Display Search Box.
				parent.find( '.yz-feeling-form' ).fadeIn();

				// Remove Class
				parent.removeClass( 'yz-activity-feeling-selected' );

			});

		});

		/**
		 * Delete Feeling / Activity Item.
		 */
		$( document ).on( 'click', '.yz-wall-feeling .yz-list-delete-item', function() {

			// Init Vars.
			var el = $( this ), parent = el.closest( '.yz-wall-feeling' ), selected_items = parent.find( '.yz-list-selected-items' );


			selected_items.fadeOut( 200, function() {

				// Reset Selected Item Text.
				selected_items.find( '.yz-item-title' ).text( '' );

				// Delete Item.
				el.closest( '.yz-selected-item' ).fadeOut( 200, function() { $( this ).remove() });

				parent.find( '.yz-feeling-close-icon' ).trigger( 'click' );

				// Remove Class
				parent.removeClass( 'yz-activity-feeling-selected' );

			});

		});

		/**
		 * Enter User Activity Value.
		 **/
		$( document ).on( 'click', '.yz-feeling-submit-button', function() {

			// Init Vars.
			var parent = $( this ).closest( '.yz-wall-feeling' ), value = parent.find( '.yz-feeling-search-input' ).val();

			if ( value.trim() == '' ) {
				return;
			}

			// Select Emoji.
			$.yz_select_mood_emojis( parent, value, value );

		});

		// Set Enter On Submit Search Button.
		$( document ).on( 'keypress', '.yz-feeling-search-input', function( e ) {
			if( e.keyCode == 13 ) {
				e.preventDefault();
				var submit = $( this ).next( '.yz-feeling-submit-button' );
				if ( submit.css( 'display' ) != 'none' ) {
					submit.click();
				}
			}
		});

		/**
		 * Display Selected Item
		 */
		$.yz_select_mood_emojis = function( parent, value, title, image ) {

			if ( parent.hasClass( 'yz-activity-feeling-selected' ) ) {
				// alert( 'hhaha');
				// return;
				parent.find( '.yz-selected-item' ).remove();
			}

			// Add Class.
			parent.addClass( 'yz-activity-feeling-selected' );

			// Display Selected Element.
			parent.find( '.yz-feeling-form' ).fadeOut( 0, function() {
				parent.find( '.yz-feeling-selected-items' ).fadeIn();
			});

			var image_div = ( typeof image === "undefined" || image === null ) ? '' : "<div class='yz-item-img' style='background-image: " + image + ";'></div>";
			var div_class = ( typeof image === "undefined" || image === null ) ? ' yz-selected-item-no-image' : '';

			// Parent.
			parent.find( '.yz-list-selected-items' ).append(
				'<div class="yz-selected-item yz-feeling-selected-item' + div_class + '">' + image_div + '<div class="yz-item-title">' + title + '</div>' +
				'<i class="fas fa-pencil-alt yz-selected-item-tool yz-list-edit-item"></i>' +
				'<i class="fas fa-trash-alt yz-selected-item-tool yz-list-delete-item"></i>' +
				'<input type="hidden" name="mood_value" value="' + value + '">' +
				'</div>' ).fadeIn();

			// Clear Search Input.
			parent.find( '.yz-feeling-search-input' ).val( '' );
		}

		/**
		 * Tag Users.
		 **/
		$( document ).on( 'click', '.yz-wall-tag-user', function() {

			// Init Vars.
			var button = $( this );

			if ( button.hasClass( 'yz-selected' ) ) {
				return;
			}

			// Add Class.
			button.addClass( 'yz-selected' );

			// Init Vars
			var parent = button.closest( '.yz-list-item' );

			$( this ).fadeOut( 200, function(){

				var item = '<div class="yz-selected-item yz-tagged-user">' +
						"<div class='yz-item-img' style='background-image: " + parent.find( '.yz-item-img' ).css( 'background-image' ) + ";'></div>" +
						'<div class="yz-item-title">' + parent.find( '.yz-item-title' ).text() + '</div><i class="fas fa-trash-alt yz-selected-item-tool yz-list-delete-item yz-tagusers-delete-user" data-user-id="' + parent.attr( 'data-user-id' ) + '"></i>' +
						'<input type="hidden" name="tagged_users[]" value="' + parent.attr( 'data-user-id' ) + '">' +
					'</div>';

				parent.closest( '.yz-wall-tagusers' ).find( '.yz-tagged-users' ).append( item );

				var users_count = button.closest( '.yz-wall-tagusers' ).find( '.yz-tagged-user' ).length;

				var form = button.closest( '.yz-wall-tagusers' );

				if ( form.find( '.yz-tagged-user' ).length == 1 ) {
					form.find( '.yz-tagged-users' ).fadeIn();
				}

			});
		});

		/**
		 * Delete Tagged Users.
		 **/
		$( document ).on( 'click', '.yz-list-close-icon.yz-tagusers-close-icon', function() {
			$( this ).closest( '.yz-tagusers-form' ).fadeOut();
		});

		/**
		 * Delete Tagged Users.
		 **/
		$( document ).on( 'click', '.yz-tagusers-delete-user', function() {

			// Init Vars.
			var parent = $( this ).parent();
			var form = $( this ).closest( '.yz-wall-tagusers' );
			var tagged_users = $( this ).closest( '.yz-tagged-users' );

			// Display select button again.
			form.find( '.yz-list-item[data-user-id=' + $( this ).attr( 'data-user-id' ) + '] .yz-wall-tag-user' ).removeClass( 'yz-selected' ).fadeIn();

			$( this ).parent().fadeOut( 200, function() {
				$( this ).remove();
				var users_count = tagged_users.find( '.yz-tagged-user' ).length;
				if ( users_count == 0 ) {
					tagged_users.fadeOut();
				}
			});

		});

		/**
		 * Hide Privacy if the post will be added to groups.
		 */
		$( document ).on( 'change', '#whats-new-post-in', function( e ) {

			if ( $( this ).val() != 0 ) {
				$( this ).closest( '.yz-wall-actions' ).find( 'div.yz-activity-privacy' ).fadeOut();
			} else {
				$( this ).closest( '.yz-wall-actions' ).find( 'div.yz-activity-privacy' ).fadeIn();
			}

		});

		/**
		 * Hide Privacy if the post will be added to groups.
		 */
		$( document ).on( 'click', '.yz-send-comment', function( e ) {

			e.preventDefault();

			if ( $( this ).hasClass( 'loading' ) ) {
				return;
			}

			var target = $( this ), form, content, form_parent, comment_id, form_id, tmp_id, ajaxdata, ak_nonce,
			new_count,show_all_a, old_icon;

			target.addClass( 'loading' );
			old_icon = target.find( 'i' ).attr( 'class' );
			target.find( 'i' ).attr( 'class', 'fas fa-spinner fa-spin' );

			/* Activity comment posting */
			// if ( target.attr('name') === 'ac_form_submit' ) {
				form = target.parents( 'form' );
				form_parent = form.parent();
				form_id = form.attr('id').split('-');

				if ( !form_parent.hasClass('activity-comments') ) {
					tmp_id = form_parent.attr('id').split('-');
					comment_id = tmp_id[1];
				} else {
					comment_id = form_id[2];
				}

				content = $( '#' + form.attr('id') + ' textarea' );

				/* Hide any error messages */
				$( '#' + form.attr('id') + ' div.error').hide();
				target.addClass('loading').prop('disabled', true);
				content.addClass('loading').prop('disabled', true);

				ajaxdata = {
					action: 'new_activity_comment',
					'cookie': bp_get_cookies(),
					'_wpnonce_new_activity_comment': $('#_wpnonce_new_activity_comment').val(),
					'comment_id': comment_id,
					'form_id': form_id[2],
					'post_type' : 'activity_comment',
					'content': content.val()
				};

				// Akismet
				ak_nonce = $('#_bp_as_nonce_' + comment_id).val();
				if ( ak_nonce ) {
					ajaxdata['_bp_as_nonce_' + comment_id] = ak_nonce;
				}

				$.post( ajaxurl, ajaxdata, function(response) {
					target.removeClass('loading');
					content.removeClass('loading');
					target.find( 'i' ).attr( 'class', old_icon );
					/* Check for errors and append if found. */
					if ( response[0] + response[1] === '-1' ) {
						form.append( $( response.substr( 2, response.length ) ).hide().fadeIn( 200 ) );
					} else {
						var activity_comments = form.parent();
						form.fadeOut( 200, function() {
							if ( 0 === activity_comments.children('ul').length ) {
								if ( activity_comments.hasClass('activity-comments') ) {
									activity_comments.prepend('<ul></ul>');
								} else {
									activity_comments.append('<ul></ul>');
								}
							}

							/* Preceding whitespace breaks output with jQuery 1.9.0 */
							var the_comment = $.trim( response );

							activity_comments.children('ul').append( $( the_comment ).hide().fadeIn( 200 ) );
							form.children('textarea').val('');
							activity_comments.parent().addClass('has-comments');
						} );
						$( '#' + form.attr('id') + ' textarea').val('');

						/* Increase the "Reply (X)" button count */
						new_count = Number( $('#activity-' + form_id[2] + ' a.acomment-reply span').html() ) + 1;
						$('#activity-' + form_id[2] + ' a.acomment-reply span').html( new_count );

						// Increment the 'Show all x comments' string, if present
						show_all_a = activity_comments.parents('.activity-comments').find('.show-all a');
						if ( show_all_a ) {
							show_all_a.html( BP_DTheme.show_x_comments.replace( '%d', new_count ) );
						}
					}

					$(target).prop('disabled', false);
					$(content).prop('disabled', false);
				});

				return false;
			// }
		});

		// Submit Comment Form if user hits enter.
		$( document ).on( 'keypress', '.ac-form textarea', function( e ) {
			if ( e.which == 13 && !e.shiftKey ) {
                e.preventDefault();
                $( this ).closest( 'form' ).find( '.yz-send-comment' ).click();
            }
		});

		/**
		 * Check Post Type.
		 **/
	    $.yz_isPostType = function( form, post_type ) {
	    	if ( post_type == form.find( 'input:radio[name="post_type"]:checked' ).val() ) {
	    		return true;
	    	}
	    	return false;
	    }


		var current_share_button;

		/**
		 * Get Share Activity.
		 **/
		$( document ).on( 'click', '.share-activity', function( e ) {

			e.preventDefault();

			// Check if there's no other share button is clicked on the same time.
			if ( $( '.share-activity.loading' )[0] ) {
				return;
			}

			// Init Vars.
			var $link = $( this ), $form, $form_wrapper,
			activity_id = $link.parents( 'li' ).attr('id').split('-')[1],
			activity_type = $link.attr( 'data-activity-type' ), show_form = true;

			if ( $link.hasClass( 'loading' ) ) {
				return;
			}

			// Add Link Loading Icon.
			$link.addClass( 'loading' );

			// Set Current Buttonn.
			current_share_button = $link;

			// Get Form Wrapper.
			$form_wrapper = $( '#youzer-edit-activity-wrapper');

			// Get Form Data.
			var data = {
				'action': 'yz_get_share_activity_form',
				'yz_share_activity_nonce': Youzer.security_nonce,
				'activity_id': activity_id
			};

			// Process Verification.
			$.post( Youzer.ajax_url, data, function( response ) {

	    		// Remove Share Button.
				if ( response.success == false ) {
	    			current_share_button.remove();
					$.yz_DialogMsg( 'error', response.error );
	    			return;
				}

				// Get Form
	    		var $form = $( response.data.form );

	    		// Append form but Hide it.
	    		$( 'body' ).append( $form.hide() );

	    		var $preview = $( response.data.preview );

	    		$preview.insertAfter( $form.find( 'textarea#whats-new' ) );

	    		// Init Nice Select.
				if ( jQuery.isFunction( $.fn.niceSelect ) ) {
	    			$form.find( 'select:not([multiple="multiple"])' ).niceSelect();
	    		}

				// Append Content.
				// $( 'body' ).append( $form );

				if ( $preview.height() > 150 ) {
					$preview.find( '.yz-shared-wrapper-container' ).addClass( 'yz-collapsed-preview' );
	    			$form.find( '.yz-shared-wrapper' ).append( response.data.show_all );
				}

				$form.find( 'form' ).append( '<input type="hidden" value="activity_share" name="post_type" />');
				$form.find( 'form' ).append( '<input type="hidden" value="' + response.data.activity_id + '" name="secondary_item_id" />');

				// Hide Form Options Tab
				$form.find( '.yz-wall-options' ).remove();

				if ( response.posts_emojis == 'on' ) {
					show_form = false;
					if ( ! jQuery().emojioneArea ) {
				        $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/emojionearea.min.js' } ).appendTo( 'head' );
				        $( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/emojionearea.min.css' } ).appendTo( 'head' );
					} else {
						$form.find( '.yz-wall-textarea' ).emojioneArea( {
			                pickerPosition: 'bottom',
			                autocomplete: true,
			                saveEmojisAs : 'image',
			                events: {
			                ready: function () {
			                  // form.find( '.emojionearea-button-open' ).click();
			                  this.editor.textcomplete([{
			                      id: 'yz_mentions',
			                      match: /\B@([\-\d\w]*)$/,
			                      search: function ( term, callback ) {
			                          var mentions = bp.mentions.users;
			                          callback( $.map(mentions, function ( mention ) {
			                          return mention.ID.indexOf( term ) === 0 || mention.name.indexOf( term ) === 0 ? mention : null;
			                      }));
			                      },
			                      template: function ( mention ) {
			                          return '<img src="' + mention.image + '" /><span class="username">@' + mention.ID + '</span><small>' +mention.name+ '</small>';
			                      },
			                      replace: function ( mention ) {
			                          return '@' + mention.ID + '&nbsp;';
			                      },
			                      cache: true,
			                      index: 1
			                   }]);
			                }
			              }
	        			} );
					}

				}

				// Remove Attachment.
				$form.find( '.yz-wall-upload-btn' ).remove();

				var setIntervalID = setInterval( function() {
			      	if ( show_form == true || ( show_form == false && $form.find( '.emojionearea' ).get( 0 ) ) ) {
						clearInterval( setIntervalID );

						// Mark Button As laoded.
						$link.attr( 'data-loaded', 'true' );

			    		// Remove Loading Class.
			    		$link.removeClass( 'loading' );

						// Show Form.
						$.yz_show_modal( $form );
				    };
			    }, 100 );

			}).fail( function( xhr, textStatus, errorThrown ) {

				// Remove Loading Class.
	    		$link.removeClass( 'loading' );

            	// Show Error Message
            	$.yz_DialogMsg( 'error', Youzer.unknown_error );

				return false;

    		});


		});

		$( document ).on( 'click', '.yz-show-all-less div', function(){

			var parent = $( this ).parent();

			if ( ! parent.hasClass( 'active' ) ) {
				parent.addClass( 'active' );
				parent.prev( '.yz-shared-wrapper-container' ).removeClass( 'yz-collapsed-preview' );
			} else {
				parent.removeClass( 'active' );
				parent.prev( '.yz-shared-wrapper-container' ).addClass( 'yz-collapsed-preview' );
			}

		});

		// Load Scripts after full load!
  //   	$( window ).on( 'load', function() {
  //   		// Giphy Script.
		//     $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-giphy.min.js' } ).appendTo( 'head' );
		// });

	});

})( jQuery );