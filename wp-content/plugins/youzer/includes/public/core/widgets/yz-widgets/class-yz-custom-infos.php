<?php

class YZ_Custom_Infos {

    // public $args;

    public function __construct() {
        // $this->args = $args;
        add_action( 'yz_profile_widgets_edit_link', array( $this, 'yz_set_xprofile_widgets_settings_edit_url' ), 10, 2 );
    }

    /**
     * Custom Info Args.
     */
    // function args() {
    //     return $this->args;
    // }

    /**
     * # Custom Informations.
     */
    function widget() {

        do_action( 'bp_before_profile_field_content' ); ?>

        <div class="yz-infos-content">

            <?php while ( bp_profile_fields() ) : bp_the_profile_field(); ?>

                <?php if ( bp_field_has_data() ) : ?>

                    <div <?php bp_field_css_class( 'yz-info-item' ); ?>>

                        <div class="yz-info-label"><?php bp_the_profile_field_name(); ?></div>
                        <div class="yz-info-data"><?php bp_the_profile_field_value(); ?></div>

                    </div>

                <?php endif; ?>

                <?php do_action( 'bp_profile_field_item' ); ?>

            <?php endwhile; ?>

        </div>

        <?php

        do_action( 'bp_after_profile_field_content' ); 

    }

    /**
     * Set Xprofile widgets edit url.
     */
    function yz_set_xprofile_widgets_settings_edit_url( $edit_url, $widget_name ) {

        // if ( ! bp_is_active( 'xprofile' )  ) {
        //     return $edit_url;
        // }
        if ( 'custom_infos' == $widget_name ) {
            $group_id = bp_get_the_profile_group_id();
            return yz_get_profile_settings_url( 'edit/group/' . $group_id );
        }

        return $edit_url;

    }

}