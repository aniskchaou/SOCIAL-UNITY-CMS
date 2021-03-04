( function( $ ) {

    'use strict';

    /**
     * KAINELABS Form Builder
     */

	 $.ukai_form_input = function( options ) {

        // Default options
        var input_label, open_option, field_class, input, input_min, input_max,
            close_option, placeholder, input_name, input_desc,
            s = $.extend( {
                options_name : 'ukai_options',
                icons_type   : 'web-application',
                input_type   : 'text',
                input_desc   : false,
                input_id     : false,
                option_item  : false,
                inner_option : false,
                option_only  : false,
                label_title  : true,
                show_label   : true,
                show_ph      : false, // Show Place Holder
                cell         : false,
                class        : null
            }, options );

        input_desc    = s.input_desc ? '<p class="option-desc">' + s.input_desc + '</p>' : '';
        input_min     = s.input_min ? 'min="' + s.input_min + '"' : '';
        input_max     = s.input_max ? 'max="' + s.input_max + '"' : '';
        input_label   = s.show_label ? '<div class="option-infos"><label>' + s.label_title + '</label>' +
                        input_desc + '</div>' : '';
        input_name    = s.options_name + '[' + s.cell + '][' + s.option_item + ']';
        open_option   = '<div class="uk-option-item">'+ input_label + '<div class="option-content">';
        close_option  = '</div></div>';

        // Get PlaceHolder value.
        if ( s.show_ph )  {
            placeholder = 'placeholder = "' + s.label_title + '"';
        } else {
            placeholder = null;
		}

        field_class = ( s.class ) ? 'class="' + s.class + '"' : '';

		// Get input content
		switch( s.input_type ) {

            case 'text':
                input = '<input type="text" name="' + input_name + '" ' + field_class + placeholder + '>';
                break;

            case 'hidden':
                input = '<input type="hidden" name="' + input_name + '" ' + field_class + '>';
                break;

            case 'number':
                input = '<input type="number" ' + input_min + ' ' + input_max + 'name="' + input_name + '" ' + field_class + placeholder + '>';
                break;

            case 'button':
                input = '<input type="button" ' + field_class + ' value="' + s.label_title + '">';
                break;

		    case 'image':
                input = '<div class="yz-uploader-item">';
                input += '<div class="yz-photo-preview" style="background-image: url(' + Yz_Account.default_img + ');"></div>';
                input += '<label for="' + s.input_id + '" class="yz-upload-photo">' + s.label_title + '</label>';
                input += '<input id="' + s.input_id + '" type="file" name="' + s.input_id + '" class="yz_upload_file" accept="image/*" />';
                input += '<input type="hidden" name="' + s.options_name + '[' + s.cell + '][image]' + '" ' + ' class="yz-photo-url"' + '>';
                input += '</div>';
                break;

		    case 'textarea':
			    input = '<textarea name="' + input_name + '" '+ placeholder + '></textarea>';
		        break;

		    case 'icon':
		    	input = '<div class="ukai_iconPicker" data-icons-type="' + s.icons_type + '">'+
                            '<div class="ukai_icon_selector">'+
                                '<i class="fas fa-globe-americas"></i>'+
                                '<span class="ukai_select_icon"><i class="fas fa-sort-down"></i></span>'+
                            '</div>'+
                            '<input type="hidden" class="ukai-selected-icon" name="'+ input_name +'">'+
                        '</div>';
		    	break;
            case 'color':
            input = '<div class="ukai-colorSelector">'+
                    '<div class="yz-picker-bg"></div>'
                    +'<input type="text" class="yz-picker-input" name="'+ input_name +'" >'
                    +'</div>';
		}

        if ( s.input_type == 'hidden' ) {
            return input;
        }

        if ( s.option_only ) {
            return '<div class="uk-option-item">' + input + '</div>';
        }

        if ( s.inner_option ) {
            var inner_option = '<div class="uk-option-item"><div class="yz-option-inner">' +
             input_label + '<div class="option-content">' + input + '</div></div></div>';
             return inner_option;
        }

 		return open_option + input + close_option;

    };

    // Make items Draggable
    var yz_enable_dragging = ( Youzer_Builder.drag_widgets_items == '1' ) ? true : false;
    if ( yz_enable_dragging == true ) {
        $( '.yz-wg-opts' ).sortable();
    }

} )( jQuery );