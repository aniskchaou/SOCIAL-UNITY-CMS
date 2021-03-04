<?php

/***
 * Media Tab.
 */
class YZ_Media_Tab {

    /**
     * Constructor
     */
    function __construct() {

        add_action( 'bp_enqueue_scripts', array( $this, 'scripts' ) );

    }

    /**
     * Group Tab.
     */
    function group_tab() {

        $current_tab = bp_action_variable();

        if ( empty( $current_tab ) || $current_tab == 'all' ) {
            $layout = yz_option( 'yz_group_media_tab_layout', '4columns' );
            $limit = yz_option( 'yz_group_media_tab_per_page', 8 );
        } else {
            $layout = yz_option( 'yz_group_media_subtab_layout', '3columns' );
            $limit = yz_option( 'yz_group_media_subtab_per_page', 24 );
        }

        $args = array( 'group_id' => bp_get_current_group_id(), 'layout' => $layout, 'limit' => $limit, 'pagination' => true );

        ?>

        <div class="item-list-tabs no-ajax" id="subnav" aria-label="<?php esc_attr_e( 'Group secondary navigation', 'youzer' ); ?>" role="navigation">
            <ul><?php bp_get_options_nav( yz_group_media_slug() ); ?></ul>
        </div>

        <div class="yz-tab yz-media yz-media-<?php echo $args['layout']; ?>">

        <?php

        switch ( $current_tab ) {
            case 'photos':
                $this->get_photos( $args );
                break;
            case 'videos':
                $this->get_videos( $args );
                break;
            case 'audios':
                $this->get_audios( $args );
                break;
            case 'files':
                $this->get_files( $args );
                break;

            default:

                // Delete Pagination.
                unset( $args['pagination'] );

                if ( 'on' == yz_option( 'yz_show_group_media_tab_photos', 'on' ) ) $this->get_photos( $args );
                if ( 'on' == yz_option( 'yz_show_group_media_tab_videos', 'on' ) ) $this->get_videos( $args );
                if ( 'on' == yz_option( 'yz_show_group_media_tab_audios', 'on' ) ) $this->get_audios( $args );
                if ( 'on' == yz_option( 'yz_show_group_media_tab_files', 'on' ) ) $this->get_files( $args );

                break;
        }

        ?>

        </div>

        <?php
    }

    /**
     * # Tab.
     */
    function tab() {

        $current_tab = bp_current_action();

        if ( empty( $current_tab ) || $current_tab == 'all' ) {
            $layout = yz_option( 'yz_profile_media_tab_layout', '4columns' );
            $limit = yz_option( 'yz_profile_media_tab_per_page', 8 );
        } else {
            $layout = yz_option( 'yz_profile_media_subtab_layout', '3columns' );
            $limit = yz_option( 'yz_profile_media_subtab_per_page', 24 );
        }

        $args = array( 'user_id' => bp_displayed_user_id(), 'layout' => $layout, 'limit' => $limit, 'pagination' => true );

        ?>

        <div class="yz-tab yz-media yz-media-<?php echo $args['layout']; ?>">

        <?php

            switch ( $current_tab ) {

                case 'photos':
                    $this->get_photos( $args );
                    break;
                case 'videos':
                    $this->get_videos( $args );
                    break;
                case 'audios':
                    $this->get_audios( $args );
                    break;
                case 'files':
                    $this->get_files( $args );
                    break;

                default:

                    // Delete Pagination.
                    unset( $args['pagination'] );

                    if ( 'on' == yz_option( 'yz_show_profile_media_tab_photos', 'on' ) ) $this->get_photos( $args );
                    if ( 'on' == yz_option( 'yz_show_profile_media_tab_videos', 'on' ) ) $this->get_videos( $args );
                    if ( 'on' == yz_option( 'yz_show_profile_media_tab_audios', 'on' ) ) $this->get_audios( $args );
                    if ( 'on' == yz_option( 'yz_show_profile_media_tab_files', 'on' ) ) $this->get_files( $args );

                    break;
            }

        ?>

        </div>

        <?php
    }

