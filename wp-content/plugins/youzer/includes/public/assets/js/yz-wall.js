var yz_load_attachments = false;
( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		if ( jQuery().viewportChecker ) {

			/**
			 * Init Wall Posts Effects.
			 */
			$.yz_init_wall_posts_effect = function() {

				if ( $( '.yz_effect' )[0] ) {
					$( '.yz_effect' ).viewportChecker( {
					    classToAdd: 'animated',
					    classToRemove: 'invisible',
					    removeClassAfterAnimation: true,
					    offset:'10%',
					    callbackFunction: function( elem, action ) {
							elem.addClass( elem.data( 'effect' ) );
					    }
					}, 500 );
				}
			}

			// Init Posts Effect.
			$.yz_init_wall_posts_effect();

			// Init Effect On the appended elements also.
			$( 'body' ).on( 'append', '#activity-stream', function( e ) {
				$.yz_init_wall_posts_effect();
	        });

			if ( $( '.yz-column-content div.activity' )[0] ) {
				// Init Effect On Activity Filters
				var yz_observer = new MutationObserver(function( mutations ) {
					$.yz_init_wall_posts_effect();
				});

				// Pass in the target node, as well as the observer options
				yz_observer.observe( $( '.yz-column-content div.activity' )[0] , { attributes: false, childList: true, subtree:false, characterData: false } );
			}

			if ( $( 'div.activity #activity-stream' )[0] ) {
				// Init Effect On Activity Filters
				var yz_observer = new MutationObserver(function( mutations ) {
					$.yz_init_wall_posts_effect();
				});

				// Pass in the target node, as well as the observer options
				yz_observer.observe( $( 'div.activity #activity-stream' )[0] , { attributes: false, childList: true, subtree:false, characterData: false } );
			}

		}

		/**
		 * # Modal.
		 */
		$( document ).on( 'click', '.yz-trigger-who-modal' , function( e ) {

			e.preventDefault();

			// Init Var.
			var button = $( this );

			if ( button.hasClass( 'loading' ) ) {
				return;
			}

			// Show loader
			button.addClass( 'loading' );

			// Init Var.
			var reset_text = false;

			// Show Loading.
			if ( ! button.find( 'i' ).get( 0 ) ) {
				var old_nbr = button.text(), reset_text = true;
				button.html( '<i class="fas fa-spin fa-spinner"></i>' );
			}

			var li =  $( this ).closest( 'li.activity-item' ),
				data = {
				'action': $( this ).data( 'action' ),
				'post_id': li.attr( 'id' ).substr( 9, li.attr( 'id' ).length )
			};

			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
			jQuery.post( Youzer.ajax_url, data, function( response ) {

				// Set Older Number.
				if ( reset_text ) {
					button.html( old_nbr );
				}

	    		// Shpow pop-up
	    		$.yz_show_modal( $( response ) );

				// Hide Loader
				button.removeClass( 'loading' );

			});

		});

		/**
		 * # Modal.
		 */
		$( document ).on( 'click', '.yz-delete-post' , function( e ) {
			/* Delete activity stream items */
			var target = $( this ),
			li = target.parents( 'div.activity ul li' ),
			timestamp = li.prop( 'class' ).match( /date-recorded-([0-9]+)/ );

			target.addClass('loading');
			jq.post( ajaxurl, {
				action: 'delete_activity',
				'cookie': bp_get_cookies(),
				'id': $( this ).parent().attr( 'data-activity-id' ),
				'_wpnonce': target.attr( 'data-nonce' )
			},
			function(response) {

				if ( response[0] + response[1] === '-1' ) {
					li.prepend( response.substr( 2, response.length ) );
					li.children('#message').hide().fadeIn(300);
				} else {
					li.slideUp(300);

					// reset vars to get newest activities
					if ( timestamp && activity_last_recorded === timestamp[1] ) {
						newest_activities = '';
						activity_last_recorded  = 0;
					}
				}
			});

		});


		/**
		 * Show Activity Tagged Users.
		 */
		$( document ).on( 'click', '.yz-show-tagged-users' , function( e ) {

			e.preventDefault();

			$( '.yz-wall-modal-overlay' ).fadeIn( 500, function() {
				$( this ).find( '.yz-modal-loader' ).fadeIn( 400 );
			});

			// Init Vars.
			var li = $( this ).closest( 'li.activity-item' );
			var data = {
				'action': 'yz_activity_tagged_users_modal',
				'post_id': li.attr( 'id' ).substr( 9, li.attr( 'id' ).length )
			};

			// Show Modal.
			jQuery.post( Youzer.ajax_url, data, function( response ) {
				var $new_modal = $( '#yz-wall-modal' ).append( response );
			    // Display Modal
				$new_modal.find( '.yz-wall-modal' ).addClass( 'yz-wall-modal-show' );
	    		// $new_modal.css( { 'position': 'absolute', 'top': $( document ).scrollTop() + 100 } );
				// Hide Loader
				$( '.yz-wall-modal-overlay' ).find( '.yz-modal-loader' ).hide();
			});

		});

		// Hide Modal If User Clicked Escape Button
		$( document ).keyup( function( e ) {
			if ( $( '.yz-wall-modal-show' )[0] ) {
			    if ( e.keyCode === 27 ) {
				    $( '.yz-wall-modal-close' ).trigger( 'click' );
			    }
			}
		});

		// # Hide Modal if User Clicked Outside
		$( document ).mouseup( function( e ) {
		    if ( $( '.yz-wall-modal-overlay' ).is( e.target ) && $( '.yz-wall-modal-show' )[0] ) {
				$( '.yz-wall-modal-close' ).trigger( 'click' );
		    }
		});

		if ( Youzer.activity_autoloader == 'on' ) {

			// Hide Load More Button.
			// $( '.youzer .activity li.load-more' ).css( 'visibility', 'hidden' );

		   var $window = $( window );

			// Check the window scroll event.
			$window.scroll( function () {
				// Find the visible "load more" button.
				// since BP does not remove the "load more" button, we need to find the last one that is visible.
				var $load_more_btn = $( '#activity-stream .load-more:visible' );
				// If there is no visible "load more" button, we've reached the last page of the activity stream.
				// If data attribute is set, we already triggered request for ths specific button.
				if ( ! $load_more_btn.get( 0 ) || $load_more_btn.data( 'yz-autoloaded' ) ) {
					return;
				}

				// Find the offset of the button.
				var pos = $load_more_btn.offset();
				var offset = pos.top - 3000;// 50 px before we reach the button.

				// If the window height+scrollTop is greater than the top offset of the "load more" button,
				// we have scrolled to the button's position. Let us load more activity.
				if ( $window.scrollTop() + $window.height() > offset ) {
					$load_more_btn.data( 'yz-autoloaded', 1 );
					$load_more_btn.find( 'a' ).trigger( 'click' );
				}

			});
		}

		/* Add / Remove friendship buttons */
		$( '#activity-stream' ).on('click', '.friendship-button a', function() {
			$(this).parent().addClass('loading');
			var fid   = $(this).attr('id'),
				nonce   = $(this).attr('href'),
				thelink = $(this);

			fid = fid.split('-');
			fid = fid[1];

			nonce = nonce.split('?_wpnonce=');
			nonce = nonce[1].split('&');
			nonce = nonce[0];

			jq.post( ajaxurl, {
				action: 'addremove_friend',
				'cookie': bp_get_cookies(),
				'fid': fid,
				'_wpnonce': nonce
			},
			function(response)
			{
				var action  = thelink.attr('rel');
				var parentdiv = thelink.parent();

				if ( action === 'add' ) {
					$(parentdiv).fadeOut(200,
						function() {
							parentdiv.removeClass('add_friend');
							parentdiv.removeClass('loading');
							parentdiv.addClass('pending_friend');
							parentdiv.fadeIn(200).html(response);
						}
						);

				} else if ( action === 'remove' ) {
					$(parentdiv).fadeOut(200,
						function() {
							parentdiv.removeClass('remove_friend');
							parentdiv.removeClass('loading');
							parentdiv.addClass('add');
							parentdiv.fadeIn(200).html(response);
						}
						);
				}
			});
			return false;
		} );

		$('#activity-stream').on('click', '.group-button a', function( e ) {

			if( ! $( this ).hasClass( 'membership-requested') ) {
				$( this ).addClass( 'yz-btn-loading' );
			}

			var gid   = $(this).parent().attr('id'),
				nonce   = $(this).attr('href'),
				thelink = $(this);

			gid = gid.split('-');
			gid = gid[1];

			nonce = nonce.split('?_wpnonce=');
			nonce = nonce[1].split('&');
			nonce = nonce[0];

			// Leave Group confirmation within directories - must intercept
			// AJAX request
			if ( thelink.hasClass( 'leave-group' ) && false === confirm( BP_DTheme.leave_group_confirm ) ) {
				return false;
			}

			jq.post( ajaxurl, {
				action: 'joinleave_group',
				'cookie': bp_get_cookies(),
				'gid': gid,
				'_wpnonce': nonce
			},
			function(response) {
				var parentdiv = thelink.parent();

				$(parentdiv).fadeOut(200,
					function() {
						parentdiv.fadeIn(200).html(response);

						var mygroups = $('#groups-personal span'),
							add        = 1;

						if( thelink.hasClass( 'leave-group' ) ) {
							// hidden groups slide up
							if ( parentdiv.hasClass( 'hidden' ) ) {
								parentdiv.closest('li').slideUp( 200 );
							}

							add = 0;
						} else if ( thelink.hasClass( 'request-membership' ) ) {
							add = false;
						}

						// change the "My Groups" value
						if ( mygroups.length && add !== false ) {
							if ( add ) {
								mygroups.text( ( mygroups.text() >> 0 ) + 1 );
							} else {
								mygroups.text( ( mygroups.text() >> 0 ) - 1 );
							}
						}

					}
				);
			});
			return false;
		} );

		$("audio").on("play", function() {
	        $("audio").not(this).each(function(index, audio) {
	            audio.pause();
	        });
	    });

		/**
		 * Nice Select - Add Attribute value to current.
		 */
		$( document ).on( 'click', '.nice-select .option', function( e ) {
			$( this ).parent().prev( '.current' ).attr( 'data-value', $( this ).attr( 'data-value' ) );
		});

		/**
		 * Shortcodes Pagination.
		 */
		$( '#activity-stream' ).on( 'click', 'li.load-more', function( e ) {

			if ( $( this ).closest('.yz-activity-shortcode')[0] ) {

				// Stop Propagation.
				e.stopImmediatePropagation();

				// Get Current Load More Button.
			    var load_more_button = $( this );

			    // Add Loading Icon.
			    load_more_button.addClass( 'loading' );

			    // Get Shortcode Container.
				var container = $( this ).parents( '.yz-activity-shortcode' );

				// Increase Page Number
			    container.attr( 'data-page', parseInt( container.attr( 'data-page' ) ) + 1 );

			    var data = container.data();

			    data.page = container.attr( 'data-page');

			    $.post( ajaxurl, { data: data, action : 'yz_activity_load_activities' }, function( response ) {

			        if ( response.success ) {
			        	load_more_button.hide();
			            // Add New Posts.
			            load_more_button.parents( 'ul.activity-list' ).append( response.data );
			        }

			    }, 'json' );

			    return false;

			}

		});

		// Add Modal DIv.
		// $( 'body' ).append( '<div id="yz-wall-modal"></div><div class="yz-wall-modal-overlay"><div class="yz-modal-loader"><i class="fas fa-spinner fa-spin"></i></div></div>' );


		/**
		 * Display Activity tools.
		 */
		$( document ).on( 'click',  '.activity-item .yz-show-item-tools', function ( e ) {

			var button = $( this ), li = button.closest( 'li.activity-item' ), default_icon = button.find( 'i' ).attr( 'class' );

			if ( button.hasClass( 'loaded' ) ) {
				li.find( '.yz-activity-tools' ).fadeToggle();
				return;
			}

			if ( button.hasClass( 'loading' ) ) {
				return;
			}

			button.addClass( 'loading' );

			button.find( 'i' ).attr( 'class', 'fas fa-spin fa-spinner' );

			// Get Activity Tools.
	        $.ajax({
	            type: 'POST',
	            url: ajaxurl,
	            dataType: 'json',
	            data: { 'activity_id' : li.attr( 'id' ).substr( 9, li.attr( 'id' ).length ), 'action': 'yz_get_activity_tools' },
	            success: function( response ) {
	            	button.find( 'i' ).attr( 'class', default_icon );
	            	button.addClass( 'loaded' );
	            	button.removeClass( 'loading' );
	            	if ( response.success ) {
	            		li.prepend( $( response.data ).fadeIn() );
	            	}

	            	// Include Sticky Scripts.
	            	if ( $( response.data ).find( '.yz-pin-tool' ).get( 0 ) ) {
						$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-sticky-posts.min.js' } ).appendTo( 'head' );
	            	}

	            	// Include Bookmark Scripts.
	            	if ( $( response.data ).find( '.yz-bookmark-tool' ).get( 0 ) ) {
						$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-bookmark-posts.min.js' } ).appendTo( 'head' );
	            	}

	            }
	        });

		});


		// Display Search Box.
    	$( '.yz-activity-show-search-form' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var button = $( this ), parent = button.closest( 'ul' );
    		parent.find( '#activity-filter-select .yz-dropdown-area' ).fadeOut( 1, function() {
    			button.closest( 'li' ).find( '.yz-dropdown-area' ).fadeToggle();
    			button.closest( 'li' ).find( 'input' ).focus();
    		});
		});

		// Display Search Box.
    	$( '.yz-activity-show-filter' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var button = $( this ), parent = button.closest( 'ul' );
    		parent.find( '.yz-activity-show-search .yz-dropdown-area' ).fadeOut( 1, function() {
    			button.closest( 'li' ).find( '.yz-dropdown-area' ).fadeToggle();
    		} );
		});


		// Display Search Box.
    	$( '.yz-show-activity-search' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var parent = $( this ).parents( '#youzer' ),
    		element = parent.find( '.yz-activity-show-search .yz-dropdown-area' );
    		parent.find( '#activity-filter-select .yz-dropdown-area, .activity-type-tabs' ).fadeOut();
    		element.fadeToggle();
    		element.find( 'input' ).focus();
		});

		// Display Filter Box.
    	$( '.yz-show-activity-filter' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var parent = $( this ).parents( '#youzer' );
    		parent.find( '.yz-activity-show-search .yz-dropdown-area, .activity-type-tabs' ).fadeOut();
    		parent.find( '#activity-filter-select .yz-dropdown-area' ).fadeToggle();
		});

		// Display Menu Box.
    	$( '.yz-show-activity-menu' ).on( 'click', function( e ) {
    		e.preventDefault();
    		var parent = $( this ).parents( '#youzer' );
    		parent.find( '#subnav .yz-dropdown-area' ).fadeOut();
    		parent.find( '.activity-type-tabs' ).fadeToggle();
		});

		/**
		 * # Hide Modal if user clicked Close Button or Icon
		 */
		$( document ).on( 'click', '.yz-wall-modal-close' , function( e ) {

			e.preventDefault();

			// Hide Form.
			$( '.yz-wall-modal' ).removeClass( 'yz-wall-modal-show' );
	        $( '.yz-wall-modal-overlay' ).fadeOut( 600 );

			setTimeout(function(){
			   // wait for card1 flip to finish and then flip 2
			   $( '.yz-wall-modal' ).remove();
			}, 500);

		});

	});

})( jQuery );