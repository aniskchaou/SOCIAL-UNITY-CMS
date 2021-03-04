<?php

class YZ_Login_Button {

    /**
     * # Profile Content.
     */
    function widget() {

    	if ( is_user_logged_in() ) {
    		return;
    	}

    	?><a href="<?php echo yz_get_login_page_url(); ?>" data-show-youzer-login="true" class="yz-profile-login yz_effect" data-effect="fadeIn"><i class="fas fa-user-circle"></i><?php _e( 'Sign in to your account', 'youzer' ); ?></a><?php
    }

}