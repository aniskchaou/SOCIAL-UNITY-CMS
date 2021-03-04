<?php
/**
 * Single Event Meta (Map) Template
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe-events/modules/meta/map.php
 *
 * @package TribeEventsCalendar
 * @version 4.4
 */
$map = tribe_get_embedded_map();

if ( empty( $map ) ) {
    return;
}
?>

<div class="tribe-events-venue-map">
    <h2 class="tribe-events-single-section-title">Venue map</h2>
    <?php
    // Display the map.
    do_action( 'tribe_events_single_meta_map_section_start' );
    olympus_render( $map );
    do_action( 'tribe_events_single_meta_map_section_end' );
    ?>
</div>
