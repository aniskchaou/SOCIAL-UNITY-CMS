<?php

require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-infos-boxes.php';

/**
 * Profile Structure Settings.
 */
function yz_profile_structure_settings() {

    // Profile Structure Script
    wp_enqueue_script( 'yz-profile-structure', YZ_AA . 'js/yz-profile-structure.min.js', array( 'jquery' ), false, true );

    wp_localize_script( 'yz-profile-structure', 'Yz_Profile_Structure', array(
		'show_wg' => __( 'Show Widget', 'youzer' ),
		'hide_wg' => __( 'Hide Widget', 'youzer' )
	) );

    /**
     * Install Widgets
     */

    if ( ! get_option( 'yzc_install_new_widgets_login' ) ) {

        $wgs = yz_options( 'yz_profile_sidebar_widgets' );

        if ( ! empty( $wgs ) && ! isset( $wgs['login'] ) ) {
	        $wgs = array( 'login' => 'visible' ) + $wgs;
	        update_option( 'yz_profile_sidebar_widgets', $wgs );
        }

        update_option( 'yzc_install_new_widgets_login', 1 );

    }

    global $Yz_Settings;

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Profile Columns Layouts', 'youzer' ),
            // 'class' => 'uk-center-layouts',
            'type'  => 'openBox'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz_profile_layout',
            'type'  => 'imgSelect',
            'opts'  => array( 'profile-left-sidebar' => 'yz-left-sidebar', 'profile-3columns' => 'yz-3columns', 'profile-right-sidebar' => 'yz-right-sidebar')
        )
    );

    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Profile Main Sidebar', 'youzer' ),
            'type'  => 'openBox',
            'class'	=> 'yz-profile-main-sidebar hide-by-default'
        )
    );

    echo '<p>' . __( "Select which sidebar should appear on the profile tabs that do not support 3 columns, you still can keep 3 colmns layout but it's not recommended as some other tabs requires much more space.", 'youzer' ) . '</p>';

    $Yz_Settings->get_field(
        array(
            'type'  => 'imgSelect',
            'id'    => 'yz_profile_main_sidebar',
            'opts'  => array( 'profile-left-sidebar' => 'yz-left-sidebar', 'profile-3columns' => 'yz-3columns', 'profile-right-sidebar' => 'yz-right-sidebar')
        )
    );

    $Yz_Settings->get_field(
        array(
            'title' => __( 'Vertical Header Position', 'youzer' ),
            'opts'  => array( 'left' => __( 'Left', 'youzer' ), 'right' => __( 'Right', 'youzer' ) ),
            'desc'  => __( 'Set which side you wanna display the header, this option work only on vertical header layouts.', 'youzer' ),
            'id'    => 'yz_profile_vertical_header_position',
            'type'  => 'select'
        )
    );
    $Yz_Settings->get_field( array( 'type' => 'closeBox' ) );

    // $Yz_Settings->get_field(
    //     array(
    //         'msg_type' => 'info',
    //         'type'     => 'msgBox',
    //         'title'    => __( 'info', 'youzer' ),
    //         'id'       => 'yz_msgbox_profile_structure',
    //         'msg'      => __( 'You have to know that theses widgets <strong>( Email, Website, Address, Phone Number, Recent Posts , Keep In Touch )</strong> can\'t be moved to the <strong>"Main Widgets"</strong> column.', 'youzer' )
    //     )
    // );

	// $default_widgets = youzer_widgets();

	// $main_colomn = array(
	// 	'option_id' => 'yz_profile_main_widgets',
	// 	'class' => 'yz-main-wgs',
	// 	'data' => 'main_widgets',
	// 	'title' => __( 'Main Widgets', 'youzer' )
	// );

    // Profile Widgets
	echo '<div class="yz-profile-structure yz-cs-content" data-layout="'. yz_option( 'yz_profile_layout', 'profile-right-sidebar' ) .'">';

	// Left Sidebar.
	yz_get_column_area( array(
		'option_id' => 'yz_profile_left_sidebar_widgets',
		'class' => 'yz-sidebar-wgs yz-left-sidebar',
		'data' => 'left_sidebar_widgets',
		'title' => __( 'Left Sidebar Widgets', 'youzer' )
	) );

	// Main Column.
	yz_get_column_area( array(
		'option_id' => 'yz_profile_main_widgets',
		'class' => 'yz-main-wgs',
		'data' => 'main_widgets',
		'title' => __( 'Main Widgets', 'youzer' )
	) );

	// Right Sidebar.
	yz_get_column_area( array(
		'option_id' => 'yz_profile_sidebar_widgets',
		'class' => 'yz-sidebar-wgs yz-right-sidebar',
		'data' => 'sidebar_widgets',
		'title' => __( 'Right Sidebar Widgets', 'youzer' )
	) );

	// Get Sidebar Widegts
	// yz_profile_left_sidebar_widgets_area( $default_widgets );

	// Get Main Widegts
	// $main_widgets = yz_profile_main_widgets_area( $default_widgets );

	// Get Sidebar Widegts
	// yz_profile_right_sidebar_widgets_area( $default_widgets, $main_widgets );

	echo '<input type="hidden" name="yz_profile_stucture" value="true">';

	echo '</div>';
}

/**
 * Get Sidebar Area
 */
function yz_get_column_area( $options ) {

	// Get Current Main Widgets
	$widgets = yz_options( $options['option_id'] );

	?>

	<div class="yz-profile-wg <?php echo $options['class'] ?>">
		<div class="yz-wgs-inner-content">
			<h2 class="yz-profile-wg-title"><?php echo $options['title']; ?></h2>
			<ul class="yz-draggable-area" data-widgets-type="<?php echo $options['data']; ?>"><?php

			if ( ! empty( $widgets ) ) {

			foreach ( $widgets as $widget_name => $visibility ) {

				// Get Args.
				$args = yz_get_profile_widget_args( $widget_name );

				if ( $args['id'] == 'ad' ) {
					$ads = yz_option( 'yz_ads' );
    				$args['name'] = sprintf( '%1s <span class="yz-ad-flag">%2s</span>', $ads[ $widget_name ]['title'], __( 'ad', 'youzer' ) );
    			}

				// Print Widget
				yz_profile_structure_template( array(
					'icon_title' => ( 'visible' == $visibility ) ? __( 'Hide Widget', 'youzer' ) : __( 'Show Widget', 'youzer' ),
					'id'	=> $widget_name,
					'icon'	=> $args['icon'],
					'name'	=> $args['name'],
					'status' => $visibility,
					'class'	=> ( 'invisible' == $visibility ) ? 'yz-hidden-wg' : '',
					'input_name' => "{$options['option_id']}[$widget_name]",
				) );
			}

			}

			?></ul>
		</div>
	</div>

	<?php

}

/**
 * Profile Structure Template.
 */
function yz_profile_structure_template( $args ) {

	?>

	<li class="<?php echo $args['class']; ?>" data-widget-name="<?php echo $args['id']; ?>">
		<h3 data-hidden="<?php _e( 'Hidden', 'youzer' ); ?>">
			<i class="<?php echo $args['icon']; ?>"></i>
			<?php echo $args['name']; ?>
		</h3>
		<a class="yz-hide-wg" title="<?php echo $args['icon_title']; ?>"></a>
		<input class="yz_profile_widget" type="hidden" name="<?php echo $args['input_name']; ?>" value="<?php echo !empty( $args['status'] ) ? $args['status'] : 'visible'; ?>">
	</li>

	<?php
}