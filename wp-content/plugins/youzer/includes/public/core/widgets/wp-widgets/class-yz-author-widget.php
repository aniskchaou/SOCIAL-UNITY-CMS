<?php

/**
 * Static Author Box Widget
 */

class YZ_Author_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_author_widget',
			__( 'Youzer - Static Author', 'youzer' ),
			array( 'description' => __( 'Static author box widget', 'youzer' ) )
		);
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Default Widget Settings
	    $defaults = array(
			'user_id'				  => '1',
	        'hide_title' 			  => 'on',
	        'title' 				  => __( 'Post Author', 'youzer' ),
	        'layout' 				  => yz_option( 'yz_author_layout', 'yzb-author-v1' ),
	        'meta_icon' 			  => yz_option( 'yz_author_meta_icon', 'fas fa-map-marker' ),
	        'meta_type' 			  => yz_option( 'yz_author_meta_type', 'full_location' ),
	        'networks_icons_type'  	  => yz_option( 'yz_author_sn_bg_type', 'silver' ),
	        'networks_icons_style' 	  => yz_option( 'yz_author_sn_bg_style', 'radius' ),
	        'show_cover_pattern' 	  => yz_option( 'yz_enable_author_pattern', 'on' ),
	        'show_cover_overlay' 	  => yz_option( 'yz_enable_author_overlay', 'on' ),
	        'statistics_silver_bg' 	  => yz_option( 'yz_author_use_statistics_bg', 'on' ),
	        'show_statistics_borders' => yz_option( 'yz_author_use_statistics_borders', 'on' ),
	    );

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance, $defaults );

	    // Get Input's Data.
		$meta_types = yz_get_panel_profile_fields();
		$networks_icons_types = array( 'silver' => __( 'Silver', 'youzer' ), 'colorful' => __( 'Colorful', 'youzer' ), 'transparent' => __( 'Transparent', 'youzer' ), 'no-bg' => __( 'No Background', 'youzer' ) );
		$networks_icons_styles = array( 'flat' => __( 'Flat', 'youzer' ), 'radius' => __( 'Radius', 'youzer' ), 'circle' => __( 'Circle', 'youzer' ) );

		?>

		<!-- Widget Title. -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<!-- Hide Widget Title-->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['hide_title'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'hide_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'hide_title' ) ); ?>">
	        <label for="<?php echo $this->get_field_id( 'hide_title' ); ?>"><?php _e( 'Hide Widget Title', 'youzer' ); ?></label>
    	</p>

		<!-- Default User ID. -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>"><?php esc_attr_e( 'Default User ID', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_id' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['user_id'] ); ?>">
		</p>

		<!-- Author Display Cover Overlay -->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['show_cover_overlay'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_cover_overlay' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_cover_overlay' ) ); ?>">
	        <label for="<?php echo $this->get_field_id( 'show_cover_overlay' ); ?>"><?php _e( 'Show Cover Overlay', 'youzer' ); ?></label>
    	</p>

		<!-- Author Display Cover Pattern-->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['show_cover_pattern'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_cover_pattern' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_cover_pattern' ) ); ?>">
	        <label for="<?php echo $this->get_field_id( 'show_cover_pattern' ); ?>"><?php _e( 'Show Cover Pattern', 'youzer' ); ?></label>
    	</p>

		<!-- Author Box Layout-->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'layout' ) ); ?>"><?php esc_attr_e( 'Author Box Layout', 'youzer' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'layout' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'layout' ) ); ?>" class="widefat" style="width:100%;">
	            <?php for ( $i = 1; $i <= 6; $i++ ) {?>
	            	<option <?php selected( $instance['layout'], 'yzb-author-v' . $i ); ?> value="<?php echo 'yzb-author-v' . $i; ?>"><?php echo sprintf( __( 'Layout Version %d', 'youzer' ), $i ); ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<!-- Author Meta types-->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_type' ) ); ?>"><?php esc_attr_e( 'Author Meta Type', 'youzer' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'meta_type' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'meta_type' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $meta_types as $meta_id => $meta_name ) { ?>
	            	<option <?php selected( $instance['meta_type'], $meta_id ); ?> value="<?php echo $meta_id; ?>"><?php echo $meta_name; ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<!-- Author Meta Icon -->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'meta_icon' ) ); ?>"><?php esc_attr_e( 'Author Meta Icon', 'youzer' ); ?></label>
			<div id="<?php echo $this->get_field_id( 'meta_icon' ); ?>" class="ukai_iconPicker" data-icons-type="web_application">
				<div class="ukai_icon_selector">
					<i class="<?php echo $instance['meta_icon']; ?>"></i>
					<span class="ukai_select_icon">
						<i class="fas fa-sort-down"></i>
					</span>
				</div>
				<input type="hidden" class="ukai-selected-icon" name="<?php echo esc_attr( $this->get_field_name( 'meta_icon' ) ); ?>" value="<?php echo $instance['meta_icon']; ?>">
			</div>

	    </p>

		<!-- Author Networks Background types-->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'networks_icons_type' ) ); ?>"><?php esc_attr_e( 'Networks Icons Type', 'youzer' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'networks_icons_type' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'networks_icons_type' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $networks_icons_types as $bg_id => $by_name ) { ?>
	            	<option <?php selected( $instance['networks_icons_type'], $bg_id ); ?> value="<?php echo $bg_id; ?>"><?php echo $by_name; ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<!-- Author Networks Icons Styles-->
	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'networks_icons_style' ) ); ?>"><?php esc_attr_e( 'Networks Icons Style', 'youzer' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'networks_icons_style' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'networks_icons_style' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $networks_icons_styles as $style_id => $style_name ) { ?>
	            	<option <?php selected( $instance['networks_icons_style'], $style_id ); ?> value="<?php echo $style_id; ?>"><?php echo $style_name; ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<!-- Author Display Statistics Borders-->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['show_statistics_borders'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'show_statistics_borders' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'show_statistics_borders' ) ); ?>">
	        <label for="<?php echo $this->get_field_id( 'show_statistics_borders' ); ?>"><?php _e( 'Show Statistics Borders', 'youzer' ); ?></label>
    	</p>


		<!-- Author Display Statistics Silver Background-->
		<p>
	        <input class="checkbox" type="checkbox" <?php checked( $instance['statistics_silver_bg'], 'on' ); ?> id="<?php echo esc_attr( $this->get_field_id( 'statistics_silver_bg' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'statistics_silver_bg' ) ); ?>">
	        <label for="<?php echo $this->get_field_id( 'statistics_silver_bg' ); ?>"><?php _e( 'Show Statistics Silver Background', 'youzer' ); ?></label>
    	</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		// Update Values.
		$instance['user_id'] = ( ! empty( $new_instance['user_id'] ) ) ? strip_tags( $new_instance['user_id'] ) : '1';
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['layout'] = ( ! empty( $new_instance['layout'] ) ) ? strip_tags( $new_instance['layout'] ) : 'yzb-author-v1';
		$instance['meta_type'] = ( ! empty( $new_instance['meta_type'] ) ) ? strip_tags( $new_instance['meta_type'] ) : 'username';
		$instance['meta_icon'] = $new_instance['meta_icon'];
		$instance['networks_icons_style'] = ( ! empty( $new_instance['networks_icons_style'] ) ) ? strip_tags( $new_instance['networks_icons_style'] ) : 'circle';
		$instance['networks_icons_type'] = ( ! empty( $new_instance['networks_icons_type'] ) ) ? $new_instance['networks_icons_type'] : 'colorful';

		// Save Checkbox Values.
		$instance['hide_title'] = $new_instance['hide_title'];
		$instance['show_cover_overlay'] = $new_instance['show_cover_overlay'];
		$instance['show_cover_pattern'] = $new_instance['show_cover_pattern'];
		$instance['statistics_silver_bg'] = $new_instance['statistics_silver_bg'];
		$instance['show_statistics_borders'] = $new_instance['show_statistics_borders'];

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		// Get Data.
		$hide_title = isset( $instance['hide_title'] ) ? $instance['hide_title'] : 'off';
		$show_cover_overlay = $instance['show_cover_overlay'] ? $instance['show_cover_overlay'] : 'off';
		$show_cover_pattern = $instance['show_cover_pattern'] ? $instance['show_cover_pattern'] : 'off';
		$statistics_silver_bg = $instance['statistics_silver_bg'] ? $instance['statistics_silver_bg'] : 'off';
		$show_statistics_borders = $instance['show_statistics_borders'] ? $instance['show_statistics_borders'] : 'off';

		// Get Post Author.
		$user_id = $instance['user_id'] ? $instance['user_id'] : '1';

		if ( 'off' == $hide_title ) {
			echo $args['before_widget'];
		}

		// Get Widget Title
		if ( ! empty( $instance['title'] ) && 'off' == $hide_title ) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
		}

		// Meta Icon
		$meta_icon = isset( $instance['meta_icon'] ) ? $instance['meta_icon'] : 'fas fa-globe';

		// Display Widgets.
		echo '<div class="yz-wp-author-widget yz-post-author-box-widget">';
		echo do_shortcode( "[youzer_author_box user_id='$user_id' layout='{$instance["layout"]}' meta_type='{$instance["meta_type"]}' meta_icon='{$meta_icon}' statistics_bg='$statistics_silver_bg' statistics_border='$show_statistics_borders' networks_type='{$instance["networks_icons_type"]}' networks_format='{$instance["networks_icons_style"]}' cover_overlay='$show_cover_overlay' cover_pattern='$show_cover_pattern']" );
		echo '</div>';

		if ( 'off' == $hide_title ) {
			echo $args['after_widget'];
		}
	}

}