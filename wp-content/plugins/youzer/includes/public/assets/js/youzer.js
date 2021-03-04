/*! lazysizes - v5.2.2 */
!function(e){var t=function(u,D,f){"use strict";var k,H;if(function(){var e;var t={lazyClass:"lazyload",loadedClass:"lazyloaded",loadingClass:"lazyloading",preloadClass:"lazypreload",errorClass:"lazyerror",autosizesClass:"lazyautosizes",srcAttr:"data-src",srcsetAttr:"data-srcset",sizesAttr:"data-sizes",minSize:40,customMedia:{},init:true,expFactor:1.5,hFac:.8,loadMode:2,loadHidden:true,ricTimeout:0,throttleDelay:125};H=u.lazySizesConfig||u.lazysizesConfig||{};for(e in t){if(!(e in H)){H[e]=t[e]}}}(),!D||!D.getElementsByClassName){return{init:function(){},cfg:H,noSupport:true}}var O=D.documentElement,a=u.HTMLPictureElement,P="addEventListener",$="getAttribute",q=u[P].bind(u),I=u.setTimeout,U=u.requestAnimationFrame||I,l=u.requestIdleCallback,j=/^picture$/i,r=["load","error","lazyincluded","_lazyloaded"],i={},G=Array.prototype.forEach,J=function(e,t){if(!i[t]){i[t]=new RegExp("(\\s|^)"+t+"(\\s|$)")}return i[t].test(e[$]("class")||"")&&i[t]},K=function(e,t){if(!J(e,t)){e.setAttribute("class",(e[$]("class")||"").trim()+" "+t)}},Q=function(e,t){var i;if(i=J(e,t)){e.setAttribute("class",(e[$]("class")||"").replace(i," "))}},V=function(t,i,e){var a=e?P:"removeEventListener";if(e){V(t,i)}r.forEach(function(e){t[a](e,i)})},X=function(e,t,i,a,r){var n=D.createEvent("Event");if(!i){i={}}i.instance=k;n.initEvent(t,!a,!r);n.detail=i;e.dispatchEvent(n);return n},Y=function(e,t){var i;if(!a&&(i=u.picturefill||H.pf)){if(t&&t.src&&!e[$]("srcset")){e.setAttribute("srcset",t.src)}i({reevaluate:true,elements:[e]})}else if(t&&t.src){e.src=t.src}},Z=function(e,t){return(getComputedStyle(e,null)||{})[t]},s=function(e,t,i){i=i||e.offsetWidth;while(i<H.minSize&&t&&!e._lazysizesWidth){i=t.offsetWidth;t=t.parentNode}return i},ee=function(){var i,a;var t=[];var r=[];var n=t;var s=function(){var e=n;n=t.length?r:t;i=true;a=false;while(e.length){e.shift()()}i=false};var e=function(e,t){if(i&&!t){e.apply(this,arguments)}else{n.push(e);if(!a){a=true;(D.hidden?I:U)(s)}}};e._lsFlush=s;return e}(),te=function(i,e){return e?function(){ee(i)}:function(){var e=this;var t=arguments;ee(function(){i.apply(e,t)})}},ie=function(e){var i;var a=0;var r=H.throttleDelay;var n=H.ricTimeout;var t=function(){i=false;a=f.now();e()};var s=l&&n>49?function(){l(t,{timeout:n});if(n!==H.ricTimeout){n=H.ricTimeout}}:te(function(){I(t)},true);return function(e){var t;if(e=e===true){n=33}if(i){return}i=true;t=r-(f.now()-a);if(t<0){t=0}if(e||t<9){s()}else{I(s,t)}}},ae=function(e){var t,i;var a=99;var r=function(){t=null;e()};var n=function(){var e=f.now()-i;if(e<a){I(n,a-e)}else{(l||r)(r)}};return function(){i=f.now();if(!t){t=I(n,a)}}},e=function(){var v,m,c,h,e;var y,z,g,p,C,b,A;var n=/^img$/i;var d=/^iframe$/i;var E="onscroll"in u&&!/(gle|ing)bot/.test(navigator.userAgent);var _=0;var w=0;var N=0;var M=-1;var x=function(e){N--;if(!e||N<0||!e.target){N=0}};var W=function(e){if(A==null){A=Z(D.body,"visibility")=="hidden"}return A||!(Z(e.parentNode,"visibility")=="hidden"&&Z(e,"visibility")=="hidden")};var S=function(e,t){var i;var a=e;var r=W(e);g-=t;b+=t;p-=t;C+=t;while(r&&(a=a.offsetParent)&&a!=D.body&&a!=O){r=(Z(a,"opacity")||1)>0;if(r&&Z(a,"overflow")!="visible"){i=a.getBoundingClientRect();r=C>i.left&&p<i.right&&b>i.top-1&&g<i.bottom+1}}return r};var t=function(){var e,t,i,a,r,n,s,l,o,u,f,c;var d=k.elements;if((h=H.loadMode)&&N<8&&(e=d.length)){t=0;M++;for(;t<e;t++){if(!d[t]||d[t]._lazyRace){continue}if(!E||k.prematureUnveil&&k.prematureUnveil(d[t])){R(d[t]);continue}if(!(l=d[t][$]("data-expand"))||!(n=l*1)){n=w}if(!u){u=!H.expand||H.expand<1?O.clientHeight>500&&O.clientWidth>500?500:370:H.expand;k._defEx=u;f=u*H.expFactor;c=H.hFac;A=null;if(w<f&&N<1&&M>2&&h>2&&!D.hidden){w=f;M=0}else if(h>1&&M>1&&N<6){w=u}else{w=_}}if(o!==n){y=innerWidth+n*c;z=innerHeight+n;s=n*-1;o=n}i=d[t].getBoundingClientRect();if((b=i.bottom)>=s&&(g=i.top)<=z&&(C=i.right)>=s*c&&(p=i.left)<=y&&(b||C||p||g)&&(H.loadHidden||W(d[t]))&&(m&&N<3&&!l&&(h<3||M<4)||S(d[t],n))){R(d[t]);r=true;if(N>9){break}}else if(!r&&m&&!a&&N<4&&M<4&&h>2&&(v[0]||H.preloadAfterLoad)&&(v[0]||!l&&(b||C||p||g||d[t][$](H.sizesAttr)!="auto"))){a=v[0]||d[t]}}if(a&&!r){R(a)}}};var i=ie(t);var B=function(e){var t=e.target;if(t._lazyCache){delete t._lazyCache;return}x(e);K(t,H.loadedClass);Q(t,H.loadingClass);V(t,L);X(t,"lazyloaded")};var a=te(B);var L=function(e){a({target:e.target})};var T=function(t,i){try{t.contentWindow.location.replace(i)}catch(e){t.src=i}};var F=function(e){var t;var i=e[$](H.srcsetAttr);if(t=H.customMedia[e[$]("data-media")||e[$]("media")]){e.setAttribute("media",t)}if(i){e.setAttribute("srcset",i)}};var s=te(function(t,e,i,a,r){var n,s,l,o,u,f;if(!(u=X(t,"lazybeforeunveil",e)).defaultPrevented){if(a){if(i){K(t,H.autosizesClass)}else{t.setAttribute("sizes",a)}}s=t[$](H.srcsetAttr);n=t[$](H.srcAttr);if(r){l=t.parentNode;o=l&&j.test(l.nodeName||"")}f=e.firesLoad||"src"in t&&(s||n||o);u={target:t};K(t,H.loadingClass);if(f){clearTimeout(c);c=I(x,2500);V(t,L,true)}if(o){G.call(l.getElementsByTagName("source"),F)}if(s){t.setAttribute("srcset",s)}else if(n&&!o){if(d.test(t.nodeName)){T(t,n)}else{t.src=n}}if(r&&(s||o)){Y(t,{src:n})}}if(t._lazyRace){delete t._lazyRace}Q(t,H.lazyClass);ee(function(){var e=t.complete&&t.naturalWidth>1;if(!f||e){if(e){K(t,"ls-is-cached")}B(u);t._lazyCache=true;I(function(){if("_lazyCache"in t){delete t._lazyCache}},9)}if(t.loading=="lazy"){N--}},true)});var R=function(e){if(e._lazyRace){return}var t;var i=n.test(e.nodeName);var a=i&&(e[$](H.sizesAttr)||e[$]("sizes"));var r=a=="auto";if((r||!m)&&i&&(e[$]("src")||e.srcset)&&!e.complete&&!J(e,H.errorClass)&&J(e,H.lazyClass)){return}t=X(e,"lazyunveilread").detail;if(r){re.updateElem(e,true,e.offsetWidth)}e._lazyRace=true;N++;s(e,t,r,a,i)};var r=ae(function(){H.loadMode=3;i()});var l=function(){if(H.loadMode==3){H.loadMode=2}r()};var o=function(){if(m){return}if(f.now()-e<999){I(o,999);return}m=true;H.loadMode=3;i();q("scroll",l,true)};return{_:function(){e=f.now();k.elements=D.getElementsByClassName(H.lazyClass);v=D.getElementsByClassName(H.lazyClass+" "+H.preloadClass);q("scroll",i,true);q("resize",i,true);q("pageshow",function(e){if(e.persisted){var t=D.querySelectorAll("."+H.loadingClass);if(t.length&&t.forEach){U(function(){t.forEach(function(e){if(e.complete){R(e)}})})}}});if(u.MutationObserver){new MutationObserver(i).observe(O,{childList:true,subtree:true,attributes:true})}else{O[P]("DOMNodeInserted",i,true);O[P]("DOMAttrModified",i,true);setInterval(i,999)}q("hashchange",i,true);["focus","mouseover","click","load","transitionend","animationend"].forEach(function(e){D[P](e,i,true)});if(/d$|^c/.test(D.readyState)){o()}else{q("load",o);D[P]("DOMContentLoaded",i);I(o,2e4)}if(k.elements.length){t();ee._lsFlush()}else{i()}},checkElems:i,unveil:R,_aLSL:l}}(),re=function(){var i;var n=te(function(e,t,i,a){var r,n,s;e._lazysizesWidth=a;a+="px";e.setAttribute("sizes",a);if(j.test(t.nodeName||"")){r=t.getElementsByTagName("source");for(n=0,s=r.length;n<s;n++){r[n].setAttribute("sizes",a)}}if(!i.detail.dataAttr){Y(e,i.detail)}});var a=function(e,t,i){var a;var r=e.parentNode;if(r){i=s(e,r,i);a=X(e,"lazybeforesizes",{width:i,dataAttr:!!t});if(!a.defaultPrevented){i=a.detail.width;if(i&&i!==e._lazysizesWidth){n(e,r,a,i)}}}};var e=function(){var e;var t=i.length;if(t){e=0;for(;e<t;e++){a(i[e])}}};var t=ae(e);return{_:function(){i=D.getElementsByClassName(H.autosizesClass);q("resize",t)},checkElems:t,updateElem:a}}(),t=function(){if(!t.i&&D.getElementsByClassName){t.i=true;re._();e._()}};return I(function(){H.init&&t()}),k={cfg:H,autoSizer:re,loader:e,init:t,uP:Y,aC:K,rC:Q,hC:J,fire:X,gW:s,rAF:ee}}(e,e.document,Date);e.lazySizes=t,"object"==typeof module&&module.exports&&(module.exports=t)}("undefined"!=typeof window?window:{});
( function( $ ) {

	'use strict';

	$( document ).ready( function() {

		// Check if Div is Empty
		function isEmpty( el ) {
		    return ! $.trim( el.html() );
		}

		if ( ! window.hasOwnProperty( 'yz_disable_niceselect' ) && ( $( '.youzer select:not([multiple="multiple"])' ).get( 0 ) || $( '.logy select' ).get( 0 ) ) ) {
    		$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/jquery.nice-select.min.js' } ).appendTo( 'head' );
			$( '.youzer select:not([multiple="multiple"])' ).niceSelect();
			$( '.logy select' ).not( '[multiple="multiple"]' ).niceSelect();
		}

		// Textarea Auto Height.
		if ( $( '.youzer textarea' ).get( 0 ) ) {
    		$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/autosize.min.js' } ).appendTo( 'head' );
			yz_autosize( $( '.youzer textarea' ) );
		}

		// Delete Empty Notices.
		$( '.widget_bp_core_sitewide_messages' ).each( function() {
	        if ( isEmpty( $( this ).find( '.bp-site-wide-message' ) ) ) {
	          $( this ).remove();
	        }
	    });

		// Delete Empty Actions.
		$( '.youzer .group-members-list .action' ).each( function() {
	        if ( isEmpty( $( this ) ) ) {
	          $( this ).remove();
	        }
	    });

		// Delete Empty Sub Navigations.
		$( '#subnav ul' ).each( function() {
	        if ( isEmpty( $( this ) ) ) {
	          $( this ).parent().remove();
	        }
	    });

		// Close SiteWide Notice.
		$( '#close-notice' ).on( 'click', function( e ) {
			$( this ).closest( '#sitewide-notice' ).fadeOut();
		});

		/**
		 * Display Activity tools.
		 */
		$( document ).on( 'click', '.yz-item .yz-show-item-tools', function ( e ) {

			// Load Reviews Script.
    		if ( $( this ).parent().hasClass( 'yz-review-item' ) ) {
    			$.yz_load_reviews_script();
    		}

			// Switch Current Icon.
			$( this ).toggleClass( 'yz-close-item-tools' );

			// Show / Hide Tools.
			$( this ).closest( '.yz-item' ).find( '.yz-item-tools' ).fadeToggle();

		});

		/**
		 * Get Url Variable.
		 */
		$.yz_get_var_in_url = function( url, name ) {
			var urla = url.split( "?" );
			var qvars = urla[1].split( "&" );//so we hav an arry of name=val,name=val
			for ( var i = 0; i < qvars.length; i++ ) {
				var qv = qvars[i].split( "=" );
				if ( qv[0] == name )
					return qv[1];
			}
			return '';
		}

		// Change Fields Privacy.
	    $( '.field-visibility-settings .radio input[type=radio]' ).change( function() {
	    	var new_privacy = $( this ).parent().find( '.field-visibility-text' ).text();
	    	$( this ).closest('.field-visibility-settings')
	    	.prev( '.field-visibility-settings-toggle' )
	    	.find( '.current-visibility-level' )
	    	.text( new_privacy );
	    });

		// Append Dialog.
		$( 'body' ).append( '<div class="youzer-dialog"></div>' );

	    /**
	     * Dialog Message.
	     */
	    $.yz_DialogMsg = function ( type, msg ) {

	     	var icon, button, title = '', confirmation_btn = '';

	     	// Get Dialog Title.
			if ( type == 'error' ) {
	     		title = Youzer.ops;
	     		button = Youzer.gotit;
	     		icon = 'exclamation-triangle';
	     	} else if ( type == 'success' ) {
	     		button = Youzer.thanks;
	     		title = Youzer.done;
	     		icon = 'check';
	     	} else {
	     		button = Youzer.cancel;
	     		icon = 'info-circle';
	     		confirmation_btn = '<li><a class="yz-confirm-dialog">' + Youzer.confirm + '</a></li>';
	     	}

	     	$( '.youzer-dialog' ).empty().append( '<div class="yz-' + type + '-dialog">' +
	            '<div class="youzer-dialog-container">' +
	                '<div class="yz-dialog-header"><i class="fas fa-' + icon + '"></i></div>' +
	                '<div class="youzer-dialog-msg">' +
	                    '<div class="youzer-dialog-desc"><div class="youzer-dialog-title">' + title + '</div><div class="yz-dialog-msg-content">' + msg + '</div>' + '</div>' +
	               	'</div>' +
	                '<ul class="yz-dialog-buttons">' + confirmation_btn +
	                	'<li><a class="yz-close-dialog">' + button + '</a></li>' +
	                '</ul>'+
	            '</div>' +
	        '</div>' ).addClass( 'yz-is-visible' );

	    }

	    // Close Dialog
	    $( '.youzer-dialog, .youzer-modal' ).on( 'click', function( e ) {
	        if ( $( e.target ).is( '.yz-close-dialog' ) || $( e.target ).is( '.youzer-dialog' ) ) {
	            e.preventDefault();
	            $( this ).removeClass( 'yz-is-visible' );
	        }
	    });

	    // Close Dialog if you user Clicked Cancel
	    $( 'body' ).on( 'click', '.yz-close-dialog', function( e ) {
	        e.preventDefault();
	        $( '.youzer-dialog,.youzer-modal' ).removeClass( 'yz-is-visible' );
	    });

		// Responsive Navbar Menu
		$( '.yz-responsive-menu' ).click( function( e ) {
	        e.preventDefault();
			// Hide Account Settings Menu to avoid any Conflect.
			if (  $( '.yz-settings-area' ).hasClass( 'open-settings-menu' ) ) {
				$( '.yz-settings-area' ).toggleClass( 'open-settings-menu'  );
				$( '.yz-settings-area .yz-settings-menu' ).fadeOut();
			}
			// Show / Hide Navigation Menu
			$( this ).toggleClass( 'is-active' );
	        $( '.yz-profile-navmenu' ).fadeToggle( 600 );
		});

		/**
		 * # Check is String is Json Code.
		 */
		$.yz_isJSON = function ( str ) {

		    if ( typeof( str ) !== 'string' ) {
		        return false;
		    }

		    try {
		        JSON.parse( str );
		        return true;
		    } catch ( e ) {
		        return false;
		    }
		}

		/**
		 * Woocommerce Add to cart with ajax.
		 */
		$( document ).on( 'click', '.yz-addtocart-ajax', function (e) {

		    e.preventDefault();

		    var $thisbutton = $(this),
		    	button_icon_class = $thisbutton.find( '.yz-btn-icon i' ).attr( 'class' ),
	            $form = $thisbutton.closest('form.cart'),
	            variation_id = $form.find('input[name=variation_id]').val() || 0;

		    var data = {
		        action: 'woocommerce_ajax_add_to_cart',
		        product_id: $thisbutton.data( 'yz-product-id' ),
		        product_sku: '',
		        quantity: 1,
		        variation_id: variation_id,
		    };

		    $( document.body ).trigger( 'adding_to_cart', [ $thisbutton, data ] );

		    $.ajax({
		        type: 'post',
		        url: Youzer.ajax_url,
		        data: data,
		        beforeSend: function (response) {

		            $thisbutton.removeClass('added').addClass('loading');
		        },
		        complete: function (response) {

					// Show Check .
		            $thisbutton.addClass( 'added' ).removeClass('loading');
		            $thisbutton.find( '.yz-btn-icon i' ).attr( 'class', 'fas fa-check' );

					setTimeout( function() {
						// Change Button Icon.
			            $thisbutton.find( '.yz-btn-icon i' ).attr( 'class', button_icon_class ).hide().fadeIn();
					}, 1000 );
		        },
		        success: function (response) {

		            if (response.error & response.product_url) {
		                window.location = response.product_url;
		                return;
		            } else {
		                $(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash]);
		            }
		        }
		    });
		    return false;
		});

		/**
		 * # Hide Modal if user clicked Close Button or Icon
		 */
		$( document ).on( 'click', '.yz-modal-close, .yz-modal-close-icon' , function( e ) {

			e.preventDefault();

			// Get Data.
			$( this ).closest( '#yz-modal' ).fadeOut( 300, function() {
				$( 'body' ).removeClass( 'yz-modal-overlay-active' );
				$( this ).remove();
			});

		});

		// Hide Modal If User Clicked Escape Button
		$( document ).keyup( function( e ) {
			if ( $( '#yz-modal' )[0] ) {
			    if ( e.keyCode === 27 ) {
				    $( '.yz-modal-close, .yz-modal-close-icon' ).trigger( 'click' );
			    }
			}
		});

        // Overrding Append Function.
        var yz_origAppend = $.fn.append;
		$.fn.append = function () {
			return yz_origAppend.apply( this, arguments ).trigger( 'append' );
		};

		$( '<div class="yz-mobile-nav yz-inline-mobile-nav"><div class="yz-mobile-nav-item yz-show-tab-menu"><div class="yz-mobile-nav-container"><i class="fas fa-bars"></i><a>' + Youzer.menu_title + '</a></div></div>' + '</div>'
		).insertBefore( $( '.yz-profile div.item-list-tabs,.yz-group div.item-list-tabs' )  );

		var yz_resizeTimer;

		$( window ).on( 'resize', function ( e ) {

			// Init Vars.
			var window_changed;

		    clearTimeout( yz_resizeTimer );

		    yz_resizeTimer = setTimeout( function () {

		        if ( $( window ).width() > 768 ) {
		        	$( '.item-list-tabs, .item-list-tabs ul, #yz-directory-search-box, #members-order-select,#groups-order-select,.yz-profile-navmenu' ).fadeIn().removeAttr("style");;
		        }

			}, 250 );
		});

		// Display Search Box.
    	$( '.yz-show-tab-menu' ).on( 'click', function( e ) {
    		e.preventDefault();
    		$( '#subnav.item-list-tabs' ).fadeToggle();
		});

		// Display Search Box.
    	$( '.yz-tool-btn' ).on( 'click', function( e ) {

    		e.preventDefault();

    		if ( $( this ).hasClass( 'yz-verify-btn' ) && ! $( 'body' ).hasClass( 'yz-verify-script-loaded' ) ) {
    			$( 'body' ).addClass( 'yz-verify-script-loaded' );
    			$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-verify-user.min.js' } ).appendTo( 'head' );
    			$( this ).trigger( 'click' );
    		}

    		// Load Reviews Script.
    		if ( $( this ).hasClass( 'yz-review-btn' ) ) {
    			$.yz_load_reviews_script( $( this ) );
    		}

		});

    	/**
    	 * Load Reviews Script
    	 */
    	$.yz_load_reviews_script = function( button ) {

    		if ( ! $( 'body' ).hasClass( 'yz-review-script-loaded' ) ) {
    			$( 'body' ).addClass( 'yz-review-script-loaded' );
    			$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-reviews.min.js' } ).appendTo( 'head' );
    			if ( button ) {
    				button.trigger( 'click' );
    			}
    		}

    	}

    	$.yz_show_modal = function( form ) {
    		$( 'body' ).append( form.show() ).addClass( 'yz-modal-overlay-active' );
    	}

		// Display Menu Box.
    	$( document ).on( 'click', '.youzer a[data-lightbox]', function( e ) {

    		if ( window.hasOwnProperty( 'yz_disable_lightbox' ) ) {
    			e.preventDefault();
    			return;
			}

    		e.preventDefault();

    		if ( ! $( 'body' ).hasClass( 'yz-lightbox-script-loaded' ) ) {
    			$( 'body' ).addClass( 'yz-lightbox-script-loaded' );
		        $( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/lightbox.min.js' } ).appendTo( 'head' );
	    		$( '<link/>', { rel: 'stylesheet', href: Youzer.assets + 'css/lightbox.min.css' } ).appendTo( 'head' );
    			$( this ).trigger( 'click' );
    		}

		});

    	$( window ).on( 'load', function() {
    		if ( Youzer.live_notifications == 'on' && wp.heartbeat ) {
		    	$( '<script/>', { rel: 'text/javascript', src: Youzer.assets + 'js/yz-live-notifications.min.js' } ).appendTo( 'head' );
    		}
		});

	});

})( jQuery );