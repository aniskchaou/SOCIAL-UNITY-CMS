<?php

if ( ! defined( 'FW' ) ) {
	die( 'Forbidden' );
}
/**
 * @var WP_Post $item
 * @var string  $title
 * @var array   $attributes
 * @var object  $args
 * @var int     $depth
 */
if ( fw()->extensions->get( 'megamenu' )->show_icon() ) {
	$meta      = fw_ext_mega_menu_get_meta( $item->ID, "icon" );
	$icon = olympus_generate_icon_html( $meta, 'universal-olympus-icon' );

	$title = $icon . $title;
}


olympus_render( $args->before );
$label             = '';
$item->description = trim( $item->description );
if ( ! empty( $item->description ) ) {
	$label = olympus_html_tag( 'div', array( 'class' => 'menu-item-description' ), $item->description );
}
/* If empty link in item - we will print title item instead link */
if ( empty( $attributes['href'] ) || $attributes['href'] === 'http://' || $attributes['href'] === 'http://#' || $attributes['href'] === 'https://' || $attributes['href'] === 'https://#' ) {
	echo '<div class="megamenu-item-info">';
	if ( $depth > 0 && true !== fw_ext_mega_menu_get_meta( $item, 'title-off' ) ) {
		echo olympus_html_tag( 'h6', array( 'class' => 'column-tittle' ), $title );
	}
	echo '</div>';
} else {
	if ( true === fw_ext_mega_menu_get_meta( $item, 'title-off' ) ) {
		olympus_render( $label );
	} else {
		echo olympus_html_tag( 'a', $attributes, $args->link_before . $title . $label . $args->link_after );
	}

}
olympus_render( $args->after );
