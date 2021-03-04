<?php

if ( ! class_exists( 'Youzer' ) ) :

/**
 * Main Youzer Class.
 */
class Youzer {

    /**
     * Init Vars
     */
    private static $instance;
    public $fields;
    public $styling;

    /**
     * Main Youzer Instance.
     */
    public static function instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Youzer ) ) {

            self::$instance = new Youzer;

            // Setup Constants.
            self::$instance->setup_constants();

            // Add Social Login Rewrite Role.
            add_action( 'init', array( self::$instance, 'add_rewrite_rule' ) );

            // Add Social Login Query Var.
            add_filter( 'query_vars', array( self::$instance, 'set_query_varaible' ) );

            // Init Plugins Files.
            add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
            add_action( 'bp_include', array( self::$instance, 'buddypress_include' ) );
            add_action( 'bp_init', array( self::$instance, 'buddypress_init' ) );
            add_action( 'bbp_init', array( self::$instance, 'bbpress_init' ) );
            add_action( 'woocommerce_init', array( self::$instance, 'woocommerce_init' ) );

            // Include General Functions
            include YZ_PUBLIC_CORE . 'functions/general/yz-general-functions.php';
            include YZ_PUBLIC_CORE . 'functions/general/yz-account-functions.php';
            include YZ_PUBLIC_CORE . 'functions/general/yz-profile-functions.php';
            include YZ_PUBLIC_CORE . 'functions/general/yz-admin-functions.php';
            include YZ_PUBLIC_CORE . 'functions/general/yz-scripts-functions.php';
            include YZ_PUBLIC_CORE . 'functions/general/yz-wall-functions.php';
            include YZ_PUBLIC_CORE . 'woocommerce/yz-woocommerce-functions.php';
            include YZ_PUBLIC_CORE . 'functions/yz-export-functions.php';

            include YZ_PUBLIC_CORE . 'class-yz-styling.php';
            include YZ_PUBLIC_CORE . 'wall/yz-class-media.php';

            if ( wp_doing_ajax() || ! is_admin() ) {

                include YZ_PUBLIC_CORE . 'functions/yz-general-functions.php';
                include YZ_PUBLIC_CORE . 'functions/yz-admin-functions.php';
                include YZ_PUBLIC_CORE . 'functions/yz-scripts-functions.php';
                include YZ_PUBLIC_CORE . 'functions/yz-xprofile-functions.php';
                include YZ_PUBLIC_CORE . 'functions/yz-profile-functions.php';

                self::$instance->includes();

                if ( wp_doing_ajax() ) {
                    include YZ_PUBLIC_CORE . 'class-yz-ajax.php';
                }

            }

            // Init Admin
            if ( is_admin() && ! class_exists( 'Youzer_Admin' ) ) {
                include YZ_PATH . 'includes/admin/class.youzer-admin.php';
            }

            // Include Membership System.
            if ( yz_is_membership_system_active() ) {
                include YZ_PATH . 'includes/logy/logy.php';
            }

            // Setup Globals.
            self::$instance->youzer_globals();

            // Setup Actions.
            self::$instance->setup_actions();

        }

        return self::$instance;
    }

    // Include Files.
    function buddypress_include() {

        if ( bp_is_active( 'notifications' ) ) {

            // Include Notifications Files.
            require YZ_PUBLIC_CORE . 'functions/yz-notifications-functions.php';

            // Include Live Notification.
            if ( 'on' == yz_option( 'yz_enable_live_notifications', 'on' ) ) {
                require YZ_PUBLIC_CORE . 'functions/yz-live-notifications-functions.php';
            }
        }

    }

    /**
     * Buddypress Init
     **/
    function buddypress_init() {

        include YZ_PUBLIC_CORE . 'class-yz-tabs.php';
        include YZ_PUBLIC_CORE . 'class-yz-fields.php';
        include YZ_PUBLIC_CORE . 'class-yz-hashtags.php';
        include YZ_PUBLIC_CORE . 'class-yz-attachments.php';

        if ( yz_is_bpfollowers_active() ) {
            require YZ_PUBLIC_CORE . 'functions/yz-buddypress-followers-integration.php';
        }

        $doing_ajax = wp_doing_ajax();

        if ( is_buddypress() || $doing_ajax ) {

            // Init Groups
            if ( bp_is_groups_component() ) {

                require_once YZ_PUBLIC_CORE . 'class-yz-header.php';
                require_once YZ_PUBLIC_CORE . 'class-yz-groups.php';

                if ( bp_is_group_activity() ) {
                    $this->include_activity_files();
                }

            }

            if ( bp_is_activity_component() || $doing_ajax ) {
                $this->include_activity_files();
            }

            if ( bp_is_user() ) {

                include YZ_PUBLIC_CORE . 'class-yz-widgets.php';

                // Account Functions.
                if ( yz_is_account_page() ) {
                    include YZ_PUBLIC_CORE . 'pages/yz-account.php';
                } else {
                    include YZ_PUBLIC_CORE . 'functions/yz-navbar-functions.php';
                    include YZ_PUBLIC_CORE . 'class-yz-user.php';
                    include YZ_PUBLIC_CORE . 'pages/yz-profile.php';
                    include YZ_PUBLIC_CORE . 'class-yz-author.php';
                    include YZ_PUBLIC_CORE . 'class-yz-header.php';
                }

            }

            if ( bp_is_members_directory() ) {
                include YZ_PUBLIC_CORE . 'class-yz-user.php';
            }

            if ( bp_is_groups_directory() ) {
                include YZ_PUBLIC_CORE . 'class-yz-user.php';
            }

            if ( bp_is_messages_component() ) {
                require_once YZ_PUBLIC_CORE . 'class-yz-messages.php';
            }

            if ( yz_option( 'yz_lazy_load', 'on' ) == 'on' ) {
                require YZ_PUBLIC_CORE . 'functions/yz-lazy-loading-functions.php';
            }

        } else {

            if ( is_404() ) {
                require_once YZ_PUBLIC_CORE . 'functions/yz-404profile-functions.php';
            }

        }

    }

    /**
     * BBpress Init
     */
    function bbpress_init() {

        if ( yz_is_bbpress_active() ) {
            require_once YZ_PUBLIC_CORE . 'functions/yz-bbpress.php';
        }

    }

    /**
     * Woocommerce Init
     */
    function woocommerce_init() {

        require YZ_PUBLIC_CORE . 'functions/yz-woocommerce.php';

        if ( yz_is_woocommerce_active() ) {

            // Init Actions
            add_action( 'bp_init', 'yz_is_cart_page' );

            global $Youzer;
            require YZ_PUBLIC_CORE . 'woocommerce/yz-woocommerce.php';
            require YZ_PUBLIC_CORE . 'woocommerce/yz-wc-activity.php';
            require YZ_PUBLIC_CORE . 'woocommerce/yz-wc-templates.php';
            require YZ_PUBLIC_CORE . 'woocommerce/yz-wc-redirects.php';
            $Youzer->wc = new Youzer_Woocommerce();
        }

    }

    /**
     * Include Activity.
     */
    function include_activity_files() {
        require_once YZ_PUBLIC_CORE . 'functions/wall/yz-wall-general-functions.php';
        require_once YZ_PUBLIC_CORE . 'class-yz-wall.php';
    }

    /**
     * Setup plugin constants.
     */
    private function setup_constants() {

        // Youzer Path.
        define( 'YZ_PATH', plugin_dir_path( __FILE__ ) );

        // Youzer Url Path.
        define( 'YZ_URL', plugin_dir_url( __FILE__ ) );

        // Version.
        define( 'YZ_Version', apply_filters( 'youzer_version', '2.6.2' ) );

        // Templates Path.
        define( 'YZ_TEMPLATE', YZ_PATH . 'includes/public/templates/' );

        // Public & Admin Core Path's
        define( 'YZ_PUBLIC_CORE', YZ_PATH. 'includes/public/core/' );
        define( 'YZ_ADMIN_CORE', YZ_PATH . 'includes/admin/core/' );

        // Assets ( PA = Public Assets & AA = Admin Assets ).
        define( 'YZ_PA', plugin_dir_url( __FILE__ ) . 'includes/public/assets/' );
        define( 'YZ_AA', plugin_dir_url( __FILE__ ) . 'includes/admin/assets/' );

        // Define Buddypress Avatars Dimensions.
        if ( ! defined( 'BP_AVATAR_THUMB_WIDTH' ) ) {
            define( 'BP_AVATAR_THUMB_WIDTH', 50 );
        }

        if ( ! defined( 'BP_AVATAR_THUMB_HEIGHT' ) ) {
            define( 'BP_AVATAR_THUMB_HEIGHT', 50 );
        }

        if ( ! defined( 'BP_AVATAR_FULL_WIDTH' ) ) {
            define( 'BP_AVATAR_FULL_WIDTH', 150 );
        }

        if ( ! defined( 'BP_AVATAR_FULL_HEIGHT' ) ) {
            define( 'BP_AVATAR_FULL_HEIGHT', 150 );
        }

        if ( ! defined( 'EDD_KAINELABS_STORE_URL' ) ) {
            define( 'EDD_KAINELABS_STORE_URL', 'https://kainelabs.com' );
        }

    }

    /**
     * Load Youzer Text Domain!
     */
    public function load_textdomain() {

        $domain = 'youzer';
        $mofile_custom = trailingslashit( WP_LANG_DIR ) . sprintf( '%s-%s.mo', $domain, get_locale() );

        if ( is_readable( $mofile_custom ) ) {
            return load_textdomain( $domain, $mofile_custom );
        } else {
            return load_plugin_textdomain( $domain, FALSE, dirname( YOUZER_BASENAME ) . '/languages/' );
        }
    }

    /**
     * Add Social Login Rewrite Role.
     */
    function add_rewrite_rule() {
        add_rewrite_rule( '^yz-auth/([^/]+)/([^/]+)/?', 'index.php?yz-authentication=$matches[1]&yz-provider=$matches[2]','top' );
    }

    /**
     * Add Social Login Query Var.
     */
    function set_query_varaible( $query_vars ) {
        $query_vars[] = 'yz-authentication';
        $query_vars[] = 'yz-provider';
        return $query_vars;
    }

    /**
     * Include required files.
     */
    private function includes() {

        // Youzer General Functions.
        require YZ_PUBLIC_CORE . 'functions/yz-buddypress-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-groups-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-user-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-messages-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-mailchimp-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-mailster-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-account-verification-functions.php';
        require YZ_PUBLIC_CORE . 'functions/yz-authentication-functions.php';

        if ( yz_is_mycred_installed() ) {
            require YZ_PUBLIC_CORE . 'mycred/yz-mycred-functions.php';
        }

        // Directory Functions.
        require YZ_PUBLIC_CORE . 'functions/directories/yz-members-directory-functions.php';
        require YZ_PUBLIC_CORE . 'functions/directories/yz-groups-directory-functions.php';

        // Youzer Classes.
        // require YZ_PUBLIC_CORE . 'install.php';

        // Integrations
        if ( defined( 'RTMEDIA_VERSION' ) ) {
            require YZ_PUBLIC_CORE . 'functions/yz-rtmedia-functions.php';
        }

        // require_once YZ_PUBLIC_CORE . 'functions/yz-themes-fixes.php';

    }

    /**
     * # Youzer Global Variables .
     */
    private function youzer_globals() {

        global $wpdb, $YZ_upload_url, $YZ_upload_dir, $Logy_users_table, $Yz_bookmark_table, $Yz_reviews_table, $YZ_upload_folder, $Yz_media_table, $Yz_albums_table;

        // Get Uploads Directory Path.
        $upload_dir = wp_upload_dir();

        // Get Uploads Directory.
        $YZ_upload_folder = apply_filters( 'youzer_upload_folder', 'youzify' );
        $YZ_upload_url = apply_filters( 'youzer_upload_url', $upload_dir['baseurl'] . '/'. $YZ_upload_folder . '/', $upload_dir['baseurl'] );
        $YZ_upload_dir = apply_filters( 'youzer_upload_dir', $upload_dir['basedir'] . '/' . $YZ_upload_folder  . '/' , $upload_dir['basedir'] );

        // Get Table Names.
        $Logy_users_table = $wpdb->prefix . 'logy_users';
        $Yz_bookmark_table = $wpdb->prefix . 'yz_bookmark';
        $Yz_reviews_table = $wpdb->prefix . 'yz_reviews';
        $Yz_media_table = $wpdb->prefix . 'yz_media';
        $Yz_albums_table = $wpdb->prefix . 'yz_albums';

    }

    /**
     * Set up the default hooks and actions.
     *
     */
    private function setup_actions() {

        // Add actions to plugin activation and deactivation hooks
        // add_action( 'activate_'   . YOUZER_BASENAME, 'youzer_activation'   );
        // add_action( 'deactivate_' . YOUZER_BASENAME, 'youzer_deactivation' );

        // If Youzer is being deactivated, do not add any actions
        // if ( yz_is_deactivation( YOUZER_BASENAME ) ) {
        //     return;
        // }

        /**
         * Fires after the setup of all BuddyPress actions.
         *
         * Includes bbp-core-hooks.php.
         *
         * @since 1.7.0
         *
         * @param BuddyPress $this. Current BuddyPress instance. Passed by reference.
         */
        do_action_ref_array( 'youzer_after_setup_actions', array( &$this ) );
    }

}

endif;