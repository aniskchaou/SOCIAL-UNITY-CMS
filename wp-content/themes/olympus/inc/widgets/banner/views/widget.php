<?php
if ( !defined( 'ABSPATH' ) ) {
    die( 'Direct access forbidden.' );
}

/**
 * @title string $title
 * @var string  $button_link
 * @var string  $button_text
 * @var string  $button_color
 * @var string  $background
 * @var string  $background_overlay
 * @var string  $description
 * @var string  $description_color
 * @var string  $icon
 * @var string  $before_widget
 * @var string  $after_widget
 */
olympus_render( $before_widget );
?>

<div class="widget w-action" style="z-index: 1; position: relative; <?php olympus_render( $background ? 'background-image: url(' . esc_attr( $background ) . ') !important;' : '' ); ?>">
    <?php if ( $background_overlay ) { ?>
        <div style="z-index: -1; position: absolute; left: 0; top: 0; width: 100%; height: 100%; background-color: <?php olympus_render( $background_overlay ); ?>"></div>
    <?php } ?>

    <?php if ( $icon ) { ?>
        <img loading="lazy" src="<?php echo esc_attr( $icon ); ?>" alt="<?php echo esc_attr( $title ? $title : esc_html__( 'Banner', 'olympus' ) ); ?>">
    <?php } ?>

    <div class="content">
        <?php if ( $title ) { ?>
            <h4 class="title" style="<?php olympus_render( $description_color ? 'color: ' . esc_attr( $description_color ) . ' !important;' : '' ); ?>"><?php echo esc_html( $title ); ?></h4>
        <?php } ?>
        <?php if ( $description ) { ?>
            <span style="<?php olympus_render( $description_color ? 'color: ' . esc_attr( $description_color ) . ' !important;' : '' ); ?>"><?php olympus_render( $description ); ?></span>
        <?php } ?>
        <?php if ( $button_text && $button_link ) { ?>
            <a href="<?php echo esc_attr( $button_link ); ?>" style="<?php olympus_render( $button_color ? 'background-color: ' . esc_attr( $button_color ) . ' !important;' : '' ); ?>" class="btn btn-bg-secondary btn-md"><?php echo esc_html( $button_text ); ?></a>
        <?php } ?>
    </div>
</div>

<?php
olympus_render( $after_widget );
