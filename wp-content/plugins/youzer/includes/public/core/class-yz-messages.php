<?php

class Youzer_Messages {

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

			// Actions.
			add_action( 'bp_init', array( self::$instance, 'hide_emoji_from_content' ) );

		}

		return self::$instance;

	}


	/**
	 * Strip Emoji From Content.
	 */
	function hide_emoji_from_content() {
	 
	    // Hide Messages Emoji.
	    if ( 'off' == yz_option( 'yz_enable_messages_emoji', 'on' ) ) {
	        add_filter( 'bp_get_the_thread_message_content', 'yz_remove_emoji' );
	        add_filter( 'bp_get_message_thread_excerpt', 'yz_remove_emoji' );
	        add_filter( 'bp_get_message_notice_text', 'yz_remove_emoji' );
	    }

	}

}


/**
 * Get a unique instance of Youzer Messages.
 */
function yz_messages() {
	return Youzer_Messages::get_instance();
}

/**
 * Launch Youzer Groups!
 */
yz_messages();