<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}
/**
 * @var string $title
 * @var string $widget_id
 * @var string $before_widget
 * @var string $after_widget
 */
olympus_render( $before_widget );
olympus_render( $title );
?>
<div class="ui-block-content">

    <?php olympus_render($content); ?>

    <!-- W-Socials -->
    <?php
    if ( !empty( $links ) ) {
        ?>
        <div class="widget w-socials socials--colored-bg">
            <h6 class="title">Other Social Networks:</h6>
            <?php
            foreach ( $links as $link ) {
                $url  = isset( $link[ 'link' ] ) ? $link[ 'link' ] : '';
                $type = isset( $link[ 'icon' ] ) ? $link[ 'icon' ] : '';

                if ( !$url || !$type ) {
                    continue;
                }
                ?>

                <a href="<?php echo esc_attr( $url ); ?>" class="social-item svg-inline-inside <?php echo esc_attr( $type ); ?>">
                    <?php get_template_part( "/templates/socials/{$type}" ); ?>
                    <?php echo esc_html( ucfirst( $type ) ); ?>
                </a>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
    <!-- ... end W-Socials -->

</div>
<?php
olympus_render( $after_widget );
