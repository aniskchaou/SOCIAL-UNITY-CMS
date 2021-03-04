"use strict";

var olympusWidgets = {
    init: function () {
        this.addEventListeners();
        this.toggleSidebarPickerInit();
        this.reinitUnysonScriptsInWidgets();
    },

    addEventListeners: function () {
        var _this = this;
        this.imagePicker.init();
        this.colorPickerInit();

        jQuery( document ).ajaxComplete( function () {
            _this.colorPickerInit();
            _this.imagePicker.init();
        } );
    },
    colorPickerInit: function () {
        jQuery( 'input.widget-color-picker' ).wpColorPicker();
    },
    imagePicker: {
        field: null,
        init: function () {
            var _this = this;

            jQuery( "input.field-image-add, .field-image-add input" ).each( function () {
                var $field = jQuery( this );
                _this.addButtons( $field );
            } );

        },
        addButtons: function ( $field ) {
            var $buttons = $field.siblings( "a" );
            if ( $buttons.length === 0 ) {
                var $removeBtn = jQuery( '<a href="#" class="remove-image-button button">Remove image</a>' ).insertAfter( $field );
                var $addBtn = jQuery( '<a href="#" class="add-image-button button">Add image</a>' ).insertAfter( $field );
                this.addEventListeners( $removeBtn, $addBtn, $field );
                jQuery( '<br>' ).insertAfter( $field );
            }
        },
        addEventListeners: function ( $removeBtn, $addBtn, $field ) {
            var _this = this;

            $removeBtn.on( "click", function ( e ) {
                e.preventDefault();
                jQuery( this ).siblings( 'input.field-image-add' ).val( "" );
                jQuery( this ).closest( '.field-image-add' ).find('input').val( "" );
            } );

            $addBtn.on( "click", function ( e ) {
                _this.openImageLibraryPopUp( $field, e );
            } );
        },
        openImageLibraryPopUp: function ( $field, e ) {
            e.preventDefault();
            var tgm_media_frame;

            if ( tgm_media_frame ) {
                tgm_media_frame.open();
                return false;
            }

            tgm_media_frame = wp.media.frames.tgm_media_frame = wp.media( {
                frame: 'select',
                multiple: false,
                library: { type: 'image' }
            } );

            tgm_media_frame.on( "select", function () {
                var media_attachment = tgm_media_frame.state().get( 'selection' ).first().toJSON();
                $field.val( media_attachment.url );
            } );

            // Now that everything has been set, let's open up the frame.
            tgm_media_frame.open();
        }
    },
    toggleSidebarPickerInit: function () {
        jQuery( window ).on( 'load', function () {
            var $psPicker = jQuery( '#fw-option-single_post_style > select' );
            if(!$psPicker.length){
                return;
            }
            var val = $psPicker.val();
            var $sbPicker = jQuery( '#fw-options-box-sidebar-picker' );
            var $stunningTab = jQuery( '#fw-options-box-customize-design a[href="#fw-options-tab-tab-header-stunning"]' )
            if ( val == 'classic' || val == '0' ) {
                $sbPicker.show();
            } else {
                $sbPicker.hide();
            }
            if ( val == 'modern' ) {
                $stunningTab.hide();
            } else {
                $stunningTab.show();
            }
        } );
        jQuery( 'body' ).on( 'change', '#fw-option-single_post_style > select', function () {
            var val = jQuery( this ).val();
            var $sbPicker = jQuery( '#fw-options-box-sidebar-picker' );
            var $stunningTab = jQuery( '#fw-options-box-customize-design a[href="#fw-options-tab-tab-header-stunning"]' )
            if ( val == 'classic' || val == '0' ) {
                $sbPicker.show();
            } else {
                $sbPicker.hide();
            }
            if ( val == 'modern' ) {
                $stunningTab.hide();
            } else {
                $stunningTab.show();
            }
        } );
    },
    reinitUnysonScriptsInWidgets: function () {
        var timeoutAddId;
        jQuery( document ).on( 'widget-added', function ( ev, $widget ) {
            clearTimeout( timeoutAddId );
            timeoutAddId = setTimeout( function () { // wait a few milliseconds for html replace to finish
                $widget.find( 'form input[type="submit"]' ).click();
            }, 500 );
                } );

        var timeoutUpdateId;
        jQuery( document ).on( 'widget-updated', function ( ev, $widget ) {
            clearTimeout( timeoutUpdateId );
            timeoutUpdateId = setTimeout( function () { // wait a few milliseconds for html replace to finish
                fwEvents.trigger( 'fw:options:init', { $elements: $widget } );
            }, 300 );
        } );
    }
};

jQuery( document ).ready( function () {
    olympusWidgets.init();
} );