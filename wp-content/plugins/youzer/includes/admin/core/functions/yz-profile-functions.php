<?php

/**
 * Xprofile Fields Functions.
 */
function yz_add_fields_group_icon_field() {

	// Get Group ID
	$group_id = isset( $_GET['group_id'] ) ? $_GET['group_id'] : null;

	// Get Group Icon. 
	$icon = yz_get_xprofile_group_icon( $group_id );

	?>

	<div class="postbox">
		<h2><?php _e( 'Field Group Icon', 'youzer' ); ?></h2>
		<div class="inside">
			<div id="fields_group_icon" class="ukai_iconPicker" data-icons-type="web_application">
				<div class="ukai_icon_selector">
					<i class="<?php echo $icon; ?>"></i>
					<span class="ukai_select_icon">
						<i class="fas fa-sort-down"></i>
					</span>
				</div>
				<input type="hidden" class="ukai-selected-icon" name="fields_group_icon" value="<?php echo $icon; ?>">
			</div>
		</div>
	</div>

	<?php

}

add_action( 'xprofile_group_before_submitbox', 'yz_add_fields_group_icon_field' );

/**
 * Save Fields Group Icon
 */
function yz_save_xprofile_group_icon( $group ) {

	// Get Group Icon.
	$group_icon = $_POST['fields_group_icon'];
	
	// Save Group Icon.
	update_option( 'yz_xprofile_group_icon_' . $group->id , $group_icon, 'no' );

}

add_action( 'xprofile_groups_saved_group', 'yz_save_xprofile_group_icon' );

/**
 * Xprofile Groups Script
 */
function yz_xprofile_groups_scripts( $hook ) {

    if ( isset( $_GET['page'] ) && 'bp-profile-setup' == $_GET['page'] ) {
        wp_enqueue_style( 'yz-icons' );
        yz_iconpicker_scripts();
    }

}

add_action( 'admin_enqueue_scripts','yz_xprofile_groups_scripts', 10, 1 );