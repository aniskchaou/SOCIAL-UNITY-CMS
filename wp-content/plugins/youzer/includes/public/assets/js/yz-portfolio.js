( function( $ ) {

    'use strict';

    $( document ).ready( function () {

        $( document ).on( 'click', '#yz-portfolio-button' , function( e ) {

            var current_wg_nbr = $( '.yz-wg-item[data-wg=portfolio]' ).length + 1;

            if ( current_wg_nbr > yz_max_portfolio_img  )  {
                // Show Error Message
                $.yz_DialogMsg( 'error', Yz_Portfolio.items_nbr + yz_max_portfolio_img );
                return false;
            }

            e.preventDefault();

            var portfolio_button = $.ukai_form_input( {
                    class       : 'yz-photo-url',
                    cell        : yz_pf_nextCell,
                    label_title : Yz_Portfolio.upload_photo,
                    options_name: 'youzer_portfolio',
                    input_id    : 'yz_portfolio_' + yz_pf_nextCell,
                    input_type  : 'image',
                    option_item : 'url',
                    option_only : true
                }),

                portfolio_link = $.ukai_form_input( {
                    option_item     : 'link',
                    options_name    : 'youzer_portfolio',
                    cell            : yz_pf_nextCell,
                    label_title     : Yz_Portfolio.photo_link,
                    input_type      : 'text',
                    show_label      : false,
                    show_ph         : true
                }),

                portfolio_title = $.ukai_form_input( {
                    options_name    : 'youzer_portfolio',
                    option_item     : 'title',
                    label_title     : Yz_Portfolio.photo_title,
                    cell            : yz_pf_nextCell,
                    input_type      : 'text',
                    show_label      : false,
                    show_ph         : true
                });

            // Add Portflio Item.
            $( '<li class="yz-wg-item" data-wg="portfolio">' +
                    '<div class="yz-wg-container">' +
                        '<div class="yz-cphoto-content">' +
                        portfolio_button + portfolio_title + portfolio_link +
                    '</div></div><a class="yz-delete-item"></a>' +
                '</li>'
            ).hide().prependTo( '.yz-wg-portfolio-options' ).fadeIn( 400 );

            // Increase ID Number.
            yz_pf_nextCell++;

            // Check Account Items List
            $.yz_CheckList();

        });

        /**
         * # Check Account Items
         */
        $.yz_CheckList = function() {

            // Check Portfolio List.
            if ( $( '.yz-wg-portfolio-options li' )[0] ) {
                $( '.yz-no-portfolio' ).remove();
            } else if ( ! $( '.yz-no-portfolio' )[0] ) {
                $( '.yz-wg-portfolio-options' ).append(
                    '<p class="yz-no-content yz-no-portfolio">' + Yz_Portfolio.no_items + '</p>'
                );
            }

        }

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

        // Check Account Items List
        $.yz_CheckList();

    });

})( jQuery );