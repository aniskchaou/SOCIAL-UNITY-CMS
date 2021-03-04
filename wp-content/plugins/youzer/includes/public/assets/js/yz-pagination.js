( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Get Page Number
		function find_page_number( el ) {
			el.find( '.yz-page-symbole' ).remove();
			return parseInt( el.text() );
		}

		// Get Posts Page
		$( document ).on( 'click', '.posts-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.yz-posts' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var page = find_page_number( $( this ).clone() ),
				yz_base = $( this ).closest( '.yz-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'pages_pagination',
					query_vars: ajaxpagination.query_vars,
					base: yz_base,
					page: page
				},
				beforeSend: function() {
					$( '#yz-main-posts' ).find( '.yz-posts-page' ).remove();
					$( document ).scrollTop();
					$( '#yz-main-posts .yz-loading' ).show();
				},
				success: function( html ) {
					$( '#yz-main-posts .yz-loading' ).hide();
					$( '#yz-main-posts' ).append( html );
				}
			})

		});

		// Get Comments Page
		$( document ).on( 'click', '.comments-nav-links a', function( e ) {

			e.preventDefault();

            $( 'html, body' ).animate( {
                scrollTop: $( '.yz-comments' ).offset().top - 150
            }, 1000 );

			// Get Page Number
			var cpage = find_page_number( $( this ).clone() ),
				cbase = $( this ).closest( '.yz-pagination' ).attr( 'data-base' );

			$.ajax( {
				url: ajaxpagination.ajaxurl,
				type: 'post',
				data: {
					action: 'comments_pagination',
					query_vars: ajaxpagination.query_vars,
					base: cbase,
					page: cpage
				},
				beforeSend: function() {
					$( '#yz-main-comments' ).find( '.yz-comments-page' ).remove();
					$( document ).scrollTop();
					$( '#yz-main-comments .yz-loading' ).show();
				},
				success: function( html ) {
					$( '#yz-main-comments .yz-loading' ).hide();
					$( '#yz-main-comments' ).append( html );
				}
			})

		});

	});

})( jQuery );