<?php

class Youzer_Widgets {

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

	function __construct() {

		// Include WIdgets
		$this->include_widgets();

	 	// Filters.
	 	add_filter( 'yz_display_profile_widget_title', array( $this, 'display_widgets_title_for_profile_owner' ), 10, 2 );

	}

	/**
	 * Include Widgets
	 */
	function include_widgets() {

        // Include Widgets
        require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-infos-boxes.php';

	}

	/**
	 * Get All Widgets
	 */
	function get_all_widgets() {
		return apply_filters( 'yz_profile_widgets_args', array( 'post', 'link', 'quote', 'video', 'flickr', 'skills', 'groups', 'friends', 'project', 'reviews', 'about_me', 'services', 'user_tags', 'portfolio', 'slideshow', 'instagram', 'wall_media', 'user_badges', 'user_balance', 'recent_posts', 'phone', 'email', 'social_networks', 'website', 'address', 'login' ) );
	}

	/**
	 * # Widget Core
	 */
	function check_widget_content( $widget_name, $function_options ) {

		ob_start();

		// if ( isset( $this->$widget_name ) ) {
			$this->$widget_name->widget( $function_options );
		// }

		ob_flush();

		$content = ob_get_contents();

		ob_end_clean();

		return $content;

	}

	/**
	 * # Widget Class Name
	 */
	function yz_widget_class_name( $args ) {

		// Create Empty Array.
		$widget_class = array( 'yz-' . $args['id'] );

		// Prepare Class Name
	    $load_effect = isset( $args['load_effect'] ) ? $args['load_effect'] : null;
	    $widget_class[] = $this->get_loading_effect( $load_effect, 'class' );

	    // Add title class.
		if ( isset( $args['display_title'] ) && $args['display_title'] == 'off' ) {
			$widget_class[] = "without-title";
		}

	    // Add background class.
	    if ( ! in_array( $args['id'], array( 'yz_widget_infos_box', 'ad' ) ) ) {
	    	$widget_class[] = 'yz-white-bg';
	    }

		// Get AD class.
	    if ( 'ad' == $args['id'] ) {
			if ( 'true' == $this->is_sponsored_ad( $args['function_options'] ) ) {
				$widget_class[] = 'yz-white-bg';
			} else {
				$widget_class[] = 'yz-no-bg';
			}
	    }

	    // Title Icon Style.
	    if ( 'on' == yz_option( 'yz_use_wg_title_icon_bg', 'on' ) ) {
			$widget_class[] = 'yz-wg-title-icon-bg';
	    }

		// Return Widget Class Name
		return yz_generate_class( $widget_class );
	}

	/**
	 * Get Loading Effect
	 */
	function get_loading_effect( $load_effect, $data_type = 'data' ) {

		// Check if it's allowed to use loading effects.
		if ( 'on' != yz_option( 'yz_use_effects', 'off' )) {
			return false;
		}

		// Use effect class.
		if ( 'class' == $data_type || 'navbar' == $data_type ) {
			return "yz_effect";
		} elseif ( $data_type == 'data' ) {
			// Get effects data value.
			if ( ! empty( $load_effect ) ) {
				return "data-effect='$load_effect'";
			} else {
				return 'data-effect="fadeIn"';
			}
		}

	}

	/**
	 * Get Widgets Without Front-end Settings
	 */
	function settings_widgets() {
		return apply_filters( 'yz_settings_widgets', array( 'about_me', 'skills', 'portfolio', 'slideshow', 'services', 'project', 'quote', 'link', 'video', 'post', 'instagram', 'flickr' ) );
	}

	/**
	 * Get Settings Widgets
	 */
	function get_settings_widgets() {

		$widgets = array();

		// All Widgets.
		$all_widgets = $this->settings_widgets();

		// if user have no posts don't show the post form.
		if ( ! current_user_can( 'edit_posts') ) {
			if ( ( $key = array_search( 'post', $all_widgets ) ) !== false ) {
			    unset( $all_widgets[ $key ] );
			}
		}

		// Get All Widgets.
		$default_widgets = youzer_widgets();

		// Unset Invisible Widgets.
		$hidden_widgets = yz_get_profile_hidden_widgets();

		foreach ( $all_widgets as $widget_name ) {

			if ( in_array( $widget_name, $hidden_widgets ) ) {
				continue;
			}

			$widgets[ $widget_name ] = yz_get_profile_widget_args( $widget_name );

		}

		// Sort array numerically.
		usort( $widgets, 'yz_sortByMenuOrder' );

		return $widgets;
	}

	/**
	 * Get AD Class
	 */
	function is_sponsored_ad( $ad_name ) {
		$get_ads = yz_option( 'yz_ads' );
		$is_sponsored = $get_ads[ $ad_name ]['is_sponsored'];
		return $is_sponsored;
	}

