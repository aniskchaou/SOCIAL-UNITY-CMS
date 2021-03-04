"use strict";

jQuery( document ).ready( function ( $ ) {

    if ( typeof fwEvents !== 'undefined' ) {
        
        //Rename demo user
        fwEvents.on('fw:ext:backups-demo:status:update', function (data) {
            if (data.active_demo.result) {
                if (data.active_demo.result === true) {
                    jQuery.ajax({
                        url: TheCore.ajaxUrl,
                        type: 'POST',
                        data: {
                            action: 'olympus_rename_demo_user',
                        },
                    });
                }
            }
        });
        
        // advanced styling button
        fwEvents.on( 'fw:options:init', function ( data ) {
            // for table (on change table type)
            var table_type = jQuery( '#fw-edit-options-modal-table-header-optionstable_purpose' );
            if ( table_type.length > 0 ) {
                fw_hide_table_styling_options( table_type.val() );
                table_type.on( 'change', function () {
                    fw_hide_table_styling_options( table_type.val() );
                } );
            }

            // for calendar (on change calendar type)
            var calendar_type = jQuery( '.calendar-styling#fw-edit-options-modal-template' );
            if ( calendar_type.length > 0 ) {
                fw_hide_calendar_styling_options( calendar_type.val() );
                calendar_type.on( 'change', function () {
                    fw_hide_calendar_styling_options( calendar_type.val() );
                } );
            }

            // for testimonials style
            var testimonials_type = jQuery( '.fw-testimonials-type' );
            if ( testimonials_type.length > 0 ) {
                var src = testimonials_type.find( '.thumbnail.selected img' ).attr( 'src' );
                fw_hide_testimonials_advanced_options( src );
                testimonials_type.on( 'change', function () {
                    var src = testimonials_type.find( '.thumbnail.selected img' ).attr( 'src' );
                    fw_hide_testimonials_advanced_options( src );
                } );
            }

            // section overlap
            var section_overlap = jQuery( '.fw-section-overlap' );
            if ( section_overlap.length > 0 ) {
                var section_overlap_checked = section_overlap.find( 'input[type="radio"]:checked' ).val();
                fw_hide_section_position( section_overlap_checked, section_overlap );
                /* this simulate the change the checked radio item */
                section_overlap.find( 'input' ).on( 'click', function () {
                    var section_overlap_checked = section_overlap.find( 'input[type="radio"]:checked' ).val();
                    fw_hide_section_position( section_overlap_checked, section_overlap );
                } );
            }

            // hide options for button style 4
            fw_hide_button_options();
            jQuery( '.fw-button-style-type select' ).on( 'change', function () {
                fw_hide_button_options();
            } );

            if ( jQuery( '#fw-backend-option-fw-option-posts_settings-blog_view' ).length ) {
                fw_show_hide_post_grid_view();
                jQuery( '#fw-backend-option-fw-option-posts_settings-blog_view input[type="checkbox"]' ).on( 'change', function () {
                    fw_show_hide_post_grid_view();
                } );
            }

            // for advanced button styling
            data.$elements.find( '.fw-option-type-popup[data-advanced-for]:not(.advanced-initialized)' ).each( function () {
                var $optionWithAdvanced = data.$elements.find( '.' + jQuery( this ).attr( 'data-advanced-for' ) );
                var $buttonLabel = jQuery( this ).find( '.button' ).html();

                if ( !$optionWithAdvanced.length ) {
                    console.warn( 'Option with advanced not found', jQuery( this ).attr( 'data-advanced-for' ) );
                    return;
                }

                var $advancedButton = jQuery( '<button type="button" class="button fw-advanced-button">' + $buttonLabel + '</button>' ),
                    $popupButton = jQuery( this ).find( '.button:first' );

                $advancedButton.on( 'click', function () {
                    $popupButton.trigger( 'click' );
                } );

                $advancedButton.insertAfter(
                    $optionWithAdvanced.closest( '.fw-backend-option-input' ).find( '> .fw-inner' )
                    );

                $popupButton.closest( '.fw-backend-option' ).addClass( 'fw-hidden' );
            } ).addClass( 'advanced-initialized' );
        } );
        // end advanced styling button
    }

    // color-palette on change a color
    jQuery( 'body' ).on( 'change', '#fw-option-color_settings #fw-option-color_settings-color_1', function () {
        jQuery( '#fw-option-color-palette-predefined .fw-palette-color-1 .fw-palette-inner' ).css( 'background-color', jQuery( this ).val() );
    } );
    jQuery( 'body' ).on( 'change', '#fw-option-color_settings #fw-option-color_settings-color_2', function () {
        jQuery( '#fw-option-color-palette-predefined .fw-palette-color-2 .fw-palette-inner' ).css( 'background-color', jQuery( this ).val() );
    } );
    jQuery( 'body' ).on( 'change', '#fw-option-color_settings #fw-option-color_settings-color_3', function () {
        jQuery( '#fw-option-color-palette-predefined .fw-palette-color-3 .fw-palette-inner' ).css( 'background-color', jQuery( this ).val() );
    } );
    jQuery( 'body' ).on( 'change', '#fw-option-color_settings #fw-option-color_settings-color_4', function () {
        jQuery( '#fw-option-color-palette-predefined .fw-palette-color-4 .fw-palette-inner' ).css( 'background-color', jQuery( this ).val() );
    } );
    jQuery( 'body' ).on( 'change', '#fw-option-color_settings #fw-option-color_settings-color_5', function () {
        jQuery( '#fw-option-color-palette-predefined .fw-palette-color-5 .fw-palette-inner' ).css( 'background-color', jQuery( this ).val() );
    } );

    // hide post tabs
    fw_show_hide_post_tabs();

    var page_header = jQuery( '#fw-options-box-page-side' );
    var page_template = jQuery( '#page_template' );

    // if on page ready template is without sidebar hide header image option
    if ( page_template.val() == 'blank-template.php' || page_template.val() == 'visual-builder-template.php' ) {
        page_header.hide();
    } else if ( page_template.val() == 'default' ) {
        page_header.show();
    }

    // on click visual page editor button set page template "visual page"
    jQuery( '#wp-content-media-buttons' ).on( 'click', '.button-primary', function () {
        if ( page_template.val() == 'blank-template.php' ) {
            page_template.val( 'blank-template.php' );
            page_header.hide();
        } else if ( page_template.val() == 'default' ) {
            page_template.val( 'visual-builder-template.php' );
            page_header.hide();
        }
    } );

    // on click default editor button set page template "default template"
    jQuery( '#post-body' ).on( 'click', '.page-builder-hide-button', function () {
        // change only previous template was from theme templates
        if ( page_template.val() == 'blank-template.php' || page_template.val() == 'visual-builder-template.php' ) {
            page_template.val( 'default' );
            page_header.show();
        }
    } );

    // on change page template hide header image option
    page_template.on( 'change', function () {
        if ( page_template.val() == 'default' ) {
            jQuery( '.page-builder-hide-button' ).trigger( 'click' );
            page_header.show();
        } else if ( page_template.val() == 'blank-template.php' || page_template.val() == 'visual-builder-template.php' ) {
            jQuery( '#wp-content-media-buttons .button-primary' ).trigger( 'click' );
            page_header.hide();
        }
    } );

    // hide search position for specific header type
    jQuery( '.fw-settings-form' ).on( 'click', '#fw-option-header_settings-header_type_picker-header_type .thumbnail', function () {
        var src = jQuery( this ).find( 'img' ).attr( 'src' );
        var search_position = jQuery( '#fw-backend-option-fw-option-header_settings-enable_search-yes-search_position' );
        fw_hide_search_position( search_position, src );
        fw_hide_data_for_header( src );
    } );
    // hide search position on click on header tab
    jQuery( '.fw-settings-form' ).on( 'click', 'li[aria-controls="fw-options-tab-header"]', function () {
        var src = jQuery( '#fw-option-header_settings-header_type_picker-header_type .thumbnail.selected img' ).attr( 'src' );
        var search_position = jQuery( '#fw-backend-option-fw-option-header_settings-enable_search-yes-search_position' );
        fw_hide_search_position( search_position, src );
        fw_hide_data_for_header( src );
    } );

    jQuery( document ).on( 'click', '.fw-auto-install-admin-notice button.notice-dismiss', function () {
        jQuery.ajax( {
            type: 'POST',
            url: TheCore.ajaxUrl,
            data: {
                action: 'the_core_dismiss_autoinstall_notice_message',
                nonce: TheCore.nonce
            }
        } );
    } );

    // Top User Menu Meta Box
    jQuery( '#menu-to-edit').on( 'click', 'a.item-edit', function() {
		var settings  = jQuery(this).closest( '.menu-item-bar' ).next( '.menu-item-settings' );
		var css_class = settings.find( '.edit-menu-item-classes' );
		if( css_class.val().indexOf( 'olympus-user-menu' ) === 0 ) {
			css_class.attr( 'readonly', 'readonly' );
			settings.find( '.field-url' ).css( 'display', 'none' );
		}
	});
} );

