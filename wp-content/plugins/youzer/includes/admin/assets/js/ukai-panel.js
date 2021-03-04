( function( $ ) {

	'use strict';

	$( document ).ready( function () {
	    
	    var yz_save_changes_title =  $( '.panel-top-actions button.yz-save-options' ).text();

		/**
	     * # Uploader.
	     */
	    $( document ).on( 'click', '.yz-fixed-save-btn' , function( e ) {
	    	e.preventDefault();
	    	// Disable Button While Saving Settings.
	    	if ( $( this ).hasClass( 'yz-loading' ) ) {
	    		return false;
	    	}
	    	// Trigger Submit.
	    	$( '.yz-settings-form' ).trigger( 'submit' );	
	    });

	    /**
	     * Saving Options With Ajax
	     */
	    $( '.yz-settings-form' ).submit( function( e ) {

	        // Don't Refresh Page
	        e.preventDefault();

	        // Show Button Effect
	        $.yz_saving_options_effect();

	        // Show Loading Message
	        $( '#youzer-wait-message' ).show();

	        // Get Data
	        var data = $( this ).serialize();

	        // Saving Data
	        $.post( yz.ajax_url, data, function( response ) {

	            // Show Processing Text While Saving.
	            $.yz_saving_options_effect( { step : 'end' } );

	            $( '#youzer-wait-message' ).hide();
	            
	            if ( response.success ) {
	                // Show Success Message
	                $.ShowPanelMessage( { type: 'success', msg : response.data.message } );
		            // Refresh Page
		            if ( response.data.result == 'refresh' ) {
		                location.reload();
		            }
	            } else {
	                // Show Error Message
	                $.ShowPanelMessage( { msg : response.data.message, type : 'error' });
	            }

	        });
	    });

	    /**
	     * Saving Options Button
	     */
	    $.yz_saving_options_effect =  function( options ) {

	        var settings = $.extend({
	            step: 'processing'
	        }, options );

	       if ( settings.step == 'processing' ) {
            	// Enable Fixed "Save Settings Button"
	            $( '.yz-fixed-save-btn' ).addClass( 'yz-loading' );
	            $( '.yz-save-options' ).fadeOut( 800, function() {
	                // Disable Save Button while saving Options.
	                $( this ).prop( 'disabled', true );
	                // Changing Button Text
	                var text = '<i class="fas fa-spinner fa-spin"></i>';
	                $( this ).html( text ).fadeIn( 1000);
	            });
	        } else if ( settings.step == 'end' ) {
            	// Disable Fixed "Save Settings Button"
            	$( '.yz-fixed-save-btn' ).removeClass( 'yz-loading' );
	            // Processing Saving
	            $( '.yz-save-options' ).fadeOut( 200, function() {
	                // Changing Button Text
	                $( this ).html( yz_save_changes_title ).fadeIn( 1000 );
	                // Enable Save Button Again.
	                $( this ).prop( 'disabled', false );
	            });
	        }
	    }
	    
        // ColorPicker
        $( '.yz-picker-input' ).wpColorPicker();

	    /**
	     * # Uploader.
	     */
	    $( document ).on( 'click', '.uk-upload-button' , function( e ) {

	        e.preventDefault();

	        var kainelabs_uploader,
	            uploader = $( this ).closest( '.uk-uploader' );

	        kainelabs_uploader = wp.media.frames.kainelabs_uploader = wp.media( {
	            title 	: 'Insert Images',
	            library : { type: 'image' },
	            button  : { text: 'Select' },
	            multiple: false
	        });

	        kainelabs_uploader.on( 'select', function() {
	            var selection = kainelabs_uploader.state().get( 'selection' );
	            selection.map( function( attachment ) {
	                attachment = attachment.toJSON();
	                uploader.find( '.uk-photo-url' ).val( attachment.url );
	                uploader.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + attachment.url + ')' );
	            });
	        });

	        kainelabs_uploader.open();

	    });

	    /**
	     * Live Photo Preview
	     */
	    $.enable_live_preview = function() {

	        $( '.uk-photo-url' ).bind( 'input change', function() {

	            // Get Data.
	            var img_url  = $( this ).val(),
	                uploader = $( this ).closest( '.uk-uploader' );

	            // If image url not working show default image
	            if ( ! $.yz_isImgExist( img_url ) ) {
	                img_url = yz.default_img;
	            }

	            // Show Live Preview
	            uploader.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + img_url + ')' );

	        });

	    }

	    // Init Function
	    $.enable_live_preview();

	    /**
	     * Check if image exist.
	     */
	    $.yz_isImgExist = function( img_src, type ) {
	        // Get Data.
	        var image = new Image();
			var type = typeof type !== 'undefined' ? type : 'photo';
	        image.src = img_src;
	        if ( image.width == 0 ) {
	            if ( type == 'banner' ) {
	                 $.ShowPanelMessage( {
	                    msg  : yz.banner_url,
	                    type : 'error'
	                } );
	            }
	            return false;
	        }
	        return true;
	    }

	    /**
	     * Reset Options With Ajax
	     */
        $( document ).on( 'click', '.yz-confirm-reset' , function( e ) {

	    	$( '.uk-popup' ).removeClass( 'is-visible' );

			e.preventDefault();

			var data, 
				reset_action = '&action=youzer_reset_settings',
				reset_elt 	 = $( this ).data( 'reset' ),
				reset_type 	 = '&reset_type=' + reset_elt;

		    // Get Data.
	        if ( reset_elt === 'tab' ) {
	        	var form_data = $( '.yz-settings-form' ).serialize();
		        data = form_data + reset_action + reset_type;
	        } else if ( reset_elt === 'all' ) {
	        	data = reset_action + reset_type;
	        }

	        // Show Loading Message
	        $( '#youzer-wait-message' ).show();

			$.post( yz.ajax_url, data, function( response ) {
				$( '#youzer-wait-message' ).hide();
	            if ( response.success ) {
	                // Show Success Message
	                $.ShowPanelMessage( { type: 'success', msg : response.data.message } );
		            location.reload();
	            } else {
	                // Show Error Message
	                $.ShowPanelMessage( { msg : yz.reset_error, type : 'error' });
	            }
			});
        });

		/**
		 * Panel Messages
		 */

		// Show/Hide Boxes
		$( '.kl-hide-box-icon' ).click( function( e ) {
	        e.preventDefault();
	        // Get Parent Box.
			var opts_box = $( this ).closest( '.uk-box-item' );
			// Display or Hide Box.
	        opts_box.find( '.uk-box-content' ).fadeToggle( 400, function(){
				// Toggle Box Message.
				opts_box.toggleClass( 'kl-hide-box' );
	        });
		});

		// Show/Hide Message
		$( '.uk-toggle-msg' ).click( function( e ) {
	        e.preventDefault();
	        // Get Parent Box.
			var msg_box = $( this ).closest( '.uk-panel-msg' );
			// Display or Hide Box.
	        msg_box.find( '.uk-msg-content' ).slideToggle( 400, function(){
				// Toggle Box Message.
				msg_box.toggleClass( 'uk-show-msg' );
				// Change Box Input Value.
				if ( msg_box.hasClass( 'uk-show-msg') ) {
					msg_box.find( 'input' ).val( 'on' );
				} else {
					msg_box.find( 'input' ).val( 'off' );
				}
	        });
		});

		// Remove Panel Message.
		$( '.uk-close-msg' ).click( function( e ) {
	        // Get Parent Box.
			var msg_box = $( this ).closest( '.uk-panel-msg' );
			// Change Box Input Value.
			msg_box.find( 'input' ).val( 'never' );
			// Remove Box.
	        $( this ).closest( '.uk-panel-msg' ).fadeOut( 600 );
		});

		/**
		 * Responsive Navbar Menu
		 */
		var kl_panel_tabs = $( '.yz-panel-menu' );

		$( '.kl-toggle-btn' ).change( function( e ) {
			$.initResponsivePanel();
		});

		$.initResponsivePanel = function () {
			if ( $( '.kl-toggle-btn' ).is( ':checked' ) ) {
				kl_panel_tabs.slideDown();
			} else {
		    	kl_panel_tabs.slideUp();
			}
		}

		$( window ).on( 'resize', function ( e ) {
			e.preventDefault();
	        if ( $( window ).width() > 768 ) {
	        	kl_panel_tabs.fadeIn( 1000 );
	        } else {
	        	$.initResponsivePanel();
	        }
		});

		// Hide Panel Menu if user choosed a tab.
		$( '.uk-sidebar a' ).click( function( e ) {
			if ( $( '.kl-toggle-btn' ).is( ':checked' ) && $( window ).width() < 769 ) {
		        // Change Menu Icon.
				$( '.kl-toggle-btn' ).attr( 'checked', false );
				// Hide Responsive Menu
				kl_panel_tabs.slideUp( 400, function() {
			        // Scroll to top.
			        $( 'html, body' ).animate( {
			            scrollTop: $( '.youzer-main-content' ).offset().top - $( '.kl-responsive-menu' ).height()
			        }, 600 );
				} );
			}
		});

		// Open Reset Tab Settings PopUp
		$( '.yz-reset-options' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#uk_popup_reset_tab' ).addClass( 'is-visible' );
		});

		// Open Reset All Settings PopUp.
		$( '#yz-reset-all-settings' ).on( 'click', function( e ) {
			e.preventDefault();
			$( '#uk_popup_reset_all' ).addClass( 'is-visible' );
		});

	});

})( jQuery );