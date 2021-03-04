<?php

if ( !defined( 'FW' ) ) {
    die( 'Forbidden' );
}

class FW_Extension_Post_Reaction extends FW_Extension {

    const NONCE = '_crumina_reaction_action';

    protected function _init() {
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueueScript' ) );

        add_action( 'wp_ajax_crumina_reaction_toggle', array( $this, 'reactionToggle' ) );
        add_action( 'wp_ajax_nopriv_crumina_reaction_toggle', array( $this, 'reactionToggle' ) );

        add_filter( 'crumina_section_single_post_elements', array( $this, 'extendSectionSinglePostElements' ) );
    }

    /**
     * Enqueue scripts
     */
    public function enqueueScript() {
        wp_enqueue_script( 'sweetalert2', $this->locate_URI( '/static/js/sweetalert2.js' ), array( 'jquery' ), $this->manifest->get_version() );
        wp_enqueue_script( 'crumina-reaction-scripts', $this->locate_URI( '/static/js/scripts.js' ), array( 'jquery', 'sweetalert2' ), $this->manifest->get_version() );

        $localize = array(
            'ajax' => admin_url( 'admin-ajax.php' ),
        );

        wp_localize_script( 'crumina-reaction-scripts', 'crumina_reaction', $localize );
    }

    /**
     * Extend Single Post Elements Section
     *
     * @param array $opt
     */
    public function extendSectionSinglePostElements( $opt ) {
        $olympus = Olympus_Options::get_instance();
        $single_reaction_show_default = array(
            'show' => 'yes',
            'yes'  => array(
                'type'   => 'without-counter',
                'design' => 'colored'
            )
        );
        $single_reaction_show_value = $olympus->get_option( 'single_reaction_show', $single_reaction_show_default, $olympus::SOURCE_SETTINGS );
        return array_merge( array(
            'single_reaction_show' => array(
                'type'    => 'multi-picker',
                'label'   => false,
                'desc'    => false,
                'value'   => $single_reaction_show_value,
                'picker'  => array(
                    'show' => array(
                        'label'        => esc_html__( 'Reactions', 'crumina' ),
                        'type'         => 'switch',
                        'right-choice' => array(
                            'value' => 'yes',
                            'label' => esc_html__( 'Enable', 'crumina' ),
                        ),
                        'left-choice'  => array(
                            'value' => 'no',
                            'label' => esc_html__( 'Disable', 'crumina' ),
                        ),
                        'value'        => 'yes',
                    ),
                ),
                'choices' => array(
                    'yes' => array(
                        'type' => array(
                            'type'    => 'radio',
                            'value'   => 'without-counter',
                            'label'   => __( 'Reaction type', 'crumina' ),
                            'choices' => array(
                                'without-counter' => __( 'Without counter', 'crumina' ),
                                'with-counter'    => __( 'With counter', 'crumina' ),
                            ),
                            'inline'  => false,
                        ),
                        'design' => array(
                            'type'    => 'radio',
                            'value'   => 'colored',
                            'label'   => __( 'Design', 'crumina' ),
                            'choices' => array(
                                'colored' => __( 'Colored', 'crumina' ),
                                'grayscale'    => __( 'Grayscale', 'crumina' ),
                            ),
                            'inline'  => false,
                        ),
                    ),
                ),
            )
        ), $opt );
    }

    /**
     * Get reactions html
     *
     * @param int $postID (default: 0)
     * @param int $reactions (default: -1)
     * @param bool $additional (default: false)
     */
    public function getReactionsHtml( $postID = 0, $reactions = -1 ) {
        wp_enqueue_style( 'crumina-reaction-styles', $this->locate_URI( '/static/css/styles.css' ), array(), $this->manifest->get_version() );

        $enable = fw_get_db_ext_settings_option( 'post-reaction', 'show-reactions' );
        $postID = (int) $postID ? (int) $postID : get_the_ID();
        if ( !$enable || !$postID ) {
            return false;
        }

        $reactions = ($reactions === -1) ? $this->getPostReactions( $postID ) : $reactions;

        $nonce              = self::NONCE;
        $view_path          = $this->locate_path( '/views/reactions.php' );
        $img_path           = $this->locate_URI( '/static/img' );
        $availableReactions = fw_get_db_ext_settings_option( 'post-reaction', 'available-reactions' );

        return fw_render_view( $view_path, compact( 'img_path', 'reactions', 'postID', 'availableReactions', 'nonce' ) );
    }

    /**
     * Get reactions count html
     *
     * @param int $postID (default: 0)
     * @param int $reactions (default: -1)
     * @param string $type (default: all)
     */
    public function getReactionsCountHtml( $type = 'all', $postID = 0,
                                           $reactions = -1 ) {
        wp_enqueue_style( 'crumina-reaction-styles', $this->locate_URI( '/static/css/styles.css' ), array(), $this->manifest->get_version() );

        $enable = fw_get_db_ext_settings_option( 'post-reaction', 'show-reactions' );
        $postID = (int) $postID ? (int) $postID : get_the_ID();
        if ( !$type || !$enable || !$postID ) {
            return false;
        }

        $reactions = ($reactions === -1) ? $this->getPostReactions( $postID ) : $reactions;

        $nonce              = self::NONCE;
        $img_path           = $this->locate_URI( '/static/img' );
        $view_path          = $this->locate_path( "/views/reactions-count-{$type}.php" );
        $availableReactions = fw_get_db_ext_settings_option( 'post-reaction', 'available-reactions' );

        return fw_render_view( $view_path, compact( 'img_path', 'postID', 'reactions', 'availableReactions', 'nonce' ) );
    }

