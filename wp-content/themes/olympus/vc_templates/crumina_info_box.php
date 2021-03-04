<?php
$wrapper_attributes = array();

extract( shortcode_atts( array(
    'css'   => '',
    'image' => '',
    'title' => '',
    'text'  => '',
    'id'    => '',
    'class' => ''
), $atts ) );

if ( (int) $image ) {
    $image = wp_get_attachment_image_src( $image, 'crumina-info-box' );
}

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'crumina-module', 'crumina-info-box', 'wpb_content_element' );

$wrapper_attributes[] = 'class="' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>


<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <?php if ( $image ) { ?>
        <div class="info-box-image">
            <img loading="lazy" src="<?php echo esc_attr( $image[ 0 ] ) ?>" alt="<?php echo esc_attr( $title ); ?>">
        </div>
    <?php } ?>
    <div class="info-box-content">
        <?php if ( $title ) { ?>
            <h3 class="info-box-title"><?php echo esc_html( $title ); ?></h3>
        <?php } ?>
        <?php if ( $text ) { ?>
            <p class="info-box-text"><?php echo esc_html( $text ); ?></p>
        <?php } ?>
    </div>
</div>