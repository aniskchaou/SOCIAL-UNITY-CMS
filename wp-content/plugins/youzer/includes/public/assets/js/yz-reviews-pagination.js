( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Get Page Number
		function find_page_number( el ) {
			el.find( '.yz-page-symbole' ).remove();
			return parseInt( el.text() );
		}

		// Get Comments Page
		$( document ).on( 'click', '.yz-reviews-nav-links a', function( e ) {

			e.preventDefault();

			var main_content = $( this ).closest( '.yz-user-reviews' );

            $( 'html, body' ).animate( {
                scrollTop: main_content.offset().top - 150
            }, 1000 );

			$.ajax( {
				url: Youzer.ajax_url,
				type: 'post',
				data: {
					action: 'yz_reviews_pagination',
					user_id: Youzer.displayed_user_id,
					base:  $( this ).closest( '.yz-pagination' ).attr( 'data-base' ),
					page: find_page_number( $( this ).clone() ),
					per_page: $( this ).closest( '.yz-pagination' ).attr( 'data-per-page' )
				},
				beforeSend: function() {
					main_content.remove();
					$( document ).scrollTop();
					$( '#yz-main-reviews .yz-loading' ).show();
				},
				success: function( html ) {
					$( '#yz-main-reviews .yz-loading' ).hide();
					$( '#yz-main-reviews' ).append( html );
				}
			})

		});

	});

})( jQuery );