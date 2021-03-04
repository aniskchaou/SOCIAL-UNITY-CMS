<?php

class YZ_User_Tags {

    public function __construct() {
    }

    /**
     * # Content.
     */
    function widget() {

        if ( ! bp_is_active( 'xprofile' ) ) {
            return;
        }
        // Get Slides.
        $tags = yz_option( 'yz_user_tags' );

        if ( empty( $tags ) ) {
            return;
        }


        // Get Data.
        $tags_content = '';
        $display_icon  = yz_option( 'yz_enable_user_tags_icon', 'on' );
        $border_type   = yz_option( 'yz_wg_user_tags_border_style', 'radius' );
        $display_desc  = yz_option( 'yz_enable_user_tags_description', 'on' );
        
        global $field;

        foreach ( $tags as $tag ) :

        // Get Data
        $field = xprofile_get_field( $tag['field'],  bp_displayed_user_id() );

        // Unserialize Profile field
        $field_values = maybe_unserialize( $field->data->value );

        if ( empty( $field_values ) ) {
            continue;
        }

        ob_start();
    

        ?>

        <div class="yz-utag-item yz-utag-item-<?php echo $field->id; ?>">
            <div class="yz-utag-name">
                <?php if ( 'on' == $display_icon ) : ?><i class="<?php echo apply_filters( 'yz_user_tags_name_icon', $tag['icon'] ); ?>"></i><?php endif; ?>
                <?php echo stripslashes_deep( $tag['name'] ); ?>
            </div>
            <?php if ( 'on' == $display_desc && ! empty( $tag['description'] ) ) : ?>
            <div class="yz-utag-description"><?php echo $tag['description']; ?></div>
            <?php endif; ?>
            <div class="yz-utag-values yz-utags-border-<?php echo $border_type; ?>">
                <?php foreach( (array) $field_values as $key => $value ) : ?>
                    <?php $value = apply_filters( 'bp_get_the_profile_field_value', $value, $field->type,  $field->id ); ?>
                        <div class="yz-utag-value-item yz-utag-value-<?php echo $key; ?>"><?php echo $value; ?></div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php

            $content = ob_get_contents();

            ob_end_clean(); 

            $tags_content .= $content;

        endforeach;

        if ( empty( $tags_content ) ) {
            return;
        }

        ?>

        <div class="yz-user-tags"><?php echo $tags_content ?></div>

        <?php
    }

}