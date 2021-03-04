( function( $ ) {

  	'use strict';

  	/**
  	 * KAINELABS Tag Editor
  	 */

 	$( document ).ready( function() {

	    $.ukai_tag_editor = function() {

			$.expr[":"].contains = $.expr.createPseudo( function( arg ) {
				return function( el ) {
					return $( el ).text().toUpperCase().indexOf( arg.toUpperCase() ) >= 0;
				};
			});

	 		var ukai_current_div;

	 		// Remove Tag item
	 		$( document ).on( 'click','.ukai-tagRemove', function( e ) {
				e.preventDefault();
				$( this ).parent().remove();
			});

			// Put Cursor inside the Input Field
			$( 'ul.ukai_tags' ).click( function() {
				$( this ).find( '.ukai_tags_field' ).focus();
				ukai_current_div = $( this );
			});

			$( '.ukai_tags_field' ).keypress( function( e ) {
		   // $( document ).on( 'keypress', '.ukai_tags_field', function(e) {

   				var keycode = (e.keyCode ? e.keyCode : e.which);

				// If user Clicks Enter
				if ( keycode == 13 ) {
					var tag 		 = $( this ).val(),
						tags_form 	 = $( this ).closest( '.ukai_tags' ),
						field_name	 = tags_form.data( 'optionName' ),
						current_elts = $.ukai_check_tag_existence( tags_form, tag );

					if ( $.trim( tag ) != '' && current_elts.length == 0 ) {

						// Add item in Case Isn't Already Exist
						$( '<li class="addedTag">' + tag +
							'<span class="ukai-tagRemove">x</span>'+
							'<input type="hidden" value="' + tag + '" name="' + field_name +'">'+
							'</li>'
						).insertAfter( ukai_current_div.find( '.tagAdd' ) );

						// Clear Input
						$( this ).val( '' );

					} else {
						
						// Add Flash Element In Case is Already Exist.
						current_elts.addClass( 'flash' );
						setTimeout( function () { current_elts.removeClass( 'flash' ); }, 1000);
						$( this ).val( '' );

					}

					e.preventDefault();

				}
			});
		}

		// Check if tag already exist.
		$.ukai_check_tag_existence = function( elt, tag ) {

			// Setup Variables.
			var tag_obj = $();

			// Check Old Tags.
			elt.find( '.addedTag' ).each( function() {
	            var str = $.trim( $( this ).text().replace( 'x', '' ) );
                if ( str.toLowerCase() === $.trim( tag ).toLowerCase() ) {
					tag_obj = $( this );
					return false;
                }
			});

			// Return Result.
			return tag_obj;
		}

		// Call Tag Editor Function
		$.ukai_tag_editor();

	});

})( jQuery );