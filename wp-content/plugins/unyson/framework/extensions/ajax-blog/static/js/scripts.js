var cruminaAjaxBlog = {
    $posts: jQuery(),
    $filter: null,
    $resetBtn: null,
    $grid: null,
    $form: null,
    $search: null,
    $catSelect: null,
    $catList: null,
    $order: null,
    $orderBy: null,
    $reactions: null,
    catExclude: crumina_ajax_blog.cat_exclude,
    append: false,
    paginateBase: crumina_ajax_blog.paginate_base,
    filterID: crumina_ajax_blog.filter_id,
    gridID: crumina_ajax_blog.grid_id,
    navType: crumina_ajax_blog.nav_type,
    type: crumina_ajax_blog.type,
    masonrySel: crumina_ajax_blog.masonry_sel,
    objID: crumina_ajax_blog.obj_id,
    templatePart: crumina_ajax_blog.template_part,
    postsPerPage: crumina_ajax_blog.posts_per_page,
    sidebarConf: crumina_ajax_blog.sidebar_conf,
    categories: crumina_ajax_blog.categories,
    preloader: 'url(' + crumina_ajax_blog.preloader + ') center top / 100px no-repeat',

    init: function () {
        this.getPostsProcessing.parent = this;

        this.$filter = jQuery('#' + this.filterID);
        this.$grid = jQuery('#' + this.gridID);

        if (!this.$filter.length || !this.$grid.data('nonce')) {
            return false;
        }

        this.$nav = jQuery('<nav id="ajax-nav"></nav>').insertAfter(this.$grid);
        this.$reactions = jQuery('ul.filter-icons > li > a', this.$filter);
        this.$form = jQuery('form.w-search', this.$filter);
        this.$search = jQuery('input', this.$form);
        this.$catSelect = jQuery('select.category', this.$filter);
        this.$catList = jQuery('ul.categories li a', this.$filter);
        this.$order = jQuery('select.order', this.$filter);
        this.$orderBy = jQuery('select.order-by', this.$filter);
        this.$resetBtn = jQuery('.reset-btn', this.$filter);

        if (this.type === 'masonry') {
            this.$grid.isotope({
                itemSelector: '.' + this.masonrySel,
                layoutMode: 'masonry'
            });
        }

        if (this.type === 'grid') {
            this.$grid.addClass('row');
        }

        if ('scrollRestoration' in history) {
            history.scrollRestoration = 'manual';
        }

        this.addEventListeners();

        this.setInitialFilters();

        this.getPosts();
    },
    addEventListeners: function () {
        var _this = this;

        this.$reactions.on('click', function (event) {
            event.preventDefault();
            var $self = jQuery(this);

            $self.toggleClass('selected');

            var reactions = _this.getSelectedReactions();
            if (reactions.length) {
                _this.updateQueryStringParameter('reactions', _this.getSelectedReactions().join(','));
            } else {
                _this.removeQueryStringParameter('reactions');
            }
            _this.updatePageNum('remove');
            _this.getPosts();
        });

        this.$catSelect.on('change', function () {
            _this.updatePageNum('remove');
            history.replaceState(null, '', jQuery(this).children('option:selected').data('url') + location.search);
            _this.getPosts();
        });

        this.$catList.on('click', function (event) {
            event.preventDefault();
            var $self = jQuery(this);
            _this.updatePageNum('remove');
            _this.$catList.parent().removeClass('active');
            $self.parent().addClass('active');
            history.replaceState(null, '', $self.data('url') + location.search);
            _this.getPosts();
        });

        this.$order.on('change', function () {
            _this.updateQueryStringParameter('order', jQuery(this).val());
            _this.getPosts();
        });

        this.$orderBy.on('change', function () {
            _this.updateQueryStringParameter('order-by', jQuery(this).val());
            _this.getPosts();
        });

        this.$resetBtn.on('click', function (event) {
            event.preventDefault();
            _this.resetFilters();
            _this.getPosts();
        });

        this.$nav.on('click', 'li.page-item:not(.disabled) > a, a.btn-more', function (event) {
            event.preventDefault();
            var $self = jQuery(this);
            var page = $self.attr('href').match(/\/page\/(\d+)/);

            if (page && page[1] && page[1] != 0 && page[1] != 1) {
                _this.updatePageNum('set', page[1]);
            } else {
                _this.updatePageNum('remove');
            }

            _this.getPosts();
        });

        this.$form.on('submit', function (event) {
            event.preventDefault();
            _this.updatePageNum('remove');
            _this.getPosts();
        })
    },
    resetFilters: function ( ) {
        this.$reactions.removeClass('selected');
        this.removeQueryStringParameter('reactions');
        this.removeQueryStringParameter('order');
        this.removeQueryStringParameter('order-by');
        this.updatePageNum('remove');

        this.$search.val('').change();

        var orderByDefOpt = this.$orderBy.children('option').filter(function () {
            return this.defaultSelected;
        });

        var orderDefOpt = this.$order.children('option').filter(function () {
            return this.defaultSelected;
        });

        if (jQuery().selectpicker) {
            this.$orderBy.selectpicker('val', orderByDefOpt.val());
            this.$order.selectpicker('val', orderDefOpt.val());
        } else {
            this.$orderBy.val(orderByDefOpt.val());
            this.$order.val(orderDefOpt.val());
        }

    },
    setInitialFilters: function ( ) {
        this.setInitialReactions();

        var order = this.getQueryStringParameter('order');
        if (order) {
            this.$order.val(order);
        }

        var orderBy = this.getQueryStringParameter('order-by');
        if (orderBy) {
            this.$orderBy.val(orderBy);
        }

        var search = this.getQueryStringParameter('search');
        if (search) {
            this.$search.val(search);
        }
    },
    setInitialReactions: function ( ) {
        var _this = this;
        var reactions = this.getQueryStringParameter('reactions');

        if (!reactions) {
            return false;
        }

        reactions.split(',').forEach(function (reaction) {
            _this.$reactions.filter("[data-type='" + reaction + "']").addClass('selected');
        });
    },
    checkSearchField: function ( ) {
        var val = this.$search.val();

        if (val) {
            this.updateQueryStringParameter('search', val);
        } else {
            this.removeQueryStringParameter('search');
        }
    },
    getSelectedReactions: function () {
        var selected = [];

        this.$reactions.filter('.selected').each(function () {
            selected.push(jQuery(this).data('type'));
        });

        return selected;
    },
    getSelectedCategory: function () {
        if (this.$catSelect.length) {
            return this.$catSelect.val();
        }
        if (this.$catList.length) {
            return this.$catList.filter(function () {
                return jQuery(this).parent().hasClass('active') ? true : false;
            }).attr('href');
        }
    },
    getPosts: function () {
        var _this = this;

        _this.checkSearchField();

        jQuery.ajax({
            url: crumina_ajax_blog.ajax,
            dataType: 'json',
            type: 'POST',
            data: {
                action: 'crumina_ajax_blog_get_posts',
                nonce: _this.$grid.data('nonce'),
                category: _this.getSelectedCategory(),
                order: _this.$order.val(),
                orderBy: _this.$orderBy.val(),
                reactions: _this.getQueryStringParameter('reactions'),
                search: _this.getQueryStringParameter('search'),
                page: _this.updatePageNum('get'),
                templatePart: _this.templatePart,
                navType: _this.navType,
                objID: _this.objID,
                categories: _this.categories,
                catExclude: _this.catExclude,
                postsPerPage: _this.postsPerPage,
                sidebarConf: _this.sidebarConf,
                paginateBase: _this.paginateBase,
            },
            beforeSend: function () {
                if (!_this.append) {
                    _this.getPostsProcessing.beforeSendReplace( );
                }
            },
            success: function (response) {
                if (response.success) {
                    if (_this.append) {
                        _this.getPostsProcessing.successAppend(response);
                    } else {
                        _this.getPostsProcessing.successReplace(response);
                    }

                    _this.$nav.html(response.data.nav);
                } else {
                    swal('Oops...', response.data.message, 'error');
                }
            },
            error: function (jqXHR, textStatus) {
                swal('Request failed', textStatus, 'error');
            },
            complete: function () {
                _this.append = false;
            }
        });
    },
    reinitScriptsIfNeeded: function () {
        var $gridContainer = this.$grid;
        var _this = this;
        $gridContainer.imagesLoaded(function () {

            if (CRUMINA.Swiper) {
                CRUMINA.Swiper.init($gridContainer.find('.swiper-container'));
            }

            CRUMINA.mediaPopups();
        });
    },
    getPostsProcessing: {
        beforeSendAppend: function () {

        },
        beforeSendReplace: function () {
            var _this = this.parent;

            if (_this.type === 'masonry') {
                _this.$grid.isotope('remove', _this.$posts);
                _this.$grid.css({
                    'background': _this.preloader,
                    'padding-top': '100px'
                });
            } else {
                _this.$grid.animate({'padding-top': '100px'}).css('background', _this.preloader);
                _this.$grid.html();
            }
        },
        successAppend: function (response) {
            var _this = this.parent;

            if (_this.type === 'masonry') {
                _this.$newPosts = jQuery(response.data.grid).filter('.' + _this.masonrySel);
                _this.$posts = _this.$posts.add(_this.$newPosts);

                _this.$newPosts.imagesLoaded(function () {
                    _this.$grid.isotope('insert', _this.$newPosts);

                    // Reinit same scripts
                    _this.reinitScriptsIfNeeded();
                });
            } else {
                _this.$grid.append(response.data.grid);

                // Reinit same scripts
                _this.reinitScriptsIfNeeded();
            }
        },
        successReplace: function (response) {
            var _this = this.parent;

            if (_this.type === 'masonry') {
                _this.$posts = jQuery(response.data.grid).filter('.' + _this.masonrySel);
                _this.$grid.css({
                    'background': '',
                    'padding-top': ''
                });

                _this.$posts.imagesLoaded(function () {
                    _this.$grid.isotope('insert', _this.$posts);

                    // Reinit same scripts
                    _this.reinitScriptsIfNeeded();
                });
            } else {
                _this.$grid.animate({'padding-top': ''}).css('background', '');
                _this.$grid.html(response.data.grid);

                // Reinit same scripts
                _this.reinitScriptsIfNeeded();
            }
        }
    },
    updateQueryStringParameter: function (key, value) {
        var params = '';
        var url = location.search;
        var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
        var separator = url.indexOf('?') !== -1 ? "&" : "?";
        if (url.match(re)) {
            params = url.replace(re, '$1' + key + "=" + value + '$2');
        } else {
            params = url + separator + key + "=" + value;
        }
        history.replaceState(null, '', location.pathname + params);
    },
    getQueryStringParameter: function (key) {
        var url = location.href;
        key = key.replace(/[\[\]]/g, "\\$&");
        var regex = new RegExp("[?&]" + key + "(=([^&#]*)|&|#|$)");
        var results = regex.exec(url);

        if (!results || !results[2]) {
            return '';
        }
        return decodeURIComponent(results[2].replace(/\+/g, " "));
    },
    removeQueryStringParameter: function (key) {
        var url = location.search;
        var prefix = encodeURIComponent(key) + '=';
        var current = url.replace('?', '');
        var pars = current.split(/[&;]/g);

        //reverse iteration as may be destructive
        for (var i = pars.length; i-- > 0; ) {
            //idiom for string.startsWith
            if (pars[i].lastIndexOf(prefix, 0) !== -1) {
                pars.splice(i, 1);
            }
        }

        var params = (pars.length > 0 && pars[0]) ? '?' + pars.join('&') : '';
        history.replaceState(null, '', location.pathname + params);
    },
    updatePageNum: function (action, newPage) {
        var url = location.href;
        switch (action) {
            case 'get':
                var page = url.match(/\/page\/(\d+)/);

                if (page && page[1] && page[1] != 0) {
                    return page[1];
                } else {
                    return 1;
                }

                break;
            case 'set':
                if (url.match(/\/page\/\d+/)) {
                    var newUrl = url.replace(/page\/\d+/, 'page/' + newPage);
                } else {
                    var sep = !location.pathname.match(/\/$/) ? '/' : '';
                    var newUrl = location.pathname + sep + 'page/' + newPage + '/' + location.search;
                }

                window.history.pushState(null, null, newUrl);
                break;
            case 'remove':
                var newUrl = url.replace(/page\/\d+\/?/, '');
                window.history.pushState(null, null, newUrl);
                break;
        }
    }
};

jQuery(document).ready(function () {
    cruminaAjaxBlog.init();
});