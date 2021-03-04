<?php

/**
 * Community Hashtags Widget
 */
class YZ_Community_Hashtags_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'yz_community_hashtags',
			__( 'Youzer - Community Hashtags', 'youzer' ),
			array( 'description' => __( 'Community hashtags widget', 'youzer' ) )
		);
	}

	/**
	 * Back-end widget form.
	 */
	public function form( $instance ) {

	    // Get Widget Data.
	    $instance = wp_parse_args( (array) $instance,
	    	array(
		    	'title' => __( 'Community Hashtags', 'youzer' ),
		        'limit' => '12',
		        'order_by' => 'popular'
	    	)
	    );

	    $options = array(
	    	'popular' => __( 'Popular', 'youzer' ),
	    	'random' => __( 'Random', 'youzer' ),
	    );

		?>

		<!-- Title. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title', 'youzer' ); ?></label>
			<input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" class="widefat" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>

	    <p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'order_by' ) ); ?>"><?php esc_attr_e( 'Order By', 'youzer' ); ?></label>
	        <select id="<?php echo $this->get_field_id( 'order_by' ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'order_by' ) ); ?>" class="widefat" style="width:100%;">
	            <?php foreach( $options as $options_id => $option_name ) { ?>
	            	<option <?php selected( $instance['order_by'], $options_id ); ?> value="<?php echo $options_id; ?>"><?php echo $option_name; ?></option>
	            <?php } ?>
	        </select>
	    </p>

		<!-- Limit Hashtags Number. -->
		<p>
			<label for="<?php echo $this->get_field_id( 'limit' ); ?>"><?php _e( 'Hashtags Number:', 'youzer' ); ?>
				<input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $instance['limit'] ); ?>" style="width: 30%" />
			</label>
		</p>

		<?php
	}

	/**
	 * Sanitize widget form values as they are saved.
	 */
	public function update( $new_instance, $old_instance ) {

		$instance = array();

		$instance = $old_instance;
		$instance['order_by'] = $new_instance['order_by'];
		$instance['limit'] = absint( $new_instance['limit'] );
		$instance['title'] = strip_tags( $new_instance['title'] );

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

		echo do_shortcode( '[youzer_community_hashtags order_by="' . $instance['order_by'] .'" limit="' . $instance['limit'] .'"]' );

		echo $args['after_widget'];

	}

}