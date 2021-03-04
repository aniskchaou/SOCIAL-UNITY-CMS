"use strict"; 

(function($){
	
	jQuery.fn.crumegamenu = function(options){
		var settings;
		$.extend( settings = {
			trigger: "hover",
			showDelay: 0,
			hideDelay: 0,
			effect: "fade",
			align: "left",
			responsive: true,
			indentChildren: true,
			scrollable: true,
			scrollableMaxHeight: 460
		}, options);
		
		// variables
		var menu_container = $(this);
        var menu = $(menu_container).find(".primary-menu-menu");
		var menu_li = $(menu).find("li");
		var showHideButton;
        var mobileWidthBase = 1023;
		var bigScreenFlag = 2000; // a number greater than "mobileWidthBase"
		var smallScreenFlag = 200; // a number less than "mobileWidthBase"
		
		// sub-menu/megamenu indicators
		$(menu).children("li").children("a").each(function(){
			if($(this).siblings(".sub-menu, .megamenu").length > 0){
				$(this).append("<span class='indicator'></span>");
			}
		});
		$(menu).children("li").children(".megamenu").each(function(){
			$(this).find('ul').removeClass('sub-menu');
		});
		$(menu).find(".sub-menu").children("li").children("a").each(function(){
			if($(this).siblings(".sub-menu").length > 0){
				$(this).append("<span class='indicator'></span>");
			}
		});
		
		// navigation alignment
		if(settings.align == "right"){ 
			$(menu).addClass("primary-menu-right");
		}
		
		// sub-menu indentation (mobile mode)
		if(settings.indentChildren){ 
			$(menu).addClass("primary-menu-indented");
		}
		
		// responsive behavior
		if(settings.responsive){ 
			$(menu_container).addClass("primary-menu-responsive");
			showHideButton = $(menu_container).children(".showhide");
		}

		// scrollable menu
		if(settings.scrollable){
			if(settings.responsive){
				$(menu).css("max-height", settings.scrollableMaxHeight).addClass("scrollable").append("<li class='scrollable-fix'></li>");
			}
		}
		
		// shows a sub-menu
		function showDropdown(item){
			if(settings.effect == "fade")
				$(item).children(".sub-menu, .megamenu").stop(true, true).delay(settings.showDelay).fadeIn(settings.showSpeed).addClass(settings.animation);
			else
				$(item).children(".sub-menu, .megamenu").stop(true, true).delay(settings.showDelay).slideDown(settings.showSpeed).addClass(settings.animation);
		}
		
		// hides a sub-menu
		function hideDropdown(item){
			if(settings.effect == "fade")
				$(item).children(".sub-menu, .megamenu").stop(true, true).delay(settings.hideDelay).fadeOut(settings.hideSpeed).removeClass(settings.animation);
			else
				$(item).children(".sub-menu, .megamenu").stop(true, true).delay(settings.hideDelay).slideUp(settings.hideSpeed).removeClass(settings.animation);
			$(item).children(".sub-menu, .megamenu").find(".sub-menu, .megamenu").stop(true, true).delay(settings.hideDelay).fadeOut(settings.hideSpeed);
		}
		
		// landscape mode
		function landscapeMode(){
			$(menu).find(".sub-menu, .megamenu").hide(0);
			if(navigator.userAgent.match(/Mobi/i) || window.navigator.msMaxTouchPoints > 0 || settings.trigger == "click"){
				$(".primary-menu-menu > li > a, .primary-menu ul.sub-menu li a").bind("click touchstart", function(e){
					e.stopPropagation(); 
					e.preventDefault();
					$(this).parent("li").siblings("li").find(".sub-menu, .megamenu").stop(true, true).fadeOut(300);
					if($(this).siblings(".sub-menu, .megamenu").css("display") == "none"){
						showDropdown($(this).parent("li"));
						return false; 
					}
					else{
						hideDropdown($(this).parent("li"));
					}
					window.location.href = $(this).attr("href");
				});
				$(document).bind("click.menu touchstart.menu", function(ev){
					if($(ev.target).closest(".primary-menu").length == 0){
						$(".primary-menu-menu").find(".sub-menu, .megamenu").fadeOut(300);
					}
				});
			}
			else{
				$(menu_li).bind("mouseenter", function(){
					showDropdown(this);
				}).bind("mouseleave", function(){
					hideDropdown(this);
				});
			}
		}
		
		// portrait mode
		function portraitMode(){
			$(menu).find(".sub-menu, .megamenu").hide(0);
			$(menu).find(".indicator").each(function(){
				if($(this).parent("a").siblings(".sub-menu, .megamenu").length > 0){
					$(this).bind("click", function(e){
						$(menu).scrollTo({top: 45, left: 0}, 600);

						if($(this).parent().prop("tagName") == "A"){
							e.preventDefault();
						}

						if($(this).parent("a").siblings(".sub-menu, .megamenu").css("display") == "none"){
							$(this).parent("a").siblings(".sub-menu, .megamenu").delay(settings.showDelay).slideDown(settings.showSpeed);
							$(this).parent("a").parent("li").siblings("li").find(".sub-menu, .megamenu").slideUp(settings.hideSpeed);
						} else {
							$(this).parent("a").siblings(".sub-menu, .megamenu").slideUp(settings.hideSpeed);
						}

					});
				}
			});
		}
		
		// Fix the submenu on the right side
		function fixSubmenuRight(){

			var submenus = $('> li .sub-menu, > .megamenu--half-width .megamenu', menu);

			if($('body').innerWidth() > mobileWidthBase){
				var menu_width = $("body").outerWidth(true);

				for(var i = 0; i < submenus.length; i++){
					var submenusPosition = $(submenus[i]).css("display", "block").offset().left;
					$(submenus[i]).css("display", "none");

					if($(submenus[i]).outerWidth() + submenusPosition > menu_width){
						$(submenus[i]).addClass("sub-menu-right");
					}

					else{
						if(menu_width == $(submenus[i]).outerWidth() || (menu_width - $(submenus[i]).outerWidth()) < 20){
							$(submenus[i]).addClass("sub-menu-right");
						}
						if(submenusPosition + $(submenus[i]).outerWidth() < menu_width){
							$(submenus[i]).addClass("sub-menu-left");
						}
					}
				}
			}
		}

		
		// show the bar to show/hide menu items on mobile
		function showMobileBar(){
			$(menu).hide(0);
			$(showHideButton).show(0).click(function(event){
				event.preventDefault();
				$(this).toggleClass('open');
				if($(menu).css("display") == "none")
					$(menu).slideDown(settings.showSpeed);
				else
					$(menu).slideUp(settings.hideSpeed).find(".sub-menu, .megamenu").hide(settings.hideSpeed);
			});
		}
		
		// hide the bar to show/hide menu items on mobile
		function hideMobileBar(){
			$(menu).show(0);
			$(showHideButton).hide(0);
		}
		
		// unbind events
		function unbindEvents(){
            $(menu).find("li, a").unbind();
            showHideButton.unbind();
			$(document).unbind("click.menu touchstart.menu");
		}
		
		// return window's width
		function windowWidth(){
			return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
		}
		
		// navigation start function
		function startMenu(){
			if(windowWidth() <= mobileWidthBase && bigScreenFlag > mobileWidthBase){
				unbindEvents();
				if(settings.responsive){
					showMobileBar();
					portraitMode();
				}
				else{
					landscapeMode();
				}
			}
			if(windowWidth() > mobileWidthBase && smallScreenFlag <= mobileWidthBase){
				unbindEvents();
				hideMobileBar();
				landscapeMode();
			}
			bigScreenFlag = windowWidth();
			smallScreenFlag = windowWidth();
			/* IE8 fix */
			if(/MSIE (\d+\.\d+);/.test(navigator.userAgent) && windowWidth() < mobileWidthBase){
				var ieversion = new Number(RegExp.$1);
				if(ieversion == 8){
					$(showHideButton).hide(0);
					$(menu).show(0);
					unbindEvents();
					landscapeMode();
				}
			}
		}

		$(window).resize(function(){
			startMenu();
		});

		$(document).ready(function(){
			fixSubmenuRight();
			startMenu();
		});
		
	}
}(jQuery));