	/**
	 * Get Widget Content.
	 */
	function get_widget_content( $widgets ) {

		// Filter
		$widgets = apply_filters( 'yz_get_widgets_content', $widgets );

		// Display Widgets
		$default_widgets = youzer_widgets();

		foreach ( $widgets as $widget_name => $visibility ) {

			$class = false;

			if ( 'visible' != $visibility ) {
				continue;
			}

			$class = $this->get_widget_class( $widget_name, $default_widgets );

			$this->yz_widget_core( $widget_name, $class );

		}
	}

	/**
	 * Get Widget Class
	 */
	function get_widget_class( $widget_name, $default_widgets = null) {

		// Init Vars
		$default_widgets = ! empty( $default_widgets ) ? $default_widgets : youzer_widgets();

		if ( isset( $default_widgets[ $widget_name ] ) ) {
			if ( isset( $default_widgets[ $widget_name ]['file'] ) ) {
    			include YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-' . $default_widgets[ $widget_name ]['file'] . '.php';
			}
    		$class = new $default_widgets[ $widget_name ]['class']();
		} else {
			if ( yz_is_custom_widget( $widget_name ) ) {
				require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-custom-widgets.php';
				$class = new YZ_Custom_Widgets( $widget_name );
			} elseif ( yz_is_ad_widget( $widget_name ) ) {
    			require_once YZ_PUBLIC_CORE . 'widgets/yz-widgets/class-yz-ads.php';
				$class = new YZ_Ads( $widget_name );
			}

		}

		return $class;
	}

	/**
	 * # Widget Core
	 */
	function yz_widget_core( $widget_name, $class, $args = null ) {

		$args = ! empty( $args ) ? $args : yz_get_profile_widget_args( $widget_name );

		// Init variables.
	 	$widget_name = $args['id'];
		$function_options = isset( $args['function_options'] ) ? $args['function_options'] : null;

		// Check Content Existence.
		ob_start();
		$class->widget( $function_options );
		$widget_content = ob_get_contents();
		ob_end_clean();

		// if there's no content exit.
		if ( empty( $widget_content ) ) {
			return false;
		}

		// Get Loading Effects.
		$args['load_effect'] = yz_option( 'yz_' . $widget_name . '_load_effect', 'fadeIn' );

		// Get Widget Data.
		$display_title = isset( $args['display_title'] ) ?  $args['display_title'] : yz_option( 'yz_wg_' . $widget_name . '_display_title', 'on' );

		// Display tilte if value equal true also.
		if ( empty( $display_title ) || 'true' == $display_title ) {
			$display_title = 'on';
		}

		?>

		<div class="yz-widget <?php echo $this->yz_widget_class_name( $args ); ?>" <?php echo $this->get_loading_effect( $args['load_effect'] ); ?>>

			<div class="yz-widget-main-content">

				<?php if ( 'on' == apply_filters( 'yz_display_profile_widget_title', $display_title, $widget_name ) ) : ?>
				<div class="yz-widget-head">
					<h2 class="yz-widget-title">
						<?php if ( 'on' == yz_option( 'yz_display_wg_title_icon', 'on' ) ) : ?>
							<?php echo apply_filters( 'yz_profile_widget_title_icon', '<i class="' . $args['icon'] . '"></i>', $widget_name ); ?>
						<?php endif; ?>
						<?php echo $args['name']; ?>
					</h2>
					<?php if ( bp_core_can_edit_settings() && ( in_array( $widget_name, $this->settings_widgets() ) || 'custom_infos' == $widget_name  ) ) :?>
					<a href="<?php echo apply_filters( 'yz_profile_widgets_edit_link', yz_get_widgets_settings_url( $widget_name ), $widget_name ); ?>"><i class="far fa-edit yz-edit-widget"></i></a>
					<?php endif; ?>
				</div>
				<?php endif; ?>

				<div class="yz-widget-content">
					<?php do_action( 'yz_before_widget_content', $widget_name ); ?>
					<?php echo $widget_content; ?>
					<?php do_action( 'yz_after_widget_content', $widget_name ); ?>
				</div>

			</div>

		</div>

		<?php

	}

	/**
	 * Display Widgets Title For Profile Owner.
	 */
	function display_widgets_title_for_profile_owner( $show, $widget_name ) {

		if ( ! bp_core_can_edit_settings() ) {
			return $show;
		}

		$widgets = array( 'quote', 'link', 'post', 'project' );

		if ( in_array( $widget_name, $widgets ) ) {
			return 'on';
		}

		return $show;
	}

}


/**
 * Get a unique instance of Youzer Widgets.
 */
function yz_widgets() {
	return Youzer_Widgets::get_instance();
}

/**
 * Launch Youzer Users!
 */
yz_widgets();