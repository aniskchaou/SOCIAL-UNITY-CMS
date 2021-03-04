<?php
$wrapper_attributes = array();

extract( shortcode_atts( array(
    'title'   => '',
    'details' => '',
    'css'     => '',
    'id'      => '',
    'class'   => ''
), $atts ) );

$details = vc_param_group_parse_atts( $details );

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'contact-item-wrap', 'wpb_content_element' );

$wrapper_attributes[] = 'class=" ' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>


<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <?php if ( $title ) { ?>
        <h3 class="contact-title"><?php echo esc_html( $title ); ?></h3>
    <?php } ?>
    <?php
    if ( !empty( $details ) ) {
        foreach ( $details as $detail ) {
            ?>
            <div class="contact-item">
                <?php if ( !empty( $detail[ 'name' ] ) ) { ?>
                    <h6 class="sub-title"><?php echo esc_html( $detail[ 'name' ] ); ?></h6>
                <?php } ?>
                <?php
                $link = vc_build_link( $detail[ 'link' ] );
                ?>

                <?php
                if ( !empty( $link[ 'url' ] ) && !empty( $link[ 'title' ] ) ) {
                    $rel = !empty( $link[ 'rel' ] ) ? ' rel="' . esc_attr( trim( $link[ 'rel' ] ) ) . '"' : '';
                    ?>
                    <a href="<?php echo esc_attr( $link[ 'url' ] ); ?>" title="<?php echo esc_attr( $link[ 'title' ] ); ?>" target="<?php echo esc_attr( trim( $link[ 'target' ] ) ); ?>" <?php olympus_render( $rel ); ?>>
                        <?php echo esc_html( $link[ 'title' ] ); ?>
                    </a>
                <?php } ?>
            </div>
            <?php
        }
    }
    ?>
</div>