    /**
     * Get Photos
     **/
    function get_photos( $args = null ) {

        ?>

        <div class="yz-media-group yz-media-group-photos">

            <div class="yz-media-group-head">
                <div class="yz-media-head-left">
                    <div class="yz-media-group-icon"><i class="fas fa-image"></i></div>
                    <div class="yz-media-group-title"><?php _e( 'Photos', 'youzer' ); ?></div>
                </div>
                <div class="yz-media-head-right">
                    <?php if ( bp_current_action() != 'photos' ) : ?>
                    <a href="<?php echo yz_media()->get_media_by_type_slug( $args ) . '/photos'; ?>" class="yz-media-group-view-all"><?php _e( 'View All', 'youzer' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="yz-media-group-content">
                <div class="yz-media-items">
                    <?php yz_media()->get_photos_items( $args ); ?>
                </div>
            </div>

        </div>
        <?php
    }

    /**
     * Get Videos
     **/
    function get_videos( $args = null ) {

        ?>

        <div class="yz-media-group yz-media-group-videos">

            <div class="yz-media-group-head">
                <div class="yz-media-head-left">
                    <div class="yz-media-group-icon"><i class="fas fa-film"></i></div>
                    <div class="yz-media-group-title"><?php _e( 'Videos', 'youzer' ); ?></div>
                </div>
                <div class="yz-media-head-right">
                    <?php if ( bp_current_action() != 'videos' ) : ?>
                    <a href="<?php echo yz_media()->get_media_by_type_slug( $args ). '/videos'; ?>" class="yz-media-group-view-all"><?php _e( 'View All', 'youzer' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="yz-media-group-content">
                <div class="yz-media-items">
                    <?php yz_media()->get_videos_items( $args ); ?>
                </div>
            </div>

        </div>

        <?php
    }

    /**
     * Get Audios
     **/
    function get_audios( $args = null ) {

        ?>

        <div class="yz-media-group yz-media-group-audios">

            <div class="yz-media-group-head">
                <div class="yz-media-head-left">
                    <div class="yz-media-group-icon"><i class="fas fa-volume-up"></i></div>
                    <div class="yz-media-group-title"><?php _e( 'Audios', 'youzer' ); ?></div>
                </div>
                <div class="yz-media-head-right">

                    <?php if ( bp_current_action() != 'audios' ) : ?>
                    <a href="<?php echo yz_media()->get_media_by_type_slug( $args ) . '/audios'; ?>" class="yz-media-group-view-all"><?php _e( 'View All', 'youzer' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="yz-media-group-content">
                <div class="yz-media-items">
                    <?php yz_media()->get_audios_items( $args ); ?>
                </div>
            </div>

        </div>

        <?php
    }

    /**
     * Get Files
     **/
    function get_files( $args = null ) {

        ?>

        <div class="yz-media-group yz-media-group-files">

            <div class="yz-media-group-head">
                <div class="yz-media-head-left">
                    <div class="yz-media-group-icon"><i class="fas fa-file-import"></i></div>
                    <div class="yz-media-group-title"><?php _e( 'Files', 'youzer' ); ?></div>
                </div>
                <div class="yz-media-head-right">

                    <?php if ( bp_current_action() != 'files' ) : ?>
                    <a href="<?php echo yz_media()->get_media_by_type_slug( $args ) . '/files'; ?>" class="yz-media-group-view-all"><?php _e( 'View All', 'youzer' ); ?></a>
                    <?php endif; ?>
                </div>
            </div>

            <div class="yz-media-group-content">
                <div class="yz-media-items">
                    <?php yz_media()->get_files_items( $args ); ?>
                </div>
            </div>

        </div>

        <?php
    }

    /**
     * Scripts
     */
    function scripts() {
        yz_media()->scripts();
    }
}