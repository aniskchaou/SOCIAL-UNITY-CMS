<?php

class Youzer_Author {

	/**
	 * Instance of this class.
	 */
	protected static $instance = null;

	/**
	 * Return the instance of this class.
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	function __construct() { /** Do Nothing Here **/ }

	/**
	 * Author Box
	 */
	function get_author_box( $args ) {

		// Get User Id.
		$user_id = isset( $args['user_id'] ) ? $args['user_id'] : bp_displayed_user_id();

		?>

		<div class="yzb-author <?php echo $this->get_cover_class( $args ); ?>">

			<?php yz_get_user_tools( $user_id ) ?>

			<!-- Box Content -->
			<?php $this->get_elements( $args ); ?>

		</div>

		<?php

	}

	/**
	 * Author Box Structure
	 */
	function get_box_structure( $layout = null ) {

		// Set Up New Array.
		$structure = array();

		$structure['yzb-author-v1'] = array(
			'cover'	  => array( 'photo' ),
			'content' => array( 'box_head', 'badges', 'ratings', 'buttons', 'networks', 'statistics' )
		);

		$structure['yzb-author-v2'] = array(
			'cover'	  => array( 'photo' ),
			'content' => array( 'box_head', 'badges', 'ratings', 'buttons', 'statistics', 'networks' )
		);

		$structure['yzb-author-v3'] = array(
			'cover'	  => array( 'photo', 'box_head' ),
			'content' => array( 'ratings', 'badges', 'statistics' , 'buttons', 'networks' )
		);

		$structure['yzb-author-v4'] = array(
			'cover'	  => array( 'photo', 'box_head' ),
			'content' => array( 'ratings', 'badges', 'buttons', 'networks', 'statistics' )
		);

		$structure['yzb-author-v5'] = array(
			'content' => array( 'photo', 'box_head', 'badges', 'ratings', 'buttons', 'statistics', 'networks' )
		);

		$structure['yzb-author-v6'] = array(
			'cover'	=> array( 'photo', 'box_head', 'ratings', 'badges', 'buttons', 'networks', 'statistics' )
		);

		$structure = apply_filters( 'yz_get_author_box_structure', $structure, $layout );

		return $structure[ $layout ];

	}

	/**
	 * Author Box Elements Generator
	 */
	function get_elements( $args = null ) {

		$elements = array( 'cover', 'content' );

		// Get Header Structure
		$header_args = $this->get_box_structure( $args['layout'] );

		foreach ( $elements as $element ) :

			if ( isset( $header_args[ $element ] ) ) :

				if ( 'cover' == $element ) {
					$cover = yz_users()->cover( $args['user_id'] );
					echo "<div class='yz-header-cover'>$cover";
				} elseif ( 'content' == $element ) {
					echo "<div class='yzb-author-content'>";
				}

				echo '<div class="yz-inner-content">';
				foreach ( $header_args[ $element ] as $element ) {
					do_action( 'yz_before_author_box_' . $element, $args );
					$function = "get_box_$element";
					yz_users()->$element( $args, $args['user_id'] );
					do_action( 'yz_after_author_box_' . $element, $args );
				}
				echo '</div>';

				echo '</div>';

			endif;

		endforeach;
	}

	/**
	 * Cover Class
	 */
	function get_cover_class( $args ) {

		// Create Empty Array.
		$cover_class = array();

		// Get Box Layout.
		$cover_class[] = $args['layout'];

		// Add header cover overlay.
		if ( 'on' == $args['cover_overlay'] ) {
			$cover_class[] = 'yz-header-overlay';
		}

		// Add header cover pattern.
		if ( 'on' == $args['cover_pattern'] ) {
			$cover_class[] = 'yz-header-pattern';
		}

	 	// Return Class Name.
		return yz_generate_class( $cover_class );
	}

}

/**
 * Get a unique instance of Author Box.
 */
function yz_author_box() {
	return Youzer_Author::get_instance();
}

/**
 * Launch Youzer Author Box!
 */
yz_author_box();