( function( $ ) {

	'use strict';

	$( document ).ready( function() {

	/**
	 * Change Form Inputs Data
	 */
	$.yz_EditForm = function( options ) {

		// Get Options & Data.
		var o 			= $.extend( {}, options ),
			dt 			= $.yz_getDataName( o.item ), /*  Get Attribute 'Data'. */
			data 		= $.yz_getItemByType( { form: o.item, type: 'data' } ),
			close_icon 	= '<i class="fas fa-times yz-md-close-icon"></i>',
			field_value, is_true;

		// Add Data ID To The Main form.
		o.form.attr( 'data-' + dt['name'], dt['value'] );

		// Change Form Data.
		o.form.find( '.yz-md-title' ).html( o.form_title + close_icon );
		o.form.find( '.yz-md-save' ).text( Yz_Functions.save_changes );
		o.form.find( '.yz-md-save' ).attr( 'id', o.button_id );

		// Change Form Values
		o.form.find( ':input' ).not( '.uk-upload-button,:button,.ukai_tags_field' ).each( function() {

			// Get Data
			var field_name 	= $( this ).attr( 'name' ),
				field_type 	= $( this ).attr( 'type' ),
				elt 		= $( this ).prop( 'nodeName' ).toLowerCase(),
				field 	   	= $( elt + '[name='+ field_name + ']' );

			$.each( data, function( i, value ) {

				if ( ~field_name.indexOf( value['name'] ) ) {

					field_value = value['value'];

					if ( field_type === 'text' || elt === 'textarea' || field_type === 'hidden' || elt === 'select' ) {

						// Get Element Value
						if ( elt === 'input' ) {
							field.val( field_value );
						} else if ( elt === 'textarea' ) {
							field.val( decodeURIComponent( field_value ) );
						} else if ( elt === 'select' ) {
							$( 'select[name='+ field_name + '] option[value=' + field_value + ']' ).prop('selected', true);
						}

						// Live Data Preview
						$.yz_FormLivePreview( {
							selector: field.parent(),
							name 	: value['name'],
							value 	: field_value
						});

					} else if ( field_type === 'checkbox' ) {
						// Get Checkbox Value
						is_true = ( field_value === 'false' ) ? false : true;
						field.prop( 'checked', is_true );
					} else if ( field_type === 'radio' ) {
						// Get Radio Value
						$( 'input[name='+ field_name + '][value=' + field_value + ']' ).trigger( 'click' );
					}

				}
			});
		});
	}

	/**
	 * # Update Fields.
	 */
	$.yz_updateFieldsData = function( form, form_type ) {

		// Get form type.
		var form_type = typeof form_type !== 'undefined' ? form_type : null;

		// Get Data.
		var id 			= $.yz_getDataName( form ),
			form_data 	= $.yz_getNewData( form ),
			item 		= $.yz_getItemObject( form ),
			field_name  = form.find( '.yz-keys-name' ).attr( 'value' );

		// Change Input Values.
		$.each( form_data, function( i, v ) {
			// Encode Custom Widgets Text Area.
			if (
				( 'yz_ads' == field_name && 'code' == v['key'] )
				||
				( 'yz_custom_tabs' == field_name && 'content' == v['key'] )
				||
				( 'yz_custom_widgets' == field_name && 'content' == v['key'] )
			) {
				v['value'] = encodeURIComponent( v['value'] );
			}

			// Set New Data.
			$( 'input[name="' + field_name  + '[' + id['value'] + '][' + v['key'] + ']"]' ).val( v['value'] );
		});

		// Live Data Preview
		$.yz_ItemLivePreview( form );

		// Hide Modal
		$.yz_HideModal( form );

	}

	/**
	 * # Clear Form Data.
	 */
	$.yz_ResetForm = function( form, form_type ) {

		// Get form type.
		var form_type = typeof form_type !== 'undefined' ? form_type : null;

		// Get Data.
		var field_names = $.yz_getFieldsNames( form, 'namesOnly' ),
			close_icon 	= '<i class="fas fa-times yz-md-close-icon"></i>', type;

		// Change Form Data Again.
		form.find( '.yz-md-save' ).text( Yz_Functions.done );
		form.find( '.yz-md-title' ).html( form.find( '.yz-md-title' ).data( 'title' ) + close_icon );
		form.find( '.yz-md-save' ).attr( 'id', form.find( '.yz-md-save' ).data( 'add' ) );

		// Change Input Values.
		$.each( field_names, function( i, field ) {
			type = field['type'];
			if ( type === 'text' ) {
				$( 'input[name=' + field['name'] + ']' ).val( '' );
			} else if ( type === 'textarea' ) {
				$( 'textarea[name=' + field['name'] + ']' ).val( '' );
			} else if ( type === 'radio' ) {
				$( 'input:radio[name=' + field['name'] + ']:first' ).trigger( 'click' );
			} else if ( type === 'select' ) {
				$( 'select[name=' + field['name'] + '] option:first' ).prop('selected', true);
			}

		});

		// Show Banner Default Image.
		if ( form_type === 'ads' ) {
			// Reset Ads Form.
			$( 'input[name=yz_ad_is_sponsored]' ).attr( 'checked', false );
			$( 'input[name=yz_ad_type][value=banner]' ).trigger( 'click' );
			form.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + yz.default_img + ')' );
		} else if ( form_type === 'reactions' ) {
			// Reset Reactions Form.
			$( 'input[name=yz_emoji_visibility]' ).attr( 'checked', true );
			form.find( '.uk-photo-preview' ).css( 'backgroundImage', 'url(' + yz.default_img + ')' );
		} else if ( form_type === 'networks' ) {
			// Reset Networks Form.
			form.find( '.ukai-selected-icon' ).val( 'fas fa-share' );
			form.find( '.wp-color-result' ).css( { 'background-color' : '' } );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-share' );
		} else if ( form_type === 'custom-widgets' ) {
			// Reset Widgets Form.
			form.find( '.ukai-selected-icon' ).val( 'fas fa-globe-asia' );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-globe-asia' );
		} else if ( form_type === 'custom-tabs' ) {
			// Reset Custom Tabs Form.
			$( 'input[name=yz_tab_display_sidebar]' ).attr( 'checked', true );
			$( 'input[name=yz_tab_display_nonloggedin]' ).attr( 'checked', true );
			$( 'input[name=yz_tab_type][value=link]' ).trigger( 'click' );
		} else if ( form_type === 'member-types' ) {
			// Reset Member Types Form.
			form.find( '.wp-color-result' ).css( { 'background-color' : ''} );
			$( 'input[name=yz_member_type_active]' ).attr( 'checked', true );
			$( 'input[name=yz_member_type_register]' ).attr( 'checked', true );
			$( 'input[name=yz_member_type_show_in_md]' ).attr( 'checked', true );
			form.find( '.ukai-selected-icon' ).val( 'fas fa-user' );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-user' );
		} else if ( form_type === 'user-tags' ) {
			// Reset Member Types Form.
			form.find( '.ukai-selected-icon' ).val( 'fas fa-globe-asia' );
			form.find( '.ukai_icon_selector > i' ).removeClass().addClass( 'fas fa-globe-asia' );
		}

		// Check Item List
		$.yz_CheckItemsList( form_type );

	}

	/**
	 * Get Form Inputs Data
	 */
	$.yz_getNewData = function( form, type ) {

		// Get form type.
		var type = typeof type !== 'undefined' ? type : null;

		// Get Options
		var item 	= $.yz_getItemObject( form ),
			names 	= $.yz_getFieldsNames( form ),
			keys  	= $.yz_getItemByType( { form: item, type: 'keys' } ),
			data 	= [];

		// Get new Values From Form
		$.each( names, function( i, elt ) {
			$.each( keys, function( i, key ) {
				if ( elt['name'].indexOf( key ) >= 0 ) {
					if ( type === 'keyToVal' ) {
						data[ key ] = elt['value'];
					} else {
						data.push( { key: key, value: elt['value'] } );
					}
				}
			});
		});

		// Return the new data.
		return data;
	}

	$.isDataEmpty = function( form ) {

		// Declare Variables.
		var data = [],
			field_value,
			is_empty = false,
			input_names = $.yz_getFieldsNames( form );

		// Check if values are empty
		$.each( input_names, function( i, field ) {
			field_value = field['value'];
			if ( field_value == null || $.trim( field_value ) == '' ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Yz_Functions.required_fields,
                    type : 'error'
                });
                is_empty = true;
			}
		});

		if ( is_empty ) {
			return true;
		} else {
			return false;
		}
	}

	$.yz_getAddData = function( form_data, field_id ) {

		// Get Data.
		var field_name,
			data = [],
			input_names = $.yz_getFieldsNames( form_data );

		// Get Values
		$.each( input_names, function( i, field ) {
			if ( field['name'] != undefined ) {
				field_name = field['name'].replace( field_id + '_', '' );
				data[ field_name ] = field['value'];
			}
		});

		// Return Data
		return data;
	}

	/**
	 * # Get Form Input Names.
	 */
	$.yz_getFieldsNames = function( form, form_type ) {

		// Get form type.
		var form_type = typeof form_type !== 'undefined' ? form_type : null;

		// o = options.
		var input_names = [],
			temp_names 	= [],
			name, type, value;

		// Get Form Input Names.
		form.find( ':input' )
		.not( '.uk-upload-button, :button, .yz-hidden-input, .ukai_tags_field' )
		.each( function() {

			// Get Data
    		name = $( this ).attr( 'name' );
    		type = $( this ).attr( 'type' );

    		// Get Input Type
    		if ( type == undefined ) {
    			type = $( this ).prop( 'nodeName' ).toLowerCase();
    		}

    		// Get Value
    		if ( type === 'checkbox' ) {
				value = $( 'input[name=' + name + ']:checked' ).length > 0;
    		} else if ( type === 'radio' ) {
				value = $( 'input[name=' + name + ']:checked' ).val();
    		} else if ( type === 'select' ) {
    			value = $( this ).val();
    		} else if ( type === 'textarea' ) {
    			value = $( this ).val();
    		} else {
    			var field_values = $( "input[name='" + name + "']" ).map( function() {
					return $( this ).val();
				} ).get();
    			value = field_values.join( ',' );
    		}

			if ( temp_names.indexOf( name ) == -1 ) {
				temp_names.push( name );
				if ( form_type == 'keyToVal' ){
					input_names[ name ] = value;
				} else {
					input_names.push( { name :name, type: type, value: value } );
				}
			}

		});

		// Return List of Input Names.
		return input_names;

	}

	/**
	 * # Get Item Data / Keys
	 */
	$.yz_getItemByType = function( options ) {

		var opts = $.extend( {}, options ),
			data = [], keys = [],
			name, value, i, input_name, widget_name;

		opts.form.find( '> input' ).each( function() {

			// Get Data.
			name  = $( this ).attr( 'name' );
			value = $( this ).attr( 'value' );
			i 	  = 0;

			// Get Input Keys.
			name.replace( /\[.+?\]/g, function( match ) {

	    		i++;

	    		// Get Input Name.
	    		input_name = match.slice( 1, -1 );

	    		// Get widget_name.
	    		if (
	    			i == 1 &&
	    			input_name.indexOf( 'yz_cwg' ) !== -1  &&
	    			input_name.indexOf( 'fields' ) !== -1
	    		) {
	    			value = input_name;
	    		}

	    		if ( i == 2 ) {
	    			// Change Input Name if name = fields to widget
	    			if ( input_name === 'fields' ) {
	    				input_name = 'widget';
	    			}
	    			// Fill Arrays
	    			keys.push( input_name );
	    			data.push( { name: input_name, value: value } );
	    		}

	    	});

		});

		// Return Data Or Keys.
		if ( opts.type === 'keys' ) {
			return keys;
		} else {
			return data;
		}

	}

	/**
	 * Get Item Live Preview
	 */
	$.yz_FormLivePreview = function( options ) {

		// o = Options
		var o = $.extend( {}, options );
		// Live Preview.
		if ( o.name === 'banner' || o.name === 'image' ) {
			o.selector.next( '.uk-photo-preview' )
			.css( 'backgroundImage', 'url(' + o.value + ')' );
		} else if ( o.name === 'color' || o.name == 'left_color' || o.name == 'right_color' ) {
			o.selector.closest( '.wp-picker-input-wrap' ).prev( '.wp-color-result' )
			.css( { 'background-color' : o.value } );
		} else if ( o.name === 'icon' ) {
			o.selector.find( '.ukai_icon_selector > i' )
			.removeClass().addClass( o.value );
			o.selector.find( '.ukai-selected-icon' ).val( o.value );
		}

	}

	/**
	 * Items Live Preview.
	 */
	$.yz_ItemLivePreview = function( form ) {

		// Set Up Variables.
		var data = $.yz_getNewData( form, 'keyToVal' ),
			item = $.yz_getItemObject( form ),
			form_type = form.attr( 'id' );

		// Live Preview.
		if ( form_type === 'yz-ads-form' ) {

			item.find( '.yz-ad-title' ).text( data['title'] );

			if ( data['type'] === 'adsense' ) {
				item.find( '.yz-ad-img' ).attr( 'style', '' );
				item.find( '.yz-ad-img i' ).show();
			} else {
				item.find( '.yz-ad-img' ).css( 'backgroundImage', 'url(' + data['banner'] + ')' );
				item.find( '.yz-ad-img i' ).hide();
			}

		} else if ( form_type === 'yz-reactions-form' ) {

			item.find( '.yz-emoji-title' ).text( data['title'] );
			item.find( '.yz-emoji-img' ).css( 'backgroundImage', 'url(' + data['image'] + ')' );

		} else if ( form_type === 'yz-networks-form' || form_type === 'yz-custom-widgets-form' ) {
			if ( form_type === 'yz-networks-form' ) {
				item.find( 'h2' ).css( { 'border-color' : data['color'] } );
			}
			item.find( 'h2 span' ).text( data['name'] );
			item.find( 'h2 i' ).removeClass().addClass( 'fab yz-network-icon ' + data['icon'] );
		} else if ( form_type === 'yz-custom-tabs-form' ) {
			item.find( 'h2 span' ).text( data['title'] );
		} else if ( form_type === 'yz-member-types-form' ) {
			item.find( 'h2 span' ).text( data['name'] );
			item.find( 'h2 i' ).removeClass().addClass( 'fab yz-member-type-icon ' + data['icon'] );
		} else if ( form_type === 'yz-user-tags-form' ) {
			item.find( 'h2 span' ).text( data['name'] );
			item.find( 'h2 i' ).removeClass().addClass( 'fab yz-user-tag-icon ' + data['icon'] );
		}

	}

	/**
	 * # Get Data Name
	 */
	$.yz_getDataName =  function( form ) {
		var data = [];
		$.each( form.data(), function( i, val ) {
			if ( i != "sortableItem" ) {
				data['name']  = i.replace(/([A-Z])/g, '-$1' ).trim().toLowerCase();
				data['value'] = form.attr( 'data-' + data['name'] );
			}
		});
		return data;
	}

	/**
	 * # Get Item Element.
	 */
	$.yz_getItemObject = function( form ) {
		var data = $.yz_getDataName( form ),
			item = $( 'li[data-' + data['name'] + '=' + data['value'] + ']' );
		return item;
	}

	/**
	 * # Check for Widget Existence.
	 */
	$.yz_CheckItemsList = function( item ) {

		// Check Ads List
		if ( item === 'ads' ) {
			if ( $( '.yz-ad-item' )[0] ) {
				$( '.yz-no-ads' ).remove();
			}
		}

		// Check Reactions List
		if ( item === 'reactions' ) {
			if ( $( '.yz-emoji-item' )[0] ) {
				$( '.yz-no-emojis' ).remove();
			}
		}

		// Check Networks List
		if ( item === 'networks' ) {
			if ( $( '.yz-network-item' )[0] ) {
				$( '.yz-no-networks' ).remove();
			}
		}

		// Check Networks List
		if ( item === 'custom-widgets' ) {
			if ( $( '.yz-custom-widget-item' )[0] ) {
				$( '.yz-no-custom-widgets' ).remove();
			}
		}

		// Check Networks List
		if ( item === 'custom-tabs' ) {
			if ( $( '.yz-custom-tab-item' )[0] ) {
				$( '.yz-no-custom-tabs' ).remove();
			}
		}

		// Check Member Types List
		if ( item === 'member-types' ) {
			if ( $( '.yz-member-type-item' )[0] ) {
				$( '.yz-no-member-types' ).remove();
			}
		}

		// Check User Tags List
		if ( item === 'user-tags' ) {
			if ( $( '.yz-user-tag-item' )[0] ) {
				$( '.yz-no-user-tags' ).remove();
			}
		}

	}

	/**
	 * # Show or Hide Options Field
	 */
	$.yz_CheckFieldOptions = function() {

		$( '#yz_field_type' ).on( 'change', function() {
			// Get Data.
			var field_type = $( this ).val(),
				options    = $( '.yz-field-options' );
			// Display / Hide Options
			if ( field_type === 'text' || field_type === 'number' || field_type === 'textarea' ) {
				options.fadeOut();
			} else {
				options.fadeIn();
			}
		});

	}

	/**
	 * # Prevent Submitting form by Hitting Enter.
	 */
	$( 'input' ).keypress( function( e ) {
		var keyCode = e.keyCode || e.which;
		  if ( keyCode === 13 ) {
		    e.preventDefault();
		    return false;
		 }
	});

	/**
	 * # Make Widgets Draggable
	 */
	$( '#yz_widgets, #yz_networks, #yz_custom_tabs, #yz_user_tags, #yz_member_types, #yz_reactions' ).sortable({
		placeholder: "dashed-placeholder"
	});

	/**
	 * # Make Fields Draggable
	 */
	$.MakeItemsSortable = function() {
		$( '.yz-fields-content' ).sortable( {
			placeholder: 'ui-state-highlight',
			connectWith: '.yz-fields-content',
			receive : function( event, ui ) {
				var widgte_data  = $( this ).parent().data( 'widgetName' ),
					field_id 	 = ui.item.context.attributes[1].nodeValue,
					widgte_class = $( this ).parent();

				widgte_class.find( "input[value='" + field_id + "']" ).remove();
				widgte_class.find( '.yz-field-item' ).append(
					'<input type="hidden" name="yz_widgets['+ widgte_data +'][fields][]" value="'+ field_id +'">'
				)
	        }
		});
	}

	$.MakeItemsSortable();

	/**
	 * # Modal.
	 */
	$( document ).on( 'click', '.yz-md-trigger' , function( e ) {

		e.preventDefault();

		// Get Button
		var button_id = '#' + $( this ).data( 'modal' );

	    // Display Modal
		$( '.yz-md-overlay' ).fadeIn( 500, function() {
			$( button_id ).addClass( 'yz-md-show' );
		});

	});

	/**
	 * # Hide Modal if user clicked Close Button or Icon
	 */
	$( document ).on( 'click', '.yz-md-close, .yz-md-close-icon' , function( e ) {

		e.preventDefault();

		// Get Data.
		var modal = $( this ).closest( '.yz-md-modal' );
		$.yz_HideModal( modal );

	});

	// Hide Modal If User Clicked Escape Button
	$( document ).keyup( function( e ) {
		if ( $( '.yz-md-show' )[0] ) {
		    if ( e.keyCode === 27 ) {
			    $( '.yz-md-close' ).trigger( 'click' );
		    }
		}
		return false;
	});

	// # Hide Modal if User Clicked Outside
	$( document ).mouseup( function( e ) {
	    if ( $( '.yz-md-overlay' ).is( e.target ) && $( '.yz-md-show' )[0] ) {
			$( '.yz-md-close' ).trigger( 'click' );
	    }
	    return false;
	});

	$.yz_HideModal = function( form ) {

		// Get Form ID.
		var form_id = form.attr( 'id' );

		// Hide Form.
		$( '.yz-md-modal' ).removeClass( 'yz-md-show' );
        $( '.yz-md-overlay' ).fadeOut( 600, function() {

        	// Reset Form.
	        if ( form_id === 'yz-networks-form' ) {
				$.yz_ResetForm( form, 'networks' );
			} else if ( form_id === 'yz-ads-form' ) {
				$.yz_ResetForm( form, 'ads' );
			}  else if ( form_id === 'yz-reactions-form' ) {
				$.yz_ResetForm( form, 'reactions' );
			} else if ( form_id === 'yz-custom-widgets-form' ) {
				$.yz_ResetForm( form, 'custom-widgets' );
			} else if ( form_id === 'yz-custom-tabs-form' ) {
				$.yz_ResetForm( form, 'custom-tabs' );
			} else if ( form_id === 'yz-member-types-form' ) {
				$.yz_ResetForm( form, 'member-types' );
			} else if ( form_id === 'yz-user-tags-form' ) {
				$.yz_ResetForm( form, 'user-tags' );
			}

        });

	}

	/**
	 * # Display Edit Modal
	 */
	$( document ).on( 'click', '.yz-edit-item', function() {

		var modal;

		if ( $( this ).hasClass( 'yz-edit-field' ) ) {
			modal = $( 'button[data-modal=yz-fields-form]' );
		} else if ( $( this ).hasClass( 'yz-edit-widget' ) ) {
			modal = $( 'button[data-modal=yz-widgets-form]' );
		} else {
			modal = $( this ).closest( 'ul' ).prev( '.yz-custom-section' ).find( '.yz-md-trigger' );
		}

		// Display Modal
		modal.trigger( 'click' );
	});

	/**
	 * # Live Scheme Preview
	 */
	$( document ).on( 'click', '.uk-panel-scheme .imgSelect label' , function( e ) {
		var panel_scheme = $( this ).prev().val();
		$( '#ukai-panel' ).removeClass().addClass( 'ukai-panel' ).addClass( panel_scheme );
	});

	});

})( jQuery );