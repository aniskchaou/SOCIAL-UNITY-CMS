var cruminaPostReaction = {
    busy: false,
    init: function () {
        this.addEventListeners();
    },

    addEventListeners: function () {
        var _this = this;

        jQuery( 'body' ).on( 'click', 'a.reaction-toggle-icon', function ( event ) {
            event.preventDefault();
            var $self = jQuery( this );

            if ( !$self.hasClass( 'disabled' ) ) {
                _this.toggleReaction( $self );
            }

        } );
    },

    toggleReaction: function ( $self ) {
        var _this = this;
        var $parent = $self.closest( '.reaction-toggle-parent' );
        var postID = $parent.data( 'post' );

        if ( _this.busy ) {
            return false;
        }

        _this.busy = true;
        jQuery.ajax( {
            url: crumina_reaction.ajax,
            dataType: 'json',
            type: 'POST',
            data: {
                action: 'crumina_reaction_toggle',
                post: postID,
                nonce: $parent.data( 'nonce' ),
                type: $self.data( 'type' )
            },
            success: function ( data ) {
                if ( data.success ) {
                    // Remove old tooltips
                    jQuery( '[data-toggle="tooltip"]' ).tooltip( 'dispose' );

                    // Replace reactions
                    jQuery( '.reactions-btns-' + postID ).replaceWith( data.data.reactions );
                    jQuery( '.reactions-count-all-' + postID ).replaceWith( data.data['count-all'] );
                    jQuery( '.reactions-count-compact-' + postID ).replaceWith( data.data['count-compact'] );
                    jQuery( '.reactions-count-used-' + postID ).replaceWith( data.data['count-used'] );

                    // Tooltips reinit
                    jQuery( '[data-toggle="tooltip"]' ).tooltip();
                } else {
                    swal( 'Oops...', data.data.message, 'error' );
                }
            },
            complete: function () {
                _this.busy = false;
            }
        } );

    }
};

jQuery( document ).ready( function () {
    cruminaPostReaction.init();
} );