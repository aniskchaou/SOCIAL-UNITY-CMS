<?php

/**
 * Olympus theme core class.
 *
 * @package olympus-wp
 */

/**
 * Class Olympus_Core
 */
class Olympus_Core extends Olympus_Singleton {

    /**
     * True if theme already initialized( or in process). To prevent multiple init.
     *
     * @var bool $initialized
     */
    private $initialized = false;

    /**
     * True when BuddyPress plugin enabled and false otherwise.
     *
     * @var bool $buddypress_module
     */
    private $buddypress_module = false;

    /**
     * True when WooCommerce plugin enabled and false otherwise.
     *
     * @var bool $woocommerce_module
     */
    private $woocommerce_module = false;

    /**
     * Function for isolated include
     *
     * @var callable $include_isolated_callable
     */
    private static $include_isolated_callable;

    /**
     * Class instance
     *
     * @var Olympus_Core $instance
     */
    protected static $instance;

    /**
     * Include file first from child theme then from parent.
     *
     * @param string $rel_path related ( by theme folder) file path.
     */
    public static function include_child_first( $rel_path ) {
        self::include_child( $rel_path );
        self::include_parent( $rel_path );
    }

    /**
     * Include file from child theme
     *
     * @param string $rel_path related ( by theme folder) file path.
     */
    public static function include_child( $rel_path ) {
        if ( is_child_theme() ) {
            $path = get_stylesheet_directory() . $rel_path;

            if ( file_exists( $path ) ) {
                self::include_isolated( $path );
            }
        }
    }

    /**
     * Include file from parent theme
     *
     * @param string $rel_path related ( by theme folder) file path.
     */
    public static function include_parent( $rel_path ) {
        $path = get_template_directory() . $rel_path;

        if ( file_exists( $path ) ) {
            self::include_isolated( $path );
        }
    }

    /**
     * Include file
     *
     * @param string $path full path to file.
     */
    private static function include_isolated( $path ) {
        call_user_func( self::$include_isolated_callable, $path );
    }

    /**
     * Change dirname format to class name format
     *
     * @param string $dirname 'foo-bar'.
     *
     * @return string 'Foo_Bar'
     */
    public static function dirname_to_classname( $dirname ) {
        $class_name = explode( '-', $dirname );
        $class_name = array_map( 'ucfirst', $class_name );
        $class_name = implode( '_', $class_name );

        return $class_name;
    }

    /**
     * Theme init.
     *
     * @return bool true if theme init is successful and false otherwise.
     */
    public function init() {
        if ( $this->initialized ) {
            return false;
        }
        $this->initialized = true;

        self::$include_isolated_callable = function($path = false) {
            if ( !$path ) {
                return;
            }
            include $path;
        };

        // After theme setup activation of all plugins and setup demo content.
        self::include_parent( '/inc/auto-setup/class-fw-auto-install.php' );

        // Include plugin that notice user about recomended and required plugins.
        if ( is_admin() ) {

            $not_show_on_pages = array( 'crum_auto_setup' );
            $page              = array_key_exists( 'page', $_REQUEST ) ? $_REQUEST[ 'page' ] : '';

            if ( !in_array( $page, $not_show_on_pages, true ) ) {
                $this->include_tgmpa();
            }
        }

        // Load function.
        self::include_parent( '/inc/functions/unyson.php' );
        // Include customizer
	    self::include_child_first( '/inc/static/customizer.php' );

        self::include_parent( '/inc/functions/crum-image-resize.php' );
        self::include_parent( '/inc/functions/related-posts.php' );

        self::include_parent( '/inc/functions/template-tags.php' );
        self::include_parent( '/inc/functions/helpers.php' );

        // Include hooks.
        self::include_child_first( '/inc/hooks/actions.php' );
        self::include_child_first( '/inc/hooks/filters.php' );

        // 3-rd party Plugins modifications
        self::include_parent( '/inc/plugins-extend/init.php' );

        // Include custom styles
        self::include_child_first( '/inc/static/custom-styles.php' );

        // Include stunning header custom styles
        self::include_child_first( '/inc/static/stunning-header-custom-styles.php' );



        // Autoload composer elements
        $elements_path = get_template_directory() . '/inc/vc_elements';

        foreach ( glob( $elements_path . "/*.php" ) as $element ) {
            load_template( $element, true );
        }

        return true;
    }

    /**
     * Automatic plugin activation.
     */
    private function include_tgmpa() {
        self::include_parent( '/inc/TGM-Plugin-Activation/class-tgm-plugin-activation.php' );

        if ( function_exists( 'tgmpa' ) ) {

        	$default_plugins = array(
		        array(
			        'name'         => esc_html__( 'Unyson', 'olympus' ),
			        'slug'         => 'unyson',
			        'required'     => true,
			        'is_automatic' => true,
		        ),
		        array(
			        'name'         => esc_attr__( 'Envato Market', 'olympus' ),
			        'slug'         => 'envato-market',
			        'source'       => 'https://envato.github.io/wp-envato-market/dist/envato-market.zip',
			        'required'     => true,
			        'is_automatic' => true,
		        ),
		        array(
			        'name'     => esc_attr__( 'BuddyPress', 'olympus' ),
			        'slug'     => 'buddypress',
			        'required' => true,
		        ),
		        array(
			        'name'     => esc_attr__( 'Youzer', 'olympus' ),
			        'slug'     => 'youzer',
			        'source'   => 'http://up.crumina.net/plugins/youzer.zip',
			        'required' => true,
			        'version'  => '2.6.2'
		        ),
	        );

        	$js_builder = array(
		        array(
			        'name'               => esc_attr__( 'WPBakery Page Builder', 'olympus' ),
			        'slug'               => 'js_composer',
			        'source'             => 'http://up.crumina.net/plugins/js_composer.zip',
			        'required'           => false,
			        'is_automatic'       => true,
			        'version'            => '6.6'
		        ),
	        );

	        $elementor_extension = array(
		        array(
			        'name'     => esc_attr__( 'Elementor Olympus Widgets', 'olympus' ),
			        'slug'     => 'elementor-olympus',
			        'source'   => 'http://up.crumina.net/plugins/elementor-olympus.zip',
			        'required' => true,
			        'version'  => '1.5.0'
		        ),
	        );

	        if ( class_exists( 'Vc_Manager' ) ) {
		        $default_plugins = array_merge( $default_plugins, $js_builder );
	        }
	        if (  did_action( 'elementor/loaded' ) ) {
		        $default_plugins = array_merge( $default_plugins, $elementor_extension );
	        }


            tgmpa( $default_plugins );
        }
    }

    /**
     * Get Unyson extension.
     *
     * @param bool|string $extension Extension name.
     *
     * @return FW_Extension|null
     */
    public static function get_extension( $extension = false ) {
		if ( ! $extension || ! function_exists( 'fw' ) ) {
            return null;
        }

        return fw()->extensions->get( $extension );
    }

    /**
     * Helpers for BuddyPress functions
     */
    public static function bp_current_component() {
        if ( function_exists( 'bp_current_component' ) ) {
            return bp_current_component();
        }
        return false;
    }

    public static function bp_is_user() {
        if ( function_exists( 'bp_is_user' ) ) {
            return bp_is_user();
        }
        return false;
    }

}
