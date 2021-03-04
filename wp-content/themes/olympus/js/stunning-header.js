var cruminaStunningHeader = {
    animType: null,
    $wrap: null,
    $container: null,
    $background: null,
    init: function () {
        this.$wrap = jQuery('.crumina-stunning-header--with-animation');
        this.$container = jQuery('.container', this.$wrap);
        this.$background = jQuery('.crumina-heading-background', this.$wrap);

        this.animType = this.$wrap.data('animate-type');

        this.addEventListeners();
    },
    addEventListeners: function () {
        var _this = this;
        jQuery(window).scroll(function () {
            _this.parallaxFade(this);
        });
    },
    parallaxFade: function (scroll) {
        var scrollPos = jQuery(scroll).scrollTop();
        this.$container.css({
            'opacity': 1 - (scrollPos / 300)
        });
        this.$background.css({
            'background-position': this.calcBgPos(scrollPos)
        });
    },
    calcBgPos: function (scrollPos) {
        switch (this.animType) {
            case 'right-to-left':
                return -scrollPos + 'px ' + 0 + 'px';
                break;

            case 'left-to-right':
                return scrollPos + 'px ' + 0 + 'px';
                break;

            case 'fixed':
                return 'center ' + scrollPos/3 + 'px';
                break;
        }
    }
};

jQuery(document).ready(function () {
    cruminaStunningHeader.init();
});