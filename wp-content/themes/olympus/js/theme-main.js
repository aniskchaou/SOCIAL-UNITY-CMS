"use strict";

var CRUMINA = {};

(function ($) {
	//----------------------------------------------------/
	// Predefined Variables
	//----------------------------------------------------/
	var $window = $(window),
		$document = $(document),
		$body = $('body'),
		$header = $('#header--standard'),
		$olympusPreloader = $('.olympus-preloader'),
		$socialHeader = $('#site-header');


	/* -----------------------------
	 * Top Search bar function
	 * ---------------------------*/
	CRUMINA.TopSearch = {
		inputTimer: null,
		$input: false,
		$result: false,
		$resultWrap: false,
		$form: false,
		init: function () {
			this.$form = jQuery('#top-search-form');
			this.$result = this.$form.find('.selectize-dropdown-content');
			this.$resultWrap = this.$result.parent();
			this.$input = this.$form.find('#s');

			this.$resultWrap.hide();

			this.addEventListeners();
		},
		addEventListeners: function () {
			var _this = this;

			jQuery(document).on('click', function (event) {
				if (!jQuery(event.target).closest(_this.$form).length) {
					_this.$resultWrap.hide();
				}
			});
			jQuery(document).on('keydown', function (event) {
				if (event.keyCode === 27) {
					_this.$resultWrap.hide();
					_this.$input.blur();
				}
			});

			this.$input.on('focus', function () {
				if (_this.$input.val().length >= 3) {
					if (_this.$result.is(':empty')) {
						_this.runSearch();
					} else {
						_this.$resultWrap.show();
					}
				}
			});

			this.$input.on('input', function () {
				if (_this.inputTimer === null) {
					_this.inputTimer = window.setTimeout(function () {
						_this.inputTimer = null;
						_this.runSearch();
					}, 500);
				}

			});
		},

		runSearch: function () {
			var _this = this;
			var word = this.$input.val();
			_this.$resultWrap.show();
			if (word.length >= 3) {
				this.$form.addClass('process');
				jQuery.ajax({
					type: 'GET',
					url: themeStrings.ajaxUrl + '?action=olympus_ajax_search',
					data: _this.$input.serialize(),
					timeout: 50000
				}).done(function (result) {
					_this.$form.removeClass('process');
					if (result) {
						_this.$result.html(result);
						return;
					}
					_this.$result.html('<li class="nothing-found">Nothing found</li>');
				});
			} else {
				_this.$resultWrap.hide();
				_this.$result.html('');
			}
		}
	};

	/* -----------------------------
	 * Material design js effects
	 * Script file: material.min.js
	 * Documentation about used plugin:
	 * http://demos.creative-tim.com/material-kit/components-documentation.html
	 * ---------------------------*/
	CRUMINA.Materialize = function () {
		$.material.init();
		$('.checkbox > label').on('click', function () {
			$(this).closest('.checkbox').addClass('clicked');
		});

		//Duct tape for material is-impty class
		jQuery(window).load(function () {
			setTimeout(function () {
				try {
					jQuery('.label-floating.is-empty > input:-webkit-autofill').each(function () {
						var $self = jQuery(this);
						$self.parent().removeClass('is-empty');
					});
				} catch (err) {
				}

			}, 1000);
		});
	};

	/* -----------------------
	 * Fixed Header
	 * --------------------- */

	CRUMINA.fixedHeader = function () {
		if ($body.hasClass('disable-sticky-both-header')) {
			return;
		}

		var m_top = 0;
		if ($body.hasClass('admin-bar')) {
			if ($window.width() > 600 && $window.width() <= 768) {
				m_top = 46;
			} else if ($window.width() > 768) {
				m_top = 32;
			}
		}

		if ($body.hasClass('enable-sticky-standard-header')) {
			if($header.length != 0){
				CRUMINA.fixedEl($header, m_top);
			}
		}

		if ($body.hasClass('enable-sticky-social-header')) {
			if($socialHeader.length != 0){
				CRUMINA.fixedEl($socialHeader, m_top);
			}
		}
	};

	CRUMINA.fixedEl = function ($el, top) {
		var height = $el.outerHeight();

		var offset_pos = 'left';
		if( $('body.rtl').length != 0 ){
			offset_pos = 'right';
		}

		var headerOffsetLeft = $('#overflow-x-wrapper').offset().left;
		if( $('body.rtl').length != 0 ){
			headerOffsetLeft = ($(window).width() - ($('#overflow-x-wrapper').offset().left + $('#overflow-x-wrapper').outerWidth()));
		}
		if ($(document).scrollTop() != 0) {
			$el.addClass('header-fixed-active');
			$el.css(offset_pos, headerOffsetLeft + 'px');
			$el.css('top', top + 'px');
		}

		if( $el.closest('.sticky-wrap').length == 0 ){
			$el.wrap('<span class="sticky-wrap"></span>');
		}

		$el.closest('.sticky-wrap').css({
			'width': '100%',
			'height': height + 'px',
			'display': 'block',
		});
		var headerOffsetTop = $el.closest('.sticky-wrap').offset().top;

		$(window).scroll(function (event) {
			if ($(document).scrollTop() + top >= (headerOffsetTop)) {
				$el.addClass('header-fixed-active');
				$el.css(offset_pos, headerOffsetLeft + 'px');
				$el.css('top', top + 'px');
			} else {
				$el.removeClass('header-fixed-active');
				$el.css(offset_pos, 'auto');
				$el.css('top', 'auto');
			}
		});
	};


	/* -----------------------
	 * Fix panel bottom position on IOS
	 * --------------------- */

	CRUMINA.panelBottom = function () {
		var $panelBottom = $('#notification-panel-bottom');
		var lastScrollTop = 0;

		$(window).scroll(function (event) {
			var st = $(this).scrollTop();
			if (st > lastScrollTop) {
				$panelBottom.addClass('anim');
			} else {
				$panelBottom.removeClass('anim')
			}
			lastScrollTop = st;
		});
	};


	/* -----------------------
	 * Fix for horizontal scroll on IOS
	 * --------------------- */

	CRUMINA.overflowXIOS = function () {
		var overflowWrapper = $('#overflow-x-wrapper');

		if (navigator.platform == 'iPad' || navigator.platform == 'iPhone' || navigator.platform == 'iPod') {
			overflowWrapper.css('overflow-x', 'hidden');
		}
	};

	/* -----------------------------
	 * Bootstrap components init
	 * Script file: theme-plugins.js, tether.min.js
	 * Documentation about used plugin:
	 * https://v4-alpha.getbootstrap.com/getting-started/introduction/
	 * ---------------------------*/
	CRUMINA.Bootstrap = function () {
		//  Activate the Tooltips
		$('[data-toggle="tooltip"], [rel="tooltip"]').tooltip();
	};

	/* -----------------------------
	 * Lightbox popups for media
	 * Script file: jquery.magnific-popup.min.js
	 * Documentation about used plugin:
	 * http://dimsemenov.com/plugins/magnific-popup/documentation.html
	 * ---------------------------*/
	CRUMINA.mediaPopups = function () {
		$('.play-video').magnificPopup({
			disableOn: 700,
			type: 'iframe',
			mainClass: 'mfp-fade',
			removalDelay: 160,
			preloader: false,
			fixedContentPos: false
		});
		$('.js-zoom-image').magnificPopup({
			type: 'image',
			removalDelay: 500, //delay removal by X to allow out-animation
			callbacks: {
				beforeOpen: function () {
					// just a hack that adds mfp-anim class to markup
					this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
					this.st.mainClass = 'mfp-zoom-in';
				}
			},
			closeOnContentClick: true,
			midClick: true
		});
		$('.js-zoom-gallery').each(function () {
			$(this).magnificPopup({
				delegate: 'a',
				type: 'image',
				gallery: {
					enabled: true
				},
				removalDelay: 500, //delay removal by X to allow out-animation
				callbacks: {
					beforeOpen: function () {
						// just a hack that adds mfp-anim class to markup
						this.st.image.markup = this.st.image.markup.replace('mfp-figure', 'mfp-figure mfp-with-anim');
						this.st.mainClass = 'mfp-zoom-in';
					}
				},
				closeOnContentClick: true,
				midClick: true
			});
		});
	};

	/* -----------------------------
	 * Sliders and Carousels
	 * Script file: swiper.jquery.min.js
	 * Documentation about used plugin:
	 * http://idangero.us/swiper/api/
	 * ---------------------------*/

	CRUMINA.Swiper = {
		$swipers: {},
		init: function () {
			var _this = this;
			$('.swiper-container').each(function (idx) {
				var $self = $(this);
				var id = 'swiper-unique-id-' + idx;
				$self.addClass(id + ' initialized').attr('id', id);
				$self.parent().find('.swiper-pagination').addClass('pagination-' + id);

				_this.$swipers[id] = new Swiper('#' + id, _this.getParams($self, id));
				_this.addEventListeners(_this.$swipers[id]);
			});
		},
		getParams: function ($swiper, id) {
			var params = {
				parallax: true,
				breakpoints: false,
				keyboardControl: true,
				setWrapperSize: true,
				preloadImages: false,
				lazy: true,
				updateOnImagesReady: true,
				prevNext: ($swiper.data('prev-next')) ? $swiper.data('prev-next') : false,
				changeHandler: ($swiper.data('change-handler')) ? $swiper.data('change-handler') : '',
				direction: ($swiper.data('direction')) ? $swiper.data('direction') : 'horizontal',
				mousewheel: ($swiper.data('mouse-scroll')) ? {
					releaseOnEdges: true
				} : false,
				slidesPerView: ($swiper.data('show-items')) ? $swiper.data('show-items') : 1,
				slidesPerGroup: ($swiper.data('scroll-items')) ? $swiper.data('scroll-items') : 1,
				spaceBetween: ($swiper.data('space-between') || $swiper.data('space-between') == 0) ? $swiper.data('space-between') : 20,
				centeredSlides: ($swiper.data('centered-slider')) ? $swiper.data('centered-slider') : false,
				autoplay: ($swiper.data('autoplay')) ? {
					delay: parseInt($swiper.data('autoplay'))
				} : false,
				autoHeight: ($swiper.hasClass('auto-height')) ? true : false,
				loop: ($swiper.data('loop') == false) ? $swiper.data('loop') : true,
				effect: ($swiper.data('effect')) ? $swiper.data('effect') : 'slide',
				pagination: {
					type: ($swiper.data('pagination')) ? $swiper.data('pagination') : 'bullets',
					el: '.pagination-' + id,
					clickable: true
				},
				coverflow: {
					stretch: ($swiper.data('stretch')) ? $swiper.data('stretch') : 0,
					depth: ($swiper.data('depth')) ? $swiper.data('depth') : 0,
					slideShadows: false,
					rotate: 0,
					modifier: 2
				},
				fade: {
					crossFade: ($swiper.data('crossfade')) ? $swiper.data('crossfade') : true
				}
			};

			if (params['slidesPerView'] > 1) {
				params['breakpoints'] = {
					// when window width is >= 320px
					320: {
						slidesPerView: 1,
						slidesPerGroup: 1
					},
					580: {
						slidesPerView: 2,
						slidesPerGroup: 2
					},
					769: {
						slidesPerView: params['slidesPerView'],
						slidesPerGroup: params['slidesPerView']
					}

				};
			}

			return params;
		},
		addEventListeners: function ($swiper) {
			var _this = this;
			var $wrapper = $swiper.$el.closest('.crumina-module-slider');

			//Prev Next clicks
			if ($swiper.params.prevNext) {
				$wrapper.on('click', '.btn-next, .btn-prev', function (event) {
					event.preventDefault();
					var $self = $(this);

					if ($self.hasClass('btn-next')) {
						$swiper.slideNext();
					} else {
						$swiper.slidePrev();
					}
				});
			}
		}
	};

	/* -----------------------
	 * Input Number Quantity
	 * --------------------- */

	$document.on('click', '.quantity-plus', function () {
		var val = parseInt($(this).prev('input').val());
		$(this).prev('input').val(val + 1).change();
		return false;
	});

	$document.on('click', '.quantity-minus', function () {
		var val = parseInt($(this).next('input').val());
		if (val !== 1) {
			$(this).next('input').val(val - 1).change();
		}
		return false;
	});

	/* -----------------------------
	 * Isotope sorting
	 * ---------------------------*/

	CRUMINA.IsotopeSort = function () {
		var $container = $('.sorting-container');
		if (typeof ($container.isotope) !== 'function')
			return;
		$container.each(function () {
			var $current = $(this);
			var layout = ($current.data('layout').length) ? $current.data('layout') : 'masonry';
			$current.isotope({
				itemSelector: '.sorting-item',
				layoutMode: layout,
				percentPosition: true
			});

			$current.imagesLoaded().progress(function () {
				$current.isotope('layout');
			});

			$window.load(function () {
				setTimeout(function () {
					$current.isotope('layout');
				}, 300);
			});

			var $sorting_buttons = $current.siblings('.sorting-menu').find('li');

			$sorting_buttons.on('click', function () {
				if ($(this).hasClass('active'))
					return false;
				$(this).parent().find('.active').removeClass('active');
				$(this).addClass('active');
				var filterValue = $(this).data('filter');
				if (typeof filterValue != "undefined") {
					$current.isotope({filter: filterValue});
					return false;
				}
			});
		});
	};

	/* -----------------------------
	 * Google map composer builder
	 * ---------------------------*/
	CRUMINA.composerGoogleMap = {
		init: function () {
			this.js();
			this.embed();
		},
		js: function () {
			var dragable = true;

			if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
				dragable = false;
			}

			$('.crumina-google-map').each(function () {
				var $this = $(this);

				var address = $this.data('locations');
				var mapType = $this.data('map-type');
				var mapZoom = $this.data('zoom');
				var mapStyle = $this.data('map-style').replace(/'/g, '"');
				var encStyle = (mapStyle.length > 0) ? JSON.parse(mapStyle) : '';
				var customMarker = $this.data('custom-marker');
				var disableScroll = ($this.data('disable-scrolling') ? true : false);

				var map = new google.maps.Map(this, {
					zoom: mapZoom,
					scrollwheel: disableScroll,
					draggable: dragable,
					mapTypeId: google.maps.MapTypeId[mapType],
					styles: encStyle,
					streetViewControl: false,
					mapTypeControl: false
				});

				var geocoder = new google.maps.Geocoder();

				geocoder.geocode({
					'address': address
				}, function (results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						new google.maps.Marker({
							position: results[0].geometry.location,
							map: map,
							icon: {
								url: customMarker,
								scaledSize: new google.maps.Size(50, 50)
							}
						});
						map.setCenter(results[0].geometry.location);
					}
				});

			});

		},
		embed: function () {
			jQuery('div.crumina-google-map-embed').each(function () {
				var $self = jQuery(this);
				var map = $self.data('map');
				var height = $self.data('map-height');
				var width = $self.data('map-width');

				if (!map || !height || !width) {
					$self.html('Something went wrong! Map cannot be rendered...');
					return;
				}

				var $map = jQuery(rawurldecode(base64_decode(map.trim())))
				$map.attr('width', width);
				$map.attr('height', height);
				$self.html($map);
			});
		}
	}

	/* -----------------------------
	 Custom input type="number"
	 https://bootsnipp.com/snippets/featured/bootstrap-number-spinner-on-click-hold
	 * ---------------------------*/

	CRUMINA.numberSpinnerInit = function () {
		var action;
		$(document).on("touchstart mousedown", ".number-spinner button", function () {
			var btn = $(this);
			var input = btn.closest('.number-spinner').find('input');
			btn.closest('.number-spinner').find('button').prop("disabled", false);

			if (btn.attr('data-dir') == 'up') {
				action = setInterval(function () {
					var value = parseInt(input.val());
					value = !value || isNaN(value) ? 0 : value;
					if (input.attr('max') == undefined || value < parseInt(input.attr('max'))) {
						input.val(value + 1);
					} else {
						btn.prop("disabled", true);
						clearInterval(action);
					}
				}, 50);
			} else {
				action = setInterval(function () {
					var value = parseInt(input.val());
					value = !value || isNaN(value) ? 0 : value;
					if (input.attr('min') == undefined || value > parseInt(input.attr('min'))) {
						input.val(value - 1);
					} else {
						btn.prop("disabled", true);
						clearInterval(action);
					}
				}, 50);
			}
		});
		$(document).on("touchend mouseup", ".number-spinner button", function () {
			clearInterval(action);
		});
	};


	/* -----------------------------
	 * Toggle calendar filter panel toggle
	 * ---------------------------*/

	CRUMINA.calendarFilterPanel = {
		$form: null,
		$panel: null,
		init: function () {
			this.$form = jQuery('#tribe-bar-form');
			this.$panel = jQuery('.tribe-bar-filters', this.$form);
			this.$date = jQuery('#tribe-bar-date', this.$form);
			this.$button = jQuery('#tribe-bar-collapse-toggle', this.$form);
			this.$button.attr('aria-expanded', 'false');
			this.$button.removeClass('tribe-bar-filters-open');

			this.addEventListeners();
		},
		addEventListeners: function () {
			var _this = this;

			this.$form.on('submit', function () {
				_this.toggle();
			});

			this.$date.on('change', function () {
				_this.toggle();
			});

		},
		toggle: function () {
			if (this.$panel.css('position') === 'absolute' && (this.$panel.css('display') === 'block' || this.$panel.css('display') === 'flex')) {
				this.$panel.css('display', 'none');
			}
		}
	};

	/* -----------------------------
	 * Mark all notifications read
	 * ---------------------------*/
	CRUMINA.notificationsMarkReadAll = {
		$btn: null,
		$count: null,
		$parent: null,
		busy: false,

		init: function () {
			this.$btn = jQuery('#bp-notifications-mark-read-all');

			if (!this.$btn.length) {
				return;
			}

			this.$count = jQuery('.label-avatar', this.$btn.closest('#notification-event'));
			this.$popup = this.$btn.closest('.more-dropdown');

			this.addEventListeners();
		},

		addEventListeners: function () {
			var _this = this;

			this.$btn.on('click', function (event) {
				event.preventDefault();
				_this.clear();
			});
		},

		clear: function () {
			var _this = this;

			if (this.busy) {
				return;
			}

			jQuery.ajax({
				url: themeStrings.ajaxUrl,
				dataType: 'json',
				type: 'POST',

				data: {
					'action': 'olympus_notifications_mark_read_all',
					'_ajax_nonce': _this.$btn.data('nonce'),
				},

				beforeSend: function () {
					_this.busy = true;
					_this.$popup.addClass('loading');
				},
				success: function (response) {
					if (!response.success) {
						alert(response.data);
						return;
					}

					_this.$popup.remove();
					_this.$count.html('0');
					_this.$count.remove();
				},
				error: function (jqXHR, textStatus) {
					_this.$popup.removeClass('loading');
					alert(textStatus);
				},
				complete: function () {
					_this.busy = false;
				}
			});
		}

	};

	/* -----------------------------
	 * Mark notifications read, unread, delete on notification page
	 * ---------------------------*/
	CRUMINA.notificationActionsAll = {
		$btn: null,
		$btn_popup: null,
		action: '',
		notif_array: [],
		$form: null,
		counter: 0,
		busy: false,

		init: function () {
			this.$btn = jQuery('#notifications-bulk-management #notification-bulk-manage');
			this.$btn_popup = jQuery('#notification-panel-top #notification-event #bp-notifications-mark-read-all');

			// if (!this.$btn.length) {
			// 	return;
			// }

			this.addEventListeners();
		},

		addEventListeners: function () {
			var _this = this;

			this.$btn_popup.on('click', function (event) {
				event.preventDefault();
				_this.action = 'read';
				_this.popup_act();
			});

			this.$btn.on('click', function (event) {
				event.preventDefault();
				_this.action = jQuery(this).closest('.notifications-options-nav').find('#notification-select').val();
				_this.counter = jQuery(this).closest('#notifications-bulk-management').find('tbody').find('tr').length;
				_this.notif_array = [];
				_this.$form = jQuery(this).closest('#notifications-bulk-management');
				_this.$form.find('tbody').find('tr .bulk-select-check input').each(function(){
					if(jQuery(this).is(':checked')){
						_this.notif_array.push(jQuery(this).val());
					}
				});

				_this.clear();
			});
		},

		popup_act: function () {
			var _this = this;

			if (this.busy) {
				return;
			}

			jQuery.ajax({
				url: themeStrings.ajaxUrl,
				dataType: 'json',
				type: 'POST',
				data: {
					'action': 'olympus_notification_mark_action_all',
					'actionmark' : _this.action,
					'count' : _this.counter,
					'notif_array' : _this.notif_array
				},
				beforeSend: function () {
					_this.busy = true;
				},
				success: function (response) {
					if (!response.success) {
						alert(response.data);
						return;
					}
					
					jQuery('#notification-panel-top #notification-event .icon-status-wrap .label-avatar').remove();
					jQuery('#notification-panel-top #notification-event .more-dropdown').remove();
				},
				error: function (jqXHR, textStatus) {
					alert(textStatus);
				},
				complete: function () {
					_this.busy = false;
				}
			});
		},

		clear: function () {
			var _this = this;

			if (this.busy) {
				return;
			}

			jQuery.ajax({
				url: themeStrings.ajaxUrl,
				dataType: 'json',
				type: 'POST',
				data: {
					'action': 'olympus_notification_mark_action_all',
					'actionmark' : _this.action,
					'count' : _this.counter,
					'notif_array' : _this.notif_array
				},
				beforeSend: function () {
					_this.busy = true;
				},
				success: function (response) {
					if (!response.success) {
						alert(response.data);
						return;
					}

					_this.$form.find('tbody').find('tr .bulk-select-check input').each(function(){
						if(jQuery(this).is(':checked')){
							jQuery(this).closest('tr').remove();
						}
					});

					if(response.data.message != ''){
						_this.$form.find('table').remove();
						_this.$form.find('.notifications-options-nav').remove();
						_this.$form.append('<div id="message" class="info"><p>'+response.data.message+'</p></div>');
					}
				},
				error: function (jqXHR, textStatus) {
					alert(textStatus);
				},
				complete: function () {
					_this.busy = false;
				}
			});
		}
	}

	CRUMINA.notificationActions = {
		$btn: null,
		$btn_popup: null,
		$parent: null,
		$counter: 0,
		$form: null,
		busy: false,

		init: function () {
			this.$btn = jQuery('#notifications-bulk-management .notification-actions .mark-read, #notifications-bulk-management .notification-actions .mark-unread, #notifications-bulk-management .notification-actions .delete');
			this.$btn_popup = jQuery('#notification-panel-top #notification-event .icons-action .mark-read, #notification-panel-top #notification-event .icons-action .delete');

			// if (!this.$btn.length) {
			// 	return;
			// }

			this.addEventListeners();
		},

		addEventListeners: function () {
			var _this = this;

			this.$btn.on('click', function (event) {
				event.preventDefault();
				_this.$parent = jQuery(this).closest('tr');
				_this.$counter = jQuery(this).closest('tbody').find('tr').length;
				_this.$form = jQuery(this).closest('#notifications-bulk-management');
				const url = jQuery(this).attr('href');
				_this.clear( url );
			});

			this.$btn_popup.on('click', function (event) {
				event.preventDefault();
				_this.$parent = jQuery(this).closest('li');
				const url = jQuery(this).attr('href');
				_this.popup_act( url );
			});
		},

		popup_act: function ( url ) {
			var _this = this;

			if (this.busy) {
				return;
			}

			jQuery.ajax({
				url: themeStrings.ajaxUrl,
				dataType: 'json',
				type: 'POST',
				data: {
					'action': 'olympus_notification_mark_action',
					'url' : url,
					'count' : _this.$counter
				},
				beforeSend: function () {
					_this.busy = true;
				},
				success: function (response) {
					if (!response.success) {
						alert(response.data);
						return;
					}

					_this.$parent.remove();

					if(response.data.message != ''){
						jQuery('#notification-panel-top #notification-event .icon-status-wrap .label-avatar').remove();
						jQuery('#notification-panel-top #notification-event .more-dropdown').remove();
					} else {
						var n = parseInt(jQuery('#notification-panel-top #notification-event .icon-status-wrap .label-avatar').text()) - 1;
						if( n < 1 ){
							jQuery('#notification-panel-top #notification-event .icon-status-wrap .label-avatar').remove();
							jQuery('#notification-panel-top #notification-event .more-dropdown').remove();
						} else {
							jQuery('#notification-panel-top #notification-event .icon-status-wrap .label-avatar').text(n);
						}
					}
				},
				error: function (jqXHR, textStatus) {
					alert(textStatus);
				},
				complete: function () {
					_this.busy = false;
				}
			});
		},

		clear: function ( url ) {
			var _this = this;

			if (this.busy) {
				return;
			}

			jQuery.ajax({
				url: themeStrings.ajaxUrl,
				dataType: 'json',
				type: 'POST',
				data: {
					'action': 'olympus_notification_mark_action',
					'url' : url,
					'count' : _this.$counter
				},
				beforeSend: function () {
					_this.busy = true;
				},
				success: function (response) {
					if (!response.success) {
						alert(response.data);
						return;
					}

					_this.$parent.remove();

					if(response.data.message != ''){
						_this.$form.find('table').remove();
						_this.$form.find('.notifications-options-nav').remove();
						_this.$form.append('<div id="message" class="info"><p>'+response.data.message+'</p></div>');
					}
				},
				error: function (jqXHR, textStatus) {
					alert(textStatus);
				},
				complete: function () {
					_this.busy = false;
				}
			});
		}
	}

	/* -----------------------------
	 * Accept/reject Friendship
	 * ---------------------------*/
	CRUMINA.acceptRejectFriendship = {
		$btn: null,
		busy: false,
		id: 0,
		act: '',
		$parent_li: null,
		nonce: '',

		init: function () {
			this.$btn = jQuery('#notification-panel-top #notification-friends .accept-request');
			this.addEventListeners();
		},

		addEventListeners: function () {
			var _this = this;

			this.$btn.on('click', function (event) {
				event.preventDefault();
				_this.id = jQuery(this).attr('data-id');
				_this.act = jQuery(this).attr('data-action');
				_this.$parent_li = jQuery(this).closest('li');
				_this.nonce = jQuery(this).attr('href').split('_wpnonce=');
				_this.nonce = _this.nonce[1];
				_this.clear();
			});
		},

		clear: function () {
			var _this = this;

			if (this.busy) {
				return;
			}

			jQuery.ajax({
				url: themeStrings.ajaxUrl,
				type: 'POST',
				data: {
					'action': _this.act,
					'id': _this.id,
					'_wpnonce': _this.nonce
				},
				beforeSend: function () {
					_this.busy = true;
				},
				success: function (response) {
					var count = parseInt(jQuery('#notification-panel-top #notification-friends .icon-status-wrap .label-avatar').text()) - 1;
					if(count < 1){
						jQuery('#notification-panel-top #notification-friends .icon-status-wrap .label-avatar').remove();
						jQuery('#notification-panel-top #notification-friends .more-dropdown').remove();
					} else {
						_this.$parent_li.remove();
						jQuery('#notification-panel-top #notification-friends .icon-status-wrap .label-avatar').text(count);
					}
				},
				error: function (jqXHR, textStatus) {
					alert(textStatus);
				},
				complete: function () {
					_this.busy = false;
				}
			});
		}
	}

	/* -----------------------------
	 * Password Verify
	 * ---------------------------*/
	CRUMINA.checkPassStrength = {
		$pEntry: null,
		$pEntryConf: null,
		init: function () {
			this.$pEntry = jQuery('.password-entry');
			this.$pEntryConf = jQuery('.password-entry-confirm');
			this.offOldEvents();
			this.addEventListeners();
		},
		offOldEvents: function () {
			this.$pEntry.off('keyup');
			this.$pEntryConf.off('keyup');
		},
		addEventListeners: function () {
			this.$pEntry.val('').keyup(this.check);
			this.$pEntryConf.val('').keyup(this.check);
		},
		check: function () {
			var $self = jQuery(this);
			var $form = $self.closest('form');

			if (!$form.length) {
				return;
			}

			var $strength = jQuery('.pass-strength-result', $form);
			var pass1 = $('.password-entry', $form).val();
			var pass2 = $('.password-entry-confirm', $form).val();
			var strength;

			// Reset classes and result text
			$strength.removeClass('short bad good strong');
			if (!pass1) {
				$strength.html(pwsL10n.empty);
				return;
			}

			strength = wp.passwordStrength.meter(pass1, wp.passwordStrength.userInputBlacklist(), pass2);

			switch (strength) {
				case 2:
					$strength.addClass('bad').html(pwsL10n.bad);
					break;
				case 3:
					$strength.addClass('good').html(pwsL10n.good);
					break;
				case 4:
					$strength.addClass('strong').html(pwsL10n.strong);
					break;
				case 5:
					$strength.addClass('short').html(pwsL10n.mismatch);
					break;
				default:
					$strength.addClass('short').html(pwsL10n['short']);
					break;
			}
		}
	};

	/* -----------------------------
	 * Resize Textareas
	 * ---------------------------*/
	jQuery.fn.extend({
		autoHeight: function () {
			function autoHeight_(element) {
				return jQuery(element)
					.css({'height': 'auto', 'overflow-y': 'hidden'})
					.height(element.scrollHeight);
			}

			return this.each(function () {
				autoHeight_(this).on('input', function () {
					autoHeight_(this);
				});
			});
		}
	});

	/* -----------------------------
	 * Notification icons
	 * ---------------------------*/

	CRUMINA.notificationIcons = {
		$nIcons: null,

		init: function () {
			this.$nIcons = jQuery('#notification-friends, #notification-message, #notification-event');
		},

		addEventListeners: function () {
			var _this = this;

			this.removeEventListeners();
			if (CRUMINA.isTouch()) {
				jQuery(document).on('click.nicon', function (event) {
					var $self = jQuery(event.target);
					if (!$self.closest(_this.$nIcons).length && !$self.is(_this.$nIcons)) {
						_this.$nIcons.removeClass('open');
					}
				});

				this.$nIcons.on('click.nicon', function () {
					var $self = jQuery(this);
					if ($self.hasClass('open')) {
						$self.removeClass('open');
						return;
					}

					_this.$nIcons.removeClass('open');
					$self.addClass('open');
				});
			} else {
				this.$nIcons.on('mouseenter.nicon mouseleave.nicon', function (event) {
					var $self = jQuery(this);
					if (event.type === 'mouseenter') {
						$self.addClass('open');
					}
					if (event.type === 'mouseleave') {
						$self.removeClass('open');
					}
				});
			}
		},

		removeEventListeners: function () {
			jQuery(document).off(".nicon");
			this.$nIcons.off(".nicon");
		}
	};

	/* -----------------------------
	 * Disable tooltips
	 * ---------------------------*/
	CRUMINA.disableTooltipsInit = function () {
		if (CRUMINA.isTouch()) {
			jQuery('[data-toggle="tooltip"]').tooltip('dispose');
		}
	};

	/* -----------------------------
	 * Responsive
	 * ---------------------------*/
	CRUMINA.responsive = {
		$nIcons: null,
		$authorPage: null,
		$profilePanel: null,
		$authorPageInner: null,
		$authorPageInnerEl: null,
		$nPanelTop: null,
		$nPanelBottom: null,
		$fixedSbLeft: null,
		$menuBar: null,
		$userBar: null,
		init: function () {
			this.$nIcons = jQuery('#notification-friends, #notification-message, #notification-event');
			this.$authorPage = jQuery('#author-page');
			this.$authorPageInner = jQuery('#author-page-inner');
			this.$authorPageInnerEl = this.$authorPageInner.children();
			this.$profilePanel = jQuery('#profile-panel-responsive');
			this.$nPanelTop = jQuery('#notification-panel-top');
			this.$nPanelBottom = jQuery('#notification-panel-bottom .control-block');
			this.$fixedSbLeft = jQuery('#fixed-sidebar-left');
			this.$overflowXWrapper = jQuery('#overflow-x-wrapper');
			this.$menuBar = jQuery('#header--standard .header--standard-wrap');
			this.$userBar = jQuery('#site-header .header-content-wrapper');

			this.update();
		},
		mixHeader: function () {
			if (window.matchMedia("(max-width: 768px)").matches) {
				this.$nPanelBottom.append(this.$nIcons);
				this.$profilePanel.append(this.$authorPage);
				this.$profilePanel.append(this.$authorPageInnerEl);

				if (this.$profilePanel.length) {
					$body.toggleClass('has-social-panel-bottom');
				}

			} else {
				this.$nPanelTop.append(this.$nIcons);
				this.$nPanelTop.append(this.$authorPage);
				this.$authorPageInner.append(this.$authorPageInnerEl);
			}
		},
		mixSidePanelBtns: function () {
			var $header = this.$userBar.length ? this.$userBar : this.$menuBar;

			if (!$header.length) {
				return;
			}

			if (window.matchMedia("(max-width: 768px)").matches) {
				this.$fixedSbLeft.prependTo($header);
			} else {
				this.$fixedSbLeft.prependTo(this.$overflowXWrapper);
			}
		},
		update: function () {
			var _this = this;
			var resizeTimer = null;
			var resize = function () {
				resizeTimer = null;

				// Methods
				_this.mixHeader();
				_this.mixSidePanelBtns();
				CRUMINA.notificationIcons.addEventListeners();
			};

			$(window).on('resize', function () {
				if (resizeTimer === null) {
					resizeTimer = window.setTimeout(function () {
						resize();
					}, 300);
				}
			}).resize();
		}
	};

	$('#comment').autoHeight(); // auto resize comment's textarea field

	/* -----------------------------
	 * Is Touch
	 * ---------------------------*/

	CRUMINA.isTouch = function () {
		if (navigator.userAgent.match(/Android/i)
			|| navigator.userAgent.match(/webOS/i)
			|| navigator.userAgent.match(/iPhone/i)
			|| navigator.userAgent.match(/iPad/i)
			|| navigator.userAgent.match(/iPod/i)
			|| navigator.userAgent.match(/BlackBerry/i)
			|| navigator.userAgent.match(/Windows Phone/i)
		) {
			return true;
		} else {
			return false;
		}
	};


	/* -----------------------------
	 * Toggle functions
	 * ---------------------------*/

	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		var target = $(e.target).attr("href"); // activated tab
		if ('#events' === target) {
			$('.fc-state-active').click();
		}
	});


	CRUMINA.smoothScrollInit = function () {
		CRUMINA.smoothScroll = new SmoothScroll('.smooth-scroll a[href*="#"]', {header: false, offset: 70});

		//Auto scroll if hash
		if (window.location.hash) {
			if (window.location.hash == "#!"){
				return;
			}
			var anchor = document.querySelector(window.location.hash); // Get the anchor
			var toggle = document.querySelector('a[href*="' + window.location.hash + '"]'); // Get the toggle (if one exists)
			var options = {}; // Any custom options you want to use would go here

			if (anchor) {
				CRUMINA.smoothScroll.animateScroll(anchor, toggle, options);
			}
		}

		//Auto scroll to fw form messages
		var $fwMessages = jQuery('.fw-flash-messages');

		if ($fwMessages.length) {
			setTimeout(function () {
				CRUMINA.smoothScroll.animateScroll($fwMessages[0]);
			}, 500);
		}
	};


	CRUMINA.customScroll = function () {
		$('.mCustomScrollbar').each(function () {
			const ps = new PerfectScrollbar($(this, {
				wheelSpeed      : 2,
				wheelPropagation: false
			})[0]);
		});
	};

	CRUMINA.replacedYouzerBtns = function () {
		let uploadBtn = jQuery(".yz-wall-comments-buttons .yz-wall-upload-btn");
		let uploadGifBtn = jQuery(".yz-wall-comments-buttons .yz-wall-add-gif");
		let uploadCommentBtn = jQuery(".yz-wall-comments-buttons .yz-send-comment");

		uploadBtn.each(function () {
			let _self = jQuery(this);
			let icon = _self.find("i.fas");
			let textBtn = themeStrings.uploadBtnText;

			_self.addClass("btn btn-sm btn-secondary");
			icon.remove();
			_self.append('<i class="fa fa-upload" aria-hidden="true"></i>');
			_self.find('i').after(textBtn);
		});

		uploadGifBtn.each(function () {
			let _self = jQuery(this);
			let icon = _self.find("i.fas");

			_self.addClass("btn btn-sm btn-purple");
			icon.remove();
			_self.append('<i class="fa fa-file-image-o" aria-hidden="true"></i>');
		});

		uploadCommentBtn.each(function () {
			let _self = jQuery(this);
			let icon = _self.find("i.fas");
			let textBtn = themeStrings.uploadCommentBtnText;

			_self.addClass("btn btn-sm btn-primary");
			icon.remove();
			_self.append('<i class="fa fa-comments-o" aria-hidden="true"></i>');
			_self.find('i').after(textBtn);
		});

	};

	CRUMINA.olympusPreloader = function () {
		if($olympusPreloader.length){
			var olympusPreloaderTimeout = parseFloat($('.olympus-preloader').data('time')) * 1000;
			setTimeout(function(){
				$('.olympus-preloader').fadeOut('slow');
			}, olympusPreloaderTimeout);
		}
	};

	/* -----------------------------
	 * On DOM ready functions
	 * ---------------------------*/




	CRUMINA.init = function () {
		CRUMINA.Bootstrap();
		CRUMINA.Materialize();

		CRUMINA.IsotopeSort();

		CRUMINA.TopSearch.init();
		CRUMINA.disableTooltipsInit();
		CRUMINA.composerGoogleMap.init();
		CRUMINA.calendarFilterPanel.init();
		CRUMINA.checkPassStrength.init();
		CRUMINA.numberSpinnerInit();
		CRUMINA.fixedHeader();
		CRUMINA.responsive.init();
		CRUMINA.notificationIcons.init();
		CRUMINA.panelBottom();
		CRUMINA.overflowXIOS();
		CRUMINA.notificationsMarkReadAll.init();
		CRUMINA.notificationActionsAll.init();
		CRUMINA.notificationActions.init();
		CRUMINA.acceptRejectFriendship.init();
		CRUMINA.smoothScrollInit();
		CRUMINA.customScroll();
		CRUMINA.replacedYouzerBtns();
		CRUMINA.olympusPreloader();


		// Megamenu
		$('.primary-menu').crumegamenu({
			showSpeed: 0,
			hideSpeed: 0,
			trigger: "hover",
			animation: "drop-up",
			indicatorFirstLevel: "&#xf0d7",
			indicatorSecondLevel: "&#xf105"
		});

		// Run scripts only if they included on page.
		if (typeof $.fn.magnificPopup !== 'undefined') {
			CRUMINA.mediaPopups();
		}
	};

	$document.ready(function () {
		CRUMINA.init();
	});

	$window.resize(function(){
		CRUMINA.fixedHeader();
	});

})(jQuery);