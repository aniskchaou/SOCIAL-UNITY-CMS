<?php
global $rtmedia_query, $rtmedia_media;

$model = new RTMediaModel();

$media = $model->get_media( array( 'id' => $rtmedia_query->media_query[ 'album_id' ] ), false, false );
if ( !isset( $media[ 0 ] ) ) {
    return;
}
$rtmedia_media = $media[ 0 ];
?>
<div class="ui-block">
    <div class="ui-block-title">
        <h6 class="title"><?php
            esc_html_e( 'Edit Album : ', 'olympus' );
            echo esc_html( $rtmedia_media->media_title );
            ?></h6>
    </div>
    <div class="ui-block-content">
        <div class="rtmedia-container rtmedia-single-container rtmedia-media-edit">
            <?php if ( rtmedia_is_global_album( $rtmedia_query->media_query[ 'album_id' ] ) ) { ?>

                <div class="rtmedia-edit-media-tabs rtmedia-editor-main">
                    <ul class="rtm-tabs clearfix">
                        <li class="active"><a href="#details-tab"><i
                                    class='dashicons dashicons-edit rtmicon'></i><?php esc_html_e( 'Details', 'olympus' ); ?>
                            </a></li>
                        <?php if ( !is_rtmedia_group_album() ) { ?>
                            <li class=""><a href="#manage-media-tab"><i
                                        class='dashicons dashicons-list-view rtmicon'></i><?php esc_html_e( 'Manage Media', 'olympus' ); ?>
                                </a></li>
                        <?php } ?>
                        <!-- use this hook to add title of a new tab-->
                        <?php do_action( 'rtmedia_add_edit_tab_title', 'album' ); ?>
                    </ul>

                    <div class="rtm-tabs-content">
                        <div class="content active" id="details-tab">
                            <form method="post" class="rtm-form">
                                <?php
                                RTMediaMedia::media_nonce_generator( $rtmedia_query->media_query[ 'album_id' ] );
                                ?>

                                <div class="rtmedia-edit-title rtm-field-wrap">
                                    <label for="media_title"><?php esc_html_e( 'Title : ', 'olympus' ); ?></label>
                                    <?php rtmedia_title_input(); ?>
                                </div>

                                <div class="rtmedia-editor-description rtm-field-wrap">
                                    <label for='description'><?php esc_html_e( 'Description: ', 'olympus' ) ?></label>
                                    <?php
                                    rtmedia_description_input( $editor = false, true );
                                    RTMediaMedia::media_nonce_generator( rtmedia_id(), true );
                                    ?>
                                </div>

                                <?php do_action( 'rtmedia_album_edit_fields', 'album-edit' ); ?>

                                <div>
                                    <input type="submit" name="submit" class='rtmedia-save-album'
                                           value="<?php esc_attr_e( 'Save Changes', 'olympus' ); ?>"/>
                                    <a class="button rtm-button rtm-button-back"
                                       href="<?php rtmedia_permalink(); ?>"><?php esc_html_e( 'Back', 'olympus' ); ?></a>
                                </div>
                            </form>
                        </div>

                        <!--media management tab-->
                        <?php if ( !is_rtmedia_group_album() ) { ?>

                            <div class="content" id="manage-media-tab">
                                <?php if ( have_rtmedia() ) { ?>
                                    <form class="rtmedia-album-edit rtmedia-bulk-actions" method="post"
                                          name="rtmedia_album_edit">
                                              <?php wp_nonce_field( 'rtmedia_bulk_delete_nonce', 'rtmedia_bulk_delete_nonce' ); ?>
                                              <?php RTMediaMedia::media_nonce_generator( $rtmedia_query->media_query[ 'album_id' ] ); ?>
                                        <p>
                                            <span><input type="checkbox" name="rtm-select-all" class="select-all"
                                                         title="<?php esc_attr_e( 'Select All Visible', 'olympus' ); ?>"/></span>
                                            <button class="button rtmedia-move" type='button'
                                                    title='<?php esc_attr_e( 'Move Selected media to another album.', 'olympus' ); ?>'><?php esc_html_e( 'Move', 'olympus' ); ?></button>
                                            <input type="hidden" name="move-selected" value="move">
                                            <button type="button" name="delete-selected" class="button rtmedia-delete-selected"
                                                    title='<?php esc_attr_e( 'Delete Selected media from the album.', 'olympus' ); ?>'><?php esc_html_e( 'Delete', 'olympus' ); ?></button>
                                        </p>

                                        <p class="rtmedia-move-container">
                                            <?php $global_albums = rtmedia_get_site_option( 'rtmedia-global-albums' ); ?>
                                            <span><?php esc_html_e( 'Move selected media to the album : ', 'olympus' ); ?></span>
                                            <select name="album" class="rtmedia-user-album-list"><?php echo rtmedia_user_album_list() ?></select>
                                            <input type="button" class="rtmedia-move-selected" name="move-selected"
                                                   value="<?php esc_attr_e( 'Move Selected', 'olympus' ); ?>"/>
                                        </p>

                                        <ul class="rtmedia-list  large-block-grid-4 ">

                                            <?php while ( have_rtmedia() ) : rtmedia(); ?>

                                                <?php get_template_part( 'rtmedia/media/media-gallery-item' ); ?>

                                            <?php endwhile; ?>

                                        </ul>


                                        <!-- these links will be handled by backbone -->
                                        <?php
                                        $display = '';
                                        if ( 0 !== rtmedia_offset() ) {
                                            $display = 'display:block;';
                                        } else {
                                            $display = 'display:none;';
                                        }
                                        ?>
                                        <a id="rtMedia-galary-prev" style='<?php echo esc_attr( $display ); ?>'
                                           href="<?php echo esc_url( rtmedia_pagination_prev_link() ); ?>"><?php esc_html_e( 'Prev', 'olympus' ); ?></a>

                                        <?php
                                        $display = '';
                                        if ( rtmedia_offset() + rtmedia_per_page_media() < rtmedia_count() ) {
                                            $display = 'display:block;';
                                        } else {
                                            $display = 'display:none;';
                                        }
                                        ?>
                                        <a id="rtMedia-galary-next" style="<?php echo esc_attr( $display ); ?>"
                                           href="<?php echo esc_url( rtmedia_pagination_next_link() ); ?>"><?php esc_html_e( 'Next', 'olympus' ); ?></a>
                                    </form>
                                <?php } else { ?>
                                    <p><?php esc_html_e( 'The album is empty.', 'olympus' ); ?></p>
                                <?php } ?>
                            </div>
                        <?php } ?>

                        <!-- use this hook to add content of a new tab-->
                        <?php do_action( 'rtmedia_add_edit_tab_content', 'album' ); ?>
                    </div>
                </div>
            <?php } else { ?>
                <p><?php esc_html_e( 'Sorry !! You can not edit this album.', 'olympus' ); ?></p>
            <?php } ?>
        </div>
    </div>
</div>
