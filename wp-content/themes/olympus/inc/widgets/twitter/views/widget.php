<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * @var $number
 * @var $before_widget
 * @var $after_widget
 * @var $title
 * @var $tweets
 */
olympus_render( $before_widget );
olympus_render( $title );

if ( !empty( $tweets->errors ) ) {
    echo esc_html__( 'Error code:', 'olympus' ) . ' ' . esc_html( $tweets->errors[ 0 ]->code ) . ' ' . esc_html( $tweets->errors[ 0 ]->message );
} elseif ( !empty( $tweets ) && is_array( $tweets ) ) {
    ?>
    <!-- W-Twitter -->
    <ul class="widget w-twitter">
        <?php foreach ( $tweets as $tweet ) { ?>
            <li class="twitter-item">
                <div class="author-folder">
                    <img loading="lazy" src="<?php olympus_render( $tweet->user->profile_image_url_https ); ?>" alt="<?php echo esc_attr( $tweet->user->name ); ?>">
                    <div class="author">
                        <a href="<?php olympus_render( $tweet->user->url ); ?>" class="author-name"><?php olympus_render( $tweet->user->name ); ?></a>
                        <a href="<?php olympus_render( $tweet->user->url ); ?>" class="group">@<?php olympus_render( $tweet->user->screen_name ); ?></a>
                    </div>
                </div>
                <p><?php echo olympus_twitter_convert_links( $tweet->text ); ?></p>
                <span class="post__date">
                    <time class="published" datetime="<?php echo date( DATE_ATOM, strtotime( $tweet->created_at ) ); ?>">
                        <?php echo olympus_relative_time( $tweet->created_at ); ?>
                    </time>
                </span>
            </li>
        <?php } ?>
    </ul>
    <!-- .. end W-Twitter -->
    <?php
}

olympus_render( $after_widget );

