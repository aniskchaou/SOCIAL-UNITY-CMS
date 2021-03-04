<?php

class YZ_About_Me {

    /**
     * # Profile Content.
     */
    function widget() {

        // Get User Biography.
        $wg_biography  = apply_filters( 'yz_profile_about_me_widget_description', yz_data( 'wg_about_me_bio' ) );

        if ( empty( $wg_biography ) ) {
            return;
        }

        // Get Widget Data
        // $wg_photo      = apply_filters( 'yz_profile_about_me_widget_image', yz_data( 'wg_about_me_photo' ) );
        $wg_description = wp_kses_post( yz_data( 'wg_about_me_desc' ) );
        $wg_title      = sanitize_text_field( yz_data( 'wg_about_me_title' ) );

        $photo = yz_data( 'wg_about_me_photo' );

    	?>

    	<div class="yz-aboutme-content yz-default-content">
            <?php if ( ! empty( $photo ) ) : ?>
    		<div class="yz-user-img yz-photo-<?php echo yz_option( 'yz_wg_aboutme_img_format', 'circle' ); ?>">
                <img loading="lazy" <?php echo yz_get_image_attributes( $photo, 'youzify-thumbnail',  'profile-about-me-widget' ); ?> alt=""></div>
            <?php endif; ?>

    		<div class="yz-aboutme-container">

                <?php if ( $wg_title || $wg_description ) : ?>
    			<div class="yz-aboutme-head">
    				<h2 class="yz-aboutme-name"><?php echo $wg_title; ?></h2>
    				<h2 class="yz-aboutme-description"><?php echo $wg_description; ?></h2>
    			</div>
                <?php endif; ?>

                <?php do_action( 'yz_after_about_me_widget_head' ); ?>

                <?php if ( $wg_biography ) : ?>
                    <div class="yz-aboutme-bio"><?php echo apply_filters( 'the_content', $wg_biography ); ?></div>
                <?php endif; ?>

    		</div>

    	</div>

    	<?php

    }

    /**
     * # Get "About Me" Photo.
     */
    // function about_me_photo() {

    //     $about_me_photo = yz_data( 'wg_about_me_photo' );

    //     $wg_photo = yz_get_file_url( $about_me_photo );

    //     return $wg_photo;

    // }

}