<?php

class YZ_Services {

    /**
     * # Content.
     */
    function widget() {

        // Get All the Exist Services.
        $services = yz_data( 'youzer_services' );

        if ( empty( $services ) ) {
            return;
        }

        // Get Services Layout
        $services_layout = yz_option( 'yz_wg_services_layout', 'vertical-services-layout' );

        ?>

        <div class="yz-services-content <?php echo $services_layout; ?>">

        <?php

            
            $limit_per_line = apply_filters( 'yz_services_widget_max_items_per_line', 4 );

            if ( 'horizontal-services-layout' != $services_layout ) {
                $services_count = count( $services );
                $width = $services_count < $limit_per_line ? 100 / $services_count : 25;
                $width .= '%';
            } else {
                $width = 'initial';
            }

            
            // Show / Hide Services Elements
            $display_icon  = yz_option( 'yz_display_service_icon', 'on' );
            $display_text  = yz_option( 'yz_display_service_text', 'on' );
            $display_title = yz_option( 'yz_display_service_title', 'on' );
            $icon_border   = yz_option( 'yz_wg_service_icon_bg_format', 'circle' );

            foreach ( $services as $service ) :

            // Get Services Data .
            $service_title = $service['title'];
            $service_desc  = $service['description'];
            $service_icon  = ! empty( $service['icon'] ) ? $service['icon'] : 'fas fa-globe';

            if ( ! $service_title ) {
                continue;
            }

            ?>

            <div class="yz-service-item" style="width: <?php echo $width; ?>;">

                <div class="yz-service-inner">

                    <?php if ( 'on' == $display_icon && $service_icon ) : ?>
                        <div class="yz-service-icon yz-icons-<?php echo $icon_border; ?>">
                            <i class="<?php echo $service_icon ;?>"></i>
                        </div>
                    <?php endif; ?>

                    <div class="yz-item-content">
                        <?php if ( 'on' == $display_title && $service_title ) : ?>
                            <h2 class="yz-item-title"><?php echo sanitize_text_field( $service_title );?></h2>
                        <?php endif; ?>
                        <?php if ( 'on' == $display_text && $service_desc ) : ?>
                            <p><?php echo sanitize_textarea_field( $service_desc ); ?></p>
                         <?php endif; ?>
                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <?php
    }

}