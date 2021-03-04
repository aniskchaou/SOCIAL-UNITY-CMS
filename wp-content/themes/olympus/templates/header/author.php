<?php
$user_ID           = get_the_author_meta( 'ID' );
$addit_prof_fields = get_the_author_meta( 'additional-profile-fields' );
$prof_thumb        = olympus_akg( 'profile-thumb/url', $addit_prof_fields, get_template_directory_uri() . '/images/post-no-image-sm.png' );
?>
<div class="container">
    <div class="row">
        <div class="col">
            <div id="cover-image-container">
                <div class="ui-block">
                    <div id="item-header-cover-image" class="top-header top-header-favorit">
                        <div class="top-header-thumb">
                            <a id="header-cover-image" style="background-image: url(<?php echo esc_url( $prof_thumb ); ?>);" href="<?php echo esc_url( get_the_author_meta( 'url' ) ); ?>"></a>
                            <div class="top-header-author">
                                <a class="author-thumb" href="<?php echo esc_url( get_the_author_meta( 'url' ) ); ?>">
                                    <?php echo get_avatar( $user_ID, 124 ); ?>
                                </a>
                                <div class="author-content">
                                    <a href="<?php echo esc_url( get_the_author_meta( 'url' ) ); ?>" class="h3 author-name"><?php echo get_the_author_meta( 'display_name' ); ?></a>
                                    <div class="country"><?php echo get_the_author_meta( 'user_profession' ); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="profile-section">
                            <div id="item-nav" class="row">
                                <div id="object-nav" role="navigation" class="item-list-tabs no-ajax col col-xl-9 m-auto col-lg-12 col-md-12">
                                    <ul class="profile-menu">
                                        <?php
                                        $post_type         = filter_input( INPUT_GET, 'post_type', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
                                        $post_type         = isset( $post_type[ 0 ] ) ? $post_type[ 0 ] : '';
                                        ?>
                                        <li id="notifications-personal-li" class="<?php echo (!$post_type || $post_type === 'post') ? 'current selected' : ''; ?>">
                                            <a id="user-notifications" href="<?php echo add_query_arg( 'post_type[]', 'post', get_author_posts_url( $user_ID ) ); ?>">
                                                <?php esc_html_e( 'Posts', 'olympus' ); ?> <span class="count"><?php echo count_user_posts( $user_ID, 'post' ); ?></span>
                                            </a>
                                        </li>
                                        <li id="notifications-personal-li" class="<?php olympus_render( ($post_type === 'page') ? 'current selected' : '' ); ?>">
                                            <a id="user-notifications" href="<?php echo add_query_arg( 'post_type[]', 'page', get_author_posts_url( $user_ID ) ); ?>">
                                                <?php esc_html_e( 'Pages', 'olympus' ); ?> <span class="count"><?php echo count_user_posts( $user_ID, 'page' ); ?></span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>