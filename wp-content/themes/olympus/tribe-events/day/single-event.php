<?php
/**
 * Day View Single Event
 * This file contains one event in the day view
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/day/single-event.php
 *
 * @version 4.6.19
 *
 */
if ( !defined( 'ABSPATH' ) ) {
    die( '-1' );
}

// Setup an array of venue details for use later in the template
$venue_details = tribe_get_venue_details();

// Venue
if ( !empty( $venue_details[ 'address' ] ) ) {
    $empty_venue_address = olympus_empty_venue_address( $venue_details[ 'address' ] );
    $has_venue_address   = ' location';
} else {
    $empty_venue_address = true;
    $has_venue_address   = '';
}

// Organizer
$organizer = tribe_get_organizer();
?>

<td class="title">
    <div class="event-author inline-items">
        <div class="author-thumb">
            <?php echo tribe_event_featured_image( null, 'thumbnail' ); ?>
        </div>
        <div class="author-date">
            <?php do_action( 'tribe_events_before_the_event_title' ); ?>
            <h6 class="author-name tribe-events-list-event-title">
                <a class="tribe-event-url" href="<?php echo esc_url( tribe_get_event_link() ); ?>" title="<?php the_title_attribute() ?>" rel="bookmark">
                    <?php the_title() ?>
                </a>
            </h6>
            <?php do_action( 'tribe_events_after_the_event_title' ); ?>
        </div>
    </div>
</td>
<td class="meta">
    <!-- Event Meta -->
    <?php do_action( 'tribe_events_before_the_meta' ) ?>
    <div class="tribe-events-event-meta">
        <div class="author <?php echo esc_attr( $has_venue_address ); ?>">

            <!-- Schedule & Recurrence Details -->
            <div class="date-event inline-items align-left tribe-event-schedule-details">
                <i class="olympus-icon-Small-Calendar-Icon"></i>
                <span class="month"><?php echo tribe_events_event_schedule_details() ?></span>
            </div>

            <?php if ( $venue_details && !$empty_venue_address ) : ?>
                <!-- Venue Display Info -->
                <div class="tribe-events-venue-details inline-items">
                    <i class="olympus-icon-Small-Calendar-Icon"></i>
                    <div class="tribe-events-venue-details-text">
                        <?php echo implode( ', ', $venue_details ); ?>
                        <?php
                        if ( tribe_show_google_map_link() ) {
                            echo tribe_get_map_link_html();
                        }
                        ?>
                    </div>
                </div> <!-- .tribe-events-venue-details -->
            <?php endif; ?>

        </div>
    </div><!-- .tribe-events-event-meta -->
    <?php do_action( 'tribe_events_after_the_meta' ) ?>
</td>
<td class="description tribe-events-list-event-description tribe-events-content">
    <?php
    do_action( 'tribe_events_before_the_content' );

    echo olympys_string_short( tribe_events_get_the_excerpt( null, array() ), 100, '...', 's' );

    do_action( 'tribe_events_after_the_content' );
    ?>
</td>
<td class="cost">
    <!--Event Cost -->
    <?php if ( tribe_get_cost() ) :
        ?>
        <span class="h6 ticket-cost"><?php echo esc_html( tribe_get_cost( null, true ) ); ?></span>
        <?php
        /** This action is documented in the-events-calendar/src/views/list/single-event.php */
        do_action( 'tribe_events_inside_cost' )
        ?>
    <?php endif; ?>
</td>
<td class="more">
    <a href="<?php echo esc_url( tribe_get_event_link() ); ?>" class="btn btn-primary btn-sm tribe-events-read-more" rel="bookmark"><?php esc_html_e( 'Find out more', 'olympus' ) ?></a>
</td>
