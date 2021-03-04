( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Display Activity tools.
		 */
		$( '#youzer' ).on( 'click', 'a.page-numbers', function ( e ) {
			// if ( target.parent().parent().hasClass( 'pagination' )  ) {
				var button_clone = $( this ).clone().html( '<i class="fas fa-spinner fa-spin"></i>' );
				$( this ).hide( 0, function(){
				button_clone.insertAfter( $( this ) );
					
				});
			});
		
		$('#members_search,#groups_search').on('click', function(){
		    $(window).off('resize');
		});

		// Add Loading Button
        $( '#yz-groups-list,#yz-members-list' ).on( 'click', 'a.group-button:not(.membership-requested),.friendship-button:not(.awaiting_response_friend) a', function(e) {
        	e.preventDefault();
    		$( this ).addClass( 'yz-btn-loading' );
		});

		// Display Search Box.
    	$( '#directory-show-search' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '.yz-directory-filter #members-order-select,.yz-directory-filter #groups-order-select,.yz-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeOut( 1 );
    		$( '#yz-directory-search-box' ).fadeToggle();
		});

		// Display Search Box.
    	$( '#directory-show-filter' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#yz-directory-search-box,.yz-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeOut( 1 );
    		$( '.yz-directory-filter #members-order-select, .yz-directory-filter #groups-order-select' ).fadeToggle();
		});

		// Display Search Box.
    	$( '#directory-show-menu' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#yz-directory-search-box,.yz-directory-filter #members-order-select,.yz-directory-filter #groups-order-select' ).fadeOut( 1 );
    		$( '.yz-directory-filter .item-list-tabs:not(#subnav) ul' ).fadeToggle();
		});

		// Activate Members Masonry Layout.
		if ( $( '#yz-members-list' )[0] ) {

			// Set the container that Masonry will be inside of in a var
		    var members_container = document.querySelector( '#yz-members-list' );
		    
		    // Create empty var msnry
		    var members_msnry;
		    
		    // Initialize Masonry after all images have loaded
		    imagesLoaded( members_container, function() {
		        members_msnry = new Masonry( members_container, {
		            itemSelector: '#yz-members-list li'
		        });
		    });

		}

		// Activate Groups Masonry Layout.
		if ( $( '#yz-groups-list' )[0] ) {

			// Set the container that Masonry will be inside of in a var
		    var groups_container = document.querySelector( '#yz-groups-list');
		   
		    // Create empty var msnry
		    var groups_msnry;

		    // Initialize Masonry after all images have loaded
		    imagesLoaded( groups_container, function() {
		        groups_msnry = new Masonry( groups_container, {
		            itemSelector: '#yz-groups-list li'
		        });
		    });

		}


		// Display Search Box.
    	$( '#directory-show-search a' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#yz-directory-search-box' ).fadeToggle();
		});

		// Display Search Box.
    	$( '#directory-show-filter a' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '.yz-directory-filter #members-order-select, .yz-directory-filter #groups-order-select' ).fadeToggle();
		});

	});

})( jQuery );