( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		/**
		 * Add New tab.
		 */
		$( document ).on( 'click', '#yz-add-custom-tab' , function( e ) {

			e.preventDefault();

			// Get Data.
			var	name_selector = $( '.yz-custom-tab-name span' ),
				tabs_form  = $( this ).closest( 'form' ).find( '#yz-custom-tabs-form' ),
				fieldName	  = 'yz_custom_tabs[yz_custom_tab_' + yz_nextTab + ']',
				tab 	  	  = $.yz_getAddData( tabs_form, 'yz_tab' ),
				tab_args   = {
					slug	: tab['slug'],
					value	: tab['title'],
					form 	: tabs_form,
					selector: name_selector,
					type	: 'text',
					tab_link  : tab['link'],
					tab_type  : tab['type'],
					tab_title : tab['title'],
					tab_content : tab['content'],
				};

			// Validate Tab Data
			if ( ! $.validate_tabs_data( tab_args ) ) {
				return false;
			}

			// Add widget item
			$( '#yz_custom_tabs' ).prepend(
				'<li class="yz-custom-tab-item" data-tab-name="yz_custom_tab_'+ yz_nextTab +'">'+
				'<h2 class="yz-custom-tab-name">'+
				'<i class="yz-custom-tab-icon '+ tab['icon'] +'"></i>'+
				'<span>' + tab['title'] + '</span>'+
				'</h2>' +
				'<input type="hidden" name="' + fieldName +'[slug]" value="' + tab['slug'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[link]" value="' + tab['link'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[type]" value="' + tab['type'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[title]" value="' + tab['title'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[content]" value="' + encodeURIComponent( tab['content'] ) + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_sidebar]" value="' + tab['display_sidebar'] + '" >'+
				'<input type="hidden" name="' + fieldName +'[display_nonloggedin]" value="' + tab['display_nonloggedin'] + '" >'+
				'<a class="yz-edit-item yz-edit-custom-tab"></a>'+
				'<a class="yz-delete-item yz-delete-custom-tab"></a>'+
				'</li>'
			);

			// Hide Modal
			$.yz_HideModal( tabs_form );

			// Increase ID Number
			yz_nextTab++;

		});

		/**
		 * Edit Tab.
		 */
		$( document ).on( 'click', '.yz-edit-custom-tab' , function( e )	{

			// Get Data.
			var tab_item  = $( this ).closest( '.yz-custom-tab-item' ),
				tabs_form = $( '#yz-custom-tabs-form' );

			// Get Form Values
			$.yz_EditForm( {
				button_id	: 'yz-update-custom-tab',
				form_title	: Yz_Custom_Tabs.update_tab,
				form 		: tabs_form,
				item 		: tab_item
			});

		});

		/**
		 * Save Tab.
		 */
		$( document ).on( 'click', '#yz-update-custom-tab' , function( e )	{

			e.preventDefault();

			// Set Up Variables.
			var tab_name = '.yz-custom-tab-name span',
				tabs_form 	= $( '#yz-custom-tabs-form' ),
				tab_item 	= $.yz_getItemObject( tabs_form ),
				tab			= $.yz_getNewData( tabs_form, 'keyToVal' ),
				tabs_args	= {
					old_title 	: tab_item.find( tab_name ).text(),
					slug		: tab['slug'],
					value		: tab['title'],
					form 		: tabs_form,
					selector 	: $( tab_name ),
					type		: 'text',
					tab_link    : tab['link'],
					tab_type    : tab['type'],
					tab_title   : tab['title'],
					tab_content : tab['content'],
				};

			// Validate Tab Data.
			if ( ! $.validate_tabs_data( tabs_args ) ) {
				return false;
			}

			// Update Data.
			$.yz_updateFieldsData( tabs_form );

		});

		/**
		 * Validate widget Data.
		 */
		$.validate_tabs_data = function( options ) {

			// O = Options
			var o = $.extend( {}, options );

			if ( o.tab_title == null || $.trim( o.tab_title ) == '') {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Yz_Custom_Tabs.tab_title_empty,
                    type : 'error'
                } );
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

			// Validate Banner Process.
			if ( o.tab_type == 'link' ) {

				if ( o.tab_link == null || $.trim( o.tab_link ) == '' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : Yz_Custom_Tabs.tab_url_empty,
						type : 'error'
					} );
					return false;
				}

			} else if ( o.tab_type == 'shortcode' ) {
				if ( o.tab_content == null || $.trim( o.tab_content ) == '' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : Yz_Custom_Tabs.tab_code_empty,
						type : 'error'
					} );
					return false;
				}
			}


			return true;
		}

		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.yz-delete-custom-tab', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.yz-custom-tab-item' )[0] ) {
				$( '#yz_custom_tabs' )
				.append( '<p class="yz-no-content yz-no-custom-tabs">' + Yz_Custom_Tabs.no_custom_tabs + '</p>' );
			}

		});

		/**
		 * Get Fields by Tab type .
		 */
		$( document ).on( 'change', 'input[name=yz_tab_type]', function() {

			var code = '.yz-custom-tabs-shortcode-items',
				link = '.yz-custom-tabs-link-item',
				form = $( this ).closest( '.yz-custom-tabs-form' );

	        if ( this.value == 'shortcode' ) {
	        	form.find( link ).fadeToggle( 400, function() {
	        		form.find( code ).fadeToggle( 400);
	        	} );
	        } else {
	        	form.find( code ).fadeToggle( 400, function() {
	        		form.find( link ).fadeToggle( 400);
	        	} );
        	}

    	});
	});

})( jQuery );