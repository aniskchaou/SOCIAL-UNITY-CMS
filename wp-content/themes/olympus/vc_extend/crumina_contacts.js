( function ( $ ) {
    window.vcCruminaContactsView = vc.shortcode_view.extend( {
        $wrapper: false,
        elementTemplate: false,

        changeShortcodeParams: function ( model ) {
            var params;
            var selector = '.vc-crumina-contacts';
            window.vcCruminaContactsView.__super__.changeShortcodeParams.call( this, model );
            params = _.extend( { }, model.get( 'params' ) );
            if ( !this.elementTemplate ) {
                this.elementTemplate = this.$el.find( selector ).html();
            }
            if ( !this.$wrapper ) {
                this.$wrapper = this.$el.find( '.wpb_element_wrapper' );
            }
            if ( _.isObject( params ) ) {
                var template = vc.template( this.elementTemplate, vc.templateOptions.custom );
                this.$wrapper.find( selector ).html( template( { params: params } ) );
            }
        }

    } );
} )( window.jQuery )