<?php
$wrapper_attributes = array();

$icon  = $items = $css   = $id    = $class = '';

extract( $atts );

$icon  = $icon ? $icon : 'fa fa-check-circle';
$items = vc_param_group_parse_atts( $items );

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'wpb_content_element' );

$wrapper_attributes[] = 'class=" ' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>

<?php if ( !empty( $items ) ) { ?>
    <div <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <ul class="list--styled">
            <?php foreach ( $items as $item ) { ?>
                <?php if ( isset( $item[ 'text' ] ) ) { ?>
                    <li>
                        <i class="<?php echo esc_attr( $icon ); ?>"></i>
                        <?php olympus_render($item[ 'text' ]); ?>
                    </li>
                <?php } ?>
            <?php } ?>
        </ul>
    </div>
<?php } ?>