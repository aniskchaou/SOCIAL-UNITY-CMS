<?php
/**
 * Events Navigation Bar Module Template
 * Renders our events navigation bar used across our views
 *
 * $filters and $views variables are loaded in and coming from
 * the show funcion in: lib/Bar.php
 *
 * Override this template in your own theme by creating a file at:
 *
 *     [your-theme]/tribe-events/modules/bar.php
 *
 * @package  TribeEventsCalendar
 * @version 4.6.19
 */
?>

<?php
$filters = tribe_events_get_filters();
$views   = tribe_events_get_views();

$current_url = tribe_events_get_current_filter_url();
?>

<?php do_action( 'tribe_events_bar_before_template' ) ?>
<div id="tribe-events-bar">

    <h2 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Search and Views Navigation', 'olympus' ), tribe_get_event_label_plural() ); ?></h2>

    <form id="tribe-bar-form" class="tribe-clearfix" name="tribe-bar-form" method="post" action="<?php echo esc_attr( $current_url ); ?>">
        <div class="tribe-bar-filter-wrap">
            <!-- Mobile Filters Toggle -->

            <div id="tribe-bar-collapse-toggle" <?php if ( count( $views ) == 1 ) { ?> class="tribe-bar-collapse-toggle-full-width"<?php } ?>>
                <?php printf( esc_html__( 'Find %s', 'olympus' ), tribe_get_event_label_plural() ); ?><span class="tribe-bar-toggle-arrow"></span>
            </div>

            <?php if ( !empty( $filters ) ) { ?>
                <div class="tribe-bar-filters tribe-clearfix">
                    <div class="tribe-bar-filters-flex">
                        <h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Search', 'olympus' ), tribe_get_event_label_plural() ); ?></h3>
                        <?php foreach ( $filters as $filter ) : ?>
                            <div class="<?php echo esc_attr( $filter[ 'name' ] ) ?>-filter">
                                <label class="label-<?php echo esc_attr( $filter[ 'name' ] ) ?>" for="<?php echo esc_attr( $filter[ 'name' ] ) ?>"><?php olympus_render( $filter[ 'caption' ] ) ?></label>
                                <?php olympus_render( $filter[ 'html' ] ); ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="tribe-bar-submit">
                            <input
                                class="tribe-events-button tribe-no-param"
                                type="submit"
                                name="submit-bar"
                                aria-label="<?php printf( esc_attr__( 'Submit %s search', 'olympus' ), tribe_get_event_label_plural() ); ?>"
                                value="<?php printf( esc_attr__( 'Find %s', 'olympus' ), tribe_get_event_label_plural() ); ?>"
                                />
                        </div>
                        <!-- .tribe-bar-submit -->
                    </div>
                </div><!-- .tribe-bar-filters -->
            <?php } // if ( !empty( $filters ) )  ?>
        </div>

        <!-- Views -->
        <?php if ( count( $views ) > 1 ) { ?>
            <div id="tribe-bar-views">
                <div class="tribe-bar-views-inner tribe-clearfix">
                    <h3 class="tribe-events-visuallyhidden"><?php printf( esc_html__( '%s Views Navigation', 'olympus' ), tribe_get_event_label_singular() ); ?></h3>

                    <ul class="tribe-bar-views-list" role="tablist">
                        <?php foreach ( $views as $view ) : ?>
                            <?php
                            switch ( $view[ 'displaying' ] ) {
                                case 'month':
                                    $icon = 'olympus-icon-Month-Calendar-Icon';
                                    break;
                                case 'list':
                                    $icon = 'olympus-icon-Week-Calendar-Icon';
                                    break;
                                case 'day':
                                    $icon = 'olympus-icon-Day-Calendar-Icon';
                                    break;
                                default:
                                    $icon = 'olympus-icon-Check-Icon';
                                    break;
                            }
                            ?>
                            <li class="<?php echo esc_attr( $view[ 'displaying' ] ); ?>">
                                <a href="<?php echo esc_attr( $view[ 'url' ] ); ?>" data-toggle="tooltip" data-placement="top" data-original-title="<?php echo esc_attr( $view[ 'anchor' ] ); ?>" class="<?php echo esc_attr( tribe_is_view( $view[ 'displaying' ] ) ? 'selected' : ''  ); ?>">
                                    <i class="<?php olympus_render( $icon ); ?>"></i>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                </div>
                <!-- .tribe-bar-views-inner -->
            </div><!-- .tribe-bar-views -->
        <?php } // if ( count( $views ) > 1 )  ?>

    </form>
    <!-- #tribe-bar-form -->

</div><!-- #tribe-events-bar -->
<?php
do_action( 'tribe_events_bar_after_template' );
