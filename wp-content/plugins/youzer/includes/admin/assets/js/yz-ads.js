( function( $ ) {

	'use strict';

	$( document ).ready( function () {

		/**
		 * Add New Ad.
		 */
		$( document ).on( 'click', '#yz-add-ad' , function( e )	{

			e.preventDefault();

			// Get Data.
			var	ads_form = $( '#yz-ads-form' ),
				data 	 = $.yz_getAddData( ads_form, 'yz_ad' ),
				ad_args	 = {
					selector 	: $( '.yz-ad-title' ),
					AD_banner 	: data['banner'],
					AD_title 	: data['title'],
					AD_type 	: data['type'],
					AD_code 	: data['code'],
					AD_url 		: data['url']
				};

			// Validate AD Data
			if ( ! $.validate_ad_data( ad_args ) ) {
				return false;
			}

			// Prepare item Data.
			var fieldName = 'yz_ads[yz_ad_' + yz_nextAD + ']',
				show_icon = ( data['type'] == 'adsense' ) ? 'yz_show_icon' : 'yz_hide_icon',
				bannerImg = ( data['type'] == 'banner' ) ? "style='background-image: url( " + data['banner'] + " );'" : '';

			// Add Widget item
			$( '#yz_ads' ).prepend(
				'<li class="yz-ad-item" data-ad-name="yz_ad_'+ yz_nextAD +'">' +
                    '<div class="yz-ad-img ' + show_icon + '" ' + bannerImg + '>' +
                    '<i class="fas fa-code"></i>' + '</div>' +
                    '<div class="yz-ad-data">' +
                        '<h2 class="yz-ad-title">' + data['title'] + '</h2>' +
                        '<div class="yz-ad-actions">' +
                        	'<a class="yz-edit-item yz-edit-ad"></a>' +
                        	'<a class="yz-delete-item yz-delete-ad"></a>' +
                        '</div>' +
                    '</div>' +
                    '<input type="hidden" name="' + fieldName + '[title]" value="' + data['title'] + '">' +
                    '<input type="hidden" name="' + fieldName + '[is_sponsored]" value="' + data['is_sponsored']  + '">' +
                    '<input type="hidden" name="' + fieldName + '[url]" value="' + data['url'] + '">' +
                    '<input type="hidden" name="' + fieldName + '[type]" value="' + data['type'] + '">' +
                    '<input type="hidden" name="' + fieldName + '[code]" value="' + encodeURIComponent( data['code'] ) + '">' +
                    '<input type="hidden" name="' + fieldName + '[banner]" value="' + data['banner'] + '">' +
                '</li>'
			);

			// Hide Modal
			$.yz_HideModal( ads_form );

			// Increase Ad Number.
			yz_nextAD++;

		});

		/**
		 * Edit AD Form.
		 */
		$( document ).on( 'click', '.yz-edit-ad', function() {

			// Get Data.
			var ad_item = $( this ).closest( '.yz-ad-item' );

			// Get Form Values
			$.yz_EditForm( {
				item		: ad_item,
				form_title	: Yz_Ads.update_ad,
				button_id	: 'yz-update-ad',
				form 		: $( '#yz-ads-form' )
			} );

			// CallBack Functions
			$.enable_live_preview();

		});

		/**
		 * Update Ad Data.
		 */
		$( document ).on( 'click', '#yz-update-ad', function( e ) {

			e.preventDefault();

			// Declare Variables.
			var ads_form = $( '#yz-ads-form' ),
				ad_item  = $.yz_getItemObject( ads_form ),
				ad_data  = $.yz_getNewData( ads_form, 'keyToVal' ),
				ad_args  = {
					old_title 	: ad_item.find( '.yz-ad-title' ).text(),
					selector 	: $( '.yz-ad-title' ),
					AD_banner 	: ad_data['banner'],
					AD_title 	: ad_data['title'],
					AD_code 	: ad_data['code'],
					AD_type 	: ad_data['type'],
					AD_url 		: ad_data['url']
				};

			// Validate AD Data
			if ( ! $.validate_ad_data( ad_args ) ) {
				return false;
			}

			// Update Data
			$.yz_updateFieldsData( ads_form );

		});

		/**
		 * Get fields by AD type .
		 */
		$( document ).on( 'change', 'input[name=yz_ad_type]', function() {

			var code 	= '.yz-adcode-item',
				banner 	= '.yz-adbanner-items',
				form 	= $( this ).closest( '.yz-ads-form' );

	        if ( this.value == 'adsense' ) {
	        	form.find( banner ).fadeToggle( 400, function() {
	        		form.find( code ).fadeToggle( 400);
	        	} );
	        } else {
	        	form.find( code ).fadeToggle( 400, function() {
	        		form.find( banner ).fadeToggle( 400);
	        	} );
        	}

    	});

		/**
		 * Validate AD Data .
		 */
		$.validate_ad_data = function( options ) {

			// o = Options .
			var o = $.extend( {}, options ),
				titleAlreadyeExist = $.yz_isAlreadyExist( {
					selector: o.selector,
					value: o.AD_title,
					old_title: o.old_title,
					type: 'text'
				} );

			if (  ! o.AD_title || titleAlreadyeExist ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Yz_Ads.empty_ad,
                    type : 'error'
                } );
                return false;
			}

			// Validate Banner Process.
			if ( o.AD_type == 'banner' ) {
				if ( ! yz_validateBanner( o.AD_banner ) ) {
					return false;
				}
			} else if ( o.AD_type == 'adsense' ) {
				if ( o.AD_code == null || $.trim( o.AD_code ) == '' ) {
					// Show Error Message
					$.ShowPanelMessage( {
						msg  : Yz_Ads.code_empty,
						type : 'error'
					} );
					return false;
				}
			}

			return true;
		}

		/**
		 * Validate Banner .
		 */
		function yz_validateBanner( AD_banner ) {

			// Validate Banner Image
			if ( ! AD_banner ) {
				// Show Error Message
                $.ShowPanelMessage( {
                    msg  : Yz_Ads.empty_banner,
                    type : 'error'
                } );
                return false;
			}

			// Checl if Banner Exist
			if ( ! $.yz_isImgExist( AD_banner, 'banner' ) ) {
				return false;
			}

			return true;
		}

		/**
		 * Remove Item.
		 */
		$( document ).on( 'click', '.yz-delete-ad', function() {

			// Remove item
			$( this ).closest( 'li' ).remove();

			if ( ! $( '.yz-ad-item' )[0] ) {
				$( '#yz_ads' )
				.append( '<p class="yz-no-content yz-no-ads">' + Yz_Ads.no_ads + '</p>' );
			}

		});

	});

})( jQuery );