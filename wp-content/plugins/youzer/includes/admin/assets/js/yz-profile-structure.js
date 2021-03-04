( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * #  Move Profile Widgets
		 */
		$( '.yz-draggable-area' ).sortable( {
			connectWith: '.yz-draggable-area',
			receive : function( event, ui ) {
				// Block Moving Unsortable Items to The Other Side
				// if ( ui.item.hasClass( 'yz_unsortable' ) )  {
				// 	ui.sender.sortable( 'cancel' );
				// 	// Show Error Message
				// 	$.ShowPanelMessage( {
				// 		msg  : Yz_Profile_Structure.move_wg,
				// 		type : 'error'
				// 	});
				// 	return false;
				// }

				// Get Widget Data
				var wg_type 	 = $( this ).data( 'widgetsType'),
					wg_name 	 = ui.item.data( 'widgetName' ),
					wg_name_attr = 'yz_profile_' + wg_type + '[' + wg_name +  ']';

				// Change widget name.
				ui.item.find( '.yz_profile_widget' ).attr( 'name', wg_name_attr );
		    }

		} );

		/**
		 * #  Hide Profile Widgets
		 */
		$( document ).on( 'click', '.yz-hide-wg', function() {
			var widget = $( this ).closest( 'li' );
			widget.toggleClass( 'yz-hidden-wg' );
			// Change Input Value
			if ( widget.hasClass( 'yz-hidden-wg' ) ) {
				widget.find( '.yz_profile_widget' ).val( 'invisible' );
				widget.find( '.yz-hide-wg' ).attr( 'title', Yz_Profile_Structure.show_wg );
			} else {
				widget.find( '.yz_profile_widget' ).val( 'visible' );
				widget.find( '.yz-hide-wg' ).attr( 'title', Yz_Profile_Structure.hide_wg );
			}
		});

		/**
		 * Show Main Sidebar.
		 */
		$( document ).on( 'change', 'input[name="youzer_options[yz_profile_layout]"]', function() {

			$( '.yz-profile-structure' ).attr( 'data-layout', $( this ).val() );

			var box = $( '.yz-profile-main-sidebar' );

	        if ( $( this ).val() == 'yz-3columns' ) {
	        	box.fadeIn();
	        } else {
	        	box.fadeOut();
        	}

    	});

		$( 'input[name="youzer_options[yz_profile_layout]"]:checked').trigger( 'change' );

	});

})( jQuery );