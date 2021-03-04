<?php
$wrapper_attributes = array();

extract( shortcode_atts( array(
    'photo'        => '',
    'name'         => '',
    'link'         => '',
    'description'  => '',
    'socials'      => '',
    'animation'    => '',
    'photo_width'  => '',
    'photo_height' => '',
    /* ------------ */
    'css'          => '',
    'id'           => '',
    'class'        => '',
), $atts ) );

if ( (int) $photo ) {
    $photo_size = ($photo_width && $photo_height) ? array( $photo_width, $photo_height ) : 'full';
    $photo      = wp_get_attachment_image_src( $photo, $photo_size );
}

$link    = vc_build_link( $link );
$socials = vc_param_group_parse_atts( $socials );

$system_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, vc_shortcode_custom_css_class( $css, ' ' ), $this->settings[ 'base' ], $atts );
$attr_class   = array( trim( $system_class ), $class, 'crumina-module', 'crumina-teammembers-item', 'wpb_content_element' );

$wrapper_attributes[] = 'class="' . esc_attr( implode( ' ', $attr_class ) ) . '"';

if ( $id ) {
    $wrapper_attributes[] = 'id="' . esc_attr( $id ) . '"';
}
?>

<div <?php echo implode( ' ', $wrapper_attributes ); ?>>

    <?php if ( isset( $photo[ 0 ] ) ) { ?>
        <?php
        $photo_wrapper_attrs = array(
            'class' => 'teammembers-thumb',
            'style' => ''
        );
        if ( $photo_width ) {
            $photo_wrapper_attrs[ 'style' ] .= "width: {$photo_width};";
        }

        if ( $photo_height ) {
            $photo_wrapper_attrs[ 'style' ] .= "height: {$photo_height};";
        }
        ?>
        <div <?php echo olympus_attr_to_html( $photo_wrapper_attrs ); ?>>
            <?php
            $photo_attrs = array(
                'class' => 'main',
                'src'   => $photo[ 0 ],
                'alt'   => $name
            );
            ?>    
            <img loading="lazy" <?php echo olympus_attr_to_html( $photo_attrs ); ?> />
            <?php
            if ( $animation ) { ?>
                <img loading="lazy" class="hover" src="<?php echo esc_attr( $photo[ 0 ] ); ?>" alt="<?php echo esc_attr( $name ); ?>" />
            <?php } ?>
        </div>
    <?php } ?>

    <div class="teammember-content">
        <?php if ( $name ) { ?>
            <?php
            if ( !empty( $link[ 'url' ] ) ) {
                $rel = !empty( $link[ 'rel' ] ) ? ' rel="' . esc_attr( trim( $link[ 'rel' ] ) ) . '"' : '';
                ?>
                <a href="<?php echo esc_attr( $link[ 'url' ] ); ?>" title="<?php esc_attr( $link[ 'title' ] ); ?>" target="<?php echo esc_attr( trim( $link[ 'target' ] ) ); ?>" class="h5 teammembers-item-name" <?php olympus_render( $rel ); ?>><?php echo esc_html( $name ); ?></a>
            <?php } else { ?>
                <div class="h5 teammembers-item-name"><?php echo esc_html( $name ); ?></div>
            <?php } ?>
        <?php } ?>

        <?php if ( $description ) { ?>
            <div class="teammembers-item-prof"><?php echo esc_html( $description ); ?></div>
        <?php } ?>

        <?php if ( $socials && is_array( $socials ) ) { ?>
            <ul class="socials socials--round socials--colored-bg">
                <?php
                foreach ( $socials as $social ) {
                    if ( !isset( $social[ 'network' ] ) || !isset( $social[ 'link' ] ) ) {
                        continue;
                    }
                    if ( empty( $social[ 'network' ] ) || empty( $social[ 'link' ] ) ) {
                        continue;
                    }
                    $link = vc_build_link( $social[ 'link' ] );

                    if ( empty( $link[ 'url' ] ) ) {
                        continue;
                    }
                    $rel = !empty( $link[ 'rel' ] ) ? ' rel="' . esc_attr( trim( $link[ 'rel' ] ) ) . '"' : '';
                    ?>
                    <li>
                        <a href="<?php echo esc_attr( $link[ 'url' ] ); ?>" title="<?php esc_attr( $link[ 'title' ] ); ?>" target="<?php echo esc_attr( trim( $link[ 'target' ] ) ); ?>" class="social-item <?php echo esc_attr( $social[ 'network' ] ); ?>" <?php olympus_render( $rel ); ?>>
                            <?php get_template_part( "/templates/socials/{$social[ 'network' ]}" ); ?>
                        </a>
                    </li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
</div>
