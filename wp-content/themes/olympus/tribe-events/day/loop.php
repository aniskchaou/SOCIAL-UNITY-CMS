<?php
/**
 * Day View Loop
 * This file sets up the structure for the day loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/loop.php
 *
 * @version 4.6.19
 * @package TribeEventsCalendar
 *
 */
if ( !defined( 'ABSPATH' ) ) {
    die( '-1' );
}
?>

<?php
global $more, $post, $wp_query;
$more             = false;
$current_timeslot = null;
?>

<table id="tribe-events-day" class="event-item-table event-item-table-fixed-width">

    <thead>
        <tr>
            <th class="title">
                <?php esc_html_e( 'Title', 'olympus' ); ?>
            </th>

            <th class="date">
                <?php esc_html_e( 'Meta', 'olympus' ); ?>
            </th>

            <th class="description">
                <?php esc_html_e( 'Description', 'olympus' ); ?>
            </th>

            <th class="cost">
                <?php esc_html_e( 'Cost', 'olympus' ); ?>
            </th>
            <th></th>
        </tr>
    </thead>

    <tbody>
        <?php while ( have_posts() ) : the_post(); ?>
            <?php do_action( 'tribe_events_inside_before_loop' ); ?>

            <?php
            if ( $current_timeslot != $post->timeslot ) {
                $current_timeslot = $post->timeslot;
            }
            ?>

            <?php if ( $current_timeslot ) { ?>
                <!-- Month / Year Headers -->
                <tr class="event-date-headers">
                    <td colspan="5" class="event-date-headers">
                        <h2 class="tribe-events-list-separator-month">
                            <span><?php olympus_render($current_timeslot); ?></span>
                        </h2>
                    </td>
                </tr>
            <?php } ?>

            <!-- Event  -->
            <?php
            $event_type = tribe( 'tec.featured_events' )->is_featured( $post->ID ) ? 'featured' : 'event';

            /**
             * Filters the event type used when selecting a template to render
             *
             * @param $event_type
             */
            $event_type = apply_filters( 'tribe_events_day_view_event_type', $event_type );
            ?>

            <tr id="post-<?php the_ID() ?>" class="event-item <?php tribe_events_event_classes(); ?>">
                <?php tribe_get_template_part( 'day/single-event' ); ?>
            </tr>

            <?php do_action( 'tribe_events_inside_after_loop' ); ?>
        <?php endwhile; ?>
    </tbody>

</table>
