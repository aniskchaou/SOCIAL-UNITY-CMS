<?php

/**
 * Replace default walker.
 *
 * @package olympus
 * */

function olympus_wp_setup_nav_menu_item( $menu_item ) {
    if ( isset( $menu_item->post_type ) ) {
        if ( 'nav_menu_item' == $menu_item->post_type ) {
            $menu_item->description = apply_filters( 'nav_menu_description', $menu_item->post_content );
        }
    }

    return $menu_item;
}

add_filter( 'wp_setup_nav_menu_item', 'olympus_wp_setup_nav_menu_item' );

function olympus_filter_mega_menu_icon_customizations( $option ) {
    $option[ 'type' ] = 'icon-v2';
    return $option;
}

function olympus_custom_packs_list( $current_packs ) {
	/**
	 * $current_packs is an array of pack names.
	 * You should return which one you would like to show in the picker.
	 */

	return $current_packs;
}
// TODO:  Change icon packs for include on frontend. Disable if that icons was not used.
//add_filter( 'fw:option_type:icon-v2:filter_packs', 'olympus_custom_packs_list' );


add_filter( 'fw:option_type:icon-v2:packs', 'olympus_filter_add_icons_for_menu' );

function olympus_filter_add_icons_for_menu( $default_packs ) {
	return array(
		'olympus' => array(
			'name'             => 'olympus',
			'css_class_prefix' => 'olympus-icon',
			'css_file'         => get_template_directory() . '/css/olympus-icons-font.css',
			'css_file_uri'     => get_template_directory_uri() . '/css/olympus-icons-font.css'
		)
	);
}
add_filter( 'fw:ext:megamenu:icon-option', 'olympus_filter_mega_menu_icon_customizations' );

function olympus_megamenu_admin_enqueue_scripts() {
    $megamenu = Olympus_Core::get_extension( 'megamenu' );

    if ( !$megamenu ) {
        return false;
    }
    
    wp_enqueue_script( "fw-ext-{$megamenu->get_name()}-admin", get_template_directory_uri() . "/framework-customizations/extensions/megamenu/static/js/admin.js", array( 'fw' ), $megamenu->manifest->get_version() );
}

add_action( 'admin_enqueue_scripts', 'olympus_megamenu_admin_enqueue_scripts', 9 );
