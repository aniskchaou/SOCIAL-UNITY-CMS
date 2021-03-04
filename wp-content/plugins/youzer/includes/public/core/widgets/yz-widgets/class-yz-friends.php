<?php

class YZ_Friends {
    
    /**
     * # Content.
     */
    function widget() {

        if ( ! bp_is_active( 'friends' ) ) {
            return;
        }
        
        // Get Member Friends Number
        $friends_nbr = friends_get_total_friend_count( bp_displayed_user_id() );

        if ( $friends_nbr <= 0 ) {
            return;
        }
        
        // Get Widget Layout.
        $widget_layout = yz_option( 'yz_wg_friends_layout', 'list' );

        // Get User Max Friends Number to show in the widget.
        $max_friends = yz_option( 'yz_wg_max_friends_items', 5 );

        // Get User Friends List.
        $user_friends = apply_filters( 'yz_friends_get_friend_user_ids', friends_get_friend_user_ids( bp_displayed_user_id() ) );

        // Limit Friends Number
        $friend_ids = array_slice( $user_friends, 0, $max_friends );

        // Get Widget Class.
        $list_class = array( 
            'yz-profile-friends-widget',
            'yz-items-' . $widget_layout . '-widget',
            'yz-profile-' . $widget_layout . '-widget',
            'yz-list-avatar-circle'
        );

        ?>

        <div class="<?php echo yz_generate_class( $list_class ); ?>">

        <div class="yz-list-inner">
            
            <?php foreach ( $friend_ids as $friend_id ) : ?>
                
            <div <?php if ( 'avatars' == $widget_layout ) echo 'data-yztooltip="' . bp_core_get_user_displayname( $friend_id )  . '"'; ?> class="yz-list-item">

                <a href="<?php echo bp_core_get_user_domain( $friend_id ); ?>" class="yz-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $friend_id, 'type' => 'full', 'width' => '60px', 'height' => '60px' ) ); ?></a>

                <?php if ( 'list' == $widget_layout ) : ?>

                    <div class="yz-item-data">
                        <a href="<?php echo bp_core_get_user_domain( $friend_id ); ?>" class="yz-item-name"><?php echo bp_core_get_user_displayname( $friend_id ); ?><?php yz_the_user_verification_icon( $friend_id ); ?></a>
                        <div class="yz-item-meta">
                            <div class="yz-meta-item">@<?php echo bp_core_get_username( $friend_id ); ?></div>
                        </div>
                    </div>

                <?php endif; ?>
                
            </div>

            <?php endforeach; ?>

            <?php if ( $friends_nbr > $max_friends ) : ?>
                <?php $more_nbr = $friends_nbr - $max_friends; ?>
                <?php $more_title = ( 'list' == $widget_layout ) ? sprintf( __( 'Show All Friends ( %s )', 'youzer' ), $friends_nbr ) : '+' . $more_nbr; ?>
                <div class="yz-more-items" <?php if ( 'avatars' == $widget_layout ) echo 'data-yztooltip="' . __( 'Show All Friends', 'youzer' )  . '"'; ?>>
                    <a href="<?php echo bp_core_get_user_domain( bp_displayed_user_id() ) . bp_get_friends_slug();?>"><?php echo $more_title; ?></a>
                </div>
            <?php endif; ?>

        </div>
        </div>
        
        <?php
    }

}