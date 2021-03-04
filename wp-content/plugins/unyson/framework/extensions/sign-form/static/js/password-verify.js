    jQuery( document ).ready( function($) {
        $('body').on('keyup', '.' + signFormConfig.selectors.formRegister +' .sign-form-password-verify', function(){
            var password_verify_confirm_el = $(this).closest('form').find('.sign-form-password-verify-confirm');
            var strength_result = $(this).closest('form').find('.sign-form-pass-strength-result');
            sign_form_check_pass_strength($(this), password_verify_confirm_el, strength_result);
        });
        $('body').on('keyup', '.' + signFormConfig.selectors.formRegister +' .sign-form-password-verify-confirm', function(){
            var password_verify_el = $(this).closest('form').find('.sign-form-password-verify');
            var strength_result = $(this).closest('form').find('.sign-form-pass-strength-result');
            sign_form_check_pass_strength(password_verify_el, $(this), strength_result);
        });

        function sign_form_check_pass_strength(password_verify, password_verify_confirm, strength_result) {
            var pass1 = password_verify.val(),
                pass2 = password_verify_confirm.val(),
                strength;
    
            // Reset classes and result text
            strength_result.removeClass( 'short bad good strong' );
            if ( ! pass1 ) {
                strength_result.html( pwsL10n.empty );
                return;
            }
    
            strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputBlacklist(), pass2 );
    
            switch ( strength ) {
                case 2:
                    strength_result.addClass( 'bad' ).html( pwsL10n.bad );
                    break;
                case 3:
                    strength_result.addClass( 'good' ).html( pwsL10n.good );
                    break;
                case 4:
                    strength_result.addClass( 'strong' ).html( pwsL10n.strong );
                    break;
                case 5:
                    strength_result.addClass( 'short' ).html( pwsL10n.mismatch );
                    break;
                default:
                    strength_result.addClass( 'short' ).html( pwsL10n['short'] );
                    break;
            }
        }
	});