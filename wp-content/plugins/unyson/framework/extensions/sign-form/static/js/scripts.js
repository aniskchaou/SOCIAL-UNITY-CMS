"use strict";
var cruminaSignForm = {

    init: function () {
        this.addClassesToFormContainer();
        this.passwordEyeInit();
        this.signAjax.init();
    },

    addClassesToFormContainer: function () {
        var $container = jQuery('.' + signFormConfig.selectors.formContainer);

        $container.each(function () {
            var $self = jQuery(this);

            jQuery('.nav-tabs .nav-item .nav-link:first', $self).addClass('active');
            jQuery('.tab-content .tab-pane:first', $self).addClass('active');
        });

        $container.addClass('visible');
    },

    passwordEyeInit: function () {
        var $eye = jQuery('.password-eye');

        $eye.on('click', function (event) {
            event.preventDefault();
            var $self = jQuery(this);

            var $input = $self.next('input');

            if ($input.attr('type') === 'password') {
                $input.attr('type', 'text');

                $self.addClass('fa-eye-slash');
                $self.removeClass('fa-eye');
            } else {
                $input.attr('type', 'password');
                $self.removeClass('fa-eye-slash');
                $self.addClass('fa-eye');
            }

        });

    },

    signAjax: {
        busy: false,
        $forms: null,

        init: function () {
            this.$forms = jQuery('.' + signFormConfig.selectors.formLogin + ', .' + signFormConfig.selectors.formRegister);

            this.addEventListeners();
            this.enableCaptcha();
        },

        enableCaptcha: function () {
            if(signFormConfigCaptcha.enable_captcha && typeof grecaptcha !== 'undefined'){
                grecaptcha.ready(function() {
                    grecaptcha.execute(signFormConfigCaptcha.captcha_site_key).then(function(token) {
                        var olympus_captcha_token_els = document.getElementsByClassName('olympus-captcha-token-register');
                        for (var i = 0; i < olympus_captcha_token_els.length; ++i) {
                            var item = olympus_captcha_token_els[i];  
                            item.value = token;
                        }
                    });
                });
                grecaptcha.ready(function() {
                    grecaptcha.execute(signFormConfigCaptcha.captcha_site_key).then(function(token) {
                        var olympus_captcha_token_els = document.getElementsByClassName('olympus-captcha-token');
                        for (var i = 0; i < olympus_captcha_token_els.length; ++i) {
                            var item = olympus_captcha_token_els[i];  
                            item.value = token;
                        }
                    });
                });
            }
        },

        addEventListeners: function () {
            var _this = this;

            this.$forms.each(function () {

                // jQuery(this).find('.fw-ext-sign-form-bp-field-group *').removeAttr('required');
                // jQuery(this).find('.fw-ext-sign-form-bp-field-group input').removeAttr('id');
                // jQuery(this).find('.fw-ext-sign-form-bp-field-group select').removeAttr('id');

                jQuery(this).on('submit', function (event) {
                    event.preventDefault();
                    _this.sign(jQuery(this));
                });
            });

            jQuery('input', this.$forms).on('change', function () {
                var $self = jQuery(this);

                $self.siblings('.invalid-feedback').remove();
                $self.removeClass('is-invalid');
                $self.closest('.has-errors').removeClass('has-errors');
            });

        },

        sign: function ($form) {
            var _this = this;

            var handler = $form.data('handler');
            var $messages = $form.find('.crumina-sign-form-messages');

            if (!handler || this.busy) {
                return;
            }

            var prepared = {
                action: handler
            };

            var data = $form.serializeArray();

            jQuery.each(data, function(i, field) {
                if (Array.isArray(prepared[field.name])) {
                    prepared[field.name].push(field.value);
                } else if (typeof prepared[field.name] !== 'undefined') {
                    var val = prepared[field.name];
                    prepared[field.name] = new Array();
                    prepared[field.name].push(val);
                    prepared[field.name].push(field.value);
                } else {
                    prepared[field.name] = field.value;
                }
            });

            jQuery.ajax({
                url: signFormConfig.ajaxUrl,
                dataType: 'json',
                type: 'POST',
                data: prepared,

                beforeSend: function () {
                    _this.busy = true;
                    $form.addClass('loading');

                    //Clear old errors
                    $messages.empty();
                    $form.find('.invalid-feedback').remove();
                    $form.find('.is-invalid, .has-errors').removeClass('is-invalid has-errors');
                    _this.enableCaptcha();
                },
                success: function (response) {

                    $form.removeClass('loading');
                    if (response.success) {
                        //Prevent double form submit during redirect
                        _this.busy = true;

                        if (response.data.redirect_to) {
                            location.replace(response.data.redirect_to);
                            return;
                        }

                        if (response.data.email_sent) {
                            $form.find('.ext-sign-form-success-email-message').addClass('active');
                            jQuery('html, body').animate({
                                scrollTop: $form.offset().top - 140
                            }, 1000);
                            return;
                        }

                        location.reload();
                        return;
                    }

                    if (response.data.message) {
                        var $msg = jQuery('<li class="error" />');
                        $msg.html(response.data.message);
                        $msg.appendTo($messages);
                        return;
                    }

                    if (response.data.errors) {
                        _this.renderFormErrors($form, response.data.errors);
                    }

                },
                error: function (jqXHR, textStatus) {
                    $form.removeClass('loading');
                    alert(textStatus);
                },
                complete: function () {
                    _this.busy = false;
                }
            });
        },

        renderFormErrors: function ($form, errors) {
            $form.find('.invalid-feedback').remove();
            $form.find('.is-invalid, .has-errors').removeClass('is-invalid has-errors');

            for (var key in errors) {
                if(key == 'captcha'){
                    $form.find('button[type="submit"]').before('<div class="invalid-feedback captcha">'+errors[key]+'</div>');
                }else{
                    var $field = jQuery('[name="' + key + '"]', $form);
                    var $group = $field.closest('.form-group');
                    var $error = jQuery('<div class="invalid-feedback" />').appendTo($field.parent());

                    $error.text(errors[key]);
                    $field.addClass('is-invalid');
                    $group.addClass('has-errors');
                }
            }
        }
    }


};

jQuery(document).ready(function () {
    cruminaSignForm.init();
});

