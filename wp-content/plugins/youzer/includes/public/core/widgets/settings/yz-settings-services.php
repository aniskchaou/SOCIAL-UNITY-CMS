<?php

/**
 * Services Settings.
 */
function yz_services_widget_settings() {

    // Call Scripts
    yz_iconpicker_scripts();
    wp_enqueue_script( 'yz-services', YZ_PA . 'js/yz-services.min.js', array( 'jquery', 'yz-builder' ), YZ_Version, true );
    wp_localize_script( 'yz-services', 'Yz_Services', array(
        'serv_desc_desc'  => __( 'Add service description', 'youzer' ),
        'serv_desc_icon'  => __( 'Select service icon', 'youzer' ),
        'service_desc'    => __( 'Service Description', 'youzer' ),
        'serv_desc_title' => __( 'Type service title', 'youzer' ),
        'service_title'   => __( 'Service Title', 'youzer' ),
        'service_icon'    => __( 'Service Icon', 'youzer' ),
        'items_nbr'       => __( 'The number of items allowed is ', 'youzer' ),
        'no_items'        => __( 'No items found!', 'youzer' )
    ) );

    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'services' );

    $Yz_Settings->get_field(
        array(
            'title'          => yz_option( 'yz_wg_services_title', __( 'Services', 'youzer' ) ),
            'button_text'    => __( 'Add New Service', 'youzer' ),
            'id'             => $args['id'],
            'icon'           => $args['icon'],
            'button_id'      => 'yz-service-button',
            'widget_section' => true,
            'type'           => 'open'
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'    => 'yz-services-data',
            'type'  => 'hidden'
        ), false, 'yz_data'
    );

    echo '<ul class="yz-wg-opts yz-wg-services-options">';

    $i = 0;
    $services_options = yz_data( 'youzer_services' );

    if ( ! empty( $services_options ) ) :

    // Options titles
    $label_title = __( 'Service Title', 'youzer' );
    $label_desc  = __( 'Service Description', 'youzer' );

    foreach ( $services_options as $service ) : $i++;

        // init Variables.
        $service_title = sanitize_text_field( $service['title'] );
        $service_desc  = sanitize_textarea_field( $service['description'] );
        $service_icon  = ! empty( $service['icon'] ) ? $service['icon'] : 'fas fa-globe';

    ?>

        <li class="yz-wg-item" data-wg="services">
            <div class="yz-wg-container">

                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php _e( 'Service Icon', 'youzer' ); ?></label>
                            <p class="option-desc"><?php _e( 'Select service icon', 'youzer' ); ?></p>
                        </div>
                        <div class="option-content">
                            <div id="ukai_icon_<?php echo $i; ?>" class="ukai_iconPicker" data-icons-type="web-application">
                                <div class="ukai_icon_selector">
                                    <i class="<?php echo apply_filters( 'yz_service_item_icon', $service_icon ); ?>"></i>
                                    <span class="ukai_select_icon"><i class="fas fa-sort-down"></i></span>
                                </div>
                                <input type="hidden" class="ukai-selected-icon" name="youzer_services[<?php echo $i; ?>][icon]" value="<?php echo $service_icon; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_title; ?></label>
                            <p class="option-desc"><?php _e( 'Type service title', 'youzer' ); ?></p>
                        </div>
                        <div class="option-content">
                            <input type="text" name="youzer_services[<?php echo $i; ?>][title]" value="<?php echo $service_title; ?>" placeholder="<?php echo $label_title; ?>">
                        </div>
                    </div>
                </div>

                <div class="uk-option-item">
                    <div class="yz-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_desc; ?></label>
                            <p class="option-desc"><?php _e( 'Add service description', 'youzer' ); ?></p>
                        </div>
                        <div class="option-content">
                            <textarea name="youzer_services[<?php echo $i; ?>][description]" placeholder="<?php echo $label_desc; ?>"><?php echo $service_desc; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <a class="yz-delete-item"></a>
        </li>

        <?php endforeach; endif; ?>

        <script>
            var yz_service_nextCell = <?php echo $i+1; ?>,
                yz_max_services_nbr = <?php echo yz_option( 'yz_wg_max_services', 4 ); ?>;
        </script>

        <?php

    echo '</ul>';

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}