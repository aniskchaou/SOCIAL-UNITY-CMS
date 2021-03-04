<?php
$wrapper_attributes = array();
$to = $units = '';

extract( shortcode_atts( array(
    'speed' => '2',
    'css'   => '',
    'to'    => '',
    'text'  => '',
    'units' => '',
    'id'    => '',
    'class' => ''
), $atts ) );

wp_enqueue_script( 'purecounter' );

$speed = (int) $speed ? $speed : 2;

if ( (int) $to < 3 ) {
    return false;
}

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'crumina-module', 'crumina-counter-item', 'wpb_content_element' );

$wrapper_attributes[] = 'class="' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>

<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <div class="counter-numbers h2">
        <span class="purecounter" data-purecounter-duration="<?php echo esc_attr( $speed ); ?>" data-purecounter-end="<?php echo esc_attr( $to ); ?>" data-purecounter-start="2"><?php echo esc_html( $to ); ?></span>
        <?php if ( $units ) { ?>
            <div class="units"><?php echo strip_tags( preg_replace( '/(\{+)(.+)(\}+)/', '<div>$2</div>', $units ), '<div>' ); ?></div>
        <?php } ?>
    </div>
    <?php if ( $text ) { ?>
        <div class="counter-title"><?php echo esc_html( $text ); ?></div>
    <?php } ?>
</div>