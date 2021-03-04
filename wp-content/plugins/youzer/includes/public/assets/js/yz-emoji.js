( function( $ ) {

  'use strict';

  $( document ).ready( function() {

    if ( jQuery().emojioneArea ) {
        // Yz_Emoji.posts_visibility == 'on'
        if ( $( '.yz-load-posts-emojis' )[0] || $('#yz-edit-activity-form')[0] ) {
          // alert('in');
        $.yz_init_wall_textarea_emojionearea = function( element ) {
            return element.emojioneArea( {
                pickerPosition: 'bottom',
                autocomplete: true,
                saveEmojisAs : 'image',
                events: {
                ready: function () {
                  // form.find( '.emojionearea-button-open' ).click();
                  this.editor.textcomplete([{
                      id: 'yz_mentions',
                      match: /\B@([\-\d\w]*)$/,
                      search: function ( term, callback ) {
                          var mentions = bp.mentions.users;
                          callback( $.map(mentions, function ( mention ) {
                          return mention.ID.indexOf( term ) === 0 || mention.name.indexOf( term ) === 0 ? mention : null;
                      }));
                      },
                      template: function ( mention ) {
                          return '<img src="' + mention.image + '" /><span class="username">@' + mention.ID + '</span><small>' +mention.name+ '</small>';
                      },
                      replace: function ( mention ) {
                          return '@' + mention.ID + '&nbsp;';
                      },
                      cache: true,
                      index: 1
                   }]);
                }     
              }
            } );
        }
        var el = $.yz_init_wall_textarea_emojionearea( $( '.yz-wall-textarea' ) );
      }

            
        // Activate Emojis in Posts Comments.Yz_Emoji.comments_visibility == 'on'
      if ( $( '.yz-comments-emojis' )[0]  ) {
        // Add Emojis.
        // $( '.ac-textarea' ).addClass( 'yz-comments-emojis' ).append( '<div class="yz-load-emojis"><i class="far fa-smile"></i></div>' );

        // Init Comments Emoji Function
        $.yz_init_comments_emoji = function() {
          var yz_emoji_textarea = $( '.youzer .ac-form textarea' ).emojioneArea( {
                pickerPosition: 'bottom',
                 saveEmojisAs : 'image',
                autocomplete: true,
                events: {
                ready: function () {
                  this.editor.textcomplete([{
                      id: 'yz_mentions',
                      match: /\B@([\-\d\w]*)$/,
                      search: function ( term, callback ) {
                          var mentions = bp.mentions.users;
                          callback( $.map(mentions, function ( mention ) {
                          return mention.ID.indexOf( term ) === 0 || mention.name.indexOf( term ) === 0 ? mention : null;
                      }));
                      },
                      template: function ( mention ) {
                          return '<img src="' + mention.image + '" /><span class="username">@' + mention.ID + '</span><small>' +mention.name+ '</small>';
                      },
                      replace: function ( mention ) {
                          return '@' + mention.ID + '&nbsp;';
                      },
                      cache: true,
                      index: 1
                   }]);
                },
                keypress: function( editor, e ) {
                    if ( e.which == 13 && !e.shiftKey ) {
                        e.preventDefault();
                        this.trigger( 'change' );
                        $( editor ).closest( 'form' ).find( '.yz-send-comment' ).click();
                    }
                }     
              }
            });
          return yz_emoji_textarea;
        }
          var comment_el = $.yz_init_comments_emoji();

        // Init Vars.
        // var comment_el = $.yz_init_comments_emoji();

          // Reset Reply Form after submit.
          $( 'body' ).on( 'append','.activity-comments ul', function(e){
            if ( e.target.localName == 'li' || e.target.localName == 'ul' ) {
            
              // Clean Textarea.
              if ( $( this ).parent().find( '.ac-form textarea' ).get(0) ) {
                $( this ).parent().find( '.ac-form textarea' ).get(0).emojioneArea.setText( '' );
              }

            }
          });

          // Reload Emoji Comments After Loading More Posts.
          $( document ).ajaxComplete(function() {
            $( '.yz-load-emojis' ).remove();
            $.yz_init_comments_emoji();
          });

      }

      // Activate Emojis in Messages.
      if ( $( '.yz-load-messages-emojis' )[0] ) {
        // Enable Emoji.
        var message_el = $( '#send-reply textarea,.yzmsg-form-item #message_content' )
        .emojioneArea( { pickerPosition: 'bottom', saveEmojisAs : 'image' } );
        // Override Val Function.
        var originalVal = this.originalVal = $.fn.val;
        $.fn.val = function(value) {
            if ( typeof value == 'undefined' ) {
                return originalVal.call( this );
            } else {
                if ( $( this ).attr( 'id' ) == 'message_content' && value == '' ) {
                  $( '#send-reply .emojionearea-editor' ).text( '' );
                }
                return originalVal.call( this, value );
            }
        };
      }


    }

    /**
     * # Modal.
     */
    $( 'div.activity' ).on( 'click', 'a.acomment-reply' , function( e ) {
        var li = $( this ).closest( 'li.activity-item' ), comment_id = li.attr( 'id' ).substr( 9, li.attr( 'id' ).length );
        setTimeout(function(){
            $( '#ac-form-' + comment_id ).find( '.emojionearea-editor' ).focus();
        }, 200);
    });

  });

})( jQuery );