<?php

class YZ_Slideshow {

    /**
     * # Content.
     */
    function widget() {

        // Get Slides.
        $slides = yz_data( 'youzer_slideshow' );

        if ( empty( $slides ) ) {
            return;
        }

        // Load Carousel CSS and JS.
        wp_enqueue_style( 'yz-carousel-css', YZ_PA . 'css/owl.carousel.min.css', array(), YZ_Version );
        wp_enqueue_script( 'yz-carousel-js', YZ_PA . 'js/owl.carousel.min.js', array( 'jquery' ), YZ_Version, true );
        wp_enqueue_script( 'yz-slider', YZ_PA . 'js/yz-slider.min.js', array( 'jquery' ), YZ_Version, true );

        ?>

        <ul class="yz-slider yz-slides-<?php echo yz_option( 'yz_slideshow_height_type', 'fixed' ); ?>-height">
        <?php foreach ( $slides as $slide ) : ?><li class="yz-slideshow-item"><img loading="lazy" <?php echo yz_get_image_attributes( $slide['image'], 'youzify-wide', 'profile-slideshow-widget' ); ?> alt=""></li><?php endforeach; ?>
    	</ul>

    	<?php

    }

}