jQuery(window).on('load', function () {
    /**Post featured metaboxes for different post formats**/
    if (jQuery('body').hasClass('post-type-post')) {
        var $selector_panel = jQuery("select[id^=\"post-format-selector\"], #post-formats-select input[name=\"post_format\"]:checked");
        var $selector_panels = jQuery("select[id^=\"post-format-selector\"], #post-formats-select input[name=\"post_format\"]");
        var $post_format_metaboxes = jQuery('#fw-options-box-post-quote, #fw-options-box-post-image, #fw-options-box-post-video, #fw-options-box-post-link, #fw-options-box-post-audio, #fw-options-box-post-gallery');

        $post_format_metaboxes.hide(); // Default Hide
        console.log($selector_panel.val());
        jQuery('#fw-options-box-post-' + $selector_panel.val()).show();

        $selector_panels.change(function () {
            $post_format_metaboxes.hide(); // Hide during changing
            console.log(jQuery(this));
            jQuery('#fw-options-box-post-' + jQuery(this).val()).show();
        });
    }
});

function fw_hide_section_position( section_overlap_checked, section_overlap ) {
    if ( section_overlap_checked == '' ) {
        jQuery( '.fw-section-position' ).parents( '.fw-backend-option' ).hide();
        section_overlap.parents( '.fw-backend-options-group' ).addClass( 'fw-padding-bottom' );
    } else {
        jQuery( '.fw-section-position' ).parents( '.fw-backend-option' ).show();
        section_overlap.parents( '.fw-backend-options-group' ).removeClass( 'fw-padding-bottom' );
    }
}