    /**
     * Convert available reactions to sql query
     */
    public function reactionsToSql() {
        $reactions          = '';
        $availableReactions = fw_get_db_ext_settings_option( 'post-reaction', 'available-reactions' );
        if ( $availableReactions ) {

            foreach ( $availableReactions as $reaction ) {
                $reactions .= $reaction[ 'ico' ];

                if ( next( $availableReactions ) ) {
                    $reactions .= "', '";
                }
            }

            $reactions = "'{$reactions}'";
        }

        return $reactions;
    }

    /**
     * Check user is reacted
     *
     * @param int $postID (default: 0)
     * @param bool $type (default: false)
     * @param int $userID (default: 0)
     */
    public function isReacted( $postID = 0, $type = false, $userID = 0 ) {
        global $wpdb;

        if ( !$postID || !$type || !$userID ) {
            return false;
        }

        $query = "SELECT meta_key FROM {$wpdb->postmeta} WHERE meta_value = '{$userID}' AND meta_key = '{$type}' AND post_id = {$postID} ";

        $result = $wpdb->get_var( $query );

        return !empty( $result ) ? $result : false;
    }

    /**
     * Add or remove reaction
     */
    public function reactionToggle() {
        check_admin_referer( self::NONCE, 'nonce' );

        $userID = $this->getUserID();
        $postID = filter_input( INPUT_POST, 'post', FILTER_VALIDATE_INT );
        $type   = filter_input( INPUT_POST, 'type', FILTER_SANITIZE_STRING );

        if ( !$postID ) {
            wp_send_json_error( array( 'message' => __( 'Missing post.', 'reactions' ) ) );
        }

        if ( !$userID ) {
            wp_send_json_error( array( 'message' => __( 'Missing user.', 'reactions' ) ) );
        }

        if ( !$type ) {
            wp_send_json_error( array( 'message' => __( 'Missing type.', 'reactions' ) ) );
        }

        if ( $this->isReacted( $postID, $type, $userID ) ) {

            // Delete old reaction
            delete_post_meta( $postID, $type, $userID );
        } else {

            // Add new reaction
            add_post_meta( $postID, $type, $userID );
        }

        $reactions = $this->getPostReactions( $postID );
        wp_send_json_success( array(
            'reactions'     => $this->getReactionsHtml( $postID, $reactions ),
            'count-all'     => $this->getReactionsCountHtml( 'all', $postID, $reactions ),
            'count-compact' => $this->getReactionsCountHtml( 'compact', $postID, $reactions ),
            'count-used'    => $this->getReactionsCountHtml( 'used', $postID, $reactions ),
        ) );

        exit();
    }

    /**
     * Get and calculate all post reactions
     *
     * @param int $postID (default: 0)
     */
    public function getPostReactions( $postID = 0 ) {
        global $wpdb;

        $userID    = $this->getUserID();
        $reactions = $this->reactionsToSql();

        if ( !$postID || !$reactions ) {
            return false;
        }

        $query = "SELECT meta_key, meta_value FROM {$wpdb->postmeta} WHERE post_id = {$postID} AND meta_key IN ( {$reactions} )";

        $reactions = $wpdb->get_results( $query, ARRAY_A );

        if ( empty( $reactions ) ) {
            return false;
        }

        // Count reactions
        $result = array(
            'total' => 0
        );

        foreach ( $reactions as $reaction ) {
            $key = $reaction[ 'meta_key' ];

            if ( !isset( $result[ $key ] ) ) {
                $result[ $key ] = array(
                    'count'   => 0,
                    'reacted' => false
                );
            }

            $result[ $key ][ 'count' ] ++;
            $result[ 'total' ] ++;

            if ( $reaction[ 'meta_value' ] == $userID ) {
                $result[ $key ][ 'reacted' ] = true;
            }
        }

        return $result;
    }

    public function filter_input_fix ($type, $variable_name, $filter = FILTER_DEFAULT, $options = NULL )
    {
    $checkTypes =[
        INPUT_GET,
        INPUT_POST,
        INPUT_COOKIE
    ];

    if ($options === NULL) {
        // No idea if this should be here or not
        // Maybe someone could let me know if this should be removed?
        $options = FILTER_NULL_ON_FAILURE;
    }

    if (in_array($type, $checkTypes) || filter_has_var($type, $variable_name)) {
        return filter_input($type, $variable_name, $filter, $options);
    } else if ($type == INPUT_SERVER && isset($_SERVER[$variable_name])) {
        return filter_var($_SERVER[$variable_name], $filter, $options);
    } else if ($type == INPUT_ENV && isset($_ENV[$variable_name])) {
        return filter_var($_ENV[$variable_name], $filter, $options);
    } else {
        return NULL;
    }
    }

    /**
     * Get user IP
     */
    public function getIP() {
        $ip        = false;
        $client_ip = $this->filter_input_fix( INPUT_SERVER, 'HTTP_CLIENT_IP' );
        $forwarded = $this->filter_input_fix( INPUT_SERVER, 'HTTP_X_FORWARDED_FOR' );
        $remote    = $this->filter_input_fix( INPUT_SERVER, 'REMOTE_ADDR' );

        if ( $client_ip ) {
            $ip = $client_ip;
        } elseif ( $forwarded ) {
            $ip = $forwarded;
        } else {
            $ip = $remote;
        }

        return $ip;
    }

    /**
     * Get user ID if logged, IP otherwise
     */
    public function getUserID() {
        $userID = get_current_user_id();
        return $userID ? $userID : $this->getIP();
    }

}
