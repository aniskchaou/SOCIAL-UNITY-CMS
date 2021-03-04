( function( $ ) {

	'use strict';

	$( document ).ready( function() {

	    // Ajax Login.
	    $( '.logy-login-form' ).on( 'submit', function( e ) {

	    	// Add Authenticating Class.
	    	$( this ).addClass( 'yz-authenticating' );

	    	// Init Vars.
	    	var yz_login_form = $( this ), yz_btn_txt, yz_btn_icon, yz_submit_btn;

	    	// Get Current Button Text & Icon.
	    	yz_submit_btn = $( this ).find( 'button[type="submit"]' );
	    	yz_btn_txt  = yz_submit_btn.find( '.logy-button-title' ).text();
	    	yz_btn_icon = yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class' );

	    	// Display "Authenticating..." Messages.
	    	yz_submit_btn.find( '.logy-button-title' ).text( Youzer.authenticating );
	    	yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class', 'fas fa-spinner fa-spin' );

	    	// Get Current Button Icon
	    	var yz_login_data = {
                'action': 'yz_ajax_login',
                'username': $( this ).find( 'input[name="log"]' ).val(),
                'password': $( this ).find( 'input[name="pwd"]' ).val(),
                'remember': $( this ).find( 'input[name="rememberme"]' ).val(),
                'redirect_to': $( this ).find( 'input[name="yz_redirect_to"]' ).val(),
                'security': $( this ).find( 'input[name="yz_ajax_login_nonce"]' ).val(),
	        };

	        $.ajax({
	            type: 'POST',
	            dataType: 'json',
	            url: ajaxurl,
	            data: yz_login_data,
	            success: function( response ) {

	                if ( response.loggedin == true ) {
	                	// Change Login Button Title.
	    				yz_submit_btn.find( '.logy-button-title' ).text( response.message );
	    				yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class', 'fas fa-check' );
		         		// Redirect.
	                    document.location.href = response.redirect_url;
	                } else {

		            	// Add Authenticating Class.
		    			yz_login_form.removeClass( 'yz-authenticating' );

	                	// Clear Inputs Depending on the errors ..
	                	if ( response.error_code && 'incorrect_password' == response.error_code ) {
	                		// Clear Password Field.
	                		yz_login_form.find( 'input[name="pwd"]' ).val( '' );
	                	} else {
	                		// If Username invalid Clear Inputs.
	                		yz_login_form.find( 'input[name="log"],input[name="pwd"]' ).val( '' );
	                	}
	                	// Change Login Button Title & Icon.
	    				yz_submit_btn.find( '.logy-button-title' ).text( yz_btn_txt );
	    				yz_submit_btn.find( '.logy-button-icon i' ).attr( 'class', yz_btn_icon );
		            	// Show Error Message.
		            	$.yz_DialogMsg( 'error', response.message );
	                }
	            }
        	});

	        e.preventDefault();

	    });

	});

})( jQuery );