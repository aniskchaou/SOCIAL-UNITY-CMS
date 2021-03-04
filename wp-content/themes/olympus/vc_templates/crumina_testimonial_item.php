<?php
$wrapper_attributes = array();

global  $allowedtags;

extract( shortcode_atts( array(
    'avatar'           => '',
    'name'             => '',
    'link'             => '',
    'user_description' => '',
    'description'      => '',
    'title'            => '',
    'rating'           => '',
    'avatar_width'     => 98,
    'avatar_height'    => 98,
    'header_overlay'   => '#ff5e3a',
    /* ------------ */
    'css'              => '',
    'id'               => '',
    'class'            => '',
), $atts ) );

if ( (int) $avatar ) {
    $avatar_size = ($avatar_width && $avatar_height) ? array( $avatar_width, $avatar_height ) : 'thumbnail';
    $avatar      = wp_get_attachment_image_src( $avatar, $avatar_size );
}

$link   = vc_build_link( $link );
$rating = (int) $rating;

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'ui-block', 'crumina-module', 'crumina-testimonial-item', 'wpb_content_element' );

$wrapper_attributes[] = 'class="' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>

<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="testimonial-header-thumb">
        <div class="testimonial-header-thumb-overlay" style="background-color: <?php echo esc_attr( $header_overlay ); ?>"></div>
	    <?php if ( isset( $avatar[ 0 ] ) ) { ?>
			<div class="author-thumb">
				<img loading="lazy" src="<?php echo esc_attr( $avatar[ 0 ] ); ?>" width="<?php echo esc_attr( $avatar_width ); ?>" height="<?php echo esc_attr( $avatar_height ); ?>" class="img-responsive" alt="<?php echo esc_attr( $name ); ?>" />
			</div>
	    <?php } ?>
    </div>

    <div class="testimonial-item-content <?php olympus_render( !isset( $avatar[ 0 ] ) ? esc_attr('no-avatar') : '' ); ?>">

        <?php if ( $title ) { ?>
            <h3 class="testimonial-title"><?php echo esc_html( $title ); ?></h3>
        <?php } ?>

        <?php if ( $rating ) { ?>
            <ul class="rait-stars">
                <?php for ( $i = 0; $i < 5; $i++ ) { ?>
                    <li>
                        <i class="fa <?php olympus_render( $i < $rating ? 'fa-star' : ' fa-star-o' ); ?> star-icon"></i>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>

        <?php if ( $description ) { ?>
            <p class="testimonial-message"><?php echo wp_kses( $description, $allowedtags ); ?></p>
        <?php } ?>

        <div class="author-content">
            <?php
            if ( !empty( $link[ 'url' ] ) ) {
                $rel = !empty( $link[ 'rel' ] ) ? ' rel="' . esc_attr( trim( $link[ 'rel' ] ) ) . '"' : '';
                ?>
                <a href="<?php echo esc_attr( $link[ 'url' ] ); ?>" title="<?php esc_attr( $link[ 'title' ] ); ?>" target="<?php echo esc_attr( trim( $link[ 'target' ] ) ); ?>" class="h6 author-name" <?php olympus_render( $rel ); ?>><?php echo esc_html( $name ); ?></a>
            <?php } else { ?>
                <div class="h6 author-name"><?php echo esc_html( $name ); ?></div>
            <?php } ?>

            <?php if ( $user_description ) { ?>
                <div class="country"><?php echo esc_html( $user_description ); ?></div>
            <?php } ?>
        </div>
    </div>
</div>