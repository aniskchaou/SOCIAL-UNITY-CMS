<?php
/**
 * List View Loop
 * This file sets up the structure for the list loop
 *
 * Override this template in your own theme by creating a file at [your-theme]/tribe-events/list/loop.php
 *
 * @version 4.4
 * @package TribeEventsCalendar
 *
 */
if ( !defined( 'ABSPATH' ) ) {
    die( '-1' );
}
?>

<?php
global $post;
global $more;
$more = false;
?>

<table class="event-item-table event-item-table-fixed-width">

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
            ob_start();
            tribe_events_list_the_date_headers();
            $event_date_headers = trim( ob_get_clean() );
            ?>

            <?php if ( $event_date_headers ) { ?>
                <!-- Month / Year Headers -->
                <tr class="event-date-headers">
                    <td colspan="5" class="event-date-headers">
                        <?php olympus_render($event_date_headers); ?>
                    </td>
                </tr>
            <?php } ?>

            <!-- Event  -->
            <?php
            $post_parent = '';
            if ( $post->post_parent ) {
                $post_parent = ' data-parent-post-id="' . absint( $post->post_parent ) . '"';
            }

            $event_type = tribe( 'tec.featured_events' )->is_featured( $post->ID ) ? 'featured' : 'event';

            /**
             * Filters the event type used when selecting a template to render
             *
             * @param $event_type
             */
            $event_type = apply_filters( 'tribe_events_list_view_event_type', $event_type );
            ?>

            <tr id="post-<?php the_ID() ?>" class="event-item <?php tribe_events_event_classes(); ?>" <?php olympus_render( $post_parent ); ?>>
                <?php tribe_get_template_part( 'list/single-event' ); ?>
            </tr>

            <?php do_action( 'tribe_events_inside_after_loop' ); ?>
        <?php endwhile; ?>
    </tbody>

</table>









