<?php

/**
 * Login Widget
 */

class logy_login_widget extends WP_Widget {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		parent::__construct(
			'logy_login_widget',
			__( 'Youzer - Login', 'youzer' ),
			array( 'description' => __( 'Youzer login widget', 'youzer' ) )
		);

	}

	/**
	 * Login Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

		// Print Form
		echo '<div class="logy-login-widget">';
		$this->logy->form->get_form( 'login' );
		echo '</div>';

	}

	/**
	 * Login Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show automatically the login box', 'youzer' ) . '</p>';
	}

}

/**
 * Register Widget
 */

class logy_register_widget extends WP_Widget {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		parent::__construct(
			'logy_register_widget',
			__( 'Youzer - Register', 'youzer' ),
			array( 'description' => __( 'Youzer register widget', 'youzer' ) )
		);

	}

	/**
	 * Register Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

		$bp = buddypress();

		// Init Step.
		if ( empty( $bp->signup->step ) ) {
		    $bp->signup->step = 'request-details';
		}

		// Print Form
		echo '<div class="logy-register-widget">';
		require_once LOGY_TEMPLATE . 'members/register.php';
		echo '</div>';
	}

	/**
	 * Register Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show automatically the register box', 'youzer' ) . '</p>';
	}

}

/**
 * Reset Password Widget
 */

class logy_reset_password_widget extends WP_Widget {

	protected $logy;

	function __construct() {

		global $Logy;

    	$this->logy = &$Logy;

		parent::__construct(
			'logy_reset_password_widget',
			__( 'Youzer - Reset Password', 'logy' ),
			array( 'description' => __( 'Youzer reset password widget', 'youzer' ) )
		);
	}

	/**
	 * Reset Password Widget Content
	 */
	public function widget( $args, $instance ) {

		//  is user is logged-in hide Form.
		if ( is_user_logged_in() ) {
			return false;
		}

		// Print Form
		echo '<div class="logy-reset-password-widget">';
		$this->logy->form->get_form( 'lost_password' );
		echo '</div>';
	}

	/**
	 * Reset Password Widget Backend
	 */
	public function form( $instance ) {
		echo '<p>' . __( 'This widget will show automatically the reset password box', 'logy' ) . '</p>';
	}

}