function fw_hide_data_for_header( src ) {
    var header_5_elements = jQuery( '#fw-backend-option-fw-option-header_settings-dropdown_bg_color, #fw-backend-option-fw-option-header_settings-dropdown_links_color' );
    var header_6_elements = jQuery( '#fw-backend-option-fw-option-header_settings-boxed_header, #fw-backend-option-fw-option-header_settings-enable_absolute_header, #fw-backend-option-fw-option-header_settings-enable_sticky_header, #fw-backend-option-fw-option-header_settings-dropdown_bg_color, #fw-backend-option-fw-option-header_settings-dropdown_links_color, #fw-backend-option-fw-option-header_settings-header_bg_color' );
    var header_bg_color = jQuery( '#fw-backend-option-fw-option-header_settings-header_bg_color' );
    var social_icon_size = jQuery( '#fw-option-header_settings-header_type_picker-header-6-enable_header_socials' );
    if ( src.indexOf( "header-type5" ) > -1 ) {
        header_6_elements.show();
        header_5_elements.hide();
        header_bg_color.addClass( 'fw-padding-bottom' );
    } else if ( src.indexOf( "header-type6" ) > -1 ) {
        header_6_elements.hide();
        social_icon_size.addClass( 'fw-padding-bottom' );
    } else {
        header_6_elements.show();
        header_bg_color.removeClass( 'fw-padding-bottom' );
        social_icon_size.removeClass( 'fw-padding-bottom' );
    }
}


function fw_hide_search_position( search_position, src ) {
    if ( src.indexOf( "header-type2" ) > -1 ) {
        search_position.hide();
    } else {
        search_position.show();
    }
}


function fw_show_hide_post_grid_view() {
    var blog_view = jQuery( '#fw-backend-option-fw-option-posts_settings-blog_view input[type="checkbox"]' ).val();
    if ( blog_view == '"grid"' ) {
        jQuery( '#fw-backend-option-fw-option-posts_settings-grid_bg_color' ).show();
    } else {
        jQuery( '#fw-backend-option-fw-option-posts_settings-grid_bg_color' ).hide();
    }
}

function fw_show_hide_post_tabs() {
    var post_format = jQuery( '#post-formats-select .post-format:checked' ).val();
    if ( post_format == undefined ) {
        return;
    }
    var tabs_number = jQuery( ".fw-options-tabs-wrapper .fw-options-tabs-list ul li" ).length;
    if ( typeof tabs == 'function' ) {
        var selected_tab = jQuery( ".fw-options-tabs-wrapper" ).tabs().tabs( "option", "active" );
    } else {
        var selected_tab = 1;
    }

    if ( tabs_number == 1 )
    {
        if ( post_format !== '0' ) {
            jQuery( '#fw-options-box-main' ).hide();
        } else {
            jQuery( '#fw-options-box-main' ).show();
        }
    } else {
        if ( post_format !== '0' ) {
            jQuery( '.fw-options-tabs-list' ).find( 'li' ).each( function () {
                if ( jQuery( this ).attr( 'aria-controls' ) === 'fw-options-tab-media' ) {
                    jQuery( this ).hide();
                    jQuery( ".fw-options-tabs-wrapper" ).tabs( "option", "active", selected_tab + 1 );
                }
            } );
        } else {
            // show tab
            jQuery( '.fw-options-tabs-list' ).find( 'li' ).each( function () {
                if ( jQuery( this ).attr( 'aria-controls' ) === 'fw-options-tab-media' ) {
                    jQuery( this ).show();
                    jQuery( ".fw-options-tabs-wrapper" ).tabs( "option", "active", 0 );
                }
            } );
        }
    }


    // hide media tab if post format not standart
    jQuery( '#post-formats-select .post-format' ).on( 'click', function () {
        var clicked_post_format = jQuery( this ).val();

        if ( tabs_number == 1 ) {
            if ( clicked_post_format !== '0' ) {
                jQuery( '#fw-options-box-main' ).hide();
            } else {
                jQuery( '#fw-options-box-main' ).show();
            }
        } else
        {
            var selected = jQuery( ".fw-options-tabs-wrapper" ).tabs( "option", "active" );

            // if no standart post format
            if ( clicked_post_format !== '0' ) {
                // hide tab
                jQuery( '.fw-options-tabs-list' ).find( 'li' ).each( function () {
                    if ( jQuery( this ).attr( 'aria-controls' ) === 'fw-options-tab-media' ) {
                        jQuery( this ).hide();
                        jQuery( ".fw-options-tabs-wrapper" ).tabs( "option", "active", selected + 1 );
                    }
                } );
            } else {
                // show tab
                jQuery( '.fw-options-tabs-list' ).find( 'li' ).each( function () {
                    if ( jQuery( this ).attr( 'aria-controls' ) === 'fw-options-tab-media' ) {
                        jQuery( this ).show();
                        jQuery( ".fw-options-tabs-wrapper" ).tabs( "option", "active", 0 );
                    }
                } );
            }
        }
    } );
}

function fw_hide_table_styling_options( select_value ) {
    var princing_options = jQuery( '#fw-backend-option-fw-edit-options-modal-table_advanced_styling' );
    var tabular_options = jQuery( '#fw-backend-option-fw-edit-options-modal-tabular_table_advanced_styling' );
    if ( select_value == 'pricing' ) {
        princing_options.show();
        tabular_options.hide();
    } else {
        princing_options.hide();
        tabular_options.show();
    }
}

function fw_hide_calendar_styling_options( select_value ) {
    var month_styling = jQuery( '#fw-backend-option-fw-edit-options-modal-advanced_styling' );
    var week_styling = jQuery( '#fw-backend-option-fw-edit-options-modal-advanced_week_styling' );
    var day_styling = jQuery( '#fw-backend-option-fw-edit-options-modal-advanced_day_styling' );

    if ( select_value == 'day' ) {
        month_styling.hide();
        week_styling.hide();
        day_styling.show();
    } else if ( select_value == 'week' ) {
        month_styling.hide();
        week_styling.show();
        day_styling.hide();
    } else {
        month_styling.show();
        week_styling.hide();
        day_styling.hide();
    }
}

function fw_hide_button_options() {
    var button_type = jQuery( '.fw-button-style-type .image_picker_selector .thumbnail.selected img' );
    if ( button_type.length > 0 ) {
        var button_color_group = jQuery( '.fw-button-color-group' );
        var src = button_type.attr( 'src' );
        if ( src.indexOf( "button-style4" ) > -1 ) {
            button_color_group.hide();
        } else {
            button_color_group.show();
        }
    }
}

function fw_hide_testimonials_advanced_options( src ) {
    var bg_group = jQuery( '.fw-testimonials-bg-group' );
    var padding_group = jQuery( '.fw-testimonials-padding-group' );
    if ( src.indexOf( "testimonials-style2" ) > -1 ) {
        bg_group.show();
        padding_group.show();
    } else {
        bg_group.hide();
        padding_group.hide();
    }
}