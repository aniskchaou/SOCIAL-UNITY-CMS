(function ($) {
	//----------------------------------------------------/
    // USE STRICT
    "use strict";
    
    $('body').on('click', 'article *[data-sharer]', function() {
        window.Sharer.init();
    })
})(jQuery);