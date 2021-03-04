<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * @var $insta_data
 * @var $before_widget
 * @var $after_widget
 * @var $title
 */
olympus_render( $before_widget );
olympus_render( $title );
?>


    <ul class="widget w-last-photo js-zoom-gallery">
        <?php
        if ( is_wp_error( $insta_data ) ) {
            echo wp_kses_post( $insta_data->get_error_message() );
        } else {
            foreach ( $insta_data['media'] as $item ) {
                ?>
                <li>
                    <a href="<?php echo esc_url( $item[ 'large' ] ); ?>">
                        <img loading="lazy" src="<?php echo esc_url( $item[ 'thumbnail' ] ); ?>" alt="<?php echo esc_attr( $item[ 'description' ] ); ?>">
                    </a>
                </li>
                <?php
            }
        }
        ?>
    </ul>

<?php
olympus_render( $after_widget );
