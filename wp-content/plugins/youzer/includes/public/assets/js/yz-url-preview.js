/**
 * Copyright (c) 2015 Leonardo Cardoso (http://leocardz.com)
 * Dual licensed under the MIT (http://www.opensource.org/licenses/mit-license.php)
 * and GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * Version: 1.0.0
 */
( function( $ ) {

    'use strict';

    $( document ).ready( function() {

        // Init Vars.
        $.YZLP = {};
        var URL_REGEX = /((https?|ftp)\:\/\/)?([a-z0-9+!*(),;?&=\$_.-]+(\:[a-z0-9+!*(),;?&=\$_.-]+)?@)?([a-z0-9-.]*)\.([a-z]{2,3})(\:[0-9]{2,5})?(\/([a-z0-9+\$_\-~@\(\)\%]\.?)+)*\/?(\?[a-z+&\$_.-][a-z0-9;:@&#%=+\/\$_.-]*)?(#[a-z_.-][a-z0-9+\$_.-]*)?/i;
        var URL_REGEX2 = /(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi;
        var yz_lp_folder = Youzer.youzer_url + 'includes/public/core/functions/live-preview/';
        var default_lp_form = $( '#yz-wall-form' ).find( '.lp-prepost-container' ).html();

        // Check if Content contains Url.
        var hasUrl = function( $text ) {
            return URL_REGEX.test( $text );
        };

        // Check if Content is Url.
        function isUrl( $text ) {
            return URL_REGEX2.test( $text );
        };

        // Detect Textarea Paste.
        $( document ).on( 'paste', '.yz-wall-textarea', function( e ) {
            $.yz_get_link_preview_data( $( this ).closest( 'form' ), $( this ).val() );
        });

        // Detect Textarea Keyup.
        $( document ).on( 'keyup', '.yz-wall-textarea', function( e) {
            if ( ( e.which == 13 || e.which == 32 || e.which == 17 ) )  {
                $.yz_get_link_preview_data( $( this ).closest( 'form' ), $( this ).val() );
            }
        });

        // Track Emojiareaone Keyup.
        $( document ).on( 'keyup', '.emojionearea-editor', function( e ) {
            // if ( ( e.which == 13 || e.which == 32 || e.which == 17 ) ) {
                $.yz_get_link_preview_data( $( this ).closest( 'form' ), $( this ).text() );
            // }
        });

        // Hide Thumbnail
        $( document ).on( 'change', '.lp-preview-no-thubmnail-text input', function() {
            var live_preview_form = $( this ).closest( '.lp-prepost-container' );
            if ( this.checked ) {
                live_preview_form.find( '.lp-preview-image' ).fadeOut( 200, function() {
                    live_preview_form.addClass( 'yz-lp-no-thumbnail' );
                });
            } else {
                live_preview_form.find( '.lp-preview-image' ).fadeIn( 200, function() {
                    live_preview_form.removeClass( 'yz-lp-no-thumbnail' );
                });
            }
        });

        // Close Url Preview & Rest Form
        $( document ).on( 'click', '.lp-button-cancel', function() {
            $( this ).closest( '.yz-lp-prepost' ).attr( 'data-loaded', false );
            $( this ).closest( '.lp-prepost-container' ).fadeOut( 200, function() {
                $( this ).html( default_lp_form ).attr( 'class', 'lp-prepost-container' );
            });
        });

        // Display Title Edit Input
        $( document ).on( 'click', '.lp-preview-title', function() {
            $( this ).fadeOut( 200, function() {
                $( this ).closest( '.yz-lp-prepost' ).find( '.lp-preview-replace-title' ).fadeIn();
            });
        });

        // Display Title Edit Input
        $( document ).on( 'click', '.lp-preview-description', function() {
            $( this ).fadeOut( 200, function() {
                $( this ).closest( '.yz-lp-prepost' ).find( '.lp-preview-replace-description' ).fadeIn();
            });
        });

        // Previous.
        $( document ).on( 'click', '.yz-lp-previous-image', function() {

            // Init Vars.
            var current_index = parseInt( $( this ).closest( '.lp-preview-thubmnail-buttons' ).attr( 'data-current' ) );

            if ( current_index < 1 ) {
                return;
            }

            // Update Image.
            $.yz_update_live_preview_image( $( this ).closest( '.yz-lp-prepost' ), current_index - 1 );

        });

        // Next.
        $( document ).on( 'click', '.yz-lp-next-image', function() {

            // Init Vars.
            var current_index = parseInt( $( this ).closest( '.lp-preview-thubmnail-buttons' ).attr( 'data-current' ) );
            var new_index = current_index + 1;

            if ( ! $.YZLP.images[ new_index ] ) {
                return;
            }

            // Update Image.
            $.yz_update_live_preview_image( $( this ).closest( '.yz-lp-prepost' ), new_index );

        });

        /**
         * Update Live Preview Image.
         */
        $.yz_update_live_preview_image = function( form, index ) {

            // Update Image.
            form.find( 'input[name="url_preview_img"]' ).val( $.YZLP.images[ index ] );
            form.find( '.lp-preview-image' ).css( 'background-image', 'url(' + $.YZLP.images[ index ] + ')' );

            // Update Pagination.
            form.find( '.lp-preview-thubmnail-buttons' ).attr( 'data-current', index );
            form.find( '.lp-preview-pagination' ).find( '.lp-preview-thubmnail-pagination' ).text( index + 1 );

        }

        /**
         * Get Link Preview.
         */
        $.yz_get_link_preview_data = function ( form, $text  ) {

            // Hide Live Preview for Comments.
            if ( form.find( 'input[name="post_type"]' ).val() == 'activity_comment' ) {
                return;
            }

            // Verify text is not empty & it has URL and there's no previous fetching.
            if ( form.find( '.yz-lp-prepost' ).attr( 'data-loaded' ) == 'true' || $text == "" || ! hasUrl( $text ) ) {
                return;
            }

            var get_url = $text.match( URL_REGEX );

            var isUrl = new RegExp( URL_REGEX2 );

            if ( isUrl.test( get_url[0] ) ) {

                // Disable Submit Button.
                form.find( '.yz-wall-post, .yz-update-post' ).attr( 'disabled', true );

                // Display Loader.
                form.find( '.lp-loading-text' ).fadeIn();

                // Set Actions.
                form.find( '.yz-lp-prepost' ).attr( 'data-loaded', true );

                // Disable Posting form.
                // ------------------------------------

                var data = JSON.stringify( {
                    // action: '',
                    text: $text,
                    imageAmount: -1
                } );

                $.ajax({
                    url: ajaxurl,
                    method: "POST",
                    data: "action=yz_get_url_live_preview&data=" + window.btoa( encodeURIComponent( data ) ),
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    success: function( response ) {
                        // Get Response.
                        response = $.parseJSON( response );

                        // Display Live Url Preview.
                        $.yz_set_live_preview_form( form.find( '.yz-lp-prepost' ), response );

                        // Enable Submit Button.
                        form.find( '.yz-wall-post, .yz-update-post' ).attr( 'disabled', false );

                    }

                });
            }
        }

    });

    /**
     * Set Live Preview Form.
     */
    $.yz_set_live_preview_form = function ( form, data ) {

        // Init Vars.
        var preview_container = form.find( '.lp-prepost-container' ),
            elements = {
                '{{preview.title}}' : data.title,
                '{{preview.site}}' : data.site,
                '{{preview.description}}' : data.description,
                '{{preview.image}}' : data.image,
                '{{thumbnailPaginationText}}' : 1,
                '{{thumbnailText}}' : data.images ? data.images.length : 1,
            };

        $.YZLP.images = data.images;

        if ( data.image ) {
            form.find( '.lp-preview-image' ).css( 'background-image', 'url(' + data.image + ')' ).prepend( '<input type="hidden" name="url_preview_img" value="' + data.image + '">' );
        }

        if ( data.link ) {
            form.find( '.lp-preview-image' ).prepend( '<input type="hidden" name="url_preview_link" value="' + data.link + '">' );
        }

        if ( data.site ) {
            form.find( '.lp-preview-image' ).prepend( '<input type="hidden" name="url_preview_site" value="' + data.site + '">' );
        }

        if ( data.title ) {
            form.find( '.lp-preview-replace-title-wrap' ).append( '<input type="text" class="lp-preview-replace-title" name="url_preview_title" value="' + data.title + '">')
        }

        if ( data.description ) {
            form.find( '.lp-preview-replace-description-wrap' ).append( '<textarea name="url_preview_desc" class="lp-no-resize lp-preview-replace-description">' + data.description + '</textarea>' )
        }

        // Remove Pagination if there less than 2 images.
        if ( data.images && data.images.length < 2 ) {
            preview_container.find( '.lp-preview-pagination' ).remove();
        } else {
            preview_container.find( '.lp-preview-thubmnail-buttons' ).attr( 'data-current', 0 );
        }

        // Remove Video Icon.
        if ( data.video == false ) {
            preview_container.find( '.lp-preview-video-icon' ).remove();
        }

        // Replace Preview Tags.
        $.each( elements, function( tag, value) {
            var myregexp = new RegExp( tag, 'g' );
            var newhtml = preview_container.html().replace( myregexp, value );
            preview_container.html( newhtml );
        });

        if ( data.use_thumbnail && data.use_thumbnail == 'on' ) {
            // preview_container
            preview_container.find( 'input[name="url_preview_use_thumbnail"]' ).attr( 'checked', true ).trigger( 'change' );
        }

        // Hide Loader & Display Url Preview.
        form.find( '.lp-loading-text' ).fadeOut( 200, function() {
            preview_container.fadeIn();
        });

    }

})( jQuery );