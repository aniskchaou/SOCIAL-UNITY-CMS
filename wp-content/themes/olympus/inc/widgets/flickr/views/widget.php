<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * @var $number
 * @var $before_widget
 * @var $after_widget
 * @var $title
 * @var $username
 */
$container_id = uniqid( 'flickr' );

olympus_render( $before_widget );
olympus_render( $title );
?>

	<ul id="<?php echo esc_attr( $container_id ); ?>" class="widget w-last-photo js-zoom-gallery"></ul>


<script type="text/javascript">
    jQuery( document ).ready( function () {
        jQuery( '#<?php echo esc_attr( $container_id ); ?>' ).jflickrfeed( {
            limit: <?php echo esc_attr( $number ); ?>,
            qstrings: {
                id: '<?php echo esc_attr( $username ); ?>'
            },
            itemTemplate: '<li><a href="{{image_b}}" title="{{title}}">' +
                '<img loading="lazy" src="{{image_q}}" alt="{{title}}" />' +
                '</a></li>'
        } );
    } );
</script>
<?php
olympus_render( $after_widget );