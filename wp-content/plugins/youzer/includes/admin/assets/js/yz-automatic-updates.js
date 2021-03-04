( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Woocommerce Add to cart with ajax.
		 */
		$( document ).on( 'click', '.yz-activate-addon-key', function (e) {
			// return;
		    e.preventDefault();

		    var button = $( this );

		    if ( button.hasClass( 'loading' ) ) {
		    	return;
		    }

	        var parent = button.closest( '.yz-addon-license-area' ),
	        	title = button.text(),
	        	data = {
		        action: 'yz_save_addon_key_license',
		        license: $( '.yz-addon-license-key' ).find( 'input' ).val(),
		        // nounce : button.data( 'nounce' ),
		        // product_id : button.data( 'product-id' ),
		        product_name : button.data( 'product-name' ),
		        name: button.data( 'option-name' )
		    };
		    $.ajax({
		        type: 'post',
		        url: yz.ajax_url,
		        data: data,
		        beforeSend: function (response) {
		        	button.addClass( 'loading' );
		            button.html( '<i class="fas fa-spin fa-spinner"></i>' );
		            parent.find( '.yz-addon-license-msg' ).remove();
		        },
		        complete: function (response) {
		            button.html( title );
		        	button.removeClass( 'loading' );
		        },
		        success: function (response) {

		            if ( response.success ) {
		            	button.parent().hide( 100, function() {
		            		$.ShowPanelMessage( { msg : response.data.message, type : 'success' });
				            location.reload();
		            		// button.closest( '.yz-addon-license-area' ).append( '<div class="yz-addon-license-msg yz-addon-success-msg">' +  response.success + '</div>' );
		            		// $( this ).remove();
		            	});
		            } else {
		            	$.ShowPanelMessage( { msg : response.data.message, type : 'error' });
		            }
		        }
		    });

		    return false;
		});

	});

})( jQuery );