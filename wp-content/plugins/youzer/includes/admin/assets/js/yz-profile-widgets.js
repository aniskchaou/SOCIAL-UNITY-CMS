( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New widget.
		 */
		$( document ).on( 'click', '#yz-add-custom-widget' , function( e ) {

			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.yz-custom-widget-name span' ),
				widgets_form  = $( '#yz-custom-widgets-form' ),
				fieldName	  = 'yz_custom_widgets[yz_custom_widget_' + yz_nextCustomWidget + ']',
				widget 	  	  = $.yz_getAddData( widgets_form, 'yz_widget' ),
				widget_args   = {
					value	: widget['name'],
					form 	: widgets_form,
					selector: name_selector,
					type	: 'text'
				};

			// Validate widget Data
			if ( ! $.validate_widgets_data( widget_args ) ) {
				return false;
			}

			// Add widget item
			$( '#yz_custom_widgets' ).prepend(
				'<li class="yz-custom-widget-item" data-widget-name="yz_custom_widget_'+ yz_nextCustomWidget +'">'+
				'<h2 class="yz-custom-widget-name">'+
				'<i class="yz-custom-widget-icon '+ widget['icon'] +'"></i>'+
				'<span>' + widget['name'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[icon]" value="' + widget['icon'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[name]" value="' + widget['name'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[content]" value="' + encodeURIComponent( widget['content'] ) + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_title]" value="' + widget['display_title'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_padding]" value="' + widget['display_padding'] + '" >'+
				'<a class="yz-edit-item yz-edit-custom-widget"></a>'+
				'<a class="yz-delete-item yz-delete-custom-widget"></a>'+
				'</li>'
			);

			// Hide Modal
			$.yz_HideModal( widgets_form );

			// Increase Social widget Number
			yz_nextCustomWidget++;

		});

		/**
		 * Edit widget.
		 */
		$( document ).on( 'click', '.yz-edit-custom-widget' , function( e )	{

			// Get Data.
			var widget_item  = $( this ).closest( '.yz-custom-widget-item' ),
				widgets_form = $( '#yz-custom-widgets-form' );

			// Get Form Values
			$.yz_EditForm( {
				button_id	: 'yz-update-custom-widget',
				form_title	: Yz_Custom_Widgets.update_widget,
				form 		: widgets_form,
				item 		: widget_item
			});

		});

		/**
		 * Save widget.
		 */
		$( document ).on( 'click', '#yz-update-custom-widget' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var widget_name 	= '.yz-custom-widget-name span',
				widgets_form 	= $( '#yz-custom-widgets-form' ),
				widget_item 	= $.yz_getItemObject( widgets_form ),
				widget			= $.yz_getNewData( widgets_form, 'keyToVal' ),
				widgets_args	= {
					old_title 	: widget_item.find( widget_name ).text(),
					value		: widget['name'],
					form 		: widgets_form,
					selector 	: $( widget_name ),
					type		: 'text'
				};

			// Validate widget Data
			if ( ! $.validate_widgets_data( widgets_args ) ) {
				return false;
			}

			// Update Data
			$.yz_updateFieldsData( widgets_form );

		});

		/**
		 * Validate widget Data.
		 */
		$.validate_widgets_data = function( options ) {

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
		$( document ).on( 'click', '.yz-delete-custom-widget', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.yz-custom-widget-item' )[0] ) {
				$( '#yz_custom_widgets' ) .append( '<p class="yz-no-content yz-no-custom-widgets">' + Yz_Custom_Widgets.no_custom_widgets + '</p>' );
			}

		});

	});

})( jQuery );