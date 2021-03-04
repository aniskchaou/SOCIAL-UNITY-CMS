<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * @var string $title
 * @var string $widget_id
 * @var string $before_widget
 * @var string $after_widget
 * @var string $content
 * @var string $map
 * @var string $height
 */
olympus_render( $before_widget );
olympus_render( $title );

$height = $height ? $height : 200;
?>

<div class="widget w-contacts">
    <div class="section">
        <?php
        if ( $map ) {
            echo preg_replace( array( '/width=[\"\']+.*?[\"\']+/i', '/height=[\"\']+.*?[\"\']+/i' ), array(
                sprintf( 'width="%s"', '100%' ),
                sprintf( 'height="%d"', intval( $height ) )
            ), $map );
        }
        ?>
    </div>

    <div class="ui-block-content">
        <?php olympus_render( $content ); ?>
    </div>
</div>

<?php
olympus_render( $after_widget );
