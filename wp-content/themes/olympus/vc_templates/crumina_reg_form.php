<?php
$wrapper_attributes = array();

$id          = $class       = $redirect_to = '';
extract( $atts );

$redirect_to = vc_build_link( $redirect_to );
$redirect_to = isset($redirect_to['url']) ? $redirect_to['url'] : '';

$attr_class  = array( $class, 'wpb_content_element' );

$wrapper_attributes[] = 'class=" ' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>

<div <?php echo implode( ' ', $wrapper_attributes ); ?>>
    <?php
    if ( function_exists( 'crumina_get_reg_form_html' ) ) {
        echo crumina_get_reg_form_html($redirect_to);
    } else {
        esc_html_e( 'Crumina Sign in Form extension required', 'olympus' );
    }
    ?>
</div>