<?php

/**
 * Community Media Widget
 */
class YZ_Community_Media_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_media',
			__( 'Youzer - Media', 'youzer' ),
			array( 'description' => __( 'Community media widget', 'youzer' ) )
		);
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance,
	    	array(
		    	'title' => __( 'Community Media', 'youzer' ),
		        'photos_number' => 9,
		        'videos_number' => 9,
		        'audios_number' => 6,
		        'files_number' => 6,
		        'item_id' => '',
		        'type' => 'community',
		        'filters' => 'photos,videos,audios,files'
	    	)
	    );

	    $options = array(
	    	'user' => __( 'User Media', 'youzer' ),
	    	'group' => __( 'Group Media', 'youzer' ),
	    	'community' => __( 'Community Media', 'youzer' ),
	    	'loggedin_user' => __( 'Logged-in User Media', 'youzer' ),
	    	'displayed_group' => __( 'Displayed Group Media', 'youzer' )
	    );

		?>

		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'Title:', 'youzer' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>">
		</p>

	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'type' ) ); ?>" style="display: block; margin-bottom: 10px;"><?php esc_attr_e( 'Feed Type:', 'youzer' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'type' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'type' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $options as $options_id => $option_name ) { ?>
	            	<option <?php selected( $instance['type'], $options_id ); ?> value="<?php echo $options_id; ?>"><?php echo $option_name; ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<p>
			<label for="<?php echo $this->get_field_id( 'filters' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'Filters:', 'youzer' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'filters' ); ?>" name="<?php echo $this->get_field_name( 'filters' ); ?>" type="text" value="<?php echo esc_attr( $instance['filters'] ); ?>">
			<p><?php _e( 'You can change the order of filters or remove some. The allowed filters names are photos, videos, audios, files', 'youzer' ); ?></p>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'item_id' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'User or Group ID:', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'item_id' ); ?>" name="<?php echo $this->get_field_name( 'item_id' ); ?>" type="text" value="<?php echo esc_attr( $instance['item_id'] ); ?>">
			<p><?php _e( 'Works If you choosed feed type User Media or Group Media.', 'youzer' ); ?></p>
		</p>


		<p>
			<label for="<?php echo $this->get_field_id( 'photos_number' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'Photos Number:', 'youzer' ); ?>
			</label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'photos_number' ); ?>" name="<?php echo $this->get_field_name( 'photos_number' ); ?>" type="number" value="<?php echo esc_attr( $instance['photos_number'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'videos_number' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'Videos Number:', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'videos_number' ); ?>" name="<?php echo $this->get_field_name( 'videos_number' ); ?>" type="number" value="<?php echo esc_attr( $instance['videos_number'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'audios_number' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'Audios Number:', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'audios_number' ); ?>" name="<?php echo $this->get_field_name( 'audios_number' ); ?>" type="number" value="<?php echo esc_attr( $instance['audios_number'] ); ?>">
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'files_number' ); ?>" style="display: block; margin-bottom: 10px;"><?php _e( 'Files Number:', 'youzer' ); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id( 'files_number' ); ?>" name="<?php echo $this->get_field_name( 'files_number' ); ?>" type="number" value="<?php echo esc_attr( $instance['files_number'] ); ?>">
		</p>
		<?php
	}

	/**
	 * Update Fields
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance = $old_instance;
		$instance['type' ] = $new_instance['type'];
		$instance['item_id'] = absint( $new_instance['item_id'] );
		$instance['title'] 	 = strip_tags( $new_instance['title'] );
		$instance['filters'] = str_replace( ' ', '', $new_instance['filters'] );
		$instance['photos_number'] = absint( $new_instance['photos_number'] );
		$instance['videos_number'] = absint( $new_instance['videos_number'] );
		$instance['audios_number'] = absint( $new_instance['audios_number'] );
		$instance['files_number' ] = absint( $new_instance['files_number'] );

		return $instance;
	}

	/**
	 * Widget Content
	 */
	public function widget( $args, $instance ) {

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) ) {
			echo $args['before_title'];
			echo apply_filters( 'widget_title', $instance['title'] );
			echo $args['after_title'];
		}

		switch ( $instance['type'] ) {
			case 'loggedin_user':
				$type = 'user_id="' . bp_loggedin_user_id()  . '"';
				break;

			case 'displayed_group':
				$type = 'group_id="' . bp_get_current_group_id()  . '"';
				break;

			case 'user':
				$type = 'user_id="' . $instance['item_id'] . '"';
				break;

			case 'group':
				$type = 'group_id="' . $instance['item_id']  . '"';
				break;

			default:
				$type = '';
				break;
		}

		echo do_shortcode( "[youzer_media $type filters='{$instance['filters']}' photos_number='{$instance['photos_number']}' videos_number='{$instance['videos_number']}' audios_number='{$instance['audios_number']}' files_number='{$instance['files_number']}']" );

		echo $args['after_widget'];

	}

}