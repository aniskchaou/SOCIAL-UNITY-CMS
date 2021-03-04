( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Open Files Uploader
		 */
		var yz_load_attachments = false;
		
		$( document ).on( 'click', '.yz-upload-btn', function( e ) {

			// Load Attachments JS.
			if ( ! yz_load_attachments ) {
				$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-attachments.min.js' } ).appendTo( 'head' );
				$( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/yz-attachments.min.css' } ).appendTo( 'head' );
				yz_load_attachments = true;
			}

			var $form = $( this ).closest( 'form' );
			if ( $form.find( '.yz-attachment-item' )[0] ) {
				return false;
			}

			$form.find( '.yz-upload-attachments' ).click();

			// Hide Comment Upload Button.
			// $form.find( '.yz-upload-btn' ).fadeOut();

		    e.preventDefault();

		});

		/**
		 * Load Emojis JS.
		 */
    	$( document ).on( 'click', '.yz-load-emojis', function() {
        	var form = $( this ).closest( 'form' );
	        $( this ).find( 'i' ).attr(  'class', 'fas fa-spin fa-spinner' );
        	$( this ).addClass( 'loading' );
	        $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/emojionearea.min.js' } ).appendTo( 'head' );
	        $( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/emojionearea.min.css' } ).appendTo( 'head' );
	        // $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-emoji.min.js' } ).appendTo( 'head' );
	    });

	});


})( jQuery );