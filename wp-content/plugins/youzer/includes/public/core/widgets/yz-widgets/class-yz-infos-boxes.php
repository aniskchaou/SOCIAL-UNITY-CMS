<?php

class YZ_Info_Box {

    /**
     * # Content.
     */
    function widget( $args ) {

        // Get Field  Id.
        $field_id = yz_option( $args['option_id'] );

        if ( ! empty( $field_id ) && bp_is_active( 'xprofile' ) ) {
        
            // Get Hidden Fields.
            $hidden_fields = bp_xprofile_get_hidden_fields_for_user();

            if ( in_array( $field_id, $hidden_fields ) )  {
                return false;
            }

            // Get Field Data.
            $field = new BP_XProfile_Field( $field_id );
            
            // Get Field Value.
            $value = maybe_unserialize( BP_XProfile_ProfileData::get_value_byid( $field_id, bp_displayed_user_id() ) ); 

            // bp_get_profile_field_data( array( 'user_id' => (), 'field' => $field_id ) );
            
            // Get Field Title.
            $title = $field->name;

        } else {

            // Get Field Title.
            $title = $args['box_title'];

            // Get Field Value.
            $value = yz_get_xprofile_field_value( $args['box_id'] );

        }

        // Hide Box if there's no content.
        if ( empty( $value ) ) {
            return false;
        }

        yz_styling()->gradient_styling( array(
            'selector'      => '.yz-box-' . $args['box_class'],
            'left_color'    => 'yz_ibox_' . $args['box_class'] . '_bg_left',
            'right_color'   => 'yz_ibox_' . $args['box_class'] . '_bg_right'
        ) );

		?>

		<div class="yz-infobox-content <?php echo "yz-box-" . $args['box_class']; ?>">
			<div class="yz-box-head">
				<div class="yz-box-icon">
					<i class="<?php echo $args['box_icon']; ?>"></i>
				</div>
				<h2 class="yz-box-title"><?php echo $title; ?></h2>
			</div>
			<div class="yz-box-content">
				<p><?php echo apply_filters( 'bp_get_profile_field_data', $value ); ?></p>
			</div>
		</div>

		<?php

	}

}