( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New Tag.
		 */
		$( document ).on( 'click', '#yz-add-user-tag' , function( e ) {


			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.yz-user-tag-name span' ),
				user_tags_form = $( '#yz-user-tags-form' ),
				fieldName	  = 'yz_user_tags[yz_user_tag_' + yz_nextUTag + ']',
				tag 	  	  = $.yz_getAddData( user_tags_form, 'yz_user_tag' ),
				user_tag_args = {
					type	 : 'text',
					value	 : tag['name'],
					tag_name : tag['name'],
					form 	 : user_tags_form,
					selector : name_selector,
				};

			// Validate Data.
			if ( ! $.validate_user_tags_data( user_tag_args ) ) {
				return false;
			}

			// Add Item.
			$( '#yz_user_tags' ).prepend(
				'<li class="yz-user-tag-item" data-user-tag-name="yz_user_tag_'+ yz_nextUTag +'">'+
				'<h2 class="yz-user-tag-name">'+
				'<i class="fa yz-user-tag-icon '+ tag['icon'] +'"></i>'+
				'<span>' + tag['name'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[icon]" value="' + tag['icon'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[name]" value="' + tag['name'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[field]" value="' + tag['field'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[description]" value="' + tag['description'] + '" >'+
				'<a class="yz-edit-item yz-edit-user-tag"></a>' +
				'<a class="yz-delete-item yz-delete-user-tag"></a>' +
				'</li>'
			);

			// Hide Modal
			$.yz_HideModal( user_tags_form );

			// Increase ID Number
			yz_nextUTag++;

		});

		/**
		 * Edit User Tag.
		 */
		$( document ).on( 'click', '.yz-edit-user-tag' , function( e )	{

			// Get Data.
			var user_tag_item  = $( this ).closest( '.yz-user-tag-item' ),
				user_tags_form = $( '#yz-user-tags-form' );

			// Get Form Values
			$.yz_EditForm( {
				button_id	: 'yz-update-user-tag',
				form_title	: Yz_User_Tags.update_user_tag,
				form 		: user_tags_form,
				item 		: user_tag_item
			});

		});

		/**
		 * Save User Tag.
		 */
		$( document ).on( 'click', '#yz-update-user-tag' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var tag_name = '.yz-user-tag-name span',
				user_tags_form = $( '#yz-user-tags-form' ),
				user_tag_item  = $.yz_getItemObject( user_tags_form ),
				tag = $.yz_getNewData( user_tags_form, 'keyToVal' ),
				user_tag_args = {
					old_name 	: user_tag_item.find( tag_name ).text(),
					value		: tag['name'],
					form 		: user_tags_form,
					selector 	: $( tag_name ),
					type		: 'text',
					tag_icon   : tag['icon'],
					tag_name   : tag['name'],
					tag_field  : tag['field'],
					tag_description : tag['description'],
				};
				console.log( user_tag_args );

			// Validate Tab Data.
			if ( ! $.validate_user_tags_data( user_tag_args ) ) {
				return false;
			}

			// Update Data.
			$.yz_updateFieldsData( user_tags_form );

		});

		/**
		 * Validate User Tag Data.
		 */
		$.validate_user_tags_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			if ( o.tag_name == null || $.trim( o.tag_name ) == '' ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Yz_User_Tags.utag_name_empty,
                    type : 'error'
                } );
                return false;
			}

			// Check if type Exist or not
			var nameAlreadyeExist = $.yz_isAlreadyExist( {
				old_title 	: o.old_name,
				selector 	: o.selector,
				value		: o.value,
				type		: 'text'
			});

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
		$( document ).on( 'click', '.yz-delete-user-tag', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.yz-user-tag-item' )[0] ) {
				$( '#yz_user_tags' ).append( '<p class="yz-no-content yz-no-user-tags">' + Yz_User_Tags.no_user_tags + '</p>' );
			}

		});

	});

})( jQuery );