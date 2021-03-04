<?php

class YZ_Portfolio {

    /**
     * # Content.
     */
    function widget() {

        $portfolio_photos = yz_data( 'youzer_portfolio' );

        if ( empty( $portfolio_photos ) ) {
            return;
        }

        ?>

    	<ul class="yz-portfolio-content">

    	<?php

            foreach ( $portfolio_photos as $photo ) :

            // Get Original Image Link.
            $original_image = wp_get_attachment_url( $photo['image'] );

            // If Photo Link is not available replace it with Photo Source Link
            $photo_link  = ! empty( $photo['link'] ) ? $photo['link'] : $original_image;

    	?>

		<li>
            <figure class="yz-project-item">
                <div class="yz-projet-img"><img loading="lazy" <?php echo yz_get_image_attributes( $photo['image'], 'youzify-medium', 'profile-portfolio-widget' ); ?> alt=""></div>
				<figcaption class="yz-pf-buttons">
                        <a class="yz-pf-url" href="<?php echo esc_url( $photo_link ); ?>" target="_blank" ><i class="fas fa-link"></i></a>
                        <a class="yz-pf-zoom"><i class="fas fa-search"></i></a>
                        <a class="yz-lightbox-img" href="<?php echo $original_image; ?>" data-lightbox="yz-portfolio" <?php if ( ! empty( $photo['title'] ) ) { echo "data-title='" . esc_attr( $photo['title'] ) . "'"; } ?>></a>
				</figcaption>
			</figure>
		</li>

    	<?php endforeach;?>

    	</ul>

    	<?php
    }

}