( function( $ ) {

    'use strict';

    $( document ).ready( function () {

        $( document ).on( 'click', '#yz-slideshow-button' , function( e ) {

            var current_wg_nbr = $( '.yz-wg-item[data-wg=slideshow]' ).length + 1;

            if ( current_wg_nbr > yz_max_slideshow_img  )  {
				// Show Error Message
                $.yz_DialogMsg( 'error', Yz_Slideshow.items_nbr + yz_max_slideshow_img );
                return false;
            }

            e.preventDefault();

            var slideshow_button = $.ukai_form_input( {
                    label_title : Yz_Slideshow.upload_photo,
                    options_name : 'youzer_slideshow',
                    input_id    : 'yz_slideshow_' + yz_ss_nextCell,
                    cell         : yz_ss_nextCell,
                    class        : 'yz-photo-url',
                    input_type  : 'image',
                    option_item  : 'original',
                    option_only : true
                });

            // Add Slideshow Item.
            $(  '<li class="yz-wg-item" data-wg="slideshow">' +
                    '<div class="yz-wg-container">' +
                        '<div class="yz-cphoto-content">' + slideshow_button +
                    '</div></div><a class="yz-delete-item"></a>' +
                '</li>'
            ).hide().prependTo( '.yz-wg-slideshow-options' ).fadeIn( 400 );

            // Increase ID Number.
            yz_ss_nextCell++;

            // Check Account Items List
            $.yz_CheckList();

        });

        /**
         * Remove Items.
         */
        $( document ).on( 'click', '.yz-delete-item', function( e ) {

            $( this ).parent().fadeOut( function() {

                // Remove Item
                $( this ).remove();

                // Check Widget Items
                $.yz_CheckList();

            });

        });

        /**
         * # Check Account Items
         */
        $.yz_CheckList = function() {

            // Check Slideshow List.
            if ( $( '.yz-wg-slideshow-options li' )[0] ) {
                $( '.yz-no-slideshow' ).remove();
            } else if ( ! $( '.yz-no-slideshow' )[0] ) {
                $( '.yz-wg-slideshow-options' ).append(
                    '<p class="yz-no-content yz-no-slideshow">' + Yz_Slideshow.no_items + '</p>'
                );
            }

        }

        // Check Account Items List.
        $.yz_CheckList();

    });

})( jQuery );