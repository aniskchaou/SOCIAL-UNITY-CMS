<?php
$wrapper_attributes = array();

extract( shortcode_atts( array(
    'clients' => '',
    /* ------------ */
    'css'     => '',
    'id'      => '',
    'class'   => '',
), $atts ) );

$clients = vc_param_group_parse_atts( $clients );

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'crumina-module', 'crumina-clients', 'wpb_content_element' );

$wrapper_attributes[] = 'class="' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>

<?php if ( $clients && is_array( $clients ) ) { ?>
    <section <?php echo implode( ' ', $wrapper_attributes ); ?>>
        <div class="container">
            <div class="row">
                <?php
                foreach ( $clients as $client ) {
                	$link = isset($client[ 'link' ]) ? $client[ 'link' ] : '';
                    $link = vc_build_link( $link );

                    $logo = array();
                    if ( (int) $client[ 'logo' ] ) {
                        $logo = wp_get_attachment_image_src( $client[ 'logo' ], 'full' );
                    }
                    if ( !isset( $logo[ 0 ] ) ) {
                        continue;
                    }
                    ?>
                    <div class="col-xl-2 m-auto col-lg-2 col-md-6 col-sm-6 col-xs-12">
                        <?php
                        if ( !empty( $link[ 'url' ] ) ) {
                            $rel = !empty( $link[ 'rel' ] ) ? ' rel="' . esc_attr( trim( $link[ 'rel' ] ) ) . '"' : '';
                            ?>
                            <a href="<?php echo esc_attr( $link[ 'url' ] ); ?>" title="<?php esc_attr( $link[ 'title' ] ); ?>" target="<?php echo esc_attr( trim( $link[ 'target' ] ) ); ?>" class="clients-item" <?php olympus_render( $rel ); ?>>
                                <img loading="lazy" class="main" src="<?php echo esc_attr( $logo[ 0 ] ); ?>" alt="<?php esc_attr_e( 'Logo', 'olympus' ); ?>" />
                            </a>
                        <?php } else { ?>
                            <span class="clients-item">
                                <img loading="lazy" class="main" src="<?php echo esc_attr( $logo[ 0 ] ); ?>" alt="<?php esc_attr_e( 'Logo', 'olympus' ); ?>" />
                            </span>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </section>
<?php } ?>
