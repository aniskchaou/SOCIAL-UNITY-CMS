<?php

/**
 * Skills Settings.
 */
function yz_skills_widget_settings() {

    // Call Scripts
    wp_enqueue_script( 'yz-skills', YZ_PA . 'js/yz-skills.min.js', array( 'jquery', 'yz-builder' ), YZ_Version, true );
    wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array( 'jquery-ui-draggable', 'jquery-ui-slider', 'jquery-touch-punch' ), YZ_Version, true );

    // Color Picker.
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'wp-color-picker', admin_url( 'js/color-picker.min.js' ), array( 'iris' ), YZ_Version, true );

    $colorpicker_translate = array(
        'clear'         => __( 'Clear', 'youzer' ),
        'defaultString' => __( 'Default', 'youzer' ),
        'pick'          => __( 'Select Color', 'youzer' ),
        'current'       => __( 'Current Color', 'youzer' ),
    );

    wp_localize_script( 'wp-color-picker', 'wpColorPickerL10n', $colorpicker_translate );

    // Skill Translations.
    wp_localize_script( 'yz-skills', 'Yz_Skills', array(
            'skill_desc_percent' => __( 'Skill bar percent', 'youzer' ),
            'skill_desc_title'   => __( 'Type skill title', 'youzer' ),
            'skill_desc_color'   => __( 'Skill bar color', 'youzer' ),
            'bar_percent'        => __( 'Percent (%)', 'youzer' ),
            'bar_title'          => __( 'Title', 'youzer' ),
            'bar_color'          => __( 'Color', 'youzer' ),
            'items_nbr'          => __( 'The number of items allowed is ', 'youzer' ),
            'no_items'           => __( 'No items found!', 'youzer' )
        )
    );


    global $Yz_Settings;

    // Get Args
    $args = yz_get_profile_widget_args( 'skills' );

    $Yz_Settings->get_field(
        array(
            'title'          => yz_option( 'yz_wg_skills_title', __( 'Skills', 'youzer' ) ),
            'button_text'    => __( 'Add New Skill', 'youzer' ),
            'id'             => $args['id'],
            'icon'           => $args['icon'],
            'button_id'      => 'yz-skill-button',
            'widget_section' => true,
            'type'           => 'open',
        )
    );

    $Yz_Settings->get_field(
        array(
            'id'   => 'yz-skills-data',
            'type' => 'hidden'
        ), false, 'yz_data'
    );

    echo '<ul class="yz-wg-opts yz-wg-skills-options">';

    // Get Data
    $i = 0;
    $skills = yz_data( 'youzer_skills' );

    if ( ! empty( $skills ) ) :

    foreach ( $skills as $skill ) : $i++; ?>

        <li class="yz-wg-item" data-wg="skills">

            <!-- Option Item. -->
            <div class="uk-option-item">
                <div class="yz-option-inner">
                    <div class="option-infos">
                        <label><?php _e( 'Title', 'youzer' ); ?></label>
                        <p class="option-desc"><?php _e( 'Type skill title', 'youzer' ); ?></p>
                    </div>
                    <div class="option-content">
                        <input type="text" name="youzer_skills[<?php echo $i; ?>][title]" value="<?php echo $skill['title']; ?>">
                    </div>
                </div>
            </div>

            <!-- Option Item. -->
            <div class="uk-option-item">
                <div class="yz-option-inner">
                    <div class="option-infos">
                        <label><?php _e( 'Percent (%)', 'youzer' ); ?></label>
                        <p class="option-desc"><?php _e( 'Skill bar percent', 'youzer' ); ?></p>
                    </div>
                    <div class="option-content">
                        <input type="number" min="1" max="100" name="youzer_skills[<?php echo $i; ?>][barpercent]" value="<?php echo $skill['barpercent']; ?>">
                    </div>
                </div>
            </div>

            <!-- Option Item. -->
            <div class="uk-option-item">
                <div class="yz-option-inner">
                    <div class="option-infos">
                        <label><?php _e( 'Color', 'youzer' ); ?></label>
                        <p class="option-desc"><?php _e( 'Skill bar color', 'youzer' ); ?></p>
                    </div>
                    <div class="option-content">
                        <input type="text" class="yz-picker-input" name="youzer_skills[<?php echo $i; ?>][barcolor]" value="<?php echo $skill['barcolor']; ?>">
                    </div>
                </div>
            </div>

            <a class="yz-delete-item"></a>

        </li>

        <?php endforeach; endif; ?>

        <script>
            var yz_skill_nextCell = <?php echo $i+1 ?>,
                yz_maximum_skills = <?php echo yz_option( 'yz_wg_max_skills', 5 ); ?>;
        </script>

    <?php

    echo '</ul>';

    $Yz_Settings->get_field( array( 'type' => 'close' ) );

}

/**
 * # Skills Content .
 */
function get_user_skills() {

}