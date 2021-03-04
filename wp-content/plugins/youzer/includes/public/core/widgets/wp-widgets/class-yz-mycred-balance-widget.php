<?php

/**
 * Mycred Balance Box Widget
 */

class YZ_Mycred_Balance_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_mycred_balance_widget',
			__( 'Youzer - User MyCRED Balance', 'youzer' ),
			array( 'description' => __( 'Mycred balance widget', 'youzer' ) )
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
	        'user_id' => false,
	        'title' => __( 'My Balance', 'youzer' ),
	    );

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance, $defaults );

	    // Get Input's Data.
		$meta_types = yz_get_panel_profile_fields();

		?>

		<!-- Widget Title. -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

		<!-- Default User ID. -->
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>"><?php esc_attr_e( 'User ID', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'user_id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'user_id' ) ); ?>" type="number" value="<?php echo esc_attr( $instance['user_id'] ); ?>">
			<p><?php _e( 'keep the user id empty to show the logged-in user balance.', 'youzer' ); ?></p>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		// Update Values.
		$instance['title'] = ! empty( $new_instance['title'] )  ? strip_tags( $new_instance['title'] ) : '';
		$instance['user_id'] = ! empty( $new_instance['user_id'] ) ? strip_tags( $new_instance['user_id'] ) : null;

		return $instance;
	}

	/**
	 * Login Widget Content
	 */
	public function widget( $args, $instance ) {

		// Get Data.
		$user_id = ! empty( $instance['user_id'] ) ? $instance['user_id'] : get_current_user_id();

		if ( empty( $user_id ) ) {
			return;
		}

		// Get title.
		$title = ! empty( $instance['title'] ) ? $instance['title'] : null;

		// Display Widgets.
		echo '<div class="yz-mycred-balance-box-widget">';
		yz_mycred_get_user_balance_box( $user_id, $title );
		echo '</div>';

	}

}