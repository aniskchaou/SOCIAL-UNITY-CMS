( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New Network.
		 */
		$( document ).on( 'click', '#yz-add-network' , function( e ) {

			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.yz-network-name span' ),
				networks_form = $( '#yz-networks-form' ),
				fieldName	  = 'yz_networks[yz_sn_' + yz_nextSN + ']',
				network 	  = $.yz_getAddData( networks_form, 'yz_network' ),
				network_args  = {
					value	: network['name'],
					form 	: networks_form,
					selector: name_selector,
					type	: 'text'
				};

			// Validate Network Data
			if ( ! $.validate_networks_data( network_args ) ) {
				return false;
			}

			// Add Network item
			$( '#yz_networks' ).prepend(
				'<li class="yz-network-item" data-network-name="yz_sn_'+ yz_nextSN +'">'+
				'<h2 class="yz-network-name" style="border-color:' + network['color'] + ';">'+
				'<i class="yz-network-icon '+ network['icon'] +'"></i>'+
				'<span>' + network['name'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[name]" value="' + network['name'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[icon]" value="' + network['icon'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[color]" value="' + network['color'] + '" >'+
				'<a class="yz-edit-item yz-edit-network"></a>'+
				'<a class="yz-delete-item yz-delete-network"></a>'+
				'</li>'
			);

			// Hide Modal
			$.yz_HideModal( networks_form );

			// Increase Social Network Number
			yz_nextSN++;

		});

		/**
		 * Edit Network.
		 */
		$( document ).on( 'click', '.yz-edit-network' , function( e )	{

			// Get Data.
			var network_item  = $( this ).closest( '.yz-network-item' ),
				networks_form = $( '#yz-networks-form' );

			// Get Form Values
			$.yz_EditForm( {
				button_id	: 'yz-update-network',
				form_title	: Yz_Networks.update_network,
				form 		: networks_form,
				item 		: network_item
			});

		});

		/**
		 * Save Network.
		 */
		$( document ).on( 'click', '#yz-update-network' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var network_name 	= '.yz-network-name span',
				networks_form 	= $( '#yz-networks-form' ),
				network_item 	= $.yz_getItemObject( networks_form ),
				network			= $.yz_getNewData( networks_form, 'keyToVal' ),
				networks_args	= {
					old_title 	: network_item.find( network_name ).text(),
					value		: network['name'],
					form 		: networks_form,
					selector 	: $( network_name ),
					type		: 'text'
				};

			// Validate Network Data
			if ( ! $.validate_networks_data( networks_args ) ) {
				return false;
			}

			// Update Data
			$.yz_updateFieldsData( networks_form );

		});

		/**
		 * Validate Network Data.
		 */
		$.validate_networks_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			// Check if Data is Empty.
			if ( $.isDataEmpty( o.form ) ) {
				return false;
			}

			// Check if widget Exist or not
			var nameAlreadyeExist = $.yz_isAlreadyExist( {
				old_title 	: o.old_title,
				selector 	: o.selector,
				value		: o.value,
				type		: 'text'
			} );

			if ( nameAlreadyeExist ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Yz_Functions.name_exist,
                    type : 'error'
                });
                return false;
			}

			return true;
		}
		
		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.yz-delete-network', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.yz-network-item' )[0] ) {
				$( '#yz_networks' )
				.append( '<p class="yz-no-content yz-no-networks">' + Yz_Networks.no_networks + '</p>' );
			}

		});

	});

})( jQuery );