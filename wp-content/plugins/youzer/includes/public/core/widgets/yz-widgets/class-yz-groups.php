<?php

class YZ_Groups_Widget {

    /**
     * # Content.
     */
    function widget() {

        if ( ! bp_is_active( 'groups' ) ) {
            return;
        }

        global $groups_template;

        // Back up the global.
        $old_groups_template = $groups_template;

        // Get User Max Groups Number to show in the widget.
        $max_groups = yz_option( 'yz_wg_max_groups_items', 3 );

        $group_args = array(
            'user_id'         => bp_displayed_user_id(),
            'per_page'        => $max_groups,
            'max'             => $max_groups
        );

        if ( bp_has_groups( $group_args ) ) :

        // Get Groups Number.
        $groups_nbr = bp_get_group_total_for_member();

        // Get Widget Class.
        $list_class = array( 'yz-items-list-widget', 'yz-profile-list-widget', 'yz-profile-groups-widget' );

        // Add Widgets Avatars Border Style Class.
        $list_class[] = 'yz-list-avatar-circle'; ?>

        <div class="<?php echo yz_generate_class( $list_class ); ?>">

            <?php while ( bp_groups() ) : bp_the_group(); ?>

                <div class="yz-list-item">

                    <a href="<?php bp_group_permalink(); ?>" class="yz-item-avatar"><?php bp_group_avatar_thumb(); ?></a>

                    <div class="yz-item-data">
                        <a href="<?php bp_group_permalink(); ?>" class="yz-item-name"><?php bp_group_name() ?></a>
                        <div class="yz-item-meta">
                            <div class="yz-meta-item"><?php echo yz_get_group_status( $groups_template->group->status ); ?></div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>

                <?php if ( $groups_nbr > $max_groups ) : ?>
                    <div class="yz-more-items">
                        <a href="<?php echo bp_core_get_user_domain( bp_displayed_user_id() ) . bp_get_groups_slug();?>"><?php echo sprintf( __( 'Show All Groups ( %s )', 'youzer' ), $groups_nbr ); ?></a>
                    </div>
                <?php endif; ?>

        </div>

        <?php

        else: return;

        endif;

        // Back up the global.
        $groups_template = $old_groups_template;

    }

}