<?php

class YZ_User_Balance {

    /**
     * # Widget Content.
     */
    function widget() {

        yz_styling()->gradient_styling( array(
                'pattern'       => 'geometric',
                'selector'      => '.yz-user-balance-box',
                'left_color'    => 'yz_user_balance_gradient_left_color',
                'right_color'   => 'yz_user_balance_gradient_right_color'
            )
        );

        do_action( 'yz_user_balance_widget_content' );
    }

}