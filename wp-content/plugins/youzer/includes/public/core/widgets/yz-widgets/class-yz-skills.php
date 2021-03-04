<?php

class YZ_Skills {
    
    /**
     * # Content.
     */
    function widget() {

        // Variables.
        $skills = yz_data( 'youzer_skills' );

        if ( empty( $skills ) ) {
            return false;
        }

        echo '<div class="yz-skills-content yz-default-content">';

        foreach ( $skills as $skill ) :

            // Get Skill Data
            $barpercent = $skill['barpercent'];
            $title      = sanitize_text_field( $skill['title'] );

            // If user Didn
            if ( empty( $title ) || empty( $barpercent ) || $barpercent > 100 || $barpercent < 0 ) {
                continue;
            }

            // Get Item Class
            $class = ( $barpercent > 95 ) ? 'yz-skillbar clearfix yz-whitepercent' : 'yz-skillbar clearfix';

            ?>

            <div class="<?php echo $class; ?>" data-percent="<?php echo $barpercent; ?>%">
                <div class="yz-skillbar-bar" style="background-color:<?php echo $skill['barcolor']; ?>">
                    <span class="yz-skillbar-title"><?php echo $title; ?></span>
                </div>
                <div class="yz-skill-bar-percent"><?php echo $barpercent; ?>%</div>
            </div>

            <?php

        endforeach;

        echo '</div>';

    